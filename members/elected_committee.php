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
$member_id = $_SESSION['member_id'] ?? null;
$position_id = $_SESSION['position_id'] ?? null;
$position_name = $_SESSION['position_name'] ?? null;
$sub_position = $_SESSION['sub_position'] ?? null;
$is_official = $_SESSION['is_official'] ?? false;
$is_bod = $_SESSION['is_bod'] ?? false;
$is_credit = $_SESSION['is_credit'] ?? false;


/* ===============================
   SAVE SUB-POSITION
=============================== */

if (isset($_POST['save_position'])) {
  $official_id = $_POST['official_id'];
  $sub_position = $_POST['sub_position'];

  $stmt = $conn->prepare(
  "UPDATE officials
   SET sub_position=?
   WHERE official_id=?"
  );

  $stmt->bind_param(
    "si",
    $sub_position,
    $official_id
  );

  $stmt->execute();

  $_SESSION['msg'] = "saved";

  header(
    "Location: elected_committee.php"
  );

  exit();
}


/* ===============================
   GET MY ELECTION ID
=============================== */
$sql = "
SELECT
  o.election_id,
  e.election_name,
  o.position_id,
  p.position_name
FROM officials o
JOIN elections e ON e.election_id = o.election_id
JOIN positions p ON p.position_id = o.position_id
WHERE o.member_id = ?
AND o.position_id = ?
AND o.status = 'active'
LIMIT 1
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $member_id,
  $position_id
);

$stmt->execute();

$result = $stmt->get_result();

$current_election = $result->fetch_assoc();
$my_election_id = $current_election ['election_id'];

$stmt->close();


/* ===============================
   GET ELECTED OFFICIALS
=============================== */
$query = "
SELECT
  o.official_id,
  o.sub_position,
  m.full_name,
  m.email,
  m.member_id,
  p.position_name,
  e.election_name,
  o.election_id
FROM officials o
JOIN members m ON m.member_id = o.member_id
JOIN positions p ON p.position_id = o.position_id
LEFT JOIN elections e ON e.election_id = o.election_id
WHERE o.appointment_type = 'elected'
AND o.status = 'active'
ORDER BY o.election_id,p.ordinal_no
";

$result_elected  =
$conn->query($query);

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

  Swal.fire({

  icon:"success",

  title:"Saved!",

  text:
  "Committee position updated."

  });

  });

</script>

<?php } ?>


<main id="main" class="main">


<div class="pagetitle">

<h1>Elected Committee</h1>

    <nav>

      <ol class="breadcrumb">

        <li class="breadcrumb-item">
          <a href="dashboard.php">
            Home
          </a>
        </li>

        <li class="breadcrumb-item">
          IMPC Elected Committee
        </li>

      </ol>

    </nav>

</div>



<section class="section">

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"> Elected Officials </h5>

          <table class="table datatable small">
            <thead>
              <tr>
                <th>Member</th>
                <th>Email</th>
                <th>Position</th>
                <th>Sub Position</th>
                <th>Election</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($row = $result_elected ->fetch_assoc()) {
              ?>
              <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['position_name']) ?></td>
                <td><?= $row['sub_position']?? 'Not Assigned' ?></td>
                <td><?= $row['election_name']?></td>
                <td width="1%" style="white-space:nowrap" class="text-end">
                  <?php
                    if ($member_id == $row['member_id']) {
                      $btn_status = 'disabled';
                    }else{
                      $btn_status = '';
                    }
                  ?>
                  <?php if ($my_election_id != $row['election_id']):?>
                    <button
                    class="btn btn-danger btn-sm"
                    onclick="removeOfficial(
                    <?= $row['official_id'] ?>,
                    '<?= $row['sub_position'] ?>'
                    )"
                    data-bs-toggle="modal"
                    data-bs-target="#positionModal"
                    >
                    Remove Official
                    </button>
                  <?php endif ?>

                  <button
                  class="btn btn-primary btn-sm <?=$btn_status?>"
                  onclick="editPosition(
                  <?= $row['official_id'] ?>,
                  '<?= $row['sub_position'] ?>'
                  )"
                  data-bs-toggle="modal"
                  data-bs-target="#positionModal"
                  >
                  Assign
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



<!-- ===============================
     MODAL
================================ -->

<div class="modal fade"

id="positionModal">

<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title">Assign Sub Position</h5>
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

<label>Sub Position</label>

<select

name="sub_position"

id="sub_position"

class="form-control"

required>

<option value="">

Select Position

</option>

<option value="Chairman">

Chairman

</option>

<option value="Vice-Chairman">

Vice-Chairman

</option>

<option value="Secretary">

Secretary

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

<!-- ===============================
     JAVASCRIPT
================================ -->

<script>

function editPosition(
official_id,
sub_position
){

document.getElementById(
"official_id"
).value = official_id;

document.getElementById(
"sub_position"
).value =
sub_position ?? "";

}

</script>

</body>

</html>