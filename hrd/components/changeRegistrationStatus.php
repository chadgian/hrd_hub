<?php

include '../../components/processes/db_connection.php';

$trainingID = $_GET['id'];
$status = $_GET['status'];
session_start();
$conn->begin_transaction();

if ($status == "0") {
  $changeStatusStmt = $conn->prepare("UPDATE training_details SET openReg = 1, lastUpdateBy = ? WHERE trainingID = ?");
  $changeStatusStmt->bind_param("ii", $_SESSION['userID'], $trainingID);

  if ($changeStatusStmt->execute()) {

    $addTrainingActivityStmt = $conn->prepare("INSERT INTO activities (trainingID, userID, newData, oldData, activityType) VALUES (?, ?, ?, ?, ?)");
    $newData = "1";
    $oldData = "0";
    $activityType = "4";
    $addTrainingActivityStmt->bind_param("iissi", $trainingID, $_SESSION['userID'], $newData, $oldData, $activityType);

    if ($addTrainingActivityStmt->execute()) {
      // Commit the transaction
      $conn->commit();
    } else {
      // Rollback the transaction in case of error
      $conn->rollback();
      echo "Error: {$addTrainingActivityStmt->error}";
    }

    header("Location: ../index.php?t=$trainingID");
    exit();
  } else {
    echo "Error: {$changeStatusStmt->error}";
  }
} else {
  $changeStatusStmt = $conn->prepare("UPDATE training_details SET openReg = 0, lastUpdateBy = ? WHERE trainingID = ?");
  $changeStatusStmt->bind_param("ii", $_SESSION['userID'], $trainingID);

  if ($changeStatusStmt->execute()) {
    $addTrainingActivityStmt = $conn->prepare("INSERT INTO activities (trainingID, userID, newData, oldData, activityType) VALUES (?, ?, ?, ?, ?)");
    // Prepare the statement for inserting into activities table
    $newData = "0";
    $oldData = "1";
    $activityType = "4";
    $addTrainingActivityStmt->bind_param("iissi", $trainingID, $_SESSION['userID'], $newData, $oldData, $activityType);

    if ($addTrainingActivityStmt->execute()) {
      // Commit the transaction
      $conn->commit();
    } else {
      // Rollback the transaction in case of error
      $conn->rollback();
      echo "Error: {$addTrainingActivityStmt->error}";
    }

    header("Location: ../index.php?t=$trainingID");
    exit();
  } else {
    echo "Error: {$changeStatusStmt->error}";
  }
}