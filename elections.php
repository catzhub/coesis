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
      "DELETE FROM elections
       WHERE election_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['msg'] = "deleted";

    header("Location: elections.php");
    exit();

  }


  /* ADD / UPDATE */

  if (isset($_POST['save_election'])) {

    $election_id = $_POST['election_id'];
    $year        = $_POST['election_year'];
    $name        = $_POST['election_name'];
    $start       = $_POST['start_date'];
    $end         = $_POST['end_date'];
    $status      = $_POST['status'];


  /* INSERT */

    if ($election_id == "") {

      $stmt = $conn->prepare(
        "INSERT INTO elections
        (election_year,election_name,start_date,end_date,status)
        VALUES (?,?,?,?,?)"
      );

      $stmt->bind_param(
        "issss",
        $year,
        $name,
        $start,
        $end,
        $status
      );

      $stmt->execute();

    }

  /* UPDATE */

    else {

      $stmt = $conn->prepare(
        "UPDATE elections
         SET election_year=?,
             election_name=?,
             start_date=?,
             end_date=?,
             status=?
         WHERE election_id=?"
      );

      $stmt->bind_param(
        "issssi",
        $year,
        $name,
        $start,
        $end,
        $status,
        $election_id
      );

      $stmt->execute();

    }

    $_SESSION['msg'] = "saved";

    header("Location: elections.php");
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
      <h1>Election Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <!-- <li class="breadcrumb-item">Tables</li> -->
          <li class="breadcrumb-item active">IMPC Elections</li>
        </ol>
      </nav>
    </div>

    <section class="section">

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-body">

              <h5 class="card-title">

                Elections

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#electionModal">

                  Add Election

                </button>

              </h5>

              <table class="table datatable">

                <thead>

                  <tr>

                    <th>Year</th>
                    <th>Name</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Actions</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query = "SELECT * FROM elections";

                  $result = $conn->query($query);

                  while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                    <td><?= $row['election_year'] ?></td>

                    <td>
                      <a href="votes.php?election_id=<?= $row['election_id'] ?>">
                        <?= htmlspecialchars($row['election_name']) ?>
                      </a>
                    </td>

                    <td><?= $row['start_date'] ?></td>

                    <td><?= $row['end_date'] ?></td>

                    <td><?= $row['status'] ?></td>

                    <td width="1%" style="white-space:nowrap">

                      <button
                        class="btn btn-outline-warning btn-sm"

                        onclick='editElection(
                          <?= $row["election_id"] ?>,
                          <?= json_encode($row["election_year"]) ?>,
                          <?= json_encode($row["election_name"]) ?>,
                          <?= json_encode($row["start_date"]) ?>,
                          <?= json_encode($row["end_date"]) ?>,
                          <?= json_encode($row["status"]) ?>
                        )'>

                      Edit

                      </button>

                      <button
                        class="btn btn-outline-danger btn-sm"
                        onclick="confirmDeleteElection(<?= $row['election_id'] ?>)">

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

    <div class="modal fade" id="electionModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">
              Election Form
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
                name="election_id"
                id="election_id">

              <div class="mb-3">

                <label>Election Year</label>

                <input
                  type="number"
                  name="election_year"
                  id="e_year"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Election Name</label>

                <input
                  type="text"
                  name="election_name"
                  id="e_name"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Start Date</label>

                <input
                  type="date"
                  name="start_date"
                  id="e_start"
                  class="form-control">

              </div>

              <div class="mb-3">

                <label>End Date</label>

                <input
                  type="date"
                  name="end_date"
                  id="e_end"
                  class="form-control">

              </div>

              <div class="mb-3">

                <label>Status</label>

                <select
                  name="status"
                  id="e_status"
                  class="form-control">

                  <option value="Open">
                    Open
                  </option>

                  <option value="Close">
                    Close
                  </option>

                </select>

              </div>

              <button
                type="submit"
                name="save_election"
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

    function editElection(
      id,
      year,
      name,
      start,
      end,
      status
    ){

      document.getElementById("election_id").value=id;
      document.getElementById("e_year").value=year;
      document.getElementById("e_name").value=name;
      document.getElementById("e_start").value=start;
      document.getElementById("e_end").value=end;
      document.getElementById("e_status").value=status;

      new bootstrap.Modal(
        document.getElementById("electionModal")
      ).show();

    }


    function confirmDeleteElection(id){

      Swal.fire({

        title:"Delete Election?",
        icon:"warning",

        showCancelButton:true,

        confirmButtonColor:"#d33"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location=
            "elections.php?delete="+id;

        }

      });

    }

    </script>

</body>

</html>