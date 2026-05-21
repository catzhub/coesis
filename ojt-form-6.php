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
  OJT Form 06
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

        OJT-ARTA Form 06

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
  PARENT'S/GUARDIAN'S CONSENT & WAIVER
</div>

</div>

<div class="mt-20">
<?php echo date("F d, Y", time()) ?>
<br><br>


<?php echo isset($form['guardian']) ? $form['guardian'] : ''; ?>
<?php echo isset($form['municipality']) ? '<br>'.$form['municipality'] : ''; ?>
<?php echo isset($form['province']) ? '<br>'.$form['province'] : ''; ?>



<br>
<br>
<div class="mt-20">
<strong>TO WHOM IT MAY CONCERN:</strong>
</div>

<div class="justify indent mt-20">

This is to certify that I,

<span class="line" style="min-width:250px;">
<strong>
<?php echo isset($form['guardian']) ? strtoupper($form['guardian']) : ''; ?>
</strong>
</span>

parent/guardian of

<span class="line" style="min-width:250px;">
<strong>

<?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
<?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0].'. ') : ''; ?>
<?php echo isset($form['lastname'])  ? strtoupper($form['lastname']) : ''; ?>
</strong>
</span>,

a student of SULTAN KUDARAT STATE UNIVERSITY - Isulan Campus, Isulan, Sultan Kudarat grants him/her permission to undergo On-the-Job Training at

<span class="line" style="min-width:250px;">

<?php echo isset($form['agency']) ? strtoupper($form['agency']) : ''; ?>

</span>

.

</div>

<div class="justify indent mt-20">

I understand and agree that this training is necessary and important implementation of the technical education being taught in the college.

</div>

<div class="justify indent mt-20">

I further affirm that SULTAN KUDARAT STATE UNIVERSITY and the preferred office/establishment are in no way responsible nor they pay compensation for accident, harm or injury happen on the student/trainee during the training and that he/she will undergo the said actual job training without compensation from either the preferred office/establishment or the SULTAN KUDARAT STATE UNIVERSITY.

</div>

<div class="justify indent mt-20">

I also certify that I am doing this in my own free will as evidence by my signature affixed below.

</div>

<table class="mt-40">
  <tr>
    <td></td>
    <td style="white-space:nowrap;text-align:left" width="1%">
      <strong><?php echo isset($form['guardian']) ? strtoupper($form['guardian']) : ''; ?></strong><br>
      Parent/Guardian
    </td>
  </tr>
</table>

<div class="mt-40" align="center">

<strong>


</strong>



</div>

<div class="mt-40">
WITNESSES:
</div>

<table width="100%" class="mt-20">

<tr>

<td width="50%" align="">

<strong><?php echo $program_chairman ?></strong>

<br>

Program Chairman/Date
<br>
<br>Isulan Campus
<br>Sultan Kudarat State University


</td>

<td width="50%" align="">
<strong>PERCILA M. PANAGDATO, PCpE</strong>

<br>

Campus OJT Coordinator/Date
<br>
<br>Isulan Campus
<br>Sultan Kudarat State University

</td>

</tr>

</table>

<div class="justify mt-40">

SUBSCRIBED AND SWORN to before me this ______ day of _____________________, 20___ at _________________________________________________.

</div>

<div class="mt-40" align="right">

___________________________________

<br>

Signature over Printed Name of

<br>

Officer Authorized by Law

</div>

<div class="mt-20" align="right">

___________________________________

<br>

Position

</div>




<table width="100%" class="mt-20">
  <tr>
    <td></td>
    <td colspan="2" style="
    border-top:1px dashed #000;
    border-right:1px dashed #000;
    border-bottom:0px dashed #000;
    border-left:1px dashed #000; 
    vertical-align:top;
  ">
      ACKNOWLEDGMENT RECEIPT
    </td>
  </tr>


  <tr>
  <td></td>

  <td width="10%" style="
    border-top:0px dashed #000;
    border-right:0px dashed #000;
    border-bottom:1px dashed #000;
    border-left:1px dashed #000; 
    vertical-align:top;
    white-space: nowrap
  ">  
  Date: <br>
  Time: <br>
  Received by: <br>
  </td>


  <td width="10%" style="
    border-top:0px dashed #000;
    border-right:1px dashed #000;
    border-bottom:1px dashed #000;
    border-left:0px dashed #000;
    vertical-align:top;
    text-align:center;
  ">  
  <br>
  <br>
  ____________________________ <br>
  Name and Signature  
  </td>

  </tr>
  <tr></tr>

</table>


</body>

</html>