<?php

session_start();

require '../db/dbconnect.php';


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


/* GET LOAN */

// if (!isset($_GET['member_loan_id'])) {

//   die("Loan not specified.");

// }

// $member_loan_id =
// intval($_GET['member_loan_id']);

$member_id = $_SESSION['member_id'];


/* DELETE */

if (isset($_GET['delete'])) {

  $id =
  intval($_GET['delete']);

  $stmt =
  $conn->prepare(

  "DELETE FROM loan_payments
   WHERE payment_id = ?"

  );

  $stmt->bind_param(
  "i",
  $id
  );

  $stmt->execute();

  $_SESSION['msg']="deleted";

  header(
  "Location: loan_payments.php?member_loan_id=".$member_loan_id
  );

  exit();

}


/* ADD / UPDATE */

if (isset($_POST['save_payment'])) {

  $payment_id =
  $_POST['payment_id'];

  $payment_date =
  $_POST['payment_date'];

  $payment_amount =
  $_POST['payment_amount'];

  $payment_type =
  $_POST['payment_type'];

  $remarks =
  $_POST['remarks'];


/* INSERT */

if ($payment_id == "") {

  $stmt =
  $conn->prepare(

  "INSERT INTO loan_payments
  (member_loan_id,
   payment_date,
   payment_amount,
   payment_type,
   remarks)

  VALUES (?,?,?,?,?)"

  );

  $stmt->bind_param(

  "isdss",

  $member_loan_id,
  $payment_date,
  $payment_amount,
  $payment_type,
  $remarks

  );

  $stmt->execute();

  logActivity(

    $conn,

    "payment_added",

    "Loan payment recorded",

    $member_loan_id

  );

}


/* UPDATE */

else {

  $stmt =
  $conn->prepare(

  "UPDATE loan_payments
   SET payment_date=?,
       payment_amount=?,
       payment_type=?,
       remarks=?

   WHERE payment_id=?"

  );

  $stmt->bind_param(

  "sdssi",

  $payment_date,
  $payment_amount,
  $payment_type,
  $remarks,
  $payment_id

  );

  $stmt->execute();

}


$_SESSION['msg']="saved";

header(
"Location: loan_payments.php?member_loan_id=".$member_loan_id
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

      <h1>Loan Payments</h1>

      <nav>

        <ol class="breadcrumb">

          <li class="breadcrumb-item">
            <a href="users-profile.php">
              Home
            </a>
          </li>

          <li class="breadcrumb-item active">
            Loan Payments
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

                Payments

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#paymentModal">

                  Add Payment

                </button>

              </h5>



              <table class="table datatable">

                <thead>

                  <tr>

                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Remarks</th>
                    <th>Actions</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query =
                  $conn->prepare(

                  "SELECT *
                   FROM loan_payments
                   WHERE member_loan_id = ?
                   ORDER BY payment_date DESC"

                  );

                  $query->bind_param(
                  "i",
                  $member_loan_id
                  );

                  $query->execute();

                  $result =
                  $query->get_result();

                  while ($row =
                  $result->fetch_assoc()) {

                  ?>

                  <tr>

                  <td>
                  <?= htmlspecialchars($row['payment_date']) ?>
                  </td>

                  <td>
                  <?= number_format(
                       $row['payment_amount'],
                       2
                     ) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['payment_type']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['remarks']) ?>
                  </td>

                  <td width="1%" style="white-space:nowrap">

                  <button

                  class="btn btn-outline-warning btn-sm"

                  onclick='editPayment(
                  <?= $row["payment_id"] ?>,
                  <?= json_encode($row["payment_date"]) ?>,
                  <?= $row["payment_amount"] ?>,
                  <?= json_encode($row["payment_type"]) ?>,
                  <?= json_encode($row["remarks"]) ?>
                  )'

                  >

                  Edit

                  </button>



                  <button

                  class="btn btn-danger btn-sm"

                  onclick="confirmDeletePayment(
                  <?= $row['payment_id'] ?>
                  )"

                  >

                  Delete

                  </button>

                  </td>

                  </tr>

                  <?php

                  }

                  ?>

                </tbody>

              </table>



            </div>

          </div>

        </div>

      </div>

    </section>

    <div class="modal fade" id="paymentModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">

              Loan Payment Form

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
                name="payment_id"
                id="payment_id">



              <div class="row">

                <!-- Payment Date -->

                <div class="col-md-6">

                  <div class="mb-3">

                    <label>

                      Payment Date

                    </label>

                    <input
                      type="date"
                      name="payment_date"
                      id="payment_date"
                      class="form-control"
                      value="<?= date('Y-m-d') ?>"
                      required>

                  </div>

                </div>



                <!-- Payment Amount -->

                <div class="col-md-6">

                  <div class="mb-3">

                    <label>

                      Payment Amount

                    </label>

                    <input
                      type="number"
                      step="0.01"
                      name="payment_amount"
                      id="payment_amount"
                      class="form-control"
                      required>

                  </div>

                </div>



                <!-- Payment Type -->

                <div class="col-md-6">

                  <div class="mb-3">

                    <label>

                      Payment Type

                    </label>

                    <select
                      name="payment_type"
                      id="payment_type"
                      class="form-control"
                      required>

                      <option value="Cash">
                        Cash
                      </option>

                      <option value="Salary Deduction">
                        Salary Deduction
                      </option>

                      <option value="Bank Transfer">
                        Bank Transfer
                      </option>

                    </select>

                  </div>

                </div>



                <!-- Remarks -->

                <div class="col-md-12">

                  <div class="mb-3">

                    <label>

                      Remarks

                    </label>

                    <textarea
                      name="remarks"
                      id="remarks"
                      class="form-control"
                      rows="3"></textarea>

                  </div>

                </div>

              </div>



              <button
                type="submit"
                name="save_payment"
                class="btn btn-primary">

                Save Payment

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

    /* EDIT PAYMENT */

    function editPayment(
      id,
      payment_date,
      payment_amount,
      payment_type,
      remarks
    ){

      document.getElementById(
        "payment_id"
      ).value = id;

      document.getElementById(
        "payment_date"
      ).value = payment_date;

      document.getElementById(
        "payment_amount"
      ).value = payment_amount;

      document.getElementById(
        "payment_type"
      ).value = payment_type;

      document.getElementById(
        "remarks"
      ).value = remarks;


      new bootstrap.Modal(
        document.getElementById(
          "paymentModal"
        )
      ).show();

    }



    /* DELETE PAYMENT */

    function confirmDeletePayment(id){

      Swal.fire({

        title: "Delete Payment?",
        text: "This action cannot be undone.",
        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#d33",

        confirmButtonText: "Delete"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location =
            "loan_payments.php?member_loan_id=<?= $member_loan_id ?>&delete=" + id;

        }

      });

    }



    /* RESET MODAL WHEN CLOSED */

    document.addEventListener(
      "DOMContentLoaded",
      function(){

        var modal =
          document.getElementById(
            "paymentModal"
          );

        modal.addEventListener(
          "hidden.bs.modal",
          function(){

            document.getElementById(
              "payment_id"
            ).value = "";

            document.getElementById(
              "payment_date"
            ).value = "<?= date('Y-m-d') ?>";

            document.getElementById(
              "payment_amount"
            ).value = "";

            document.getElementById(
              "payment_type"
            ).value = "Cash";

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