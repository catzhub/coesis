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

/* GET ELECTION */

if (!isset($_GET['id'])) {
  die("Invalid Election");
}

$election_id = intval($_GET['id']);

/* FETCH ELECTION INFO */

$stmt = $conn->prepare("
  SELECT *
  FROM elections
  WHERE election_id=?
");

$stmt->bind_param(
  "i",
  $election_id
);

$stmt->execute();

$election =
$stmt->get_result()
->fetch_assoc();


/* CHECK IF OFFICIALS ALREADY SAVED */

$check_sql = "
  SELECT COUNT(*) total
  FROM officials
  WHERE election_id=?
  AND status='active'
";

$check_stmt =
$conn->prepare($check_sql);

$check_stmt->bind_param(
  "i",
  $election_id
);

$check_stmt->execute();

$already_saved =
$check_stmt
->get_result()
->fetch_assoc()['total'] > 0;


/* SAVE WINNERS */

if (isset($_POST['save_officials'])) {

  /* DEACTIVATE CURRENT */

  $conn->query("
    UPDATE officials
    SET status='inactive'
    WHERE status='active'
  ");

  /* INSERT WINNERS */

  $insert_sql = "

    INSERT INTO officials
    (
      member_id,
      position_id,
      election_id,
      appointment_type,
      term_start,
      term_end,
      status
    )

    SELECT
      ranked.member_id,
      ranked.position_id,
      ?,
      'elected',
      CURDATE(),
      DATE_ADD(CURDATE(), INTERVAL 1 YEAR),
      'active'

    FROM (

      SELECT
        c.member_id,
        c.position_id,
        COUNT(v.vote_id) total_votes,

        ROW_NUMBER() OVER (
          PARTITION BY c.position_id
          ORDER BY COUNT(v.vote_id) DESC
        ) rank_no

      FROM candidates c

      LEFT JOIN votes v
        ON v.candidate_id=c.candidate_id

      WHERE c.election_id=?

      GROUP BY
        c.member_id,
        c.position_id

    ) ranked

    JOIN positions p
      ON p.position_id=ranked.position_id

    WHERE ranked.rank_no <= p.max_vote

  ";

  $insert_stmt =
  $conn->prepare($insert_sql);

  $insert_stmt->bind_param(
    "ii",
    $election_id,
    $election_id
  );

  $insert_stmt->execute();

  $_SESSION['msg'] = "officials_saved";

  header(
    "Location: winners.php?id=".$election_id
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

    <h1>Election Winners</h1>

  </div>

  <section class="section">

    <div class="row">

      <div class="col-lg-12">

        <div class="card">

          <div class="card-body">

            <h5 class="card-title">

              <?= htmlspecialchars(
                $election['election_name']
              ) ?>

              Winners

            </h5>


            <!-- TABLE (NO PAGINATION) -->

            <table class="table">

              <thead>

                <tr>
                  <th>No.</th>

                  <th>Position</th>

                  <th>Member</th>

                  <th>Votes</th>

                </tr>

              </thead>

              <tbody>

                <?php
                $count=1;

                $sql = "

                SELECT

                  p.position_name,

                  m.full_name,

                  ranked.total_votes

                FROM (

                  SELECT

                    c.candidate_id,
                    c.member_id,
                    c.position_id,

                    COUNT(v.vote_id) total_votes,

                    ROW_NUMBER() OVER (
                      PARTITION BY c.position_id
                      ORDER BY COUNT(v.vote_id) DESC
                    ) rank_no

                  FROM candidates c

                  LEFT JOIN votes v
                    ON v.candidate_id=c.candidate_id

                  WHERE c.election_id=?

                  GROUP BY
                    c.candidate_id,
                    c.member_id,
                    c.position_id

                ) ranked

                JOIN positions p
                  ON p.position_id=ranked.position_id

                JOIN members m
                  ON m.member_id=ranked.member_id

                WHERE ranked.rank_no <= p.max_vote

                ORDER BY
                  p.ordinal_no,
                  ranked.total_votes DESC

                ";

                $stmt =
                $conn->prepare($sql);

                $stmt->bind_param(
                  "i",
                  $election_id
                );

                $stmt->execute();

                $result =
                $stmt->get_result();

                while ($row =
                $result->fetch_assoc()) {

                ?>

                <tr>
                <td><?=$count++?></td>  

                <td>
                <?= htmlspecialchars(
                $row['position_name']
                ) ?>
                </td>

                <td>
                <?= htmlspecialchars(
                $row['full_name']
                ) ?>
                </td>

                <td>
                <?= $row['total_votes'] ?>
                </td>

                </tr>

                <?php } ?>

              </tbody>

            </table>


            <!-- SAVE BUTTON -->

            <form method="POST">

              <div class="mt-3">

                <button
                  type="submit"
                  name="save_officials"
                  class="btn btn-success"

                  <?= $already_saved ? "disabled" : "" ?>

                >

                  Save as New Officers

                </button>

              </div>

            </form>


          </div>

        </div>

      </div>

    </div>

  </section>

</main>

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

<?php

if (isset($_SESSION['msg'])) {

  if ($_SESSION['msg'] == "officials_saved") {

?>

Swal.fire({

  icon: "success",
  title: "Saved!",
  text: "New officers successfully created."

});

<?php

  }

  unset($_SESSION['msg']);

}

?>

});

</script>

</body>

</html>