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


/* =========================
   GET ACTIVE ELECTION
========================= */

$latest_query =
$conn->query(

"SELECT *
 FROM elections
 WHERE status='Open'
 LIMIT 1"

);

if ($latest_row = $latest_query->fetch_assoc()) {

  $selected_election =
  $latest_row['election_id'];

  $election_name =
  $latest_row['election_name'];

} else {

  $selected_election = null;
  $election_name = "No Active Election";

}

if (!$selected_election) {

  die("No active election available.");

}



/* =========================
   CHECK EXISTING VOTES
========================= */

$voted_positions = [];

$check_stmt =
$conn->prepare(

"SELECT position_id,
        candidate_id

 FROM votes

 WHERE election_id = ?
 AND voter_member_id = ?"

);

$check_stmt->bind_param(
"ii",
$selected_election,
$member_id
);

$check_stmt->execute();

$result =
$check_stmt->get_result();

while ($row =
$result->fetch_assoc()) {

  $voted_positions[
    $row['position_id']
  ][] =
  $row['candidate_id'];

}

$has_voted =
(count($voted_positions) > 0);


/* =========================
   SAVE VOTES
========================= */

if (isset($_POST['submit_votes'])
    && !$has_voted) {

  foreach ($_POST['votes']
           as $position_id =>
           $candidate_ids) {

    foreach ($candidate_ids as $candidate_id) {

      $stmt =
      $conn->prepare(

      "INSERT INTO votes
       (election_id,
        position_id,
        candidate_id,
        voter_member_id,
        voted_at)

       VALUES (?,?,?,?,NOW())"

      );

      $stmt->bind_param(
        "iiii",
        $selected_election,
        $position_id,
        $candidate_id,
        $member_id
      );

      $stmt->execute();

    }

  }

  logActivity(
    $conn,
    "vote_cast",
    "Member submitted votes",
    $member_id
  );

  $_SESSION['msg']="voted";

  header("Location: votes.php");

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

      <h1><?= $election_name ?></h1>

      <nav>

        <ol class="breadcrumb">

          <li class="breadcrumb-item">
            <a href="dashboard.php">
              Home
            </a>
          </li>

          <li class="breadcrumb-item active">
            Cast Votes
          </li>

        </ol>

      </nav>

    </div>



    <section class="section">

      <form method="POST">

        <div class="row">

          <?php

$positions =
$conn->query(
"SELECT *
 FROM positions
 WHERE
   status='active'
   AND position_type='Elected'
 ORDER BY ordinal_no"
);

          while ($p =
          $positions->fetch_assoc()) {

          $position_id =
          $p['position_id'];

          ?>

          <div class="col-lg-6">

            <div class="card">

              <div class="card-body">

                <h5 class="card-title">

                  <?= htmlspecialchars($p['position_name']) ?>

                  <small class="text-muted">

                    (Vote up to <?= $p['max_vote'] ?>)

                  </small>

                </h5>



<?php

$candidates = $conn->query(

"SELECT
  candidates.candidate_id,
  members.full_name,
  candidates.photo

 FROM candidates

 JOIN members
 ON candidates.member_id =
    members.member_id

 WHERE candidates.position_id =
       $position_id

 AND candidates.election_id =
       $selected_election"

);

while ($c = $candidates->fetch_assoc()) {

$candidate_id = $c['candidate_id'];

$is_checked = isset($voted_positions[$position_id]) && in_array( $candidate_id, $voted_positions[$position_id]);


/* Candidate Image */

$photo =
!empty($c['photo'])
? htmlspecialchars(
  $c['photo']
)
: "../assets/img/profile-img.jpg";

?>

<div class="card mb-2 <?= $is_checked ? 'border-success bg-primary bg-opacity-10' : '' ?>">

  <div class="card-body p-2">

    <div class="d-flex align-items-center">

      <!-- Radio -->

      <div class="me-2">

        <input
          class="form-check-input vote-checkbox"
          type="checkbox"
          name="votes[<?= $position_id ?>][]"
          value="<?= $candidate_id ?>"
          data-position="<?= $position_id ?>"
          data-maxvote="<?= $p['max_vote'] ?>"

          <?= $has_voted ? 'disabled' : '' ?>
          <?= $is_checked ? 'checked' : '' ?>

        >
        
      </div>
      <!-- Photo -->

      <div class="me-2">
        <img src="<?= $photo ?>" class="rounded-circle" width="50" height="50" style="object-fit:cover;">
      </div>




      <!-- Name -->

      <div>

        <strong>

          <?= htmlspecialchars(
               $c['full_name']
             ) ?>

        </strong>

      </div>


      <div class="ms-auto text-end">

        <?php if ($is_checked) { ?>

                <div class="text-success">

                  ✓ Your Vote

                </div>

        <?php } ?>

      </div>




    </div>

  </div>

</div>

<?php

}

?>

              </div>

            </div>

          </div>

          <?php

          }

          ?>

        </div>



        <?php if (!$has_voted) { ?>

          <div class="row mt-3">

            <div class="col-lg-12 text-center">

              <button

                type="submit"

                name="submit_votes"

                class="btn btn-primary btn-lg">

                Submit Votes

              </button>

            </div>

          </div>

        <?php } else { ?>

        <div class="row mt-3">

          <div class="col-lg-12">

            <div class="alert alert-success text-center">

              You have already voted.
              Your selections are displayed above.

            </div>

          </div>

        </div>

        <?php } ?>

      </form>

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

    document.addEventListener(
      "DOMContentLoaded",
      function(){

        document
          .querySelectorAll(".vote-checkbox")
          .forEach(function(cb){

            cb.addEventListener(
              "change",
              function(){

                let position =
                  cb.dataset.position;

                let maxVote =
                  parseInt(
                    cb.dataset.maxvote
                  );

                let checked =
                  document.querySelectorAll(
                    ".vote-checkbox[data-position='"
                    + position +
                    "']:checked"
                  ).length;

                if (checked > maxVote){

                  cb.checked = false;

                  Swal.fire({
                    icon: "warning",
                    title: "Vote Limit Reached",
                    text:
                      "You can only vote up to "
                      + maxVote +
                      " candidates for this position."
                  });

                }

              }
            );

          });

      }

    );

  </script>

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