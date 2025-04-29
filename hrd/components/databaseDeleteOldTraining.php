<?php

include '../../components/processes/db_connection.php';

$conn->begin_transaction();
$trainingID = $_POST['trainingID'];

$deleteTrainingStmt = $conn->prepare("DELETE FROM oldtraining_details WHERE oldTrainingID = ?");
$deleteTrainingStmt->bind_param("i", $trainingID);
if ($deleteTrainingStmt->execute()) {
  $deleteParticipantsStmt = deleteParticipants($conn, $trainingID);
  if ($deleteParticipantsStmt) {
    $conn->commit();
    echo "ok";
  } else {
    $conn->rollback();
    echo "failed";
  }
}
$deleteTrainingStmt->close();

function deleteParticipants($conn, $trainingID)
{
  $deleteParticipantsStmt = $conn->prepare("DELETE FROM oldtraining_participants WHERE oldTrainingID = ?");
  $deleteParticipantsStmt->bind_param("i", $trainingID);
  return $deleteParticipantsStmt->execute();
}