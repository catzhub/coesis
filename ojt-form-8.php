<?php

session_start();

require 'db/dbconnect.php';

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$stmt = $conn->prepare("
  SELECT *
  FROM ojt_form_details
  WHERE email=?
  LIMIT 1
");

$stmt->bind_param(
  "s",
  $email
);

$stmt->execute();

$result = mysqli_stmt_get_result($stmt);

$form = mysqli_fetch_assoc($result);

$age = '';

if (
  isset($form['dob']) &&
  $form['dob'] != '' &&
  $form['dob'] != '0000-00-00'
) {

  $dob = new DateTime($form['dob']);
  $today = new DateTime();

  $age = $dob->diff($today)->y;

}

$program_chairman = '';

if (isset($form['course'])) {

  if (
    $form['course'] == 'Bachelor of Science in Civil Engineering'
  ) {

    $program_chairman = 'KIM TYRONE P. CARDENAS, MSCE';

  }

  else if (
    $form['course'] == 'Bachelor of Science in Computer Engineering'
  ) {

    $program_chairman = 'CHARITY L. ORIA';

  }

  else if (
    $form['course'] == 'Bachelor of Science in Electronics Engineering'
  ) {

    $program_chairman = 'IVAN ROY S. EVANGELISTA, ME-ECE';

  }

}

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>
  OJT Form 08
</title>

<style>



@page {

  size: 8.5in 13in;
  margin: 0.4in 1in 1in 1in;

}

body {

  font-family: "Times New Roman", serif;
  font-size: 16px;
  line-height: 1.3;
  color: #000;

}
html,
body {
  width: 8.5in;
  min-height: 13in;
}

.center {
  text-align: center;
}

.justify {
  text-align: justify;
}

.title {
  font-size: 18px;
  font-weight: bold;

}

.subtitle {
  font-size: 16px;
  font-weight: bold;
}

.mt-10 {
  margin-top: 10px;
}

.mt-20 {
  margin-top: 20px;
}

.mt-30 {
  margin-top: 30px;
}

.mt-40 {
  margin-top: 40px;
}

.indent {
  text-indent: 0.5in;
}

.line-full {
  border-bottom: 1px solid #000;
  margin-top: 5px;

}
table {

  width: 100%;
  border-collapse: collapse;

}

.table,
.table td,
.table th {

  border: 1px solid #000;

}

.table td,
.table th {

  padding: 5px;
  vertical-align: top;

}

@media print {
  .no-print {
    display: none;
  }
}

</style>

</head>

<body onload="window.print();">

  <div class="no-print">

    <button onclick="window.print();">
      Print
    </button>

  </div>

  <table style="font-size:8px;">

    <tr>

      <td></td>

      <td width="1%" style="white-space:nowrap;text-align:left;">

        OJT-ARTA Form 08

        <br>

        Revision No.: ___________

        <br>

        Date: __________________

      </td>

    </tr>

  </table>

  <table width="100%" >

    <tr>

      <td width="15%" align="center">

        <img
        src="images/sksu1.png"
        width="80">

      </td>

      <td width="70%" align="center">

        <div>
          Republic of the Philippines
        </div>

        <div class="title">
          SULTAN KUDARAT STATE UNIVERSITY
        </div>

        <div>
          Province of Sultan Kudarat
        </div>

      </td>

      <td width="15%" align="center">

        <img
        src="images/sksulogo.png"
        width="80">

      </td>

    </tr>

  </table>

<div class="center mt-20">

<div class="subtitle">
  STUDENT'S RECORD OF JOB EXPERIENCES
</div>

</div>

<table style="margin-top:20px;line-height:1.5;">

<tr>

<td width="1%" style="white-space:nowrap">
  Name:
</td>

<td width="55%">

  <strong>
    <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    <?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
    <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>

  </strong>

</td>

<td width="15%">
  School Year:
</td>

<td width="20%">
  2025-2026
</td>

</tr>

<tr>

<td>
  Course:
</td>

<td>

  <strong>
    <?php echo isset($form['course']) ? $form['course'] : ''; ?>
  </strong>

</td>

<td>
  Major:
</td>

<td>

  <strong>
    <?php echo isset($form['major']) ? $form['major'] : ''; ?>
  </strong>

</td>

</tr>

<tr>

<td>
  College:
</td>

<td colspan="3">
  <strong>College of Engineering</strong>
</td>

</tr>

<tr>

<td colspan="4">
  Training Establishment/Shop/Agency:

  <strong>
    <?php echo isset($form['agency']) ? $form['agency'] : ''; ?>
  </strong>
</td>


</tr>

<tr>

<td>
  Address:
</td>

<td colspan="3">
  <strong>

  <?php

  $address = array();

  if (!empty($form['agencyaddress1'])) {
    $address[] = $form['agencyaddress1'];
  }

  if (!empty($form['agencyaddress2'])) {
    $address[] = $form['agencyaddress2'];
  }

  if (!empty($form['agencyaddress3'])) {
    $address[] = $form['agencyaddress3'];
  }

  if (!empty($form['agencyaddress4'])) {
    $address[] = $form['agencyaddress4'];
  }

  if (!empty($form['agencyaddress5'])) {
    $address[] = $form['agencyaddress5'];
  }

  echo implode(', ', $address);

  ?>
  </strong>
</td>

</tr>

</table>

<table class="table mt-20">

<tr align="center">

<th width="10%">
  JOB NO.
</th>

<th width="50%">
  JOB DESCRIPTIONS
</th>

<th width="25%">
  INCLUSIVE TIME/DATE
  <br>
  START -- END
</th>

<th width="15%">
  TRAINOR'S SIGNATURE
</th>

</tr>

<?php for ($i = 1; $i <= 20; $i++) { ?>

<tr>

<td style="height:25px;">
</td>

<td>
</td>

<td>
</td>

<td>
</td>

</tr>

<?php } ?>

</table>
<table>
  <tr>
    <td></td>
    <td width="1%" style="white-space:nowrap">
      <div class="mt-30" align="left">

      <strong><?=$form['representative'] ?></strong>

      <br>

      Head/Representative of Cooperating Agency

      </div>
    </td>
  </tr>
</table>

      

<div class="mt-30 justify" style="font-size:11px;">

NOTE: This is a permanent record of the student. It should be accomplished throughout the duration of the student's training period. Before the student is transferred to another shop this record should be submitted to the shop or agency head that will turn it over to the OJT coordinator.

<br><br>

At the end of the term or when the student quits OJT, this record should be filed at the office of the shop/agency who will turn it over to the OJT coordinator.

</div>

</body>

</html>