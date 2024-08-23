<?php

include '../../components/processes/db_connection.php';

$participantID = $_POST['participantID'];
$attendance = $_POST['attendance'];
$outputs = $_POST['outputs'];
$payment = $_POST['payment'];

$updatePaxStatusStmt = $conn->prepare("UPDATE training_participants SET attendance = ?, outputs = ?, payment = ? WHERE participantID = ?");
$updatePaxStatusStmt->bind_param("ssss", $attendance, $outputs, $payment, $participantID);

if ($updatePaxStatusStmt->execute()) {
  echo "ok";
} else {
  echo $updatePaxStatusStmt->error;
}