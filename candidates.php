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
      "DELETE FROM candidates
       WHERE candidate_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['msg'] = "deleted";

    header("Location: candidates.php");
    exit();

  }


  /* ADD / UPDATE */

  if (isset($_POST['save_candidate'])) {

    $candidate_id = $_POST['candidate_id'];
    $election_id  = $_POST['election_id'];
    $position_id  = $_POST['position_id'];
    $member_id    = $_POST['member_id'];
    $status       = $_POST['status'];


  /* INSERT */

    if ($candidate_id == "") {

      $stmt = $conn->prepare(
        "INSERT INTO candidates
        (election_id,position_id,member_id,status)
        VALUES (?,?,?,?)"
      );

      $stmt->bind_param(
        "iiis",
        $election_id,
        $position_id,
        $member_id,
        $status
      );

      $stmt->execute();

    }

  /* UPDATE */

    else {

      $stmt = $conn->prepare(
        "UPDATE candidates
         SET election_id=?,
             position_id=?,
             member_id=?,
             status=?
         WHERE candidate_id=?"
      );

      $stmt->bind_param(
        "iiisi",
        $election_id,
        $position_id,
        $member_id,
        $status,
        $candidate_id
      );

      $stmt->execute();

    }

    $_SESSION['msg'] = "saved";

    header("Location: candidates.php");
    exit();

  }


  /* =========================
   GET SELECTED ELECTION
  ========================= */

  /* Get latest election */

  $latest_query =
    "SELECT election_id
     FROM elections
     ORDER BY election_year DESC
     LIMIT 1";

  $latest_result =
    $conn->query($latest_query);

  $latest_row =
    $latest_result->fetch_assoc();

  $latest_election =
    $latest_row['election_id'];


  /* If election selected */

  if (isset($_GET['election_id'])) {

    $selected_election =
      intval($_GET['election_id']);

  }

  else {

    $selected_election =
      $latest_election;

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

  <main id="main" class="main"  style="min-height:1000px">

    <div class="pagetitle">
      <h1>Candidate Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <!-- <li class="breadcrumb-item">Tables</li> -->
          <li class="breadcrumb-item active">IMPC Election Candidates</li>
        </ol>
      </nav>
    </div>

    <section class="section">

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-body">

              <h5 class="card-title">

                Candidates

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#candidateModal">

                  Add Candidate

                </button>

              </h5>

              <!-- Election Selector -->

              <form method="GET">

                <div class="row mb-3">

                  <div class="col-md-4">

                    <label>
                      Select Election
                    </label>

                    <select
                      name="election_id"
                      class="form-control"
                      onchange="this.form.submit()">

              <?php

              $elections =
                $conn->query(
                  "SELECT *
                   FROM elections
                   ORDER BY election_year DESC"
                );

              while ($e =
                $elections->fetch_assoc()) {

              ?>

              <option
                value="<?= $e['election_id'] ?>"

                <?= ($selected_election ==
                      $e['election_id'])
                      ? 'selected' : '' ?>>

              <?= $e['election_name'] ?>

              </option>

              <?php } ?>

                    </select>

                  </div>

                </div>

              </form>

              <table class="table datatable">

                <thead>

                  <tr>

                    <th>Election</th>
                    <th>Position</th>
                    <th>Member</th>
                    <th>Status</th>
                    <th>Actions</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query = "
                  SELECT candidates.*,
                         elections.election_name,
                         positions.position_name,
                         members.full_name

                  FROM candidates

                  JOIN elections
                  ON candidates.election_id =
                     elections.election_id

                  JOIN positions
                  ON candidates.position_id =
                     positions.position_id

                  JOIN members
                  ON candidates.member_id =
                     members.member_id

                  WHERE candidates.election_id =
                        $selected_election
                  ORDER BY
                      positions.ordinal_no
                  ";
                  $result = $conn->query($query);

                  while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                  <td>
                  <?= htmlspecialchars($row['election_name']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['position_name']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['full_name']) ?>
                  </td>

                  <td>
                  <?= $row['status'] ?>
                  </td>

                  <td width="1%" style="white-space:nowrap">

                  <button
                    class="btn btn-outline-warning btn-sm"

                    onclick='editCandidate(
                      <?= $row["candidate_id"] ?>,
                      <?= json_encode($row["election_id"]) ?>,
                      <?= json_encode($row["position_id"]) ?>,
                      <?= json_encode($row["member_id"]) ?>,
                      <?= json_encode($row["status"]) ?>
                    )'>

                    Edit

                  </button>

                  <button
                  class="btn btn-outline-danger btn-sm"
                  onclick="confirmDeleteCandidate(<?= $row['candidate_id'] ?>)">

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

    <div class="modal fade" id="candidateModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">
              Candidate Form
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
                name="candidate_id"
                id="candidate_id">

              <input
                type="hidden"
                name="election_id"
                id="c_election"
                value="<?= $selected_election ?>">

              <!-- Position -->

              <div class="mb-3">

                <label>Position</label>

                <select
                  name="position_id"
                  id="c_position"
                  class="form-control">

                  <?php

                  $positions =
                  $conn->query(
                  "SELECT * FROM positions"
                  );

                  while ($p =
                  $positions->fetch_assoc()) {

                  ?>

                  <option
                  value="<?= $p['position_id'] ?>">

                  <?= $p['position_name'] ?>

                  </option>

                  <?php } ?>

                </select>

              </div>


              <!-- Member -->

              <div class="mb-3">

                <label>Member</label>

                <select
                  name="member_id"
                  id="c_member"
                  class="form-control">

                  <?php

                  $members =
                  $conn->query(
                  "SELECT * FROM members"
                  );

                  while ($m =
                  $members->fetch_assoc()) {

                  ?>

                  <option
                  value="<?= $m['member_id'] ?>">

                  <?= $m['full_name'] ?>

                  </option>

                  <?php } ?>

                </select>

              </div>


              <!-- Status -->

              <div class="mb-3">

                <label>Status</label>

                <select
                  name="status"
                  id="c_status"
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
                name="save_candidate"
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

    function editCandidate(
      id,
      election,
      position,
      member,
      status
    ){

      document.getElementById("candidate_id").value = id;

      document.getElementById("c_election").value = election;

      document.getElementById("c_position").value = position;

      document.getElementById("c_member").value = member;

      document.getElementById("c_status").value = status;

      new bootstrap.Modal(
        document.getElementById("candidateModal")
      ).show();

    }


    function confirmDeleteCandidate(id){

      Swal.fire({

        title:"Delete Candidate?",
        icon:"warning",

        showCancelButton:true,

        confirmButtonColor:"#d33"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location=
            "candidates.php?delete="+id;

        }

      });

    }

  </script>

  <script>

    document
    .querySelector("[data-bs-target='#candidateModal']")
    .addEventListener("click", function(){

      document.getElementById("candidate_id").value="";

      document.getElementById("c_election").value =
        <?= $selected_election ?>;

    });

  </script>

</body>

</html>