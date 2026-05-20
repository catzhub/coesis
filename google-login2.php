<?php

require 'vendor/autoload.php';
require 'db/dbconnect.php';
require 'include/activity_log.php';


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
    "status"=>"error",
    "message"=>"Missing credential"
  ]);

  exit;

}


/* Verify Google */

$client = new Client([
  'client_id' =>
  '807074098909-sc38on4sjquq5nj95ofpfhf3e03r19jk.apps.googleusercontent.com'
]);

$payload =
$client->verifyIdToken(
  $data['credential']
);

if (!$payload) {

  echo json_encode([
    "status"=>"error",
    "message"=>"Invalid Google token"
  ]);

  exit;

}


/* Extract */

$email     = $payload['email'];
$name      = $payload['name'];
$picture   = $payload['picture'];
$google_id = $payload['sub'];


/* Get user */

$stmt =
$conn->prepare(

"SELECT
  users.*,
  categories.category_name,
  members.member_id

FROM users

JOIN categories
ON users.category_id = categories.category_id

JOIN members
ON members.email = users.email

WHERE users.email = ?
AND users.status = 'active'
AND members.status = 'active'"

);

$stmt->bind_param(
  "s",
  $email
);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


/* Unauthorized */

if (!$user) {

  echo json_encode([
    "status"=>"error",
    "message"=>"Unauthorized email"
  ]);

  exit;

}else{


	logActivity(

	  $conn,

	  "user_login",

	  $email,

	  $user['user_id']

	);
}


/* Save Google ID */

if (empty($user['google_id'])) {

  $stmt =
  $conn->prepare(

  "UPDATE users
   SET google_id=?,
       full_name=?,
       profile_picture=?
   WHERE user_id=?"

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


/* Decide redirect */

if ($user['category_name'] === 'admin') {

  $redirect = "dashboard.php";

}

else if ($user['category_name'] === 'member') {

  /* TEMP redirect */

  $redirect = "members/member_loans.php";

}

else {

  echo json_encode([
    "status"=>"error",
    "message"=>"Invalid role"
  ]);

  exit;

}


/* Update login */

$stmt =
$conn->prepare(

"UPDATE users
 SET last_login = NOW()
 WHERE user_id=?"

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


$_SESSION['member_id'] =
$user['member_id'];


/* Security */

session_regenerate_id(true);


/* Return */

echo json_encode([

  "status"=>"success",
  "redirect"=>$redirect

]);