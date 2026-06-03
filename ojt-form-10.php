<?php

require 'include/auth.php';
require 'db/dbconnect.php';
require 'include/get_form_details.php';

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

      font-family: Arial, Helvetica, sans-serif;
      font-size: 18px;
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
  EVALUATION REPORT
</div>

<div>
  (To be accomplished by the OJT Supervisor)
</div>

</div>

<table style="margin-top:20px;line-height:1.2;">

<tr>

<td width="20%">
  Student's Name:
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

Direction: Read the following descriptions and rate the students using the following rating scale. All answers will be treated confidentiality. Just check the boxes of the choices made.

</div>

<div class="mt-10">

1 – Poor

&nbsp;&nbsp;&nbsp;

2 – Fair

&nbsp;&nbsp;&nbsp;

3 – Good

&nbsp;&nbsp;&nbsp;

4 – Very Good

&nbsp;&nbsp;&nbsp;

5 – Excellent

</div>

<table class="table mt-20">

<?php

function rating_rows($title, $rows, $letter) {

?>

<tr>

  <td align="center">
    <strong><?php echo $letter; ?></strong>
  </td>

  <td colspan="1">
    <strong><?php echo $title; ?></strong>
  </td>

  <th class="rating" width="4%">1</th>
  <th class="rating" width="4%">2</th>
  <th class="rating" width="4%">3</th>
  <th class="rating" width="4%">4</th>
  <th class="rating" width="4%">5</th>

</tr>

<?php

foreach ($rows as $key => $value) {

?>

<tr>

  <td align="center">
    <?php echo ($key + 1); ?>
  </td>

  <td>
    <?php echo $value; ?>
  </td>

  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>

</tr>

<?php

}

?>

<tr>

  <td></td>

  <td>
    Average Rating
  </td>

  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>

</tr>

<?php

}

rating_rows(

  'PERSONALITY',

  array(
    'General appearance/Personal Grooming',
    'Leadership/Regularity in attendance',
    'Cooperativeness',
    'Enthusiasm',
    'Honesty',
    'Trustworthiness',
    'Industriousness',
    'Punctuality',
    'Social awareness',
    'Perseverance',
    'Proper and effective use of equipment'
  ),

  'A'

);

rating_rows(

  'PERSONAL ABILITIES',

  array(
    'Attitudes towards assigned works',
    'Problem solving capability',
    'Planning and action',
    'Takes and uses suggestions',
    'Speed, accuracy and precision',
    'Diagnose problems and give remedial measures',
    'Carrying work to completion',
    'Keeping accurate records',
    'Adapt to different situations',
    'Initiative and resourcefulness'
  ),

  'B'

);

rating_rows(

  'ACQUISITION OF TECHNICAL KNOWLEDGE AND PROFICIENCY',

  array(
    'New management practices learned',
    'New operations/job performed',
    'New managerial responsibilities development',
    'New skills mastered',
    'Habits performed and attitudes developed',
    'Technical adequacy and experience',
    'Ability to work well with superiors and fellow workers'
  ),

  'C'

);

rating_rows(

  'WORK HABIT ASSESSMENT',

  array(
    'Carries out orders/instructions from superior correctly',
    'Demonstrates effective oral and written communication ability in his/her work',
    'Demonstrates interest and enthusiasm',
    'Demonstrates sense of accountability for actions and decisions',
    'Demonstrates consistency in assuming responsibilities',
    'Show flexibility in dealing with specific situations',
    'Show greater awareness of one’s social responsibilities',
    'Demonstrates mastery of work functions',
    'Shows ability to influence others (ideas, opinions, decisions)',
    'Implements plans and program with confidence'
  ),

  'D'

);

rating_rows(

  'STUDENTS ATTRIBUTES',

  array(
    'Work performance',
    'Performance in comparison with other students',
    'Progress made while undergoing OJT/OIP',
    'Getting along with other people',
    'Attendance',
    'Dependability',
    'Ability to take responsibility/is',
    'Potential for advancement/growth',
    'Degree of supervision needed',
    'Overall attitude'
  ),

  'E'

);

?>

<tr>

<td></td>

<td>
  Overall Rating
</td>

<td></td>
<td></td>
<td></td>
<td></td>
<td></td>

</tr>

</table>
<table>
  <tr>
    <td></td>
    <td width="1%" style="white-space:nowrap">
      <div class="mt-30" align="left">

      <strong><?php echo $form['representative'] ?></strong>

      <br>

      Head/Representative of Cooperating Agency

      </div>
    </td>
  </tr>
</table>

      

<div
  class="mt-20 justify"
  style="font-size:10px;line-height:1.2;">

<strong>
NOTE:
</strong>

This is a permanent record of the student. It should be accomplished throughout the duration of the student's training period. Before the student is transferred to another shop this record should be submitted to the shop or agency head that will turn it over to the OJT coordinator.

<br><br>

At the end of the term or when the student quits OJT, this record should be filed at the office of the shop/agency who will turn it over to the OJT coordinator.

</div>

<table
style="margin-top:20px;font-size:10px;line-height:1.3;">

<tr>

<td width="20%">
  <strong>
    Legend:
  </strong>
</td>

<td width="15%">
  <strong>
    Score
  </strong>
</td>

<td width="25%">
  <strong>
    Equivalents
  </strong>
</td>

<td width="40%">
  <strong>
    Description
  </strong>
</td>

</tr>

<tr>
  <td></td>
  <td>1</td>
  <td>70 – 76</td>
  <td>Poor</td>
</tr>

<tr>
  <td></td>
  <td>2</td>
  <td>77 – 82</td>
  <td>Fair</td>
</tr>

<tr>
  <td></td>
  <td>3</td>
  <td>83 – 88</td>
  <td>Good</td>
</tr>

<tr>
  <td></td>
  <td>4</td>
  <td>89 – 94</td>
  <td>Very Good</td>
</tr>

<tr>
  <td></td>
  <td>5</td>
  <td>95 – 100</td>
  <td>Excellent</td>
</tr>

</table>

</body>

</html>