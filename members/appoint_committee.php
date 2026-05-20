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

/* DELETE APPOINTMENT */

if (isset($_GET['delete'])) {

  $id =
  intval($_GET['delete']);

  $stmt =
  $conn->prepare(

"UPDATE officials
 SET
   status='inactive',
   deleted_by='$member_id',
   date_deleted=NOW()
 WHERE official_id=?"

  );

  $stmt->bind_param(
    "i",
    $id
  );

  $stmt->execute();

  $_SESSION['msg'] = "deleted";

  logActivity( 
    $conn, 
    "removed commitee", 
    "Credit committee was removed", 
    $id

  );

  header(
    "Location: appoint_committee.php"
  );

  exit();

}


/* SAVE APPOINTMENT */

if (isset($_POST['save_appointment'])) {

  $official_id =
  $_POST['official_id'];

  $member_id =
  $_POST['member_id'];

  $position_id =
  $_POST['position_id'];

  $term_start =
  $_POST['term_start'];

  $term_end =
  $_POST['term_end'];

  $election_id =
  $_POST['election_id'];


/* INSERT */

  if ($official_id == "") {

    $stmt =
    $conn->prepare(

      "INSERT INTO officials
      (
        member_id,
        position_id,
        election_id,
        appointment_type,
        term_start,
        term_end,
        status
      )

      VALUES
      (?,?,?,'appointed',?,?,'active')"

    );

    $stmt->bind_param(
      "iiiss",
      $member_id,
      $position_id,
      $election_id,
      $term_start,
      $term_end
    );

    $stmt->execute();

  }

  $_SESSION['msg'] =
  "saved";

  header(
    "Location: appoint_committee.php"
  );

  exit();

}


/* GET APPOINTED POSITIONS */

$positions =
$conn->query(

  "SELECT
      position_id,
      position_name

   FROM positions

   WHERE
     position_type='Appointed'
     AND status='active'

   ORDER BY ordinal_no"

);


/* GET MEMBERS */

$members =
$conn->query(

  "SELECT
      member_id,
      full_name, email

   FROM members

   WHERE status='Active'

   ORDER BY full_name"

);


/* GET CURRENT ELECTION */

$election =
$conn->query(

  "SELECT
      election_id,
      election_name

   FROM elections

   ORDER BY election_year DESC
   LIMIT 1"

)->fetch_assoc();


include 'header.php';

?>

<?php

if (isset($_SESSION['msg'])) {

  $msg =
  $_SESSION['msg'];

  unset($_SESSION['msg']);

?>

<script>

document.addEventListener(
  "DOMContentLoaded",
  function(){

<?php if ($msg=="saved") { ?>

  Swal.fire({

    icon:"success",

    title:"Saved!",

    text:
    "Committee member appointed successfully."

  });

<?php } ?>

<?php if ($msg=="deleted") { ?>

  Swal.fire({

    icon:"success",

    title:"Deleted!",

    text:
    "Appointment removed successfully."

  });

<?php } ?>

  }
);

</script>

<?php } ?>

<main id="main" class="main">

<div class="pagetitle">

<h1>Appointed Committee</h1>

    <nav>

      <ol class="breadcrumb">

        <li class="breadcrumb-item">
          <a href="dashboard.php">
            Home
          </a>
        </li>

        <li class="breadcrumb-item">
          IMPC Appointed Committee
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

Appointed Committee Members

<button
  class="btn btn-primary btn-sm float-end"
  data-bs-toggle="modal"
  data-bs-target="#appointmentModal">

  Appoint Member

</button>

</h5>


<table class="table datatable small">

<thead>

<tr>

<th>Member</th>
<th>Email</th>

<th>Position</th>

<th>Term Start</th>

<th>Term End</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

$query = "

SELECT

  o.*,

  m.full_name,

  p.position_name, email

FROM officials o

JOIN members m
ON m.member_id=o.member_id

JOIN positions p
ON p.position_id=o.position_id

WHERE
  o.appointment_type='appointed'
  AND o.status='active'

ORDER BY
  p.position_name

";

$result =
$conn->query($query);

while ($row=$result->fetch_assoc()) {

?>

<tr>

<td>
<?= htmlspecialchars(
$row['full_name']
) ?>
</td>

<td>
<?= htmlspecialchars(
$row['email']
) ?>
</td>

<td>
<?= htmlspecialchars(
$row['position_name']
).' - '.htmlspecialchars(
$row['sub_position']
) ?>
</td>

<td>
<?= $row['term_start'] ?>
</td>

<td>
<?= $row['term_end'] ?>
</td>

<td width="1%" style="white-space:nowrap">

<button
class="btn btn-outline-danger btn-sm"
onclick="confirmDelete(<?= $row['official_id'] ?>)" >

Remove

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


<div class="modal fade" id="appointmentModal">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Appoint Committee Member

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
name="official_id"
id="official_id">


<div class="mb-3">

<label>Member</label>

<select
name="member_id"
class="form-control"
required>

<option value="">
Select Member
</option>

<?php
while ($m=$members->fetch_assoc()) {
?>

<option
value="<?= $m['member_id'] ?>">

<?= $m['full_name'] ?> (<?= $m['email'] ?>)

</option>

<?php } ?>

</select>

</div>


<div class="mb-3">

<label>Position</label>

<select
name="position_id"
class="form-control"
required>

<option value="">
Select Position
</option>

<?php
while ($p=$positions->fetch_assoc()) {
?>

<option
value="<?= $p['position_id'] ?>">

<?= $p['position_name'] ?>

</option>

<?php } ?>

</select>

</div>


<div class="mb-3">

<label>Term Start</label>

<input
type="date"
name="term_start"
class="form-control"
required>

</div>


<div class="mb-3">

<label>Term End</label>

<input
type="date"
name="term_end"
class="form-control"
required>

</div>


<input
type="hidden"
name="election_id"
value="<?= $election['election_id'] ?>">


<button
type="submit"
name="save_appointment"
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

function confirmDelete(id){

Swal.fire({

title:"Remove Appointment?",

icon:"warning",

showCancelButton:true,

confirmButtonColor:"#d33"

}).then((result)=>{

if(result.isConfirmed){

window.location=
"appoint_committee.php?delete="+id;

}

});

}

</script>

</body>

</html>