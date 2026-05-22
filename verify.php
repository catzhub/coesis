<?php

require_once 'vendor/autoload.php';
header('location:test.php');

session_start();
header('Content-Type: application/json');

$id_token = isset($_POST['credential']) ? $_POST['credential'] : '';

if ($id_token == '') {
  echo json_encode([
    "status" => "error",
    "message" => "Missing token"
  ]);
  exit;
}

$CLIENT_ID = "216794808536-2or0j3bikibqm8a1nsf7k3d0b578ampi.apps.googleusercontent.com";

$client = new Google_Client(['client_id' => $CLIENT_ID]);

$payload = $client->verifyIdToken($id_token);

if (!$payload) {
  echo json_encode([
    "status" => "error",
    "message" => "Invalid token"
  ]);
  exit;
}

$_SESSION['google_id'] = $payload['sub'];

$_SESSION['email'] = $payload['email'];

$_SESSION['full_name'] = $payload['name'];

$_SESSION['first_name'] = $payload['given_name'];

$_SESSION['last_name'] = $payload['family_name'];

$_SESSION['picture'] = $payload['picture'];

session_write_close();

echo json_encode([

  "status" => "success",
  "redirect" => "forms-elements.php"

]);

exit;