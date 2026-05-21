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

<div class="center mt-30">

<div class="subtitle">
  STUDENT'S PLEDGE
</div>

</div>

<div class="justify indent mt-30">

I,

<span class="line" style="min-width:250px;">
<strong>
    <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    <?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
    <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>
</strong>
</span>,

student of

<span class="line" style="min-width:250px;">
<strong>
<?php echo isset($form['course']) ? $form['course'] : ''; ?>

<?php 
echo isset($form['major']) ? 
' major in '.$form['major'] : ''; 
?>
</strong>
</span>

in SULTAN KUDARAT STATE UNIVERSITY, Isulan Campus, do hereby agree and promise to abide by the rules on student conduct of the College while undergoing the On-the-Job Training at

<span class="line" style="min-width:250px;">

<strong><?php echo isset($form['agency']) ? strtoupper($form['agency']) : ''; ?></strong>

</span>,

as well as the pertinent rules and regulations of the employer/agency/business.

</div>

<div class="justify indent mt-20">

I understand that while on this On-the-Job training assignment, I shall be under the direct supervision of the cooperating manager or his/her representative or equivalent responsible government official/agency officer.

</div>
<br>
<br>

<div class="mt-50" align="center">
<strong>
    <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    <?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
    <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>
</strong>

<br>

Signature of Student/Date

</div>

<div class="mt-40">

Signed in the presence of the <br>Adviser/OJT coordinator

</div>

<div class="mt-40" align="left">

<strong><?=$program_chairman ?></strong>

<br>

Signature of Program Chairman/Date

</div>



</body>

</html>