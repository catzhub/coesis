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

  // if (!isset($_GET['member_id'])) {
  //   die("Member not specified.");
  // }

  $member_id = $_SESSION['member_id'];



  /* CHECK EXISTING LOAN PER TYPE */

  function hasExistingLoanType(
    $conn,
    $member_id,
    $loan_type_id
  ){

    $stmt =
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

    $stmt->bind_param(
      "ii",
      $member_id,
      $loan_type_id
    );

    $stmt->execute();

    $result =
    $stmt->get_result();

    $row =
    $result->fetch_assoc();

    return $row['total'] > 0;

  }


 
  /* ADD / UPDATE */

  if (isset($_POST['save_loan'])) {

    $member_id  = $member_id;
    $member_loan_id = $_POST['member_loan_id'] ?? "";

    $loan_type_id     = $_POST['loan_type_id'];
    $application_date = date('Y-m-d');
    $mode_of_payment  = $_POST['mode_of_payment'];
    $loan_term_years  = $_POST['loan_term_years'];
    $loan_purpose     = $_POST['loan_purpose'];

    $amount_applied   = $_POST['amount_applied'];

    $loan_status      = "Pending";

    /* INSERT */

    if ($member_loan_id == "") {

      $stmt = $conn->prepare(
        "INSERT INTO member_loans
        (member_id,
         loan_type_id,
         application_date,
         mode_of_payment,
         loan_term_years,
         loan_purpose,
         amount_applied,
         loan_status,
         created_at)
        VALUES (?,?,?,?,?,?,?,?,NOW())"
      );

      $stmt->bind_param(
        "iissssds",
        $member_id,
        $loan_type_id,
        $application_date,
        $mode_of_payment,
        $loan_term_years,
        $loan_purpose,
        $amount_applied,
        $loan_status
      );

      $stmt->execute();

      /* AFTER LOAN INSERT */
      $member_loan_id = $conn->insert_id;

      /* CREATE APPROVAL QUEUE */

      $sql = "

      INSERT INTO loan_approvals
      (
        member_loan_id,
        position_id,
        sequence_order
      )

      SELECT
        ?,
        position_id,
        sequence_order

      FROM loan_type_signatories

      WHERE loan_type_id=?

      ORDER BY sequence_order

      ";

      $stmt =
      $conn->prepare($sql);

      $stmt->bind_param(
        "ii",
        $member_loan_id,
        $loan_type_id
      );

      $stmt->execute();


      $stmt->close();


    /* SUCCESS */

      $_SESSION['msg'] =
      "loan_saved";

      header(
        "Location: myloans.php"
      );

      exit();



      require '../include/activity_log.php';

      logActivity(

        $conn,

        "loan_created",

        "New loan created",

        $member_loan_id

      );

    }

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

  <?php if ($msg == "saved") { ?>

    Swal.fire({
      icon: "success",
      title: "Saved!",
      text: "User successfully saved."
    });

  <?php } ?>

  <?php if ($msg == "deleted") { ?>

    Swal.fire({
      icon: "success",
      title: "Deleted!",
      text: "User successfully deleted."
    });

  <?php } ?>

  });

  </script>

  <?php

  }

?>

  <main id="main" class="main">
    <?php //var_dump($_SESSION) ?>

    <div class="pagetitle">
      <h1>My Loans</h1>

      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="users-profile.php">Home</a>
          </li>
          <li class="breadcrumb-item active">
            Member Loans
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
                My Loans
                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#loanModal">
                  Add Loan
                </button>
              </h5>

              <table class="table datatable">
                <thead>
                  <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Loan Type</th>
                    <th class="text-center">Term</th>
                    <th class="text-center">Amount Applied</th>
                    <th class="text-center">Net Proceeds</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $query = "
                    SELECT ml.*, lt.loan_type_name
                    FROM member_loans ml
                    LEFT JOIN loan_types lt ON lt.loan_type_id = ml.loan_type_id
                    WHERE ml.member_id = ?
                    ORDER BY lt.loan_type_name DESC
                    ";

                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $member_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                  ?>

                  <tr>
                    <td class="text-center"><?= htmlspecialchars($row['application_date']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['loan_type_name']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['loan_term_months']) ?> months</td>
                    <td class="text-center"><?= number_format($row['amount_applied'], 2) ?></td>
                    <td class="text-center"><?= number_format($row['net_proceeds'], 2) ?></td>
                    <td class="text-center" width= "1%" style="white-space:nowrap">
                      <?php
                        $status = $row['loan_status'];
                        $badge = "secondary";
                        if ($status == "Pending Approval")
                          $badge = "warning";
                        if ($status == "Approved")
                          $badge = "info";
                        if ($status == "Released")
                          $badge = "primary";
                        if ($status == "On-going")
                          $badge = "success";
                        if ($status == "Paid")
                          $badge = "dark";
                        if ($status == "Disapproved")
                          $badge = "danger";
                      ?>
                      <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="modal fade" id="loanModal">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Member Loan Form
            </h5>

            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal">
            </button>

          </div>



          <div class="modal-body">

            <div class="row">

            <?php

            $loanTypes =
            $conn->query(

            "SELECT
              loan_type_id,
              loan_type_name,
              max_loan_amount,
              max_month_duration

             FROM loan_types

             WHERE status='active'

             ORDER BY loan_type_name"

            );

            while ($lt =
            $loanTypes->fetch_assoc()) {

            /* CHECK IF MEMBER HAS ACTIVE LOAN
               FOR THIS TYPE */

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
            $lt['loan_type_id']
            );

            $checkStmt->execute();

            $checkResult =
            $checkStmt->get_result();

            $checkRow =
            $checkResult->fetch_assoc();

            $hasLoan =
            $checkRow['total'] > 0;

            ?>

            <div class="col-md-4 mb-3">

            <div class="card h-100">

            <div class="card-body text-center">

            <h5 class="card-title">

            <?= htmlspecialchars(
                 $lt['loan_type_name']
               ) ?>

            </h5>

            <p class="mb-1">

            <strong>Max Amount:</strong>

            ₱<?= number_format(
                  $lt['max_loan_amount'],
                  2
                ) ?>

            </p>

            <p>

            <strong>Max Months:</strong>

            <?= $lt['max_month_duration'] ?>

            </p>

            <?php if ($hasLoan) { ?>

            <button
              class="btn btn-secondary btn-sm"
              disabled>

            Existing Loan

            </button>

            <?php } else { ?>

            <a
            href="loan_apply.php?loan_type_id=<?= $lt['loan_type_id'] ?>"

            class="btn btn-primary btn-sm">

            Apply

            </a>

            <?php } ?>

            </div>

            </div>

            </div>

            <?php } ?>

            </div>

          </div>

        </div>

      </div>

    </div>

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

    /* RESET MODAL WHEN CLOSED */

    document.addEventListener(
      "DOMContentLoaded",
      function(){

        var modal =
          document.getElementById(
            "loanModal"
          );

        modal.addEventListener(
          "hidden.bs.modal",
          function(){

            document.getElementById(
              "member_loan_id"
            ).value = "";

            document.getElementById(
              "application_date"
            ).value = "";

            document.getElementById(
              "mode_of_payment"
            ).value = "";

            document.getElementById(
              "loan_term_years"
            ).value = "";

            document.getElementById(
              "loan_purpose"
            ).value = "";

            document.getElementById(
              "amount_applied"
            ).value = "";

            document.getElementById(
              "amount_granted"
            ).value = "";

            document.getElementById(
              "remarks"
            ).value = "";

          }

        );

      }

    );

  </script>

</body>

</html>