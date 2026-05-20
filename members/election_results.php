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


/* CHECK IF OFFICIALS ALREADY IMPORTED */

$check_sql = "

  SELECT COUNT(*) total
  FROM officials
  WHERE
    election_id=?
    AND appointment_type='elected'

";

$check_stmt =
$conn->prepare($check_sql);

$check_stmt->bind_param(
  "i",
  $election_id
);

$check_stmt->execute();

$already_imported =
$check_stmt
->get_result()
->fetch_assoc()['total'] > 0;



/* IMPORT WINNERS */

if (isset($_POST['import_winners']) && $election['status'] == 'Close') {


  if (!$already_imported) {

    /* DEACTIVATE CURRENT OFFICIALS */

    // $conn->query("

    //   UPDATE officials
    //   SET status='inactive'
    //   WHERE status='active'

    // ");


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
        DATE_ADD(
          CURDATE(),
          INTERVAL 1 YEAR
        ),
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

        WHERE
          c.election_id=?

        GROUP BY
          c.member_id,
          c.position_id

      ) ranked

      JOIN positions p
        ON p.position_id=
           ranked.position_id
        AND p.position_type='Elected'

      WHERE
        ranked.rank_no <=
        p.max_vote

    ";

    $stmt =
    $conn->prepare($insert_sql);

    $stmt->bind_param(
      "ii",
      $election_id,
      $election_id
    );

    $stmt->execute();


    $_SESSION['msg'] =
    "winners_imported";

    header(
      "Location: election_results.php?id=".$election_id
    );

    exit();

  }

}


include 'header.php';

?>

<?php

if (isset($_SESSION['msg'])) {

  $msg = $_SESSION['msg'];

  unset($_SESSION['msg']);

?>

<script>

document.addEventListener(
  "DOMContentLoaded",
  function(){

<?php if ($msg == "winners_imported") { ?>

  Swal.fire({

    icon: "success",

    title: "Import Successful",

    text:
    "Winners have been successfully imported to officials."

  });

<?php } ?>

  }
);

</script>

<?php } ?>


<main id="main" class="main">

  <div class="pagetitle">

    <h1>Election Results</h1>

    <nav>

      <ol class="breadcrumb">

        <li class="breadcrumb-item">
          <a href="dashboard.php">
            Home
          </a>
        </li>

        <li class="breadcrumb-item">
          Elections
        </li>

        <li class="breadcrumb-item active">
          Results
        </li>

      </ol>

    </nav>

  </div>


  <section class="section">

    <!-- VIEW WINNERS BUTTON -->

<div class="mb-3">

  <form method="POST">

    <button type="submit" name="import_winners" id="btnImportWinners" class="btn btn-success"
      <?= ( $already_imported || $election['status'] == 'Open' ) ? "disabled" : "" ?>
    >
      Import Winners to Officials
    </button>

  </form>

</div>



<?php

/* GET POSITIONS */

$pos_sql = "

  SELECT *
  FROM positions
  WHERE
    status='active'
    AND position_type='Elected'
  ORDER BY ordinal_no

";

$pos_result =
$conn->query($pos_sql);

while ($position =
$pos_result->fetch_assoc()) {

  $position_id =
  $position['position_id'];

?>

    <!-- POSITION CARD -->

    <div class="card mb-4">

      <div class="card-body">

        <h5 class="card-title">

          <?= htmlspecialchars(
            $position['position_name']
          ) ?>

        </h5>


        <table class="table small">

          <thead>

            <tr>

              <th>Rank</th>
              <th>Candidate</th>
              <th>Votes</th>
              <th>Remarks</th>

            </tr>

          </thead>

          <tbody>

<?php

/* GET RESULTS PER POSITION */

$sql = "

SELECT

  m.full_name,

  ranked.total_votes,

  ranked.rank_no

FROM (

  SELECT

    c.member_id,

    COUNT(v.vote_id) total_votes,

    ROW_NUMBER() OVER (

      PARTITION BY c.position_id
      ORDER BY COUNT(v.vote_id) DESC

    ) rank_no

  FROM candidates c

  LEFT JOIN votes v
    ON v.candidate_id=c.candidate_id

  WHERE

    c.election_id=?
    AND c.position_id=?

  GROUP BY

    c.member_id,
    c.position_id

) ranked

JOIN members m
  ON m.member_id=ranked.member_id

ORDER BY

  ranked.rank_no

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $election_id,
  $position_id
);

$stmt->execute();

$result =
$stmt->get_result();

while ($row =
$result->fetch_assoc()) {

$is_winner =
($election['status'] == 'Close')
&&
($row['rank_no']
<=
$position['max_vote']);
?>

<tr
<?= $is_winner ? 'class="table-success"' : '' ?>
>

<td>

<?= $row['rank_no'] ?>

</td>

<td>

<?= htmlspecialchars(
$row['full_name']
) ?>

</td>

<td>

<?= $row['total_votes'] ?>

</td>

<td>

<?= $is_winner ? 'Winner' : '' ?>

</td>

</tr>

<?php } ?>

          </tbody>

        </table>


      </div>

    </div>

<?php } ?>


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

    const form =
    document.querySelector(
      'form'
    );

    if (form) {

      form.addEventListener(
        "submit",
        function(e){

          e.preventDefault();

          /* BLOCK IF OPEN */

          if (
            "<?= $election['status'] ?>"
            === "Open"
          ){

            Swal.fire({

              icon: "warning",

              title:
              "Election Still Open",

              text:
              "Close the election before importing winners."

            });

            return;

          }

          /* CONFIRM IMPORT */

          Swal.fire({

            title:
            "Import Winners?",

            text:
            "Current officials will be marked inactive and replaced.",

            icon:
            "warning",

            showCancelButton:
            true,

            confirmButtonText:
            "Yes, Import",

            cancelButtonText:
            "Cancel",

            confirmButtonColor:
            "#198754",

            cancelButtonColor:
            "#6c757d"

          }).then((result)=>{

            if (result.isConfirmed) {

              form.submit();

            }

          });

        }

      );

    }

  }

);

</script>

</body>

</html>