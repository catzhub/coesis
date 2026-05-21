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
  OJT Form 04
</title>

<style>



@page {

  size: 8.5in 13in;
  margin: 0.4in 1in 1in 1in;

}

body {

  font-family: "Times New Roman", serif;
  font-size: 16px;
  line-height: 1.1;
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

  /*border: 1px solid #000;*/

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

        OJT-ARTA Form 04

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
  OJT FEEDBACKING QUESTIONNAIRE
</div>

<div>
  (Level of cooperation of cooperating Agency)
</div>

</div>

<table style="margin-top:20px;line-height:1.5;">

<tr>

<td width="20%">
  Name of Student:
</td>

<td width="80%">

  <strong>

    <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    <?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
    <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>

  </strong>

</td>

</tr>

<tr>

<td>
  Institution:
</td>

<td>
  Sultan Kudarat State University
</td>

</tr>

<tr>

<td>
  Cooperating Agency:
</td>

<td>

  <strong>
    <?php echo isset($form['agency']) ? strtoupper($form['agency']) : ''; ?>
  </strong>

</td>

</tr>

</table>

<div class="justify mt-20">

Direction: Using the rating scale 1 – 5 where 5 is the highest and 1 is the lowest, rate the following statements. Encircle the number that represents your answers. All responses shall be treated confidential.

</div>

<div class="mt-20">

5 - Excellent

<br>

4 - Very Good

<br>

3 - Good

<br>

2 - Fair

<br>

1 - Poor

</div>


<table class="table mt-10">

<tr>
  <td colspan="6">
    <div class="mt-20">
        <strong class="mt-20">
        A. WORK ENVIRONMENT
        </strong>
    </div>
  </td>
</tr>

<?php

$work_environment = array(
  "Schedule of activities are well planned and organized.",
  "Students are well oriented before actual work performance.",
  "Time allotted is enough to finish and complete task/s.",
  "Equipment/supplies needed are sufficient to perform/accomplish scheduled activities.",
  "The work environment is suitable to accomplish tasks.",
  "Student accommodation is provided.",
  "Students are provided with written or verbal information on the company’s safety regulations and procedures.",
  "Qualified persons are assigned to specific tasks.",
  "Smooth flow of communication is maintained.",
  "Students are provided with updated written policies/limits authorization.",
  "Avenues for student feedback are provided.",
  "Student interns are treated like learners not mere workers."
);

foreach ($work_environment as $key => $value) {

?>

<tr>

<td>

  <?php echo ($key + 1); ?>

</td>

<td>

  <?php echo $value; ?>

</td>

<td class="rating" style="text-align:center">1</td>
<td class="rating" style="text-align:center">2</td>
<td class="rating" style="text-align:center">3</td>
<td class="rating" style="text-align:center">4</td>
<td class="rating" style="text-align:center">5</td>

</tr>

<?php } ?>
<tr>
  <td colspan="6">
    <div class="mt-20">
        <strong class="mt-20">
        B. KNOWLEDGE, SKILLS AND WORK VALUES
        </strong>
    </div>
  </td>
</tr>



<?php

$skills = array(
  "Sufficient time provided to student to achieve mastery of skills.",
  "Skills training activities are arranged sequentially.",
  "Completion of tasks performed is well monitored and recorded.",
  "Quality of work jibes with set standards in workplace.",
  "Supervisor and Manager exhibit mastery of skills which student interns are tasked to perform.",
  "Preserve smooth interpersonal relations between and among student intern and supervisor.",
  "Company policies relevant to the observance of work values are in place.",
  "Skills development of the student intern is a primary concern of the company."
);

foreach ($skills as $key => $value) {

?>

<tr>

<td>

  <?php echo ($key + 1)?>

</td>

<td>

  <?php echo $value; ?>

</td>

<td class="rating" style="text-align:center">1</td>
<td class="rating" style="text-align:center">2</td>
<td class="rating" style="text-align:center">3</td>
<td class="rating" style="text-align:center">4</td>
<td class="rating" style="text-align:center">5</td>

</tr>

<?php } ?>

</table>

<div class="mt-30">
Thank you very much for your cooperation.
</div>

<table width="100%">
  <tr>
    <td></td>
    <td width="1%" style="white-space:nowrap">

      <div class="mt-40" align="left">

      <strong><?php echo $form['representative'] ?></strong>

      <br>

      Head/Representative of Cooperating Agency

      </div>
      
    </td>
  </tr>
</table>

</body>

</html>