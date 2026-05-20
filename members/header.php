<?php

/* DETECT ROLE */

$member_id =
$_SESSION['member_id'];

$is_bod =
$_SESSION['is_bod'] ?? false;

$is_credit =
$_SESSION['is_credit'] ?? false;

?>

<?php

$pending_count = 0;

/* CREDIT COMMITTEE PENDING */

if ($is_credit) {

  $sql = "

  SELECT COUNT(*)

  FROM loan_approvals

  WHERE

    approver_id=?
    AND status='Pending'

  ";

  $stmt = $conn->prepare($sql);

  $stmt->bind_param(
    "i",
    $member_id
  );

}

/* BOD PENDING */

if ($is_bod) {

  $sql = "

  SELECT COUNT(*)

  FROM loan_approvals la

  WHERE

    la.approver_id=?
    AND la.status='Pending'

    AND NOT EXISTS (

      SELECT 1

      FROM loan_approvals cc

      WHERE

        cc.member_loan_id =
        la.member_loan_id

        AND cc.position_id=4
        AND cc.status='Pending'

    )

  ";

$stmt_pending = $conn->prepare($sql);

$stmt_pending->bind_param(
  "i",
  $member_id
);

$stmt_pending->execute();

$stmt_pending->bind_result(
  $pending_count
);

$stmt_pending->fetch();

$stmt_pending->close();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>IMPC</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <!-- SweetAlert2 -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body style="min-height:1000px">

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="users-profile.php" class="logo d-flex align-items-center">
        <img src="../assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">IMPC</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">
            <?= $pending_count ?>
            </span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have <?= $pending_count ?> new notifications
              <!-- <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a> -->
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <?php

              $sql = "

              SELECT

                la.approval_id,
                m.full_name,
                ml.application_date,
                ml.amount_applied

              FROM loan_approvals la

              JOIN member_loans ml
              ON ml.member_loan_id=
                 la.member_loan_id

              JOIN members m
              ON m.member_id=
                 ml.member_id

              WHERE

                la.approver_id=?
                AND la.status='Pending'

              LIMIT 5

              ";

$stmt_notify = $conn->prepare($sql);

$stmt_notify->bind_param(
  "i",
  $member_id
);

$stmt_notify->execute();

$result = $stmt_notify->get_result();


              while ($row=$result->fetch_assoc()) {

              ?>

              <li class="notification-item">

                <i class="bi bi-cash text-success"></i>

                <div>

                <h4>
                <?= htmlspecialchars(
                $row['full_name']
                ) ?>
                </h4>

                <p>
                Loan ₱<?= number_format(
                $row['amount_applied'],2
                ) ?>
                </p>

                <p>
                <?= $row['application_date'] ?>
                </p>

                </div>

                </li>

                <li>
                <hr class="dropdown-divider">
                </li>

                <?php

                }

                $stmt_notify->close();

                ?>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown"  style="display:none">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="../assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="../assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="../assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
              <img src="<?=htmlspecialchars($_SESSION['user_picture']) ?>" alt="">

            <span class="d-none d-md-block dropdown-toggle ps-2">
              <?php
              echo htmlspecialchars($_SESSION['user_name']);
              ?>
            </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>

              <?php
              echo htmlspecialchars($_SESSION['user_name']);
              ?>

              </h6>

              <span>

              <?php
              echo htmlspecialchars($_SESSION['user_email']);
              ?>

              </span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="../logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.php">
          <i class="bi bi-grid"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Dashboard Nav -->


      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="users.php">
          <i class="bi bi-person"></i>
          <span>Users</span>
        </a>
      </li><!-- End Profile Page Nav -->
      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="members.php">
          <i class="bi bi-person"></i>
          <span>Members</span>
        </a>
      </li><!-- End Members Page Nav -->
      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="elections.php">
          <i class="bi bi-person"></i>
          <span>Elections</span>
        </a>
      </li><!-- End Elections Page Nav -->
      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="positions.php">
          <i class="bi bi-person"></i>
          <span>Positions</span>
        </a>
      </li><!-- End Positions Page Nav -->
      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="candidates.php">
          <i class="bi bi-person"></i>
          <span>Candidates</span>
        </a>
      </li><!-- End Candidates Page Nav -->
<?php

$active_election =
$conn->query("

  SELECT election_id
  FROM elections
  WHERE status='Open'
  LIMIT 1

");

$hasElection =
$active_election->num_rows > 0;

?>

<li class="nav-item">

  <a
    class="nav-link collapsed"
    href="<?= $hasElection ? 'votes.php' : '#' ?>"
    id="navElection"
    data-haselection="<?= $hasElection ? '1' : '0' ?>"
  >
    <i class="bi bi-check2-square"></i>
    <span>
      Election
      <?php if (!$hasElection) { ?>
        <small class="text-danger">
          (No Active Election)
        </small>
      <?php } ?>
    </span>
  </a>
</li>

<script>

document.addEventListener(
  "DOMContentLoaded",
  function(){

    const nav =
    document.getElementById(
      "navElection"
    );

    if (nav) {

      nav.addEventListener(
        "click",
        function(e){

          const hasElection =
          nav.dataset.haselection;

          if (hasElection === "0") {

            e.preventDefault();

            Swal.fire({

              icon: "warning",

              title:
              "No Active Election",

              text:
              "There is currently no active election available.",

              confirmButtonColor:
              "#dc3545"

            });

          }

        }

      );

    }

  }

);

</script>

      <li class="nav-item">
        <a class="nav-link collapsed" href="mycbu.php">
          <i class="bi bi-piggy-bank"></i>
          <span>My CBU</span>
        </a>
      </li><!-- End Member CBU Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="myloans.php">
          <i class="bi bi-cash-stack"></i>
          <span>My Loans</span>
        </a>
      </li><!-- End Member Loans Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="loan_payments.php">
          <i class="bi bi-receipt"></i>
          <span>My Payments</span>
        </a>
      </li><!-- End Loan Payments Page Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>My Loans</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="member_loans.php">
              <i class="bi bi-circle"></i><span>My Loans</span>
            </a>
          </li>
          <li>
            <a href="loan_payments.php">
              <i class="bi bi-circle"></i><span>My Payments</span>
            </a>
          </li>
          <!-- <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li> -->
        </ul>
      </li><!-- End Forms Nav -->


      <?php
      $member_id = $_SESSION['member_id'];
      $sql = " 
      SELECT o.official_id 
      FROM officials o 
      JOIN positions p ON p.position_id=o.position_id
      WHERE o.member_id=?
      AND o.status='active'
      AND p.position_name='Election Committee'
      AND o.sub_position='Chairman'
      LIMIT 1
      ";

$stmt_election = $conn->prepare($sql);

$stmt_election->bind_param(
  "i",
  $member_id
);

$stmt_election->execute();

$result = $stmt_election->get_result();

      if ($result->num_rows > 0) {
      ?>
      <li class="nav-heading">My Office</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="elections.php">
          <i class="bi bi-gear"></i>
          <span>Manage Election</span>
        </a>
      </li>
      <?php } ?>

      <?php
      $member_id = $_SESSION['member_id'];
      $sql = " 
      SELECT o.official_id 
      FROM officials o 
      JOIN positions p ON p.position_id=o.position_id
      WHERE o.member_id=?
      AND o.status='active'
      AND p.position_name='Board of Director'
      AND o.sub_position='Chairman'
      LIMIT 1
      ";

      $stmt_bod = $conn->prepare($sql);
      $stmt_bod->bind_param("i",$member_id);
      $stmt_bod->execute();
      $result = $stmt_bod->get_result();

      if ($result->num_rows > 0) {
      ?>
      <li class="nav-heading">My Office</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="member.php">
          <i class="bi bi-gear"></i>
          <span>Manage Members</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="positions.php">
          <i class="bi bi-gear"></i>
          <span>Manage Positions</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="elected_committee.php">
          <i class="bi bi-gear"></i>
          <span>Elected Committee</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="appoint_committee.php">
          <i class="bi bi-gear"></i>
          <span>Appointed Committee</span>
        </a>
      </li>
      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="member_loans.php">
          <i class="bi bi-gear"></i>
          <span>Member Loans</span>
        </a>
      </li>
      <?php } ?>
      <?php
      $member_id = $_SESSION['member_id'];
      $sql = " 
      SELECT o.official_id 
      FROM officials o 
      JOIN positions p ON p.position_id=o.position_id
      WHERE o.member_id=?
      AND o.status='active'
      AND p.position_name='Credit Committee'
      AND o.sub_position='Chairman'
      LIMIT 1
      ";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$member_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
      ?>
      <li class="nav-heading">My Office</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="member_loans.php">
          <i class="bi bi-gear"></i>
          <span>Member Loans</span>
        </a>
      </li>

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="components-alerts.html">
              <i class="bi bi-circle"></i><span>Alerts</span>
            </a>
          </li>
          <li>
            <a href="components-accordion.html">
              <i class="bi bi-circle"></i><span>Accordion</span>
            </a>
          </li>
          <li>
            <a href="components-badges.html">
              <i class="bi bi-circle"></i><span>Badges</span>
            </a>
          </li>
          <li>
            <a href="components-breadcrumbs.html">
              <i class="bi bi-circle"></i><span>Breadcrumbs</span>
            </a>
          </li>
          <li>
            <a href="components-buttons.html">
              <i class="bi bi-circle"></i><span>Buttons</span>
            </a>
          </li>
          <li>
            <a href="components-cards.html">
              <i class="bi bi-circle"></i><span>Cards</span>
            </a>
          </li>
          <li>
            <a href="components-carousel.html">
              <i class="bi bi-circle"></i><span>Carousel</span>
            </a>
          </li>
          <li>
            <a href="components-list-group.html">
              <i class="bi bi-circle"></i><span>List group</span>
            </a>
          </li>
          <li>
            <a href="components-modal.html">
              <i class="bi bi-circle"></i><span>Modal</span>
            </a>
          </li>
          <li>
            <a href="components-tabs.html">
              <i class="bi bi-circle"></i><span>Tabs</span>
            </a>
          </li>
          <li>
            <a href="components-pagination.html">
              <i class="bi bi-circle"></i><span>Pagination</span>
            </a>
          </li>
          <li>
            <a href="components-progress.html">
              <i class="bi bi-circle"></i><span>Progress</span>
            </a>
          </li>
          <li>
            <a href="components-spinners.html">
              <i class="bi bi-circle"></i><span>Spinners</span>
            </a>
          </li>
          <li>
            <a href="components-tooltips.html">
              <i class="bi bi-circle"></i><span>Tooltips</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->
      <?php } ?>



      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="components-alerts.html">
              <i class="bi bi-circle"></i><span>Alerts</span>
            </a>
          </li>
          <li>
            <a href="components-accordion.html">
              <i class="bi bi-circle"></i><span>Accordion</span>
            </a>
          </li>
          <li>
            <a href="components-badges.html">
              <i class="bi bi-circle"></i><span>Badges</span>
            </a>
          </li>
          <li>
            <a href="components-breadcrumbs.html">
              <i class="bi bi-circle"></i><span>Breadcrumbs</span>
            </a>
          </li>
          <li>
            <a href="components-buttons.html">
              <i class="bi bi-circle"></i><span>Buttons</span>
            </a>
          </li>
          <li>
            <a href="components-cards.html">
              <i class="bi bi-circle"></i><span>Cards</span>
            </a>
          </li>
          <li>
            <a href="components-carousel.html">
              <i class="bi bi-circle"></i><span>Carousel</span>
            </a>
          </li>
          <li>
            <a href="components-list-group.html">
              <i class="bi bi-circle"></i><span>List group</span>
            </a>
          </li>
          <li>
            <a href="components-modal.html">
              <i class="bi bi-circle"></i><span>Modal</span>
            </a>
          </li>
          <li>
            <a href="components-tabs.html">
              <i class="bi bi-circle"></i><span>Tabs</span>
            </a>
          </li>
          <li>
            <a href="components-pagination.html">
              <i class="bi bi-circle"></i><span>Pagination</span>
            </a>
          </li>
          <li>
            <a href="components-progress.html">
              <i class="bi bi-circle"></i><span>Progress</span>
            </a>
          </li>
          <li>
            <a href="components-spinners.html">
              <i class="bi bi-circle"></i><span>Spinners</span>
            </a>
          </li>
          <li>
            <a href="components-tooltips.html">
              <i class="bi bi-circle"></i><span>Tooltips</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="forms-elements.html">
              <i class="bi bi-circle"></i><span>Form Elements</span>
            </a>
          </li>
          <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>Form Layouts</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form Editors</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link " data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html" class="active">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="charts-chartjs.html">
              <i class="bi bi-circle"></i><span>Chart.js</span>
            </a>
          </li>
          <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>ApexCharts</span>
            </a>
          </li>
          <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li>
        </ul>
      </li><!-- End Charts Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="icons-bootstrap.html">
              <i class="bi bi-circle"></i><span>Bootstrap Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-remix.html">
              <i class="bi bi-circle"></i><span>Remix Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-boxicons.html">
              <i class="bi bi-circle"></i><span>Boxicons</span>
            </a>
          </li>
        </ul>
      </li><!-- End Icons Nav -->

      <li class="nav-heading" style="display:none">Pages</li>

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="pages-faq.html">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Contact</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="pages-register.html">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="pages-login.html">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li><!-- End Login Page Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="pages-error-404.html">
          <i class="bi bi-dash-circle"></i>
          <span>Error 404</span>
        </a>
      </li><!-- End Error 404 Page Nav -->

      <li class="nav-item" style="display:none">
        <a class="nav-link collapsed" href="pages-blank.html">
          <i class="bi bi-file-earmark"></i>
          <span>Blank</span>
        </a>
      </li><!-- End Blank Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->