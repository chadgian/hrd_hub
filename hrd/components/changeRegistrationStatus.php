<?php

include '../../components/processes/db_connection.php';

$trainingID = $_GET['id'];
$status = $_GET['status'];

if ($status == "0") {
  $changeStatusStmt = $conn->prepare("UPDATE training_details SET openReg = 1 WHERE trainingID = ?");
  $changeStatusStmt->bind_param("i", $trainingID);

  if ($changeStatusStmt->execute()) {
    header("Location: ../index.php?t=$trainingID");
    exit();
  } else {
    echo "Error: {$changeStatusStmt->error}";
  }
} else {
  $changeStatusStmt = $conn->prepare("UPDATE training_details SET openReg = 0 WHERE trainingID = ?");
  $changeStatusStmt->bind_param("i", $trainingID);

  if ($changeStatusStmt->execute()) {
    header("Location: ../index.php?t=$trainingID");
    exit();
  } else {
    echo "Error: {$changeStatusStmt->error}";
  }
}