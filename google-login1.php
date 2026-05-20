<?php

require 'vendor/autoload.php';
require 'db/dbconnect.php';

use Google\Client;

header('Content-Type: application/json');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

session_start();

/* Read input */

$data = json_decode(
file_get_contents("php://input"),
true
);

if (!isset($data['credential'])) {

echo json_encode([
"status"=>"error"
]);

exit;

}

/* Verify Google */

$client = new Client([
'client_id' =>
'463114917800-nj0vf31qe9s1l8rl59qnkpnqors47hcd.apps.googleusercontent.com'
]);

$payload =
$client->verifyIdToken(
$data['credential']
);

if (!$payload) {

echo json_encode([
"status"=>"error"
]);

exit;

}

/* Extract data */

$email = $payload['email'];
$name = $payload['name'];
$picture = $payload['picture'];
$google_id = $payload['sub'];

/* Check user exists */

$stmt =
$conn->prepare(

"SELECT users.*, categories.category_name
 FROM users
 JOIN categories
 ON users.category_id = categories.category_id
 WHERE users.email = ?
 AND users.status = 'active'"

);

$stmt->bind_param(
"s",
$email
);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

/* If email not found */

if (!$user) {

echo json_encode([
"status"=>"error",
"message"=>"Unauthorized email"
]);

exit;

}

/* Save Google ID if first login */

if (empty($user['google_id'])) {

$stmt =
$conn->prepare(

"UPDATE users
 SET google_id = ?,
     full_name = ?,
     profile_picture = ?
 WHERE user_id = ?"

);

$stmt->bind_param(
"sssi",
$google_id,
$name,
$picture,
$user['user_id']
);

$stmt->execute();

}

/* Allow only admin */

if ($user['category_name'] !== 'admin') {

echo json_encode([
"status"=>"error",
"message"=>"Access denied"
]);

exit;

}

/* Update last login */

$stmt =
$conn->prepare(

"UPDATE users
 SET last_login = NOW()
 WHERE user_id = ?"

);

$stmt->bind_param(
"i",
$user['user_id']
);

$stmt->execute();

/* Create session */

$_SESSION['user_id'] =
$user['user_id'];

$_SESSION['user_email'] =
$email;

$_SESSION['user_name'] =
$name;

$_SESSION['user_picture'] =
$picture;

$_SESSION['user_role'] =
$user['category_name'];

echo json_encode([
"status"=>"success"
]);