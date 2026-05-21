<?php

session_start();

header('Content-Type: application/json');

require 'db/dbconnect.php';

$credential =
isset($_POST['credential'])
? $_POST['credential']
: '';

if ($credential == '') {

  echo json_encode([
    "status" => "error",
    "message" => "Missing credential"
  ]);

  exit;

}

$verify_url =
"https://oauth2.googleapis.com/tokeninfo?id_token=".$credential;

$response =
file_get_contents($verify_url);

$payload =
json_decode($response, true);


if (!$payload) {

  echo json_encode([
    "status" => "error",
    "message" => "Invalid token"
  ]);

  exit;

}

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

echo json_encode([

  "status" => "success",
  "redirect" => "forms-elements.php"

]);

exit;

?>