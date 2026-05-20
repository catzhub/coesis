<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>Login</title>

<meta name="google-signin-client_id"
content="463114917800-nj0vf31qe9s1l8rl59qnkpnqors47hcd.apps.googleusercontent.com">

<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">

<script src="https://accounts.google.com/gsi/client" async defer></script>

</head>

<body>

<main>

<div class="container">

<section
class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">

<div class="container">

<div class="row justify-content-center">

<div
class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

<!-- Logo -->

<div class="d-flex justify-content-center py-4">

<a href="index.html"
class="logo d-flex align-items-center w-auto">

<img src="assets/img/logo.png">

<span class="d-none d-lg-block">
NiceAdmin
</span>

</a>

</div>

<!-- Login Card -->

<div class="card mb-3">

<div class="card-body text-center">

<div class="pt-4 pb-2">

<h5 class="card-title pb-0 fs-4">

Login to Your Account

</h5>

</div>

<form class="row g-3 needs-validation" novalidate>

<div class="col-12 text-center">

<!-- Google Identity -->

<div
id="g_id_onload"
data-client_id="463114917800-nj0vf31qe9s1l8rl59qnkpnqors47hcd.apps.googleusercontent.com"
data-callback="handleCredentialResponse"
data-auto_prompt="false">
</div>

<div
class="g_id_signin"
data-type="standard"
data-size="large"
data-theme="outline"
data-text="signin_with"
data-shape="rectangular">
</div>

</div>

</form>

</div>

</div>

<div class="credits text-center">

Designed by
<a href="https://bootstrapmade.com/">
BootstrapMade
</a>

</div>

</div>

</div>

</div>

</section>

</div>

</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>

/* Secure Google Login */

function handleCredentialResponse(response) {

fetch("google-login.php", {

method: "POST",

headers: {
"Content-Type": "application/json"
},

body: JSON.stringify({
credential: response.credential
})

})

.then(res => res.json())

.then(data => {

/* Decide redirect */

if ($user['category_name'] === 'admin') {

  $redirect = "dashboard.php";

}

else if ($user['category_name'] === 'member') {

  $redirect = "members/member_loans.php";

}

else {

  echo json_encode([
    "status"=>"error",
    "message"=>"Invalid user role"
  ]);

  exit;

}


/* Return success with redirect */

echo json_encode([

  "status"   => "success",
  "redirect" => $redirect

]);

})

.catch(error => {

console.error(error);

});

}

</script>

</body>

</html>