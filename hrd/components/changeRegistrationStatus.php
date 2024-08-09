<?php

include '../../components/processes/db_connection.php';

$trainingID = $_GET['id'];
$status = $_GET['status'];
session_start();

if ($status == "0") {
  $changeStatusStmt = $conn->prepare("UPDATE training_details SET openReg = 1, lastUpdateBy = ? WHERE trainingID = ?");
  $changeStatusStmt->bind_param("ii", $_SESSION['userID'], $trainingID);

  if ($changeStatusStmt->execute()) {
    header("Location: ../index.php?t=$trainingID");
    exit();
  } else {
    echo "Error: {$changeStatusStmt->error}";
  }
} else {
  $changeStatusStmt = $conn->prepare("UPDATE training_details SET openReg = 0, lastUpdateBy = ? WHERE trainingID = ?");
  $changeStatusStmt->bind_param("ii", $_SESSION['userID'], $trainingID);

  if ($changeStatusStmt->execute()) {
    header("Location: ../index.php?t=$trainingID");
    exit();
  } else {
    echo "Error: {$changeStatusStmt->error}";
  }
}