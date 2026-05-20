<?php

  session_start();

  require '../db/dbconnect.php';
  require '../include/activity_log.php';

/* ===============================
   ACCESS CONTROL
=============================== */

if (!isset($_SESSION['member_id'])) {

  header("Location: ../userlogin.php");

  exit();

}


/* ===============================
   GET SESSION VALUES
   (Already set in google-login.php)
=============================== */

$member_id =
$_SESSION['member_id'] ?? null;

$position_id =
$_SESSION['position_id'] ?? null;

$position_name =
$_SESSION['position_name'] ?? null;

$sub_position =
$_SESSION['sub_position'] ?? null;

$is_official =
$_SESSION['is_official'] ?? false;

$is_bod =
$_SESSION['is_bod'] ?? false;

$is_credit =
$_SESSION['is_credit'] ?? false;


/* ===============================
   OPTIONAL SAFETY CHECK
=============================== */

if (!$member_id) {

  die("Invalid session.");

}

  /* GET MEMBER */

  $member_id = $_SESSION['member_id'];

  /* GET MEMBER TOTAL CBU */

  $cbuStmt =
    $conn->prepare(
      "SELECT COALESCE(SUM(amount),0) AS total_cbu
       FROM member_cbu
       WHERE member_id=?
       AND status='active'"
    );

  $cbuStmt->bind_param("i",$member_id);

  $cbuStmt->execute();
  $cbuResult = $cbuStmt->get_result();
  $cbuRow = $cbuResult->fetch_assoc();
  $total_cbu = $cbuRow['total_cbu'];

  /* GET SELECTED LOAN TYPE */

  if (!isset($_GET['loan_type_id'])) {
    die("Loan type not specified.");
  }

  $loan_type_id =
    intval($_GET['loan_type_id']);



  /* LOAD LOAN TYPE DETAILS */

  $stmt =
    $conn->prepare(

      "SELECT
        loan_type_name,
        max_loan_amount,
        max_month_duration,
        service_fee,
        insurance_fee,
        notary_fee,
        cbu_percentage
      FROM loan_types
      WHERE loan_type_id=?"

    );

  $stmt->bind_param(
    "i",
    $loan_type_id
  );

  $stmt->execute();
  $loanResult = $stmt->get_result();
  $loanType = $loanResult->fetch_assoc();


  /* COMPUTE LOAN LIMIT */

  if ($loanType['loan_type_name'] == 'REGULAR') {
    $cbu_limit = $total_cbu * 3;
    $loan_limit = min($cbu_limit,$loanType['max_loan_amount']);
  }
  else {
    $loan_limit = $loanType['max_loan_amount'];
  }

  /* CHECK EXISTING LOAN */

  $checkStmt =
    $conn->prepare(

      "SELECT COUNT(*) total

       FROM member_loans

       WHERE member_id = ?

       AND loan_type_id = ?

       AND loan_status IN (

         'Pending Approval',
         'Approved',
         'Released',
         'On-going'

       )"

    );

  $checkStmt->bind_param(
    "ii",
    $member_id,
    $loan_type_id
  );

  $checkStmt->execute();

  $checkResult =
    $checkStmt->get_result();

  $checkRow =
    $checkResult->fetch_assoc();

  if ($checkRow['total'] > 0) {

    die("Existing loan for this type.");

  }

  /* SAVE LOAN */

  if (isset($_POST['save_loan'])) {

    $mode_of_payment = $_POST['mode_of_payment'];
    $loan_term_months = $_POST['loan_term_months'];
    $loan_purpose = $_POST['loan_purpose'];
    $amount_applied = $_POST['amount_applied'];

    $service_fee =
    $_POST['service_fee'];

    $insurance_fee =
    $_POST['insurance_fee'];

    $notarial_fee =
    $_POST['notarial_fee'];

    $cbu_amount =
    $_POST['capital_build_up'];

    $total_deductions =
    $_POST['total_deductions'];

    $net_proceeds =
    $_POST['net_proceeds'];

    /* INVALID NET PROCEED AMOUNT */
    if ($net_proceeds <= 0) {

      $_SESSION['msg'] =
      "invalid_net";

      header(
        "Location: loan_apply.php?loan_type_id=" .
        $loan_type_id
      );

      exit();

    }

    /* VALIDATE MAX AMOUNT */

    if ($amount_applied > $loan_limit) {
      die( "Amount exceeds allowed limit.");
    }

    /* VALIDATE MAX TERM */

    if ($loan_term_months > $loanType['max_month_duration']) {
      die("Term exceeds maximum.");
    }



    $loan_status =
      "Pending Approval";

    $application_date =
      date('Y-m-d');

    $stmt =
      $conn->prepare(

        "INSERT INTO member_loans
        (
          member_id,
          loan_type_id,
          application_date,
          mode_of_payment,
          loan_term_months,
          loan_purpose,
          amount_applied,

          service_fee,
          insurance_fee,
          notarial_fee,
          capital_build_up,
          total_deductions,
          net_proceeds,

          loan_status,
          created_at
        )

       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())"

      );

      $stmt->bind_param(

      "iissisddddddss",

      $member_id,
      $loan_type_id,
      $application_date,
      $mode_of_payment,
      $loan_term_months,
      $loan_purpose,
      $amount_applied,

      $service_fee,
      $insurance_fee,
      $notarial_fee,
      $cbu_amount,
      $total_deductions,
      $net_proceeds,

      $loan_status

      );

    $stmt->execute();

    $member_loan_id = $conn->insert_id;
    $loan_type_id = $_POST['loan_type_id'];



    // INSERT 2% CBU
    $cbu_amount = ($loanType['cbu_percentage']/100) * $amount_applied;

    $cbuStmt = $conn->prepare(

        "INSERT INTO member_cbu
        (
          member_id,
          transaction_date,
          amount,
          transaction_type,
          reference_id,
          remarks,
          created_at,
          status
        )

        VALUES
        (
          ?,NOW(),?,
          'Loan Deduction',
          ?,
          'Loan Deduction',
          NOW(),
          'Active'
        )"

      );

    $cbuStmt->bind_param(
      "idi",
      $member_id,
      $cbu_amount,
      $loan_type_id
    );

    $cbuStmt->execute();




      /* CREATE APPROVAL QUEUE */



      /* CREATE MULTI-APPROVER QUEUE */

$sql = "

INSERT INTO loan_approvals
(
  member_loan_id,
  position_id,
  approver_id,
  sequence_order,
  status
)

SELECT

  ?,
  lts.position_id,
  o.member_id,
  lts.sequence_order,
  'Pending'

FROM loan_type_signatories lts

INNER JOIN officials o
  ON o.position_id =
     lts.position_id

INNER JOIN positions p
  ON p.position_id =
     o.position_id

WHERE
  lts.loan_type_id=?

  AND p.position_name =
      'Credit Committee'

  AND o.sub_position =
      'Chairman'

  AND o.status='active'

ORDER BY
  lts.sequence_order

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $member_loan_id,
  $loan_type_id
);

$stmt->execute();


/* DEBUG CHECK */

if ($stmt->affected_rows == 0) {

  die("No approval records inserted");

}

$stmt->close();


    /* SUCCESS */

      $_SESSION['msg'] =
      "loan_saved";

      header(
        "Location: myloans.php"
      );

      exit();


    // Save Activity

    logActivity(

      $conn,
      "loan_created",
      "New loan created",
      $loan_type_id

    );

    $_SESSION['msg'] = "saved";

    header(
      "Location: member_loans.php"
    );

    exit();

  }

  include 'header.php';

?>

  <?php
    if (isset($_SESSION['msg'])) {
      $msg = $_SESSION['msg'];
    
    unset($_SESSION['msg']);
  ?>


  <script>

    document.addEventListener("DOMContentLoaded", function() {

    <?php 
      if ($msg == "invalid_net") : ?>
      Swal.fire({
      icon: "error",
      title: "Invalid Net Proceeds",
      text: "Net proceeds must be greater than zero. Please review loan deductions."
      });
    <?php endif ?>

    <?php if ($msg == "saved") : ?>
      Swal.fire({
        icon: "success",
        title: "Saved!",
        text: "User successfully saved."
      });
    <?php endif ?>

    <?php if ($msg == "deleted") : ?>

      Swal.fire({
        icon: "success",
        title: "Deleted!",
        text: "User successfully deleted."
      });
    <?php endif ?>

    });

  </script>

  <?php

  }

?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>
      Apply Loan — <?= htmlspecialchars($loanType['loan_type_name']) ?>
    </h1>

    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="member_loans.php">
            My Loans
          </a>
        </li>
        <li class="breadcrumb-item active">
          Apply Loan
        </li>
      </ol>
    </nav>

  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              Loan Details
            </h5>


            <?php if ($loanType['loan_type_name'] == 'REGULAR') { ?>
              <div class="alert alert-info">
                Your Total CBU:
                <strong> ₱<?= number_format($total_cbu,2) ?></strong>
                <br>
                Maximum Loan: 3 × CBU
              </div>

            <?php } ?>

            <form  method="POST"  id="loanForm">

<div id="step1">

  <!-- loan details fields here -->
              <input
                type="hidden"
                name="computed_flag"
                id="computed_flag"
                value="0">

              <input
                type="hidden"
                name="loan_type_id"
                value="<?= $loan_type_id ?>">

                <input type="hidden"
                name="service_fee"
                value="<?= $loanType['service_fee'] ?>">

                <input type="hidden"
                name="notarial_fee"
                value="<?= $loanType['notary_fee'] ?>">

                <input type="hidden"
                name="insurance_fee"
                id="insurance_fee_input">

                <input type="hidden"
                name="capital_build_up"
                id="cbu_input">

                <input type="hidden"
                name="total_deductions"
                id="total_input">

                <input type="hidden"
                name="net_proceeds"
                id="net_input">

              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">

                    <label>
                      Mode of Payment
                    </label>

                    <select
                      name="mode_of_payment"
                      class="form-control"
                      required>

                      <option value="Salary">
                        Salary
                      </option>

<!--                       <option value="OTC">
                        OTC
                      </option> -->

                    </select>

                  </div>
                </div>

                <div class="col-md-4">
                  <div class="mb-3">

                    <label>
                      Amount Applied
                      <small class="text-muted">
                        Max Allowed: ₱<?= number_format($loan_limit, 2) ?>
                      </small>
                    </label>

                    <input
                      type="number"
                      step="1"
                      name="amount_applied"
                      id="amount_applied"
                      class="form-control"
                      max="<?= $loan_limit ?>"
                      required>

                  </div>
                </div>

                <div class="col-md-4">
                  <div class="mb-3">

                    <label>

                      Loan Term (Months)

                      ( Max:
                      <?= $loanType['max_month_duration'] ?> )

                    </label>

                    <input
                      type="number"
                      name="loan_term_months"
                      id="loan_term_months"
                      class="form-control"
                      max="<?= $loanType['max_month_duration'] ?>"
                      min="1"
                      required>

                  </div>
                </div>
              </div>

              



              <div class="mb-3">

                <label>
                  Loan Purpose
                </label>

                <textarea
                  name="loan_purpose"
                  class="form-control"
                  rows="3"
                  required></textarea>

              </div>

</div>

<!-- DEDUCTIONS -->
<div id="step2" style="display:none;">

  <!-- deduction table here -->
              <div class="card mt-3">

                <div class="card-body">

                  <h6 class="card-title">

                    Deductions

                  </h6>

<table class="table table-bordered table-sm">

<thead class="table-light">

<tr>

<th colspan="2" class="text-center">

Loan Computation Summary

</th>

</tr>

</thead>

<tbody>

<tr class="table-primary">

<td>

<strong>Loan Amount Applied</strong>

</td>

<td class="text-end">

<strong>

₱<span id="loan_amount_display">
0.00
</span>

</strong>

</td>

</tr>



<tr>

<td>Service Fee</td>

<td class="text-end">

₱<?= number_format(
  $loanType['service_fee'],
  2
) ?>

</td>

</tr>



<tr>

<td>Insurance</td>

<td class="text-end">

₱<span id="insurance_display">
0.00
</span>

</td>

</tr>



<tr>

<td>Notarial Fee</td>

<td class="text-end">

₱<?= number_format(
  $loanType['notary_fee'],
  2
) ?>

</td>

</tr>



<tr>

<td>

CBU (
<?= $loanType['cbu_percentage'] ?>%
)

</td>

<td class="text-end">

₱<span id="cbu_display">
0.00
</span>

</td>

</tr>



<tr class="table-warning fw-bold">

<td>Total Deductions</td>

<td class="text-end">

₱<span id="total_deductions">
0.00
</span>

</td>

</tr>



<tr class="table-success fw-bold">

<td>Net Proceeds</td>

<td class="text-end">

₱<span id="net_proceeds">
0.00
</span>

</td>

</tr>

</tbody>

</table>

                </div>

              </div>

</div>




<button
  type="button"
  id="next_btn"
  class="btn btn-primary">

  Next →

</button>

<button
  type="submit"
  name="save_loan"
  id="apply_btn"
  class="btn btn-success"
  style="display:none;">

  Apply Loan

</button>

<button
  type="button"
  id="back_btn"
  class="btn btn-secondary"
  style="display:none;">

  ← Back

</button>

              <a
                href="member_loans.php"
                class="btn btn-secondary">

                Cancel

              </a>

            </form>

          </div>

        </div>

      </div>

    </div>

  </section>

<!-- </main> -->





  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>


<script>

document.addEventListener(
  "DOMContentLoaded",
  function(){

    /* ==============================
       ELEMENT REFERENCES
    ============================== */

    const loanForm =
      document.getElementById("loanForm");

    const amountInput =
      document.getElementById("amount_applied");

    const termInput =
      document.getElementById("loan_term_months");

    const insuranceDisplay =
      document.getElementById("insurance_display");

    const loanAmountDisplay =
      document.getElementById("loan_amount_display");

    const cbuDisplay =
      document.getElementById("cbu_display");

    const totalDisplay =
      document.getElementById("total_deductions");

    const netDisplay =
      document.getElementById("net_proceeds");

    const computedFlag =
      document.getElementById("computed_flag");


    /* ==============================
       STEP ELEMENTS
    ============================== */

    const step1 =
      document.getElementById("step1");

    const step2 =
      document.getElementById("step2");

    const nextBtn =
      document.getElementById("next_btn");

    const backBtn =
      document.getElementById("back_btn");

    const applyBtn =
      document.getElementById("apply_btn");


    /* ==============================
       PHP VALUES
    ============================== */

    const serviceFee =
      <?= floatval($loanType['service_fee']) ?>;

    const notaryFee =
      <?= floatval($loanType['notary_fee']) ?>;

    const cbuPercent =
      <?= floatval($loanType['cbu_percentage']) ?>;

    const hasInsurance =
      "<?= strtolower(trim($loanType['insurance_fee'])) ?>";



    /* ==============================
       COMPUTE FUNCTION
    ============================== */

    function computeLoan(){

      let amount =
        Number(amountInput.value);

      let months =
        Number(termInput.value);

      /* DISPLAY LOAN AMOUNT */

      loanAmountDisplay.innerText =
        amount.toLocaleString(
          undefined,
          {
            minimumFractionDigits:2,
            maximumFractionDigits:2
          }
        );

      if (!amount || !months) {

        Swal.fire({
          icon: "warning",
          title: "Missing Information",
          text:
            "Enter loan amount and term first."
        });

        return false;

      }

      /* INSURANCE */

      let insurance = 0;

      if (hasInsurance === "yes") {

        insurance =
          1.35 *
          (amount / 1000) *
          months;

      }


      /* CBU */

      let cbu =
        (cbuPercent / 100)
        * amount;


      /* TOTAL */

      let total =
        serviceFee +
        notaryFee +
        insurance +
        cbu;


      /* NET */

      let net =
        amount - total;

      if (net < 0) net = 0;

      /* SAVE VALUES FOR SUBMISSION */

      document.getElementById(
        "insurance_fee_input"
      ).value = insurance;

      document.getElementById(
        "cbu_input"
      ).value = cbu;

      document.getElementById(
        "total_input"
      ).value = total;

      document.getElementById(
        "net_input"
      ).value = net;


      /* DISPLAY */

      insuranceDisplay.innerText =
        insurance.toLocaleString(
          undefined,
          {
            minimumFractionDigits:2,
            maximumFractionDigits:2
          }
        );

      cbuDisplay.innerText =
        cbu.toLocaleString(
          undefined,
          {
            minimumFractionDigits:2,
            maximumFractionDigits:2
          }
        );

      totalDisplay.innerText =
        total.toLocaleString(
          undefined,
          {
            minimumFractionDigits:2,
            maximumFractionDigits:2
          }
        );

      netDisplay.innerText =
        net.toLocaleString(
          undefined,
          {
            minimumFractionDigits:2,
            maximumFractionDigits:2
          }
        );

      /* MARK COMPUTED */

      computedFlag.value = "1";

      return true;

    }



    /* ==============================
       NEXT BUTTON
    ============================== */

    nextBtn.addEventListener(
      "click",
      function(){

        let ok =
          computeLoan();

        if (!ok) return;

        /* LOCK INPUTS */

        amountInput.readOnly = true;
        termInput.readOnly = true;

        /* SHOW STEP 2 */

        step1.style.display = "none";
        step2.style.display = "block";

        nextBtn.style.display = "none";
        applyBtn.style.display = "inline-block";
        backBtn.style.display = "inline-block";

      }
    );



    /* ==============================
       BACK BUTTON
    ============================== */

    backBtn.addEventListener(
      "click",
      function(){

        /* UNLOCK INPUTS */

        amountInput.readOnly = false;
        termInput.readOnly = false;

        /* RETURN STEP */

        step1.style.display = "block";
        step2.style.display = "none";

        nextBtn.style.display = "inline-block";
        applyBtn.style.display = "none";
        backBtn.style.display = "none";

        /* RESET FLAG */

        computedFlag.value = "0";

      }
    );



    /* ==============================
       RESET FLAG IF USER CHANGES DATA
    ============================== */

    amountInput.addEventListener(
      "input",
      function(){

        computedFlag.value = "0";

      }
    );

    termInput.addEventListener(
      "input",
      function(){

        computedFlag.value = "0";

      }
    );



    /* ==============================
       PREVENT INVALID SUBMIT
    ============================== */

    loanForm.addEventListener(
      "submit",
      function(e){

        if (computedFlag.value !== "1") {

          e.preventDefault();

          Swal.fire({
            icon: "error",
            title: "Review Required",
            text:
              "Please review computation before applying."
          });

          return false;

        }

      }
    );



    /* ==============================
       SUCCESS MESSAGE
    ============================== */

    <?php
    if (
      isset($_SESSION['msg']) &&
      $_SESSION['msg'] == "saved"
    ) {
    ?>

    Swal.fire({

      icon: "success",

      title: "Loan Submitted",

      text:
        "Your loan application was submitted."

    });

    <?php
    unset($_SESSION['msg']);
    }
    ?>

  }

);

</script>

</body>

</html>