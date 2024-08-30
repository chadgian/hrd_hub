<?php

include '../../components/processes/db_connection.php';

$participantID = $_POST['participantID'];
$attendance = $_POST['attendance'];
$attendanceRemark = $_POST['attendanceRemark'];
$outputs = $_POST['outputs'];
$payment = $_POST['payment'];
$remarks = $_POST['remarks'];

$updatePaxStatusStmt = $conn->prepare("UPDATE training_participants SET attendance = ?, attendanceRemarks = ?, outputs = ?, payment = ?, remarks = ? WHERE participantID = ?");
$updatePaxStatusStmt->bind_param("ssssss", $attendance, $attendanceRemark, $outputs, $payment, $remarks, $participantID);

if ($updatePaxStatusStmt->execute()) {
  echo "ok";
} else {
  echo $updatePaxStatusStmt->error;
}