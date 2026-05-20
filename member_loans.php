<?php

  session_start();

  require 'db/dbconnect.php';

  /* ACCESS CONTROL */

  if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
  }

  if ($_SESSION['user_role'] !== 'admin') {
    die("Access Denied");
  }


  /* GET MEMBER */

  if (!isset($_GET['member_id'])) {
    die("Member not specified.");
  }

  $member_id = intval($_GET['member_id']);


  /* DELETE */

  if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare(
      "DELETE FROM member_loans
       WHERE member_loan_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['msg'] = "deleted";

    header(
      "Location: member_loans.php?member_id=" . $member_id
    );

    exit();

  }


  /* ADD / UPDATE */

  if (isset($_POST['save_loan'])) {

    $member_loan_id  = $_POST['member_loan_id'];

    $loan_type_id     = $_POST['loan_type_id'];
    $application_date = $_POST['application_date'];
    $mode_of_payment  = $_POST['mode_of_payment'];
    $loan_term_years  = $_POST['loan_term_years'];
    $loan_purpose     = $_POST['loan_purpose'];

    $amount_applied   = $_POST['amount_applied'];
    $amount_granted   = $_POST['amount_granted'];

    $loan_status      = $_POST['loan_status'];
    $remarks          = $_POST['remarks'];


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
         amount_granted,
         loan_status,
         remarks,
         created_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,NOW())"
      );

      $stmt->bind_param(
        "iissssddss",
        $member_id,
        $loan_type_id,
        $application_date,
        $mode_of_payment,
        $loan_term_years,
        $loan_purpose,
        $amount_applied,
        $amount_granted,
        $loan_status,
        $remarks
      );

      $stmt->execute();

    }


    /* UPDATE */

    else {

      $stmt = $conn->prepare(
        "UPDATE member_loans
         SET loan_type_id=?,
             application_date=?,
             mode_of_payment=?,
             loan_term_years=?,
             loan_purpose=?,
             amount_applied=?,
             amount_granted=?,
             loan_status=?,
             remarks=?
         WHERE member_loan_id=?"
      );

      $stmt->bind_param(
        "issssddssi",
        $loan_type_id,
        $application_date,
        $mode_of_payment,
        $loan_term_years,
        $loan_purpose,
        $amount_applied,
        $amount_granted,
        $loan_status,
        $remarks,
        $member_loan_id
      );

      $stmt->execute();

    }

    $_SESSION['msg'] = "saved";

    header(
      "Location: member_loans.php?member_id=" . $member_id
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

    <div class="pagetitle">
      <h1>Member Loans</h1>

      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="members.php">Members</a>
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

              <h4 class="card-title">
                <?php  
                $query = "
                  SELECT m.full_name
                  FROM member_loans ml
                  LEFT JOIN members m
                    ON m.member_id = ml.member_id
                  WHERE ml.member_id = ?
                  GROUP BY ml.member_id
                  ";
                  $stmt = $conn->prepare($query);
                  $stmt->bind_param("i", $member_id);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $row = $result->fetch_assoc();
                  echo $row['full_name'];
                ?>

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#loanModal">

                  Add Loan

                </button>

              </h4>



              <table class="table datatable">

                <thead>

                  <tr>

                    <th>Date</th>
                    <th>Loan Type</th>
                    <th>Mode</th>
                    <th>Term</th>
                    <th>Applied</th>
                    <th>Granted</th>
                    <th>Status</th>
                    <th>Actions</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                    $query = "
                    SELECT
                      ml.*,
                      lt.loan_type_name

                    FROM member_loans ml

                    LEFT JOIN loan_types lt
                      ON lt.loan_type_id = ml.loan_type_id

                    WHERE ml.member_id = ?

                    ORDER BY ml.application_date DESC
                    ";

                    $stmt = $conn->prepare($query);

                    $stmt->bind_param("i", $member_id);

                    $stmt->execute();

                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                  <td>
                  <?= htmlspecialchars($row['application_date']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['loan_type_name']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['mode_of_payment']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['loan_term_years']) ?>
                  </td>

                  <td>
                  <?= number_format($row['amount_applied'], 2) ?>
                  </td>

                  <td>
                  <?= number_format($row['amount_granted'], 2) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['loan_status']) ?>
                  </td>

                  <td width="1%" style="white-space:nowrap">

                  <button
                    class="btn btn-outline-warning btn-sm"

                    onclick='editLoan(
                      <?= $row["member_loan_id"] ?>,
                      <?= $row["loan_type_id"] ?>,
                      <?= json_encode($row["application_date"]) ?>,
                      <?= json_encode($row["mode_of_payment"]) ?>,
                      <?= json_encode($row["loan_term_years"]) ?>,
                      <?= json_encode($row["loan_purpose"]) ?>,
                      <?= $row["amount_applied"] ?>,
                      <?= $row["amount_granted"] ?>,
                      <?= json_encode($row["loan_status"]) ?>,
                      <?= json_encode($row["remarks"]) ?>
                    )'>

                    Edit

                  </button>


                  <button
                    class="btn btn-danger btn-sm"
                    onclick="confirmDeleteLoan(
                      <?= $row['member_loan_id'] ?>
                    )">

                    Delete

                  </button>

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

            <form method="POST">

              <input
                type="hidden"
                name="member_loan_id"
                id="member_loan_id">



              <div class="row">

                <!-- Loan Type -->

                <div class="col-md-6">

                  <div class="mb-3">

                    <label>Loan Type</label>

                    <select
                      name="loan_type_id"
                      id="loan_type_id"
                      class="form-control"
                      required>

                      <?php

                        $lt_query =
                        "SELECT *
                         FROM loan_types
                         WHERE status='active'
                         ORDER BY loan_type_name";

                        $lt_result = $conn->query($lt_query);

                        while ($lt = $lt_result->fetch_assoc()) {

                      ?>

                      <option
                      value="<?= $lt['loan_type_id'] ?>">

                      <?= htmlspecialchars($lt['loan_type_name']) ?>

                      </option>

                      <?php } ?>

                    </select>

                  </div>

                </div>



                <!-- Application Date -->

                <div class="col-md-6">

                  <div class="mb-3">

                    <label>Application Date</label>

                    <input
                      type="date"
                      name="application_date"
                      id="application_date"
                      class="form-control"
                      required>

                  </div>

                </div>



                <!-- COLUMN 1 -->

                <div class="col-md-4">

                  <div class="mb-3">

                    <label>Mode of Payment</label>                      

                    <select
                      type="text"
                      name="mode_of_payment"
                      id="mode_of_payment"
                      class="form-control" required>

                      <option value="Salary">
                        Salary
                      </option>

                      <option value="OTC">
                        OTC
                      </option>

                    </select>

                  </div>



                  <div class="mb-3">

                    <label>Loan Term (Years)</label>

                    <input
                      type="number"
                      name="loan_term_years"
                      id="loan_term_years"
                      class="form-control" required>

                  </div>

                </div>



                <!-- COLUMN 2 -->

                <div class="col-md-4">

                  <div class="mb-3">

                    <label>Loan Purpose</label>

                    <input
                      type="text"
                      name="loan_purpose"
                      id="loan_purpose"
                      class="form-control" required>

                  </div>



                  <div class="mb-3">

                    <label>Amount Applied</label>

                    <input
                      type="number"
                      step="0.01"
                      name="amount_applied"
                      id="amount_applied"
                      class="form-control" required>

                  </div>

                </div>



                <!-- COLUMN 3 -->

                <div class="col-md-4">

                  <div class="mb-3">

                    <label>Amount Granted</label>

                    <input
                      type="number"
                      step="0.01"
                      name="amount_granted"
                      id="amount_granted"
                      class="form-control" required>

                  </div>



                  <div class="mb-3">

                    <label>Loan Status</label>

                    <select name="loan_status" id="loan_status" class="form-control" required>

                      <option value="Pending">
                        Pending
                      </option>

                      <option value="Approved">
                        Approved
                      </option>

                      <option value="rReturnedeturned">
                        Returned
                      </option>

                      <option value="Disapproved">
                        Disapproved
                      </option>

                      <option value="Paid">
                        Paid
                      </option>

                    </select>

                  </div>



                  <div class="mb-3">

                    <label>Remarks</label>

                    <textarea
                      name="remarks"
                      id="remarks"
                      class="form-control"
                      rows="4"></textarea>

                  </div>

                </div>



              </div>



              <button
                type="submit"
                name="save_loan"
                class="btn btn-primary">

                Save

              </button>

            </form>

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
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>


  <script>

    /* EDIT LOAN */

    function editLoan(
      id,
      loan_type_id,
      application_date,
      mode_of_payment,
      loan_term_years,
      loan_purpose,
      amount_applied,
      amount_granted,
      loan_status,
      remarks
    ){

      document.getElementById(
        "member_loan_id"
      ).value = id;

      document.getElementById(
        "loan_type_id"
      ).value = loan_type_id;

      document.getElementById(
        "application_date"
      ).value = application_date;

      document.getElementById(
        "mode_of_payment"
      ).value = mode_of_payment;

      document.getElementById(
        "loan_term_years"
      ).value = loan_term_years;

      document.getElementById(
        "loan_purpose"
      ).value = loan_purpose;

      document.getElementById(
        "amount_applied"
      ).value = amount_applied;

      document.getElementById(
        "amount_granted"
      ).value = amount_granted;

      document.getElementById(
        "loan_status"
      ).value = loan_status;

      document.getElementById(
        "remarks"
      ).value = remarks;


      new bootstrap.Modal(
        document.getElementById(
          "loanModal"
        )
      ).show();

    }



    /* DELETE LOAN */

    function confirmDeleteLoan(id){

      Swal.fire({

        title: "Delete Loan?",
        text: "This action cannot be undone.",
        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#d33",

        confirmButtonText: "Delete"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location =
            "member_loans.php?member_id=<?= $member_id ?>&delete=" + id;

        }

      });

    }



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