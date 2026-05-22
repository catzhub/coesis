<?php

require 'include/auth.php';
require 'db/dbconnect.php';

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$stmt = $pdo->prepare("
	SELECT * FROM ojt_form_details
	WHERE email = ?"
	);
if (condition) {
	# code...
$stmt->execute([$email]);
var_dump($stmt);
}else{
	var_dump($_SESSION);
}
