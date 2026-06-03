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
    OJT Endorsement Letter
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
    .form-title{
      font-size: 8px;
    }

    .center {
      text-align: center;
    }

    .justify {
      text-align: justify;
    }

    .header {
      margin-bottom: 30px;
    }

    .title {
      font-weight: bold;
      text-transform: uppercase;
    }

    .subtitle {
      font-size: 16px;
      font-weight: bold;
    }

    .mt-20 {
      margin-top: 20px;
    }

    .mt-30 {
      margin-top: 30px;
    }

    .mt-50 {
      margin-top: 50px;
    }

    .signature {
      font-weight: bold;
    }

    .footer {
      margin-top: 60px;
      font-size: 12px;
    }
    .data{
      white-space: nowrap;
      width: 1%;
      border:1px #000;
    }
    /*.table td{
      border:1px solid #000;
    }*/

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
  <table class="form-title">
    <tr>
      <td></td>
      <td width="1%" style="white-space:nowrap; text-align:left" >
        OJT-ARTA Form 01 <br>
        Revision No.: ___________<br>
        Date: __________________
      </td>
    </tr>
  </table>

<table width="100%">
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

      <div style="font-weight:bold;font-size:20px;">
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
  PERSONAL DATA
</div>

</div>

<table class="mt-20 table">

<tr>

  <td style="white-space:nowrap" width="1%" width="15%">Last name: </td>
  <td style="white-space:nowrap" width="1%" width="35%">
    <strong>
      <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>
    </strong>
  </td>
  <td style="white-space:nowrap" width="1%" width="15%">
    First name:
  </td>

  <td style="white-space:nowrap" width="1%" width="25%">

    <strong>
      <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%" width="10%">
    MI:

    <strong>
      <?php echo isset($form['middlename']) && $form['middlename'] != '' ? strtoupper($form['middlename'][0]) : ''; ?>
    </strong>

  </td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Sex:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['gender']) ? $form['gender'] : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    Marital Status:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['marital_status']) ? $form['marital_status'] : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">

    Citizenship:

  </td>
  <td>

    <strong>
      <?php echo isset($form['citizenship']) ? $form['citizenship'] : ''; ?>
    </strong>
    </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Municipality/City:
  </td>

  <td style="white-space:nowrap" colspan="5">

    <strong>
      <?php echo isset($form['municipality']) ? $form['municipality'] : ''; ?>
    </strong>

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Provincial Address:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['province']) ? $form['province'] : ''; ?>
    </strong>

  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Mobile No.:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['contactno']) ? $form['contactno'] : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    Local no.:
  </td>

  <td style="white-space:nowrap" width="1%">

    __________________

  </td>

  <td style="white-space:nowrap" width="1%">
    E-mail:
  </td>

  <td style="white-space:nowrap" width="">

    <strong>
      <?php echo $email; ?>
    </strong>

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Date of Birth:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>

      <?php echo isset($form['dob']) && $form['dob'] != '0000-00-00' && $form['dob'] != '' ? date('F d, Y', strtotime($form['dob'])) : ''; ?>

    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    Place of Birth:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['birthplace']) ? $form['birthplace'] : ''; ?>
    </strong>

  </td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Height:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['height']) ? $form['height'] : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    Weight:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['weight']) ? $form['weight'] : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    Religion:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['religion']) ? $form['religion'] : ''; ?>
    </strong>

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Father's Name:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['father']) ? strtoupper($form['father']) : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    Occupation:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['fatheroccupation']) ? $form['fatheroccupation'] : ''; ?>
    </strong>

  </td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Mother's Name:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['mother']) ? strtoupper($form['mother']) : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    Occupation:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['motheroccupation']) ? $form['motheroccupation'] : ''; ?>
    </strong>

  </td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Parent's Address:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['guardianaddress']) ? $form['guardianaddress'] : ''; ?>
    </strong>

  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Languages/Dialects:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['dialect']) ? $form['dialect'] : ''; ?>
    </strong>

  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Course:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['course']) ? $form['course'] : ''; ?>
    </strong>

  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Major:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>
      <?php echo isset($form['major']) ? $form['major'] : ''; ?>
    </strong>

  </td>

  <td style="white-space:nowrap" width="1%">
    
  </td>

  <td style="white-space:nowrap" width="1%">

    

  </td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Year level:
  </td>

  <td style="white-space:nowrap" width="1%">

    4th Year

  </td>

  <td style="white-space:nowrap" width="1%">
    Campus:
  </td>

  <td style="white-space:nowrap" width="1%">

    Isulan Campus

  </td>
  <td></td>
  <td></td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    OJT/OIP Assignment:
  </td>

  <td style="white-space:nowrap" width="1%" colspan="5">

    <strong>
      <?php echo isset($form['agency']) ? $form['agency'] : ''; ?>
    </strong>

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Address:
  </td>

  <td style="white-space:nowrap" width="1%" colspan="5">

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

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Head of Office:
  </td>

  <td style="white-space:nowrap" width="1%" colspan="5">

    <strong>
      <?php echo isset($form['representative']) ? $form['representative'] : ''; ?>
    </strong>

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Address:
  </td>

  <td style="white-space:nowrap" width="1%" colspan="5">

    <?php echo implode(', ', $address); ?>

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Duration of OJT/OIP:
  </td>

  <td style="white-space:nowrap" width="1%" colspan="5">

    ________________________________

  </td>

</tr>

<tr>

  <td style="white-space:nowrap" width="1%">
    Date started:
  </td>

  <td style="white-space:nowrap" width="1%">

    <strong>

      <?php echo isset($form['datestart']) && $form['datestart'] != '0000-00-00' && $form['datestart'] != '' ? date('F d, Y', strtotime($form['datestart'])) : ''; ?>

    </strong>

  </td>

  <td style="white-space:nowrap" width="1%" colspan="4">
    Target date of completion: 

    __________________________
  </td>

</tr>

</table>

<div class="mt-30">

I hereby certify that the above information is true and correct to the best of my knowledge and belief.

</div>

<div class="mt-30" align="center">

<strong>


    <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    <?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
    <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>

</strong>

<br>

Applicant's Signature

</div>

<table class="mt-30" style="" width="100%">

<tr>
  <td width="1%" style="white-space:nowrap"> SSS No.</td>
  <td>:__________________</td>
</tr>
<tr>
  <td width="1%" style="white-space:nowrap">Passport No.</td>
  <td>:__________________</td>
</tr>
<tr>
  <td style="white-space:nowrap" width="1%">Date Expire</td>
  <td>:__________________</td>
</tr>

</table>

</body>

</html>