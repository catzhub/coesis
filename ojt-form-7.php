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
  OJT Form 07
</title>

<style>



@page {

  size: 8.5in 13in;
  margin: 0.4in 1in 1in 1in;

}

body {

  font-family: "Times New Roman", serif;
  font-size: 16px;
  line-height: 1.2;
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

        OJT-ARTA Form 07

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
  MEMORANDUM OF TRAINING AGREEMENT
</div>

</div>

<div class="mt-20" align="right">

<?php echo date("F d, Y", time()) ?>


</div>

<div class="justify mt-20">

The

<span class="line" style="min-width:250px;">

<strong><?php echo isset($form['agency']) ? strtoupper($form['agency']) : ''; ?></strong>

</span>

agrees to permit

<span class="line" style="min-width:250px;">
<strong>
<?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
<?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
<?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>
</strong>
</span>

to work in this office, establishment/agency under On-the-Job Training Program for the purpose of gaining experiences, knowledge, and skills in the various phases of his/her field of study.

</div>

<div class="mt-20">

In addition, it is agreed that:

</div>

<ol class="justify">

<li>

The On-the-Job Training Program is designed to run for a minimum of

<span class="line" style="min-width:80px;">

<?php echo isset($form['ojthours']) ? $form['ojthours'] : ''; ?>

</span>

hours required for the work experiences. The same may start on

<span class="line" style="min-width:150px;">

<?php echo isset($form['datestart']) && $form['datestart'] != '0000-00-00' && $form['datestart'] != '' ? date('F d, Y', strtotime($form['datestart'])) : '______________'; ?>

</span>

and end about one or two weeks before the beginning of the next semester.

</li>

<li>

The student while in process of training will have the status of student trainee, neither displacing a regular worker/employee nor substituting the worker needed by the Cooperating Establishment or Agency.

</li>

<li>

The student agrees to perform diligently the work experiences assigned to him/her by the Cooperating Agency. The student also agrees to pursue faithfully the prescribed course of study to take advantage of every opportunity to improve his/her chosen occupation as a desirable employee until the termination of the training period.

</li>

<li>

SKSU official shall make supervisory and instructional visit to the Cooperating Agency or Establishment during the training period to evaluate the student's progress and to discuss the training program with the concerned official of the Cooperating Agency/Establishment.

</li>

<li>

The College representative or coordinator shall have the authority to transfer or withdraw the student-trainee at any time for reasons of:

<br>

a.) Conduct unbecoming

<br>

b.) Inefficiency

<br>

c.) Habitual tardiness

<br>

d.) Absenteeism

<br>

e.) Non-compliance of requirements set by the College or the Cooperating Agency or Establishment.

</li>

<li>

The student has the right to ask for a change of training station. His/her request for transfer shall be evaluated by the College representative and shall be granted if properly justified.

</li>

<li>

The Cooperating Agency/Establishment may provide the student-trainee such allowances and other benefits as the former may deem fit.

</li>

<li>

The Cooperating Agency/Establishment reserves the right to dismiss the student-trainee at any time without the consultation with the OJT Supervisor or his/her authorized representative.

</li>

<li>

All complaints of the Cooperating Establishment or Agency regarding the conduct and performance of the student-trainee shall be made only to the school in writing for record purposes.

</li>

<li>

The On-the-Job Training program of the student-trainee shall conform to all laws and regulations promulgated by the constituted authorities.

</li>

<li>

The representative of the College shall prepare a schedule of work experiences of the On-the-Job Training and technical information parallel to what has been taught by the college. This shall serve as guide in providing work experiences to the student/trainee.

</li>

</ol>
<table width="100%" class="mt-40">

<tr>

  <td width="50%" align="center">

  <strong>

  <?php echo isset($form['representative']) ? strtoupper($form['representative']) : ''; ?>

  </strong>


  </td>

  <td width="50%" align="center">

  <strong>
  ROMMEL M. LAGUMEN, PhD
  </strong>

  <br>



  </td>

</tr>
<tr>
  <td align="center" style="vertical-align:top">
  (Head/Representative of Agency/Establishment)
  <br>
  <?php echo isset($form['agency']) ? strtoupper($form['agency']) : ''; ?>
  </td>
  <td align="center" style="vertical-align:top">
  Campus Director, Isulan Campus/Date

  <br>
  SULTAN KUDARAT STATE UNIVERISTY

  </td>
</tr>

</table>

<div class="mt-30">
WITNESSES
</div>

<table class="mt-20" width="100%">

<tr>

<td width="50%" align="center">

<strong>PERCILA M. PANAGDATO, PCpE</strong>

<br>

OJT Coordinator/Date
<br>
SKSU - Isulan Campus

</td>

<td width="50%" align="center">

<strong><?=$program_chairman ?></strong>

<br>

Program Chairman
<br>
SKSU - Isulan Campus

</td>

</tr>

<tr>

</table>

<div class="justify mt-40">

SUBSCRIBED AND SWORN to before me this ___ day of __________________, 20____ at _______________________________________.

</div>

<div class="mt-40" align="center">

_______________________________________________

<br>

Signature over Printed Name of Officer Authorized by Law

</div>

<div class="mt-20" align="center">

_______________________________________________

<br>

Position

</div>

</body>

</html>