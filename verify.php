<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

require 'db/dbconnect.php';

header('Content-Type: application/json');

session_start();

/* ============================
   READ INPUT
============================ */

// $data = json_decode(file_get_contents("php://input"), true);

// if (!isset($data['credential'])) {
//   echo json_encode([
//     "status"=>"error",
//     "message"=>"Missing credential"
//   ]);
//   exit;
// }



$credential =
isset($_POST['credential'])
? $_POST['credential']
: '';

if ($credential == '') {

  echo json_encode([
    "status"=>"error",
    "message"=>"Missing credential"
  ]);

  exit;

}

/* ============================
   VERIFY GOOGLE TOKEN
============================ */

$verify_url = "https://oauth2.googleapis.com/tokeninfo?id_token=".$credential;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $verify_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);

curl_close($ch);

$payload = json_decode($response, true);

if (!$payload) {

  echo json_encode([
    "status"=>"error",
    "message"=>"Invalid Google token"
  ]);

  exit;

}

/* ============================
   VALIDATE CLIENT ID
============================ */

$client_id =
"216794808536-2or0j3bikibqm8a1nsf7k3d0b578ampi.apps.googleusercontent.com";

if ($payload['aud'] !== $client_id) {

  echo json_encode([
    "status"=>"error",
    "message"=>"Invalid client ID"
  ]);

  exit;

}

/* ============================
   GOOGLE DATA
============================ */

$email            = $payload['email'];
$google_id        = $payload['sub'];
$full_name        = $payload['name'];
$first_name       = isset($payload['given_name']) ? $payload['given_name'] : '';
$last_name        = isset($payload['family_name']) ? $payload['family_name'] : '';
$picture          = $payload['picture'];
$email_verified   = $payload['email_verified'] ? 1 : 0;


$_SESSION['email'] = $email;
$_SESSION['full_name'] = $full_name;
$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['picture'] = $picture;

/* ============================
   CHECK USER
============================ */

// $stmt =
// $conn->prepare(

// "SELECT *
//  FROM users
//  WHERE email=?
//  LIMIT 1"

// );

// $stmt->bind_param(
//   "s",
//   $email
// );

// $stmt->execute();

// $result =
// $stmt->get_result();

// $user =
// $result->fetch_assoc();

/* ============================
   CREATE USER IF NOT EXISTS
============================ */

// if (!$user) {

//   $stmt =
//   $conn->prepare(

//   "INSERT INTO student_users (

//     google_id,
//     email,
//     full_name,
//     first_name,
//     last_name,
//     profile_picture,
//     email_verified,
//     last_login

//   )

//   VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"

//   );

//   $stmt->bind_param(

//     "ssssssi",

//     $google_id,
//     $email,
//     $full_name,
//     $first_name,
//     $last_name,
//     $picture,
//     $email_verified

//   );

//   $stmt->execute();

//   $user_id =
//   $conn->insert_id;

// }
// else {

//   $user_id =
//   $user['user_id'];

//   /* Update latest info */

//   $stmt =
//   $conn->prepare(

//   "UPDATE student_users

//    SET

//     google_id=?,
//     full_name=?,
//     first_name=?,
//     last_name=?,
//     profile_picture=?,
//     email_verified=?,
//     last_login=NOW()

//    WHERE user_id=?"

//   );

//   $stmt->bind_param(

//     "sssssii",

//     $google_id,
//     $full_name,
//     $first_name,
//     $last_name,
//     $picture,
//     $email_verified,
//     $user_id

//   );

//   $stmt->execute();

// }

/* ============================
   SESSION
============================ */

// $_SESSION['user_id'] =
// $user_id;


/* ============================
   SUCCESS
============================ */

ob_clean();

echo json_encode([

  "status"=>"success",
  "redirect"=>"forms-elements.php"

]);

exit;
?>