<?php

session_start();
require '../db/dbconnect.php';

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
  die("Invalid Election");
}

$election_id = intval($_GET['id']);



/* STEP 1 — CLOSE ELECTION */

$stmt = $conn->prepare(

"UPDATE elections
 SET status='Close'
 WHERE election_id=?"

);

$stmt->bind_param("i", $election_id);
$stmt->execute();



/* STEP 2 — INSERT WINNERS INTO officials */

$sql = "

INSERT INTO officials
(
  member_id,
  position_id,
  election_id,
  appointment_type,
  term_start,
  term_end,
  status
)

SELECT
  ranked.member_id,
  ranked.position_id,
  ?,
  'Elected',
  CURDATE(),
  DATE_ADD(CURDATE(), INTERVAL 1 YEAR),
  'Active'

FROM (

  SELECT
    c.member_id,
    c.position_id,

    COUNT(v.vote_id) AS total_votes,

    ROW_NUMBER() OVER (

      PARTITION BY c.position_id

      ORDER BY COUNT(v.vote_id) DESC

    ) AS rank_no

  FROM candidates c

  LEFT JOIN votes v

    ON v.candidate_id = c.candidate_id

  WHERE c.election_id = ?

  GROUP BY
    c.member_id,
    c.position_id

) ranked

JOIN positions p

  ON p.position_id = ranked.position_id

WHERE ranked.rank_no <= p.max_vote

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
  "ii",
  $election_id,
  $election_id
);

$stmt->execute();



$_SESSION['msg'] = "closed";

header("Location: elections.php");
exit();

?>
