<?php

session_start();
require 'db/dbconnect.php';

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$stmt = $conn->prepare(" SELECT *
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
      font-size: 14px;
      line-height: 1.4;
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

    .header {

      margin-bottom: 30px;

    }

    .title {

      font-weight: bold;
      text-transform: uppercase;

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

    @media print {

      .no-print {

        display: none;

      }

    }

  </style>

</head>

<body>

  <div class="no-print">
    <button onclick="window.print();">
      Print
    </button>
  </div>

  <div class="header" style="text-align:right">

    OJT-ARTA Form 01

    <br>

    Revision No.: ___________

    <br>

    Date: __________________

  </div>

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


  <div class="mt-30">

    <strong>

      <?php

      echo isset($form['representative'])
      ? strtoupper($form['representative'])
      : '';

      ?>

    </strong>


    <?php

    echo isset($form['agency_position'])
    ? $form['agency_position']
    : '';

    ?>
    <br>

    <?php

    echo isset($form['agency'])
    ? $form['agency']
    : '';

    ?>


<?php if (!empty($form['agencyaddress1'])) { ?>
  <?php echo $form['agencyaddress1']; ?>
<?php } ?>

<?php if (!empty($form['agencyaddress2'])) { ?>
  , <?php echo $form['agencyaddress2']; ?>
<?php } ?>

<?php if (!empty($form['agencyaddress3'])) { ?>
  , <?php echo $form['agencyaddress3']; ?>
<?php } ?>

<?php if (!empty($form['agencyaddress4'])) { ?>
  <?php echo $form['agencyaddress4']; ?>
<?php } ?>

<?php if (!empty($form['agencyaddress5'])) { ?>
  , <?php echo $form['agencyaddress5']; ?>
<?php } ?>

  </div>


  <div class="mt-20">

    Sir/Madam:

  </div>


  <div class="mt-20">

    Peace be with you!!!

  </div>


  <div class="justify mt-20">

    The graduating students of

    <strong>

      <?php

      echo isset($form['course'])
      ? $form['course']
      : '';

      ?>

    </strong>

    of

    <strong>
      SULTAN KUDARAT STATE UNIVERSITY - Isulan Campus
    </strong>

    will be going through an On-the-Job Training (OJT) Program as a basic requirement for graduation.

    This OJT program aims to provide our students a concrete venue for adequate exposure to their field of specialization and also for the application of the knowledge and skills they have learned in the classroom to actual situations in the real world of work.

  </div>


  <div class="justify mt-20">

    In line with this objective, we would like to inform your prestigious office/firm/company or establishment that you are chosen to be a partner in this OJT program by giving our students the opportunity for such exposure and to render service that would meet the working hours required of them by the program.

  </div>


  <div class="justify mt-20">

    We are confident that our students who shall serve in your office/firm/company or establishment will be able to contribute in one way or another to the good of your organization.

    Your kind consideration and approval will be highly appreciated.

  </div>


  <div class="justify mt-20">

    We wish you more power and more productive years of operation.

  </div>


  <div class="mt-50">

    Very truly yours,

  </div>


  <div class="mt-50">

    <div class="signature">
      PERCILA M. PANAGDATO, PCpE
    </div>

    <div>
      OJT Coordinator/Faculty/Date
    </div>

  </div>


  <div class="mt-50">

    Approved:

  </div>


  <div class="mt-30">

    <div class="signature">

      <?php

      echo isset($form['representative'])
      ? strtoupper($form['representative'])
      : '';

      ?>

    </div>

    <div>

      <?php

      echo isset($form['agency_position'])
      ? $form['agency_position']
      : '';

      ?>

    </div>

  </div>



</body>

</html>