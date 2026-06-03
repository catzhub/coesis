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
  OJT Form 04
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

</body>

<div class="center mt-20">

<div class="subtitle">
OFFICE OF THE ON-THE JOB TRAINING COORDINATOR
</div>

</div>

<div class="mt-30">

<span class="" style="min-width:250px;">
<strong>
<?php echo isset($form['representative']) ? strtoupper($form['representative']) : ''; ?>  
</strong>



</span>

<br>

<span class="" style="min-width:250px;">
  <?php echo isset($form['agency']) ? $form['agency'] : ''; ?>
</span>

<br>

<span class="" style="min-width:250px;">
<?php

$address = array();

if (!empty($form['agencyaddress1'])) {
  $address[] = $form['agencyaddress1'];
}

if (!empty($form['agencyaddress2'])) {
  $address[] = '<br>'.$form['agencyaddress2'];
}

if (!empty($form['agencyaddress3'])) {
  $address[] = '<br>'.$form['agencyaddress3'];
}

if (!empty($form['agencyaddress4'])) {
  $address[] = '<br>'.$form['agencyaddress4'];
}

if (!empty($form['agencyaddress5'])) {
  $address[] = '<br>'.$form['agencyaddress5'];
}

echo implode(', ', $address);

?>

</span>

</div>

<div class="mt-20">
Sir/Madam:
</div>

<div class="justify mt-20" style="text-indent:0.5in;">

This is to recommend

<span class="line" style="font-weight:bold;min-width:250px;">

    <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    <?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
    <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>

</span>

a bonafide

<strong>

<?php echo isset($form['course']) ? $form['course'] : ''; ?>

</strong>

student of

<strong>
SULTAN KUDARAT STATE UNIVERSITY – Isulan Campus
</strong>

to undergo On-the-Job Training in your prestigious agency/office/establishment.

</div>

<div class="justify mt-20" style="text-indent:0.5in;">

The program is a course requirement for the student trainee to gain firsthand experience in the actual job related to his/her field of specialization.

</div>

<div class="justify mt-20" style="text-indent:0.5in;">

His/Her work attitude deserves commendation and endorsement to your institution.

</div>

<div class="justify mt-20" style="text-indent:0.5in;">

May we look forward to your favorable consideration of this recommendation.

</div>

<div class="mt-20" style="text-indent:0.5in;">
Thank you very much and more power.
</div>

<div class="mt-30">
Very truly yours,
</div>

<table width="100%" class="mt-40">

<tr>

<td width="50%" style="line-height:1.1;">

<strong>
PERCILA M. PANAGDATO, PCpE
</strong>

<br>

Campus OJT Coordinator/Date

</td>

</tr>

</table>

<div class="mt-30" style="line-height:1.1;">

Tel. No. ___________ (Office of the Dean)

<br>

___________ (Guard House)

</div>

<div class="mt-40">

<strong>
</strong>

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