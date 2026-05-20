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
      "DELETE FROM loan_types
       WHERE loan_type_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['msg'] = "deleted";

    header("Location: loan_types.php");
    exit();

  }


  /* ADD / UPDATE */

  if (isset($_POST['save_loan_type'])) {

    $loan_type_id   = $_POST['loan_type_id'];
    $loan_type_name = $_POST['loan_type_name'];
    $description    = $_POST['description'];
    $cbu_percentage = $_POST['cbu_percentage'];
    $status         = $_POST['status'];
    $max_loan_amount     = $_POST['max_loan_amount'];
    $max_month_duration  = $_POST['max_month_duration'];
    $service_fee         = $_POST['service_fee'];
    $insurance_fee       = $_POST['insurance_fee'];
    $notary_fee          = $_POST['notary_fee'];


  /* INSERT */

    if ($loan_type_id == "") {

      $stmt = $conn->prepare(
        "INSERT INTO loan_types
          (
            loan_type_name,
            description,
            max_loan_amount,
            max_month_duration,
            service_fee,
            insurance_fee,
            notary_fee,
            cbu_percentage,
            status
          )
          VALUES (?,?,?,?,?,?,?,?,?)"
      );

      $stmt->bind_param(
        "ssdiiddds",
        $loan_type_name,
        $description,
        $max_loan_amount,
        $max_month_duration,
        $service_fee,
        $insurance_fee,
        $notary_fee,
        $cbu_percentage,
        $status
      );

      $stmt->execute();

    }

  /* UPDATE */

    else {

      $stmt = $conn->prepare(
          "UPDATE loan_types
           SET loan_type_name=?,
               description=?,
               max_loan_amount=?,
               max_month_duration=?,
               service_fee=?,
               insurance_fee=?,
               notary_fee=?,
               cbu_percentage=?,
               status=?
           WHERE loan_type_id=?"
        );

        $stmt->bind_param(
          "ssdiisddsi",
          $loan_type_name,
          $description,
          $max_loan_amount,
          $max_month_duration,
          $service_fee,
          $insurance_fee,
          $notary_fee,
          $cbu_percentage,
          $status,
          $loan_type_id
        );

      $stmt->execute();

    }

    $_SESSION['msg'] = "saved";

    header("Location: loan_types.php");
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
      <h1>Loan Types</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <!-- <li class="breadcrumb-item">Tables</li> -->
            <li class="breadcrumb-item active">IMPC Member</li>
          </ol>
        </nav>
    </div>

    <section class="section">

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-body">

              <h5 class="card-title">

                Loan Types

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#loanTypeModal">

                  Add Loan Type

                </button>

              </h5>

              <table class="table datatable small">

                <thead>

                  <tr>

                    <th class="text-center">Loan Type</th>
                    <th class="text-center" width="1%">Max Amount</th>
                    <th class="text-center" width="1%">Max Months</th>
                    <th class="text-center" width="1%">Service Fee</th>
                    <th class="text-center" width="1%">Insurance Fee</th>
                    <th class="text-center" width="1%">Notary Fee</th>
                    <th class="text-center">CBU %</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query = "
                  SELECT *
                  FROM loan_types
                  ORDER BY loan_type_name
                  ";

                  $result = $conn->query($query);

                  while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                    <td class="text-center"><?= htmlspecialchars($row['loan_type_name']) ?></td>
                    <td class="text-end"><?= number_format($row['max_loan_amount'],2) ?></td>
                    <td class="text-center"><?= $row['max_month_duration'] ?></td>
                    <td class="text-end"><?= number_format($row['service_fee'],2) ?></td>
                    <td class="text-end"><?= $row['insurance_fee'] ?></td>
                    <td class="text-end"><?= number_format($row['notary_fee'],2) ?></td>
                    <td class="text-end"><?= $row['cbu_percentage'] ?>%</td>
                    <td class="text-center"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="text-center"><?= $row['status'] ?></td>
                    <td width="1%" style="white-space:nowrap">
                      <button
                        class="btn btn-outline-warning btn-sm"
                        onclick='editLoanType(
                          <?= $row["loan_type_id"] ?>,
                          <?= json_encode($row["loan_type_name"]) ?>,
                          <?= json_encode($row["description"]) ?>,
                          <?= json_encode($row["max_loan_amount"]) ?>,
                          <?= json_encode($row["max_month_duration"]) ?>,
                          <?= json_encode($row["service_fee"]) ?>,
                          <?= json_encode($row["insurance_fee"]) ?>,
                          <?= json_encode($row["notary_fee"]) ?>,
                          <?= json_encode($row["cbu_percentage"]) ?>,
                          <?= json_encode($row["status"]) ?>
                        )'>
                      Edit
                      </button>
                      <button
                        class="btn btn-danger btn-sm"
                        onclick="confirmDeleteLoanType(<?= $row['loan_type_id'] ?>)">
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



    <div class="modal fade" id="loanTypeModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">
              Loan Type Form
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
                name="loan_type_id"
                id="loan_type_id">

              <div class="mb-3">

                <label>Loan Type Name</label>

                <input
                  type="text"
                  name="loan_type_name"
                  id="lt_name"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">
                <label>Max Loan Amount</label>
                <input
                  type="number"
                  step="0.01"
                  name="max_loan_amount"
                  id="lt_max_amount"
                  class="form-control"
                  required>
              </div>

              <div class="mb-3">
                <label>Max Month Duration</label>
                <input
                  type="number"
                  name="max_month_duration"
                  id="lt_max_month"
                  class="form-control"
                  required>
              </div>

              <div class="mb-3">
                <label>Service Fee</label>
                <input
                  type="number"
                  step="0.01"
                  name="service_fee"
                  id="lt_service_fee"
                  class="form-control">
              </div>

              <div class="mb-3">
                <label>Insurance Fee</label>
                <select
                  name="insurance_fee"
                  id="lt_insurance_fee"
                  class="form-control">

                  <option value="Yes">Yes</option>
                  <option value="No">No</option>

                </select>
              </div>

              <div class="mb-3">
                <label>Notary Fee</label>
                <input
                  type="number"
                  step="0.01"
                  name="notary_fee"
                  id="lt_notary_fee"
                  class="form-control">
              </div>

              <div class="mb-3">

                <label>CBU Percentage</label>

                <input
                  type="number"
                  step="0.01"
                  name="cbu_percentage"
                  id="lt_cbu"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Description</label>

                <textarea
                  name="description"
                  id="lt_desc"
                  class="form-control"></textarea>

              </div>

              <div class="mb-3">

                <label>Status</label>

                <select
                  name="status"
                  id="lt_status"
                  class="form-control">

                  <option value="active">
                    Active
                  </option>

                  <option value="inactive">
                    Inactive
                  </option>

                </select>

              </div>

              <button
                type="submit"
                name="save_loan_type"
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

    function editLoanType(
      id,
      name,
      desc,
      max_amount,
      max_month,
      service_fee,
      insurance_fee,
      notary_fee,
      cbu,
      status
    ){

      document.getElementById("loan_type_id").value=id;
      document.getElementById("lt_name").value=name;
      document.getElementById("lt_desc").value=desc;
      document.getElementById("lt_max_amount").value=max_amount;
      document.getElementById("lt_max_month").value=max_month;
      document.getElementById("lt_service_fee").value=service_fee;
      document.getElementById("lt_insurance_fee").value=insurance_fee;
      document.getElementById("lt_notary_fee").value=notary_fee;
      document.getElementById("lt_cbu").value=cbu;
      document.getElementById("lt_status").value=status;

      new bootstrap.Modal(
        document.getElementById("loanTypeModal")
      ).show();

    }


    function confirmDeleteLoanType(id){

      Swal.fire({

        title:"Delete Loan Type?",
        icon:"warning",

        showCancelButton:true,

        confirmButtonColor:"#d33"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location=
            "loan_types.php?delete="+id;

        }

      });

    }

  </script>

</body>

</html>