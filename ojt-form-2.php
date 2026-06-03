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
    OJT Form 02
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

    table {
      border-collapse: collapse;
    }

    .center {
      text-align: center;
    }

    .justify {
      text-align: justify;
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

    .title {
      font-size: 14px;
      font-weight: bold;
    }

    .subtitle {
      font-size: 14px;
      font-weight: bold;
    }

    .line {
      display: inline-block;
      border-bottom: 1px solid #000;
      min-width: 300px;
    }

    .checkbox {
      display: inline-block;
      width: 14px;
    }
    .line-full {
      min-width: 100%;
      height: 1px;
      background: #000;
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

  <table style="font-size:8px">
    <tr>
      <td></td>
      <td width="1%" style="white-space:nowrap; text-align:left" >
        OJT-ARTA Form 02 <br>
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

        <div class="">
          <strong>SULTAN KUDARAT STATE UNIVERSITY</strong>
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


  <div class="center mt-10">

    <div class="subtitle">
      RESPONSE SLIP
    </div>

    <div class="subtitle">
      COOPERATING AGENCY PROFILE
    </div>

  </div>


  <div class="mt-30">
    <strong>THE CAMPUS DIRECTOR</strong>
  </div>
  <br>
  <table class="mt-">
    <tr>
      <td></td>
      <td>Thru:</td>
      <td><strong>PERCILA M. PANAGDATO, PCpE</strong></td>
    </tr>
    <tr>
      <td width="10%"></td>
      <td></td>
      <td>The Campus OJT Coordinator</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>Isulan Campus</td>
    </tr>
  </table>



  <div class="mt-20">
    Sir/Madam:
  </div>


  <div class="mt-20">

    <span class="checkbox">( )</span>

    Yes we are willing to be a site of OJT/Occupational internship program.

  </div>


  <div class="">

    <span class="checkbox">( )</span>

    No, we are not willing to be a site of OJT/Occupational internship program.

  </div>


  <div class="mt-10">

    Name of Company/Agency:

    <span class="">

      <?php

      echo isset($form['agency']) ? $form['agency'] : '';

      ?>

    </span>

  </div>


  <div class="">

    Address:

    <span class="" style="min-width:500px;">

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

    </span>

  </div>


  <div class="mt-">

    Type of Company:

    <span class="checkbox">( )</span> Public

    <span class="checkbox">( )</span> Private

  </div>


  <div class="mt-">

    Maximum number of students that can be accommodated:

    <span class="line" style="min-width:120px;"></span>

  </div>


<div class="mt-20">

  Area of training that we can offer:

</div>

<table width="100%" style="margin-top:10px;">

  <tr>

    <td width="50%">

      ( ) Animal Production (specify) _________________

      <br>

      ( ) Crop Production (specify) __________________

      <br>

      ( ) Post harvest Technology (specify) __________________

      <br>

      ( ) Farm mechanization/shop work (specify) _____________

      <br>

      ( ) Business management skills (specify) _______________

      <br>

      ( ) Automotive shop (specify) _________________________

      <br>

      ( ) Catering services (specify) _______________________

      <br>

      ( ) Computer works related activities (specify) _______________

      <br>

      ( ) Planning centers (specify) ___________________________

      <br>

      ( ) Food related services (specify) _______________________

      <br>

      ( ) Electrical services (specify) ________________________

      <br>

      ( ) Private or public Schools, Elementary, secondary and College

      <br>

      ( ) Private offices

      <br>

      ( ) LGU's

      <br>

      ( ) Internet café's

      <br>

      ( ) Provincial offices

      <br>

      ( ) Municipal offices

      <br>

      ( ) Provincial Capitol

      <br>

      ( ) Automotive shops

    </td>


    <td width="50%" valign="top">

      ( ) Electrical Shops

      <br>

      ( ) Electronics shops and merchandize

      <br>

      ( ) Hotel and restaurants

      <br>

      ( ) Catering services

      <br>

      ( ) Art shops

      <br>

      ( ) Electrical agency

      <br>

      ( ) Water industry

      <br>

      ( ) Cooperatives

      <br>

      ( ) Barangay offices

      <br>

      ( ) Department stores (food courts)

      <br>

      ( ) Computer shops

      <br>

      ( ) Laboratories

      <br>

      ( ) Tele companies

      <br>

      ( ) PNP Offices

      <br>

      ( ) Drug stores

      <br>

      ( ) Manufacturing industries

      <br>

      ( ) Processing plants

      <br>

      ( ) Banks

      <br>

      ( ) Others (specify) __________________

    </td>

  </tr>

</table>


  <div class="mt-20">

    Remuneration/Incentives we can offer to students:

    <br>
    _______________________________________________________________________________________________________________

  </div>


  <div class="mt-20">

    Company standard operating procedure (SOP) that will be observed by the interns:

    <br>

    _______________________________________________________________________________________________________________

    <br>

    _______________________________________________________________________________________________________________

    <br>

    _______________________________________________________________________________________________________________

  </div>


  <div class="mt-20">

    For the Company/Agency:

  </div>


  <table width="100%" class="mt-20">

    <tr>

      <td width="60%">

        Accomplished by: ___________________________________

      </td>

      <td width="40%">

        Signature: ___________________________

      </td>

    </tr>

    <tr>

      <td width="60%">

        Position: ___________________________________

      </td>

      <td width="40%">

        Date:

        ___________________

      </td>

    </tr>

  </table>


  <table width="100%" class="mt-30">

  </table>

</body>

</html>