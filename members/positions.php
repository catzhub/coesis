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


  /* DELETE */

  if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare(
      "DELETE FROM positions
       WHERE position_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['msg'] = "deleted";

    header("Location: positions.php");
    exit();

  }


  /* ADD / UPDATE */

  if (isset($_POST['save_position'])) {

    $position_id   = $_POST['position_id'];
    $position_name = $_POST['position_name'];
    $position_type = $_POST['position_type'];
    $ordinal_no    = $_POST['ordinal_no'];
    $max_vote      = $_POST['max_vote'];
    $status        = $_POST['status'];


  /* INSERT */

    if ($position_id == "") {

      $stmt = $conn->prepare(
        "INSERT INTO positions
        (position_name,position_type,ordinal_no,max_vote,status)
        VALUES (?,?,?,?,?)"
      );

      $stmt->bind_param(
        "ssiis",
        $position_name,
        $position_type,
        $ordinal_no,
        $max_vote,
        $status
      );

      $stmt->execute();

    }

  /* UPDATE */

    else {

      $stmt = $conn->prepare(
        "UPDATE positions
         SET position_name=?,
             position_type=?,
             ordinal_no=?,
             max_vote=?,
             status=?
         WHERE position_id=?"
      );

      $stmt->bind_param(
        "ssiisi",
        $position_name,
        $position_type,
        $ordinal_no,
        $max_vote,
        $status,
        $position_id
      );

      $stmt->execute();

    }

    $_SESSION['msg'] = "saved";

    header("Location: positions.php");
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

  <main id="main" class="main" style="min-height:1000px">

    <div class="pagetitle">
      <h1>Position Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <!-- <li class="breadcrumb-item">Tables</li> -->
          <li class="breadcrumb-item active">IMPC Officials</li>
        </ol>
      </nav>
    </div>

    <section class="section">

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-body">

              <h5 class="card-title">

                Positions

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#positionModal">

                  Add Position

                </button>

              </h5>

              <table class="table datatable">

                <thead>

                  <tr>

                    <th>Order</th>
                    <th>Position</th>
                    <th>Type</th>
                    <th>Max Vote</th>
                    <th>Status</th>                    
                    <th>Actions</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query = "SELECT * FROM positions ORDER BY ordinal_no";

                  $result = $conn->query($query);

                  while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                    <td><?= $row['ordinal_no'] ?></td>
                    <td><?= htmlspecialchars($row['position_name']) ?></td>
                    <td><?= htmlspecialchars($row['position_type']) ?></td>
                    <td><?= $row['max_vote'] ?></td>
                    <td><?= $row['status'] ?></td>

                    <td width="1%" style="white-space:nowrap">

                      <button
                        class="btn btn-outline-warning btn-sm"

                        onclick='editPosition(
                          <?= $row["position_id"] ?>,
                          <?= json_encode($row["position_name"]) ?>,
                          <?= json_encode($row["position_type"]) ?>,
                          <?= json_encode($row["ordinal_no"]) ?>,
                          <?= json_encode($row["max_vote"]) ?>,
                          <?= json_encode($row["status"]) ?>
                        )'>

                      Edit

                      </button>

                      <button
                        class="btn btn-danger btn-sm"
                        onclick="confirmDeletePosition(<?= $row['position_id'] ?>)">

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

    <div class="modal fade" id="positionModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">
              Position Form
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
                name="position_id"
                id="position_id">

              <div class="mb-3">

                <label>Position Name</label>

                <input
                  type="text"
                  name="position_name"
                  id="p_name"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Type</label>

                <select
                  name="position_type"
                  id="p_type"
                  class="form-control">

                  <option value="Membership">
                    Membership
                  </option>

                  <option value="Appointed">
                    Appointed
                  </option>

                  <option value="Elected">
                    Elected
                  </option>

                </select>

              </div>

              <div class="mb-3">

                <label>Sequence Order</label>

                <input
                  type="number"
                  name="ordinal_no"
                  id="p_order"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Max Vote</label>

                <input
                  type="number"
                  name="max_vote"
                  id="p_vote"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Status</label>

                <select
                  name="status"
                  id="p_status"
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
                name="save_position"
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

    function editPosition(
      id,
      name,
      p_type,
      order,
      vote,
      status
    ){

      document.getElementById("position_id").value=id;
      document.getElementById("p_type").value=p_type;
      document.getElementById("p_name").value=name;
      document.getElementById("p_order").value=order;
      document.getElementById("p_vote").value=vote;
      document.getElementById("p_status").value=status;

      new bootstrap.Modal(
        document.getElementById("positionModal")
      ).show();

    }


    function confirmDeletePosition(id){

      Swal.fire({

        title:"Delete Position?",
        icon:"warning",

        showCancelButton:true,

        confirmButtonColor:"#d33"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location=
            "positions.php?delete="+id;

        }

      });

    }

    </script>
</body>

</html>