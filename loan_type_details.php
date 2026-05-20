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


  /* DELETE */

  if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare(
      "DELETE FROM loan_type_details
       WHERE loan_type_detail_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['msg'] = "deleted";

    header("Location: loan_type_details.php");
    exit();

  }


   /* ADD / UPDATE */

  if (isset($_POST['save_detail'])) {

    $loan_type_detail_id = $_POST['loan_type_detail_id'];

    $loan_type_id          = $_POST['loan_type_id'];
    $has_term_years        = $_POST['has_term_years'];
    $has_mode_of_payment   = $_POST['has_mode_of_payment'];
    $has_purpose           = $_POST['has_purpose'];
    $has_standing_balance  = $_POST['has_standing_balance'];
    $has_previous_nthp     = $_POST['has_previous_nthp'];
    $has_amortization      = $_POST['has_amortization'];
    $has_notarial_fee      = $_POST['has_notarial_fee'];
    $has_insurance_fee     = $_POST['has_insurance_fee'];
    $has_service_fee       = $_POST['has_service_fee'];
    $status                = $_POST['status'];


    /* INSERT */

    if ($loan_type_detail_id == "") {

      $stmt = $conn->prepare(
        "INSERT INTO loan_type_details
        (loan_type_id,
         has_term_years,
         has_mode_of_payment,
         has_purpose,
         has_standing_balance,
         has_previous_nthp,
         has_amortization,
         has_notarial_fee,
         has_insurance_fee,
         has_service_fee,
         status)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)"
      );

      $stmt->bind_param(
        "issssssssss",
        $loan_type_id,
        $has_term_years,
        $has_mode_of_payment,
        $has_purpose,
        $has_standing_balance,
        $has_previous_nthp,
        $has_amortization,
        $has_notarial_fee,
        $has_insurance_fee,
        $has_service_fee,
        $status
      );

      $stmt->execute();

    }

    /* UPDATE */

    else {

      $stmt = $conn->prepare(
        "UPDATE loan_type_details
         SET loan_type_id=?,
             has_term_years=?,
             has_mode_of_payment=?,
             has_purpose=?,
             has_standing_balance=?,
             has_previous_nthp=?,
             has_amortization=?,
             has_notarial_fee=?,
             has_insurance_fee=?,
             has_service_fee=?,
             status=?
         WHERE loan_type_detail_id=?"
      );

      $stmt->bind_param(
        "issssssssssi",
        $loan_type_id,
        $has_term_years,
        $has_mode_of_payment,
        $has_purpose,
        $has_standing_balance,
        $has_previous_nthp,
        $has_amortization,
        $has_notarial_fee,
        $has_insurance_fee,
        $has_service_fee,
        $status,
        $loan_type_detail_id
      );

      $stmt->execute();

    }

    $_SESSION['msg'] = "saved";

    header("Location: loan_type_details.php");
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
      <h1>Loan Type Details</h1>

      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="dashboard.php">Home</a>
          </li>
          <li class="breadcrumb-item active">
            Loan Type Details
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

                Loan Type Details

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#detailModal">

                  Add Details

                </button>

              </h5>



              <table class="table datatable">

                <thead>

                  <tr>

                    <th>Loan Type</th>
                    <th>Term</th>
                    <th>Mode</th>
                    <th>Standing</th>
                    <th>Previous</th>
                    <th>Amort</th>
                    <th>Notarial</th>
                    <th>Insurance</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Actions</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                    $query = "
                    SELECT
                      ltd.*,
                      lt.loan_type_name

                    FROM loan_type_details ltd

                    LEFT JOIN loan_types lt
                    ON lt.loan_type_id = ltd.loan_type_id

                    ORDER BY lt.loan_type_name
                    ";

                    $result = $conn->query($query);

                    while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                  <td>
                  <?= htmlspecialchars($row['loan_type_name']) ?>
                  </td>

                  <td>
                  <?= $row['has_term_years'] ?>
                  </td>

                  <td>
                  <?= $row['has_mode_of_payment'] ?>
                  </td>

                  <td>
                  <?= $row['has_standing_balance'] ?>
                  </td>

                  <td>
                  <?= $row['has_previous_nthp'] ?>
                  </td>

                  <td>
                  <?= $row['has_amortization'] ?>
                  </td>

                  <td>
                  <?= $row['has_notarial_fee'] ?>
                  </td>

                  <td>
                  <?= $row['has_insurance_fee'] ?>
                  </td>

                  <td>
                  <?= $row['has_service_fee'] ?>
                  </td>

                  <td>
                  <?= $row['status'] ?>
                  </td>

                  <td width="1%" style="white-space:nowrap">

                  <button
                    class="btn btn-outline-warning btn-sm"

                    onclick='editDetail(
                      <?= $row["loan_type_detail_id"] ?>,
                      <?= $row["loan_type_id"] ?>,
                      <?= json_encode($row["has_term_years"]) ?>,
                      <?= json_encode($row["has_mode_of_payment"]) ?>,
                      <?= json_encode($row["has_standing_balance"]) ?>,
                      <?= json_encode($row["has_previous_nthp"]) ?>,
                      <?= json_encode($row["has_amortization"]) ?>,
                      <?= json_encode($row["has_notarial_fee"]) ?>,
                      <?= json_encode($row["has_insurance_fee"]) ?>,
                      <?= json_encode($row["has_service_fee"]) ?>,
                      <?= json_encode($row["status"]) ?>
                    )'>

                  Edit

                  </button>


                  <button
                    class="btn btn-danger btn-sm"
                    onclick="confirmDeleteDetail(
                      <?= $row['loan_type_detail_id'] ?>
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



    <div class="modal fade" id="detailModal">

      <div class="modal-dialog modal-xl">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">
              Loan Type Details Form
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
                name="loan_type_detail_id"
                id="loan_type_detail_id">



              <div class="row">

                <!-- Loan Type -->

                <div class="col-md-12">

                  <div class="mb-3">

                    <label>Loan Type</label>

                    <select
                      name="loan_type_id"
                      id="loan_type_id"
                      class="form-control">

                      <?php

                      $lt =
                      $conn->query(
                      "SELECT *
                       FROM loan_types
                       ORDER BY loan_type_name"
                      );

                      while ($row =
                      $lt->fetch_assoc()) {

                      ?>

                      <option
                      value="<?= $row['loan_type_id'] ?>">

                      <?= htmlspecialchars(
                      $row['loan_type_name']
                      ) ?>

                      </option>

                      <?php } ?>

                    </select>

                  </div>

                </div>



                <!-- COLUMN 1 -->

                <div class="col-md-4">

                  <div class="mb-3">
                    <label>Has Term Years</label>
                    <select name="has_term_years" id="has_term_years" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Has Standing Balance</label>
                    <select name="has_standing_balance" id="has_standing_balance" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Has Notarial Fee</label>
                    <select name="has_notarial_fee" id="has_notarial_fee" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                </div>



                <!-- COLUMN 2 -->

                <div class="col-md-4">

                  <div class="mb-3">
                    <label>Has Mode of Payment</label>
                    <select name="has_mode_of_payment" id="has_mode_of_payment" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Has Previous NTHP</label>
                    <select name="has_previous_nthp" id="has_previous_nthp" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Has Insurance Fee</label>
                    <select name="has_insurance_fee" id="has_insurance_fee" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                </div>



                <!-- COLUMN 3 -->

                <div class="col-md-4">

                  <div class="mb-3">
                    <label>Has Amortization</label>
                    <select name="has_amortization" id="has_amortization" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label>Has Service Fee</label>
                    <select name="has_service_fee" id="has_service_fee" class="form-control">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>

                  <div class="mb-3">

                    <label>Status</label>

                    <select
                      name="status"
                      id="status"
                      class="form-control">

                      <option value="active">
                        Active
                      </option>

                      <option value="inactive">
                        Inactive
                      </option>

                    </select>

                  </div>

                </div>


              </div>



              <button
                type="submit"
                name="save_detail"
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

    function editDetail(
      id,
      loan_type_id,
      term,
      mode,
      standing,
      previous,
      amort,
      notarial,
      insurance,
      service,
      status
    ){
      
      document.getElementById("loan_type_detail_id").value = id;

      document.getElementById("loan_type_id").value = loan_type_id;

      document.getElementById("has_term_years").value = term;

      document.getElementById("has_mode_of_payment").value = mode;

      document.getElementById("has_standing_balance").value = standing;

      document.getElementById("has_previous_nthp").value = previous;

      document.getElementById("has_amortization").value = amort;

      document.getElementById("has_notarial_fee").value = notarial;

      document.getElementById("has_insurance_fee").value = insurance;

      document.getElementById("has_service_fee").value = service;

      document.getElementById("status").value = status;


      new bootstrap.Modal(
        document.getElementById("detailModal")
      ).show();

    }



    function confirmDeleteDetail(id){

      Swal.fire({

        title:"Delete Record?",
        icon:"warning",

        showCancelButton:true,

        confirmButtonColor:"#d33"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location=
            "loan_type_details.php?delete="+id;

        }

      });

    }

  </script>

</body>

</html>