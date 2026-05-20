<?php

  session_start();

  require 'db/dbconnect.php';

  /* ACCESS */

  if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
  }

  if ($_SESSION['user_role'] !== 'admin') {
    die("Access Denied");
  }


  /* =========================
     GET LATEST ELECTION
  ========================= */

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


  /* SELECTED */

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

  <main id="main" class="main" style="min-height:1000px">

    <div class="pagetitle">
      <h1>Candidate Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <!-- <li class="breadcrumb-item">Tables</li> -->
          <li class="breadcrumb-item active">IMPC Election Votes</li>
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

                    <th>Position</th>
                    <th>Candidate</th>
                    <th>Votes</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query = "

                  SELECT

                    positions.position_name,

                    members.full_name,

                    COUNT(votes.vote_id)
                    AS vote_count

                  FROM candidates

                  JOIN positions
                  ON candidates.position_id =
                     positions.position_id

                  JOIN members
                  ON candidates.member_id =
                     members.member_id

                  LEFT JOIN votes
                  ON candidates.candidate_id =
                     votes.candidate_id

                  WHERE candidates.election_id =
                        $selected_election

                  GROUP BY
                    candidates.candidate_id

                  ORDER BY
                    positions.ordinal_no,
                    vote_count DESC

                  ";

                  $result =
                    $conn->query($query);

                  while ($row =
                    $result->fetch_assoc()) {

                  ?>

                  <tr>

                  <td>
                  <?= htmlspecialchars($row['position_name']) ?>
                  </td>

                  <td>
                  <?= htmlspecialchars($row['full_name']) ?>
                  </td>

                  <td>

                  <strong>
                  <?= $row['vote_count'] ?>
                  </strong>

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