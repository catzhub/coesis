<?php

session_start();

require '../db/dbconnect.php';
require '../include/activity_log.php';


/* ACCESS CONTROL */

/* ===============================
   ACCESS CONTROL
=============================== */

if (!isset($_SESSION['member_id'])) {

  header("Location: ../userlogin.php");

  exit();

}


/* ===============================
   GET SESSION VALUES
   (Already set in google-login.php)
=============================== */

$member_id =
$_SESSION['member_id'] ?? null;

$position_id =
$_SESSION['position_id'] ?? null;

$position_name =
$_SESSION['position_name'] ?? null;

$sub_position =
$_SESSION['sub_position'] ?? null;

$is_official =
$_SESSION['is_official'] ?? false;

$is_bod =
$_SESSION['is_bod'] ?? false;

$is_credit =
$_SESSION['is_credit'] ?? false;


/* ===============================
   OPTIONAL SAFETY CHECK
=============================== */

if (!$member_id) {

  die("Invalid session.");

}

if (!isset($_GET['id'])) {

  die("Invalid approval request.");

}else{
  // print_r("Valid approval request.</br>");
}


$approval_id = intval($_GET['id']);


/* GET APPROVAL DETAILS */

$sql = " 
SELECT
  member_loan_id,
  sequence_order,
  position_id
FROM loan_approvals
WHERE approval_id=?
LIMIT 1

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "i",
  $approval_id
);

$stmt->execute();

$result =
$stmt->get_result();

if ($result->num_rows == 0) {

  die("Approval record not found.");

}else{
  // print_r("Approval record founds.</br>");
  // die();
}

$row = $result->fetch_assoc();

$member_loan_id = $row['member_loan_id'];
$sequence_order = $row['sequence_order'];
$position_id = $row['position_id'];
$stmt->close();




/* IF USER IS BOD, CHECK CREDIT APPROVALS */

if ($position_id == 1) { // BOD

  $sql = "

  SELECT COUNT(*) AS credit_pending

  FROM loan_approvals

  WHERE

    member_loan_id=?
    AND position_id=4
    AND status='Pending'

  ";

  $stmt =
  $conn->prepare($sql);

  $stmt->bind_param(
    "i",
    $member_loan_id
  );

  $stmt->execute();

  $result =
  $stmt->get_result();

  $row =
  $result->fetch_assoc();

  if ($row['credit_pending'] > 0) {

    $_SESSION['msg'] =
    "waiting_credit";

    header("Location: member_loans.php");

    exit();

  }

  $stmt->close();

}



/* CHECK IF PREVIOUS LEVEL IS COMPLETE */

$sql = "

SELECT COUNT(*) AS pending
FROM loan_approvals
WHERE
  member_loan_id=?
  AND sequence_order > ?
  AND status='Pending'

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $member_loan_id,
  $sequence_order
);

$stmt->execute();

$result =
$stmt->get_result();

$row =
$result->fetch_assoc();

if ($row['pending'] > 0) {

  $_SESSION['msg'] = "waiting_previous";

  header("Location: member_loans.php");

  exit();
}

$stmt->close();



/* APPROVE CURRENT RECORD */
$sql = "

UPDATE loan_approvals

SET
  status='Approved',
  approval_date=NOW()

WHERE
  approval_id=?
  AND approver_id=?

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $approval_id,
  $member_id
);

$stmt->execute();

if ($stmt) {
  // print_r("Updated</br>");
}else{

// print_r("Failed</br>");
}

$stmt->close();



/* CHECK IF CURRENT LEVEL COMPLETED */

$sql = "

SELECT COUNT(*) AS pending
FROM loan_approvals
WHERE
  member_loan_id=?
  AND sequence_order < ?
  AND status='Pending'

";

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $member_loan_id,
  $sequence_order
);

$stmt->execute();

$result =
$stmt->get_result();

$row =
$result->fetch_assoc();

$current_pending =
$row['pending'];

$stmt->close();



/* IF CURRENT LEVEL DONE */

if ($current_pending == 0) {

  /* CHECK IF FINAL LEVEL */

  $sql = "

  SELECT MAX(sequence_order)
  AS max_sequence

  FROM loan_approvals

  WHERE member_loan_id=?

  ";

  $stmt =
  $conn->prepare($sql);

  $stmt->bind_param(
    "i",
    $member_loan_id
  );

  $stmt->execute();

  $result =
  $stmt->get_result();

  $row =
  $result->fetch_assoc();

  $max_sequence =
  $row['max_sequence'];

  $stmt->close();


  /* FINAL APPROVAL */

  if ($sequence_order ==
      $max_sequence) {

    $loan_status =
    "Approved";

  }

  else {

    $loan_status =
    "Under Review";

  }


  /* UPDATE LOAN STATUS */

$sql = "

UPDATE member_loans

SET loan_status=?

WHERE
member_loan_id=?  

";

  $stmt =
  $conn->prepare($sql);

  $stmt->bind_param(
    "si",
    $loan_status,
    $member_loan_id
  );

  $stmt->execute();

  $stmt->close();

}



/* LOG ACTIVITY */

logActivity(
  $conn,
  'loan_approved',
  'Loan approval processed',
  $member_loan_id
);


/* SUCCESS MESSAGE */

$_SESSION['msg'] =
"approved";

header("Location: member_loans.php");

exit();

?>