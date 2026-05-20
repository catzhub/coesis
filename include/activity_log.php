<?php

function logActivity(
  $conn,
  $type,
  $description,
  $reference_id = null
){

  if (!isset($_SESSION['user_id'])) {
    return;
  }

  $stmt =
  $conn->prepare(

  "INSERT INTO activities
  (activity_type,
   description,
   reference_id,
   created_by)

  VALUES (?,?,?,?)"

  );

  $stmt->bind_param(

  "ssii",

  $type,
  $description,
  $reference_id,
  $_SESSION['user_id']

  );

  $stmt->execute();

}