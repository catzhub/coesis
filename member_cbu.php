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

  /* GET MEMBER */

  if (!isset($_GET['member_id'])) {
    die("Member not specified.");
  }

  $member_id =
    intval($_GET['member_id']);

  /* LOAD MEMBER INFO */

  $stmt =
    $conn->prepare(

      "SELECT
        full_name

       FROM members

       WHERE member_id=?"

    );

  $stmt->bind_param(
    "i",
    $member_id
  );

  $stmt->execute();

  $member =
    $stmt->get_result()
          ->fetch_assoc();

  if (!$member) {
    die("Member not found.");
  }

  /* DELETE (VOID TRANSACTION) */

  if (isset($_GET['delete'])) {

    $id =
      intval($_GET['delete']);

    $stmt =
      $conn->prepare(

        "UPDATE member_cbu
         SET status='void'
         WHERE member_cbu_id=?"

      );

    $stmt->bind_param(
      "i",
      $id
    );

    $stmt->execute();

    logActivity(

      $conn,

      "delete_cbu",

      "CBU transaction voided",

      $id

    );

    $_SESSION['msg'] =
      "deleted";

    header(
      "Location: member_cbu.php?member_id=".$member_id
    );

    exit();

  }

  /* ADD CBU TRANSACTION */

  if (isset($_POST['save_cbu'])) {

    $amount =
      $_POST['amount'];

    $type =
      $_POST['transaction_type'];

    $remarks =
      $_POST['remarks'];

    $stmt =
      $conn->prepare(

        "INSERT INTO member_cbu

        (
          member_id,
          transaction_date,
          amount,
          transaction_type,
          remarks,
          created_at,
          status
        )

        VALUES (

          ?,
          CURDATE(),
          ?,
          ?,
          ?,
          NOW(),
          'active'

        )"

      );

    $stmt->bind_param(

      "idss",

      $member_id,
      $amount,
      $type,
      $remarks

    );

    $stmt->execute();

    logActivity(

      $conn,

      "add_cbu",

      "CBU transaction added",

      $member_id

    );

    $_SESSION['msg'] =
      "saved";

    header(
      "Location: member_cbu.php?member_id=".$member_id
    );

    exit();

  }

  /* COMPUTE TOTAL CBU */

  $totalStmt =
    $conn->prepare(

      "SELECT
        SUM(amount) total_cbu

       FROM member_cbu

       WHERE member_id=?

       AND status='active'"

    );

  $totalStmt->bind_param(
    "i",
    $member_id
  );

  $totalStmt->execute();

  $totalResult =
    $totalStmt->get_result();

  $totalRow =
    $totalResult->fetch_assoc();

  $total_cbu =
    $totalRow['total_cbu'] ?? 0;

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

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>

        Member CBU —
        <?= htmlspecialchars(
          $member['full_name']
        ) ?>

      </h1>

      <nav>

        <ol class="breadcrumb">

          <li class="breadcrumb-item">
            <a href="member.php">
              Members
            </a>
          </li>

          <li class="breadcrumb-item active">
            Member CBU
          </li>

        </ol>

      </nav>

    </div>

    <section class="section">

      <div class="row">

        <!-- TOTAL CARD -->

        <div class="col-md-4">

          <div class="card">

            <div class="card-body">

              <h5 class="card-title">

                Total CBU

              </h5>

              <h3 class="text-primary">

                ₱<?= number_format(
                  $total_cbu,
                  2
                ) ?>

              </h3>

            </div>

          </div>

        </div>

      </div>

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-body">

              <h5 class="card-title">

                CBU Transactions

                <button
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#cbuModal">

                  Add CBU

                </button>

              </h5>

              <table class="table table-sm datatable">

                <thead>

                <tr>

                <th>Date</th>
                <th>Type</th>

                <th class="text-end">
                Amount
                </th>

                <th>Remarks</th>

                <th width="1%">
                Actions
                </th>

                </tr>

                </thead>

                <tbody>

                <?php

                $query =
                $conn->prepare(

                "SELECT *

                 FROM member_cbu

                 WHERE member_id=?

                 AND status='active'

                 ORDER BY transaction_date DESC"

                );

                $query->bind_param(
                "i",
                $member_id
                );

                $query->execute();

                $result =
                $query->get_result();

                while ($row =
                $result->fetch_assoc()) {

                ?>

                <tr>

                <td>
                <?= $row['transaction_date'] ?>
                </td>

                <td>
                <?= $row['transaction_type'] ?>
                </td>

                <td class="text-end">

                ₱<?= number_format(
                $row['amount'],2
                ) ?>

                </td>

                <td>
                <?= htmlspecialchars(
                $row['remarks']
                ) ?>
                </td>

                <td>

                <button
                class="btn btn-danger btn-sm"

                onclick="confirmDeleteCBU(
                <?= $row['member_cbu_id'] ?>
                )">

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





    <div class="modal fade" id="cbuModal">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title">

              Add CBU Transaction

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
                value="<?= $member_id ?>">

              <div class="mb-3">

                <label>
                  Transaction Type
                </label>

                <select
                  name="transaction_type"
                  class="form-control">

                  <option value="Deposit">
                    Deposit
                  </option>

                  <option value="Loan Deduction">
                    Loan Deduction
                  </option>

                  <option value="Adjustment">
                    Adjustment
                  </option>

                </select>

              </div>

              <div class="mb-3">

                <label>
                  Amount
                </label>

                <input
                  type="number"
                  step="0.01"
                  name="amount"
                  class="form-control"
                  required>

              </div>

              <div class="mb-3">

                <label>
                  Remarks
                </label>

                <textarea
                  name="remarks"
                  class="form-control"
                  rows="2"></textarea>

              </div>

              <button
                type="submit"
                name="save_cbu"
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