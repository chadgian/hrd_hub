<?php
include '../../components/processes/db_connection.php';

if (isset($_POST['participantID']) && isset($_POST['type']) && isset($_POST['value'])) {
  $participantID = $_POST['participantID'];
  $type = $_POST['type'];
  $value = $_POST['value'];

  $changeStatusStmt = $conn->prepare("UPDATE training_participants SET $type = ? WHERE participantID = ?");
  $changeStatusStmt->bind_param("ss", $value, $participantID);

  if ($changeStatusStmt->execute()) {
    echo "ok";
  } else {
    echo "error: {$changeStatusStmt->error}";
  }

}