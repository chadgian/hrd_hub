<?php
include '../../components/functions/security.php';
requirePostMethod();
requireRole('admin');

include '../../components/processes/db_connection.php';

$participantID = (int) ($_POST['participantID'] ?? 0);
$type = $_POST['type'] ?? '';
$value = $_POST['value'] ?? '';

$allowedColumns = ['attendance', 'attendanceRemarks', 'outputs', 'payment', 'remarks'];
if (!in_array($type, $allowedColumns, true)) {
  jsonResponse(400, ['ok' => false, 'message' => 'Invalid field update request']);
}

$changeStatusStmt = $conn->prepare("UPDATE training_participants SET {$type} = ? WHERE participantID = ?");
$changeStatusStmt->bind_param('si', $value, $participantID);

if ($changeStatusStmt->execute()) {
  echo 'ok';
  exit();
}

echo 'Status update failed';
