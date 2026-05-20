<?php

session_start();

/* Destroy session */

$_SESSION = [];

session_destroy();

/* Redirect */

header("Location: userlogin.php");
exit();