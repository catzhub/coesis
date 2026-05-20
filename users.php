<?php

session_start();

require 'db/dbconnect.php';
require 'include/activity_log.php';

/* ACCESS CONTROL */

if (!isset($_SESSION['user_id'])) {
  header("Location: userlogin.php");
  exit();
}

if ($_SESSION['user_role'] !== 'admin') {
  die("Access Denied");
}


/* =========================
   DELETE USER
========================= */

if (isset($_GET['delete'])) {

  $id = intval($_GET['delete']);

  if ($id != $_SESSION['user_id']) {

    /* GET EMAIL BEFORE DELETE */
    $stmt = $conn->prepare(
      "SELECT email
       FROM users
       WHERE user_id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $email = $row['email'];

    /* DELETE USER */
    $stmt = $conn->prepare(
      "DELETE FROM users
       WHERE user_id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();

    logActivity($conn, "delete_user", "Member user ".$email." was deleted", $id);

  }

  $_SESSION['msg'] = "deleted";
  header("Location: users.php");
  exit();

}


/* =========================
   ADD / UPDATE USER
========================= */

if (isset($_POST['save_user'])) {

  $user_id     = $_POST['user_id'];
  $full_name   = $_POST['full_name'];
  $email       = $_POST['email'];
  $category_id = $_POST['category_id'];
  $status      = $_POST['status'];


/* INSERT */

  if ($user_id == "") {

    /* Check duplicate email */


/* Check member exists */

$member_check =
$conn->prepare(

"SELECT member_id
 FROM members
 WHERE email = ?
 AND status='active'"

);

$member_check->bind_param(
"s",
$email
);

$member_check->execute();

$member_result =
$member_check->get_result();

if ($member_result->num_rows == 0) {

  echo "<script>

    alert('Email must belong to an active member');

    window.location='users.php';

  </script>";

  exit();

}


/* Check duplicate user */

$check =
$conn->prepare(

"SELECT user_id
 FROM users
 WHERE email = ?"

);

$check->bind_param(
"s",
$email
);

$check->execute();

$result =
$check->get_result();

if ($result->num_rows > 0) {

  echo "<script>

    alert('User already exists');

    window.location='users.php';

  </script>";

  exit();

}

    // $check = $conn->prepare(
    //   "SELECT user_id
    //    FROM users
    //    WHERE email = ?"
    // );

    // $check->bind_param("s", $email);
    // $check->execute();

    // $result = $check->get_result();

    // if ($result->num_rows > 0) {

    //   echo "<script>
    //     alert('Email already exists');
    //     window.location='users.php';
    //   </script>";

    //   exit();

    // }

    $stmt = $conn->prepare(
      "INSERT INTO users
      (full_name, email, category_id, status)
      VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
      "ssis",
      $full_name,
      $email,
      $category_id,
      $status
    );

    $stmt->execute();

    logActivity($conn, "add_user", "Member user ".$email." was added", $email);

  }


/* UPDATE */

  else {

    $stmt = $conn->prepare(
      "UPDATE users
       SET full_name   = ?,
           email       = ?,
           category_id = ?,
           status      = ?
       WHERE user_id   = ?"
    );

    $stmt->bind_param(
      "ssisi",
      $full_name,
      $email,
      $category_id,
      $status,
      $user_id
    );

    $stmt->execute();

  }

  $_SESSION['msg'] = "saved";
  header("Location: users.php");
  exit();

}


/* LOAD TEMPLATE HEADER */

include 'header.php';



?>

<?php

  if (isset($_SESSION['msg'])) {

    $msg = $_SESSION['msg'];

    unset($_SESSION['msg']);

?>

<script>

  document.addEventListener("DOMContentLoaded", function() {

  <?php if ($msg == "saved") { ?>

    Swal.fire({
      icon: "success",
      title: "Saved!",
      text: "User successfully saved."
    });

  <?php } ?>

  <?php if ($msg == "deleted") { ?>

    Swal.fire({
      icon: "success",
      title: "Deleted!",
      text: "User successfully deleted."
    });

  <?php } ?>

  });

  </script>

  <?php

  }

?>

  <main id="main" class="main"  style="min-height:1000px">

    <div class="pagetitle">
      <h1>User Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <!-- <li class="breadcrumb-item">Users</li> -->
          <li class="breadcrumb-item active">IMPC Users</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
                Users
                <button
                    class="btn btn-primary btn-sm float-end"
                    data-bs-toggle="modal"
                    data-bs-target="#userModal">
                    Add User
                </button>
              </h5>
              <table class="table datatable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                      $query = "
                        SELECT
                          users.*,
                          user_categories.category_name,
                          members.full_name

                        FROM users

                        JOIN user_categories
                        ON users.category_id =
                           user_categories.category_id

                        LEFT JOIN members
                        ON members.email =
                           users.email
                      ";
                      $result = $conn->query($query);
                      while ($row = $result->fetch_assoc()) {
                    ?>

                      <tr>
                        <td>
                          <?php
                          echo htmlspecialchars($row['full_name']);
                          ?>
                        </td>

                        <td>
                          <?php
                          echo htmlspecialchars($row['email']);
                          ?>
                        </td>

                        <td>
                          <?php
                          echo htmlspecialchars($row['category_name']);
                          ?>
                        </td>

                        <td>
                          <?php
                          echo htmlspecialchars($row['status']);
                          ?>
                        </td>

                        <td width="1%" style="white-space:nowrap">

                          <button
                            class="btn btn-outline-warning btn-sm"

                            onclick="editUser(
                            '<?php echo $row['user_id']; ?>',
                            '<?php echo $row['full_name']; ?>',
                            '<?php echo $row['email']; ?>',
                            '<?php echo $row['category_id']; ?>',
                            '<?php echo $row['status']; ?>'
                                                                        )">

                            Edit

                          </button>
                          <button class="btn btn-danger btn-sm"  onclick="confirmDelete(<?= $row['user_id']; ?>)"> 
                            Delete
                          </button>
                        </td>
                      </tr>

                      <?php
                      }
                      ?>
                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- USER MODAL -->

    <div class="modal fade" id="userModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
                User Form
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal">
            </button>
          </div>

          <div class="modal-body">
            <form method="POST" onsubmit="return confirmSave();">
              <input
                type="hidden"
                name="user_id"
                id="user_id">
              <div class="mb-3">

                <label>Member</label>

                <select
                  name="email"
                  id="email"
                  class="form-control"
                  required>

              <?php

              $members =
              $conn->query(

              "SELECT
                full_name,
                email

               FROM members

               WHERE status='active'

               ORDER BY full_name"

              );

              while ($m =
              $members->fetch_assoc()) {

              ?>

              <option
              value="<?= $m['email'] ?>">

              <?= htmlspecialchars(
                   $m['full_name']
                 ) ?>

              (<?= htmlspecialchars(
                    $m['email']
                  ) ?>)

              </option>

              <?php } ?>

                </select>

              </div>


              <div class="mb-3">

                <label>Role</label>

                <select
                  name="category_id"
                  id="category_id"
                  class="form-control">

                  <?php

                  $roles = $conn->query(
                      "SELECT * FROM user_categories"
                  );

                  while ($role = $roles->fetch_assoc()) {

                  ?>

                    <option value="<?php echo $role['category_id']; ?>">
                      <?php echo $role['category_name']; ?>
                    </option>

                  <?php } ?>
                </select>
              </div>


              <div class="mb-3">

                <label>Status</label>

                <select
                  name="status"
                  id="status"
                  class="form-control">

                  <option value="active">
                      Active
                  </option>

                  <option value="inactive">
                      Inactive
                  </option>

                </select>

              </div>


              <button
                type="submit"
                name="save_user"
                class="btn btn-primary">

                Save

              </button>

            </form>
          </div>
        </div>
      </div>

    </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>

    function editUser(
        id,
        name,
        email,
        category,
        status
    ) {

        document
            .getElementById("user_id")
            .value = id;
        document
            .getElementById("email")
            .value = email;

        document
            .getElementById("category_id")
            .value = category;

        document
            .getElementById("status")
            .value = status;

        new bootstrap.Modal(
            document.getElementById("userModal")
        ).show();

    }

  </script>

  <script>

    function confirmSave() {

      let user_id =
        document.getElementById("user_id").value;

      let action =
        user_id === "" ? "add" : "update";

      return new Promise((resolve) => {

        Swal.fire({

          title:
            action === "add"
              ? "Add User?"
              : "Update User?",

          text:
            action === "add"
              ? "Create this new user?"
              : "Save changes to user?",

          icon: "question",

          showCancelButton: true,

          confirmButtonColor: "#3085d6",

          confirmButtonText: "Yes, Save",

          cancelButtonText: "Cancel"

        }).then((result) => {

          if (result.isConfirmed) {

            resolve(true);

            document.forms[0].submit();

          } else {

            resolve(false);

          }

        });

      });

      return false;

    }

    </script>


  <script>

    function confirmDelete(user_id) {

      Swal.fire({

        title: "Delete User?",
        text: "This action cannot be undone.",
        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",

        confirmButtonText: "Yes, delete it!"

      }).then((result) => {

        if (result.isConfirmed) {

          window.location =
            "users.php?delete=" + user_id;

        }

      });

    }

  </script>

</body>

</html>