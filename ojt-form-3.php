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
  OJT Form 03
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

        OJT-ARTA Form 03

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
      APPLICATION FOR ADMITTANCE TO THE
    </div>

    <div class="subtitle">
      ON-THE-JOB TRAINING PROGRAM
    </div>

  </div>

  <div class="mt-20">

    <strong>
      ROMMEL M. LAGUMEN, PhD
    </strong>

    <br>

    Campus Director, Isulan Campus

    <br>

    This University

  </div>

  <div class="mt-20">
    Sir:
  </div>

  <div class="justify indent mt-20">

    I wish to apply for admittance to undergo
    On-the-Job-Training program.

  </div>

  <table style="margin-top:20px;line-height:1.2;">

    <tr>

      <td
      width="1%"
      style="white-space:nowrap;">

        Name

      </td>

      <td>

        :

        <strong>

          <?php echo isset($form['lastname']) ? strtoupper($form['lastname']): ''; ?>
          <?php echo isset($form['firstname']) ? ', '.strtoupper($form['firstname']) : ''; ?>
          <?php echo isset($form['middlename']) && $form['middlename'] != '' ? ' '.strtoupper($form['middlename'][0]).'.' : ''; ?>

        </strong>

      </td>

      <td
      width="1%"
      style="white-space:nowrap;"></td>

      <td width="30%"></td>

    </tr>

    <tr>

      <td style="white-space:nowrap;">

        Year & Course

      </td>

      <td>

        :

        <strong>

          <?php echo isset($form['course']) ? $form['course'] : ''; ?>

        </strong>

      </td>

      <td
      width="1%"
      style="white-space:nowrap;">

        Major

      </td>

      <td>

        :

        <strong>

          <?php echo isset($form['major']) ? $form['major'] : ''; ?>

        </strong>

      </td>

    </tr>

    <tr>

      <td style="white-space:nowrap;">

        Date of Birth

      </td>

      <td>
        :

        <strong>

          <?php echo isset($form['dob']) && $form['dob'] != '0000-00-00' && $form['dob'] != '' ? date('F d, Y', strtotime($form['dob'])) : ''; ?>

        </strong>

      </td>

      <td>
        Age
      </td>

      <td>

        :

        <strong>

          <?php echo $age; ?>

        </strong>

      </td>

    </tr>

    <tr>

      <td style="white-space:nowrap;">

        Address

      </td>

      <td>

        :

        <strong>

          <?php echo isset($form['municipality']) ? $form['municipality'] : ''; ?>,
          <?php echo isset($form['province']) ? $form['province'] : ''; ?>

        </strong>

      </td>

      <td></td>

      <td></td>

    </tr>

    <tr>

      <td style="white-space:nowrap;">

        Contact No

      </td>

      <td>

        :

        <strong>

          <?php echo isset($form['contactno']) ? $form['contactno'] : ''; ?>

        </strong>

      </td>

      <td></td>

      <td></td>

    </tr>

    <tr>

      <td style="white-space:nowrap;">

        Parent/Guardian

      </td>

      <td>

        :

        <strong>

          <?php echo isset($form['guardian']) ? $form['guardian'] : ''; ?>

        </strong>

      </td>

      <td></td>

      <td></td>

    </tr>

  </table>

<div class="mt-20">

  Major and related subjects completed
  (specify descriptive title)

  <div class="line-full"></div>

  <div class="line-full"></div>

</div>

<div class="mt-20">

  Preferred office/establishment/shop
  where to undergo On-the-Job Training:

</div>

<table style="margin-top:10px;line-height:1.2;">

<tr>

  <td width="18%">
    Name of Agency
  </td>

  <td width="82%">

    :

    <strong><?php echo isset($form['agency']) ? $form['agency'] : ''; ?></strong>

  </td>

</tr>

<tr>

  <td>
    Name of Manager
  </td>

  <td>

    :
    <strong><?php echo isset($form['representative']) ? $form['representative'] : ''; ?></strong>
   

  </td>

</tr>

<tr>

  <td valign="top">
    Complete Address
  </td>

  <td>

    :
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

<tr>

  <td>
    Telephone Number
  </td>

  <td>
    : 
    <strong>
    <?php  
      if (!empty($form['agencycontact'])) {
        echo $form['agencycontact'];
      }
    ?>
    </strong>
  </td>

</tr>

</table>

<div class="justify indent mt-20">

  I have discussed with my parents or guardian
  whose approval is signified by his/her
  signature below regarding my application
  in this program.

</div>

<table width="100%" class="mt-40">

  <tr>
    <td width="50%"></td>

    <td width="50%" align="center">

      <strong>

        
    <?php echo isset($form['firstname']) ? strtoupper($form['firstname']) : ''; ?>
    <?php echo isset($form['middlename']) ? ' '.strtoupper($form['middlename'][0]).'. ' : ''; ?>
    <?php echo isset($form['lastname']) ? strtoupper($form['lastname']) : ''; ?>

      </strong>

      <br>

      Student-applicant

    </td>

  </tr>

</table>

<table width="100%" class="mt-40">

  <tr>

    <td width="50%" align="center">

    </td>

    <td width="50%" align="center">

      <strong>

        <?php echo isset($form['guardian']) ? strtoupper($form['guardian']) : ''; ?>

      </strong>

      <br>

      Parent/Guardian

    </td>

  </tr>

</table>

<div class="mt-30">
  Attested by:
</div>

<div class="mt-20">

  <strong>

    <?php echo $program_chairman; ?>

  </strong>

  <br>

  Program Chairman/Date

</div>

<table width="100%" class="mt-30">

  <tr>

    <td>
      Recommending Approval:
    </td>

    <td>
      Approved:
    </td>
  </tr>

  <tr>

    <td width="50%" style="padding-top:20px;">
      <strong>
        LENMAR T. CATAJAY
      </strong>
      <br>
      College Dean/Date
    </td>
    <td
    width="50%"
    style="padding-top:20px;">

      <strong>
        ROMMEL M. LAGUMEN, PhD
      </strong>

      <br>
      Campus Director/Date
    </td>

  </tr>

</table>

</body>

</html>