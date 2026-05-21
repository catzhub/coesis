<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>Login</title>

<meta name="google-signin-client_id"
content="216794808536-2or0j3bikibqm8a1nsf7k3d0b578ampi.apps.googleusercontent.com">

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
class="col-lg-8 col-md-8 d-flex flex-column align-items-center justify-content-center">

<!-- Logo -->

  <div class="d-flex justify-content-center py-4">

    <a href="index.html"
    class="logo d-flex align-items-center w-auto">

    <img src="images/Engineering-Logo.jpg">

    <span class="d-none d-lg-block">
    CoE-SIS
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


          <div id="g_id_onload"
               data-client_id="216794808536-2or0j3bikibqm8a1nsf7k3d0b578ampi.apps.googleusercontent.com"
               data-callback="handleCredentialResponse"
               data-auto_prompt="false">
          </div>

          <div class="g_id_signin"></div>

          <script>
            function handleCredentialResponse(response) {

              $.ajax({

                url: "verify.php",

                type: "POST",

                data: {
                  credential: response.credential
                },
                success: function(res) {
                  console.log(res);
                  if (res.status == "success") {
                    window.location = res.redirect;
                  }
                }

              });

            }
          </script>
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
</body>

</html>