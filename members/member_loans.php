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


/* ===============================
   LOAD HEADER
=============================== */

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

<h1>Loan Approvals</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <!-- <li class="breadcrumb-item">Tables</li> -->
            <li class="breadcrumb-item active">Member Loans</li>
          </ol>
        </nav>

</div>

<section class="section">

<div class="card">

<div class="card-body">

<h5 class="card-title">

Board Approval

</h5>

<ul class="nav nav-tabs">

<li class="nav-item">

<button
class="nav-link active"
data-bs-toggle="tab"
data-bs-target="#waiting">

Waiting Approval

</button>

</li>

<li class="nav-item">

<button
class="nav-link"
data-bs-toggle="tab"
data-bs-target="#approved">

Approved

</button>

</li>

</ul>


<div class="tab-content pt-3">
<div
class="tab-pane fade show active"
id="waiting">

<table
class="table datatable">

<thead>

<tr>

<th>Date</th>
<th>Member</th>
<th>Loan Type</th>
<th>Amount</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

$sql = "

SELECT
  la.approval_id,
  la.member_loan_id,

  ml.application_date,
  ml.amount_applied,

  m.full_name,
  lt.loan_type_name,

  la.sequence_order,

  (

    SELECT COUNT(*)
    FROM loan_approvals prev
    WHERE
      prev.member_loan_id =
      la.member_loan_id
      AND prev.sequence_order
          < la.sequence_order
      AND prev.status='Pending'

  ) AS previous_pending,

  (

    SELECT COUNT(*)
    FROM loan_approvals cc
    WHERE
      cc.member_loan_id =
      la.member_loan_id
      AND cc.position_id = 4
      AND cc.status='Pending'

  ) AS credit_pending

FROM loan_approvals la

JOIN member_loans ml
ON ml.member_loan_id =
   la.member_loan_id

JOIN members m
ON m.member_id =
   ml.member_id

JOIN loan_types lt
ON lt.loan_type_id =
   ml.loan_type_id

WHERE

  la.approver_id=?
  AND la.status='Pending'
  AND la.position_id=?

ORDER BY
  ml.application_date DESC

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $member_id,
  $position_id
);

$stmt->execute();

$result =
$stmt->get_result();

while ($row=
$result->fetch_assoc()) {

?>

<tr>

<td>
<?= $row['application_date'] ?>
</td>

<td>
<?= htmlspecialchars(
$row['full_name']
) ?>
</td>

<td>
<?= htmlspecialchars(
$row['loan_type_name']
) ?>
</td>

<td>
₱<?= number_format(
$row['amount_applied'],2
) ?>
</td>

<td>

<?php

$disabled = false;

/* CREDIT LOGIC */

if ($is_credit) {

  /* Credit users can approve their pending items */

  $disabled = false;

}

/* BOD LOGIC */

if ($is_bod) {

  if ($row['credit_pending'] > 0) {

    $disabled = true;

  }

}

?>

<button
class="btn btn-success btn-sm"

<?= $disabled ? "disabled" : "" ?>

onclick="approveLoan(
<?= $row['approval_id'] ?>
)">

Approve

</button>

<button
class="btn btn-danger btn-sm"

<?= $disabled ? "disabled" : "" ?>

onclick="rejectLoan(
<?= $row['approval_id'] ?>
)">

Reject

</button>

<?php if ($disabled) { ?>

<br>

<small class="text-muted">

<?php if ($disabled && $is_bod) { ?>

<br>

<small class="text-muted">

Waiting for Credit Committee approval

</small>

<?php } ?>

</small>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<div
class="tab-pane fade"
id="approved">

<table
class="table datatable">

<thead>

<tr>

<th>Date</th>
<th>Member</th>
<th>Loan Type</th>
<th>Amount</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$sql = "

SELECT

  ml.application_date,
  m.full_name,
  lt.loan_type_name,
  ml.amount_applied,
  la.status

FROM loan_approvals la

JOIN member_loans ml
ON ml.member_loan_id=
   la.member_loan_id

JOIN members m
ON m.member_id=
   ml.member_id

JOIN loan_types lt
ON lt.loan_type_id=
   ml.loan_type_id

WHERE

  la.approver_id=?
  AND la.status='Approved'

ORDER BY
  ml.application_date DESC

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "i",
  $member_id
);

$stmt->execute();

$result =
$stmt->get_result();

while ($row=
$result->fetch_assoc()) {

?>

<tr>

<td>
<?= $row['application_date'] ?>
</td>

<td>
<?= htmlspecialchars(
$row['full_name']
) ?>
</td>

<td>
<?= htmlspecialchars(
$row['loan_type_name']
) ?>
</td>

<td>
₱<?= number_format(
$row['amount_applied'],2
) ?>
</td>

<td>

<span
class="badge bg-success">

Approved

</span>

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

function approveLoan(id){

Swal.fire({

title:"Approve Loan?",

icon:"question",

showCancelButton:true,

confirmButtonColor:"#198754"

}).then((result)=>{

if(result.isConfirmed){

window.location=
"loan_approve.php?id="+id;

}

});

}


function rejectLoan(id){

Swal.fire({

title:"Reject Loan?",

icon:"warning",

showCancelButton:true,

confirmButtonColor:"#d33"

}).then((result)=>{

if(result.isConfirmed){

window.location=
"loan_reject.php?id="+id;

}

});

}

</script>

</body>

</html>