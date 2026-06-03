<?php  
require 'include/auth.php';
require 'db/dbconnect.php';
$email = isset($_SESSION['email']) ? mysqli_real_escape_string($conn,$_SESSION['email']) : '';

$query = " SELECT *
  FROM ojt_form_details
  WHERE email='$email'
  LIMIT 1
  ";

  $select = mysqli_query($conn, $query);
  $form = mysqli_fetch_assoc($select);

  $program_chairman = '';
  $program_position = '';

  if ( isset($form['course']) ) {
    if (
      $form['course'] == 'Bachelor of Science in Civil Engineering'
    ) {

      $program_chairman = 'KIM TYRONE P. CARDENAS, MSCE';
      $program_position = 'Program Chairman, BSCE';

    }

    else if (
      $form['course'] == 'Bachelor of Science in Computer Engineering'
    ) {

      $program_chairman = 'CHARITY L. ORIA, DEng';
      $program_position = 'Program Chairman, BSCpE';

    }

    else if (
      $form['course'] == 'Bachelor of Science in Electronics Engineering'
    ) {

      $program_chairman = 'IVAN ROY S. EVANGELISTA, ME-ECE';
      $program_position = 'Program Chairman, BSECE';

    }

  }

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




?>