<?php

// Detect environment
$isLocalhost = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

// Localhost credentials
if ($isLocalhost) {
  $servername = "localhost";
  $username   = "root";
  $password   = "vertrigo";
  $database   = "tnrmssks25_sksucampman";
}
else {
  // Online server credentials
  $servername = "localhost";
  $username   = "tnrmssks25";
  $password   = "Mn3m0n1cs_18";
  $database   = "tnrmssks25_sksucampman";
}

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// echo "Connected successfully!";
?>