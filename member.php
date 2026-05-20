<?php

session_start();

require 'db/dbconnect.php';

/* ACCESS CONTROL */

if (!isset($_SESSION['user_id'])) {
  header("Location: userlogin.php");
  exit();
}

if ($_SESSION['user_role'] !== 'admin') {
  die("Access Denied");
}


/* DELETE MEMBER */

if (isset($_GET['delete'])) {

  $id = intval($_GET['delete']);

  $stmt = $conn->prepare(
    "DELETE FROM members
     WHERE member_id = ?"
  );

  $stmt->bind_param("i", $id);
  $stmt->execute();

  $_SESSION['msg'] = "deleted";

  header("Location: member.php");
  exit();

}


/* ADD / UPDATE MEMBER */

if (isset($_POST['save_member'])) {

  $member_id = $_POST['member_id'];
  $full_name = $_POST['full_name'];
  $email     = $_POST['email'];
  $contact   = $_POST['contact_no'];
  $address   = $_POST['address'];
  $status    = $_POST['status'];


/* INSERT */

  if ($member_id == "") {

    $stmt = $conn->prepare(
      "INSERT INTO members
      (full_name,email,contact_no,address,status)
      VALUES (?,?,?,?,?)"
    );

    $stmt->bind_param(
      "sssss",
      $full_name,
      $email,
      $contact,
      $address,
      $status
    );

    $stmt->execute();

  }

/* UPDATE */

  else {

    $stmt = $conn->prepare(
      "UPDATE members
       SET full_name=?,
           email=?,
           contact_no=?,
           address=?,
           status=?
       WHERE member_id=?"
    );

    $stmt->bind_param(
      "sssssi",
      $full_name,
      $email,
      $contact,
      $address,
      $status,
      $member_id
    );

    $stmt->execute();

  }

  $_SESSION['msg'] = "saved";

  header("Location: member.php");
  exit();

}

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

  <main id="main" class="main" style="min-height:1000px">

    <div class="pagetitle">
      <h1>Member Management</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <!-- <li class="breadcrumb-item">Tables</li> -->
            <li class="breadcrumb-item active">IMPC Member</li>
          </ol>
        </nav>
    </div>

    <section class="section">

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-body">

              <h5 class="card-title">

                Members

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#memberModal">

                  Add Member

                </button>

              </h5>

              <table class="table datatable table-">

                <thead>

                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th class="text-end"> Total CBU</th>
                    <th>Actions</th>
                  </tr>

                </thead>

                <tbody>

                  <?php

                  $query = "

                    SELECT

                      m.*,

                      COALESCE(
                        SUM(mc.amount),
                        0
                      ) AS total_cbu

                    FROM members m

                    LEFT JOIN member_cbu mc
                    ON mc.member_id = m.member_id
                    AND mc.status='active'

                    GROUP BY m.member_id

                    ORDER BY m.full_name

                    ";

                  $result = $conn->query($query);

                  while ($row = $result->fetch_assoc()) {

                  ?>

                  <tr>

                    <td>
                      <?= htmlspecialchars($row['full_name']) ?>
                    </td>

                    <td>
                      <?= htmlspecialchars($row['email']) ?>
                    </td>

                    <td>
                      <?= htmlspecialchars($row['contact_no']) ?>
                    </td>

                    <td>
                      <?= htmlspecialchars($row['address']) ?>
                    </td>

                    <td>
                      <?= htmlspecialchars($row['status']) ?>
                    </td>
                    <td class="text-end">

                      <a href='member_cbu.php?member_id=<?= $row["member_id"] ?>'
                        class="text-primary text-decoration-none">
                        ₱<?= number_format($row['total_cbu'],2) ?>
                      </a>

                    </td>

                    <td width="1%" style="white-space:nowrap">


                      <a
                        class="btn btn-outline-info btn-sm"
                        href="member_loans.php?member_id=<?= $row['member_id'] ?>">

                        <i class="bi bi-cash-stack"></i>
                        Loans

                      </a>

                      <button
                        class="btn btn-outline-warning btn-sm"

                        onclick='editMember(
                          <?= $row["member_id"] ?>,
                          <?= json_encode($row["full_name"]) ?>,
                          <?= json_encode($row["email"]) ?>,
                          <?= json_encode($row["contact_no"]) ?>,
                          <?= json_encode($row["address"]) ?>,
                          <?= json_encode($row["status"]) ?>
                        )'>

                        Edit

                      </button>

                      <button
                        class="btn btn-outline-danger btn-sm"
                        onclick="confirmDeleteMember(<?= $row['member_id'] ?>)">

                        Delete

                      </button>

                    </td>

                  </tr>

                  <?php } ?>

                </tbody>

              </table>

            </div>

          </div>

        </div>

      </div>

    </section>

    <div class="modal fade" id="memberModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">
              Member Form
            </h5>

            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal">
            </button>

          </div>

          <div class="modal-body">

            <form method="POST">

              <input
                type="hidden"
                name="member_id"
                id="member_id">

              <div class="mb-3">

                <label>Name</label>

                <input
                  type="text"
                  name="full_name"
                  id="m_full_name"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Email</label>

                <input
                  type="email"
                  name="email"
                  id="m_email"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>Contact</label>

                <input
                  type="text"
                  name="contact_no"
                  id="m_contact"
                  class="form-control">

              </div>

              <div class="mb-3">

                <label>Address</label>

                <input
                  type="text"
                  name="address"
                  id="m_address"
                  class="form-control">

              </div>

              <div class="mb-3">

                <label>Status</label>

                <select
                  name="status"
                  id="m_status"
                  class="form-control">

                  <option value="Active">
                    Active
                  </option>
                  <option value="Retired">
                    Retired
                  </option>
                  <option value="Separated">
                    Separated
                  </option>
                  <option value="Inactive">
                    Inactive
                  </option>

                </select>

              </div>

              <button
                type="submit"
                name="save_member"
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

    function editMember(
      id,
      name,
      email,
      contact,
      address,
      status
    ){

      document.getElementById("member_id").value=id;
      document.getElementById("m_full_name").value=name;
      document.getElementById("m_email").value=email;
      document.getElementById("m_contact").value=contact;
      document.getElementById("m_address").value=address;
      document.getElementById("m_status").value=status;

      new bootstrap.Modal(
        document.getElementById("memberModal")
      ).show();

    }


    function confirmDeleteMember(id){

      Swal.fire({

        title:"Delete Member?",
        icon:"warning",

        showCancelButton:true,

        confirmButtonColor:"#d33"

      }).then((result)=>{

        if(result.isConfirmed){

          window.location=
            "member.php?delete="+id;

        }

      });

    }

  </script>

</body>

</html>