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
      "DELETE FROM loan_type_signatories
       WHERE loan_type_signatory_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['msg'] = "deleted";

    header("Location: loan_signatories.php");
    exit();

  }


  /* ADD / UPDATE */

  if (isset($_POST['save_signatory'])) {

    $loan_type_signatory_id =
      $_POST['loan_type_signatory_id'];

    $loan_type_id =
      $_POST['loan_type_id'];

    $position_id =
      $_POST['position_id'];

    $sequence_order =
      $_POST['sequence_order'];


  /* INSERT */

    if ($loan_type_signatory_id == "") {

      $stmt = $conn->prepare(
        "INSERT INTO loan_type_signatories
        (loan_type_id,
         position_id,
         sequence_order)
        VALUES (?,?,?)"
      );

      $stmt->bind_param(
        "iii",
        $loan_type_id,
        $position_id,
        $sequence_order
      );

      $stmt->execute();

    }

  /* UPDATE */

    else {

      $stmt = $conn->prepare(
        "UPDATE loan_type_signatories
         SET loan_type_id=?,
             position_id=?,
             sequence_order=?
         WHERE loan_type_signatory_id=?"
      );

      $stmt->bind_param(
        "iiii",
        $loan_type_id,
        $position_id,
        $sequence_order,
        $loan_type_signatory_id
      );

      $stmt->execute();

    }

    $_SESSION['msg'] = "saved";

    header("Location: loan_signatories.php");
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
      <h1>Loan Signatories</h1>

      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="dashboard.php">Home</a>
          </li>
          <li class="breadcrumb-item active">
            Loan Signatories
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

                Loan Signatories

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#signatoryModal">

                  Add Signatory

                </button>

              </h5>



              <table class="table datatable">

                <thead>

                  <tr>
                    <th>Loan Type</th>
                    <th>Position</th>
                    <th>Sequence</th>
                    <th>Actions</th>
                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query = "
                  SELECT
                    lts.loan_type_signatory_id,
                    lt.loan_type_name,
                    p.position_name,
                    lts.loan_type_id,
                    lts.position_id,
                    lts.sequence_order

                  FROM loan_type_signatories lts

                  LEFT JOIN loan_types lt
                  ON lt.loan_type_id = lts.loan_type_id

                  LEFT JOIN positions p
                  ON p.position_id = lts.position_id

                  ORDER BY
                    lt.loan_type_name,
                    lts.sequence_order
                  ";

                  $result = $conn->query($query);

                  while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                  <td>
                  <?= htmlspecialchars($row['loan_type_name']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['position_name']) ?>
                  </td>

                  <td>
                  <?= $row['sequence_order'] ?>
                  </td>

                  <td width="1%" style="white-space:nowrap">

                  <button
                  class="btn btn-outline-warning btn-sm"

                  onclick='editSignatory(
                  <?= $row["loan_type_signatory_id"] ?>,
                  <?= $row["loan_type_id"] ?>,
                  <?= $row["position_id"] ?>,
                  <?= $row["sequence_order"] ?>
                  )'>

                  Edit

                  </button>


                  <button
                  class="btn btn-danger btn-sm"
                  onclick="confirmDeleteSignatory(
                  <?= $row['loan_type_signatory_id'] ?>
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

    <div class="modal fade" id="signatoryModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">
              Signatory Form
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
                name="loan_type_signatory_id"
                id="loan_type_signatory_id">



              <div class="mb-3">

                <label>Loan Type</label>

                <select
                  name="loan_type_id"
                  id="loan_type_id"
                  class="form-control"
                  required>

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



              <div class="mb-3">

                <label>Position</label>

                <select
                  name="position_id"
                  id="position_id"
                  class="form-control"
                  required>

                  <?php

                  $p =
                  $conn->query(
                  "SELECT *
                   FROM positions
                   ORDER BY ordinal_no"
                  );

                  while ($row =
                  $p->fetch_assoc()) {

                  ?>

                  <option
                  value="<?= $row['position_id'] ?>">

                  <?= htmlspecialchars(
                  $row['position_name']
                  ) ?>

                  </option>

                  <?php } ?>

                </select>

              </div>



              <div class="mb-3">

                <label>Sequence Order</label>

                <input
                  type="number"
                  name="sequence_order"
                  id="sequence_order"
                  class="form-control"
                  required>

              </div>



              <button
                type="submit"
                name="save_signatory"
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

    function editSignatory(
      id,
      loan_type_id,
      position_id,
      sequence
    ){

      document.getElementById(
        "loan_type_signatory_id"
      ).value=id;

      document.getElementById(
        "loan_type_id"
      ).value=loan_type_id;

      document.getElementById(
        "position_id"
      ).value=position_id;

      document.getElementById(
        "sequence_order"
      ).value=sequence;

      new bootstrap.Modal(
        document.getElementById(
          "signatoryModal"
        )
      ).show();

    }



    function confirmDeleteSignatory(id){

      Swal.fire({

        title:"Delete Signatory?",
        icon:"warning",

        showCancelButton:true,

        confirmButtonColor:"#d33"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location=
            "loan_signatories.php?delete="+id;

        }

      });

    }

  </script>

</body>

</html>