<?php
include '../../components/processes/db_connection.php';

header('Content-Type: application/json');

// Validate and sanitize input
$participantID = filter_input(INPUT_POST, 'participantID', FILTER_SANITIZE_STRING);
$participantName = filter_input(INPUT_POST, 'participantName', FILTER_SANITIZE_STRING);
$participantAgency = filter_input(INPUT_POST, 'participantAgency', FILTER_SANITIZE_STRING);
$participantStatus = filter_input(INPUT_POST, 'participantStatus', FILTER_SANITIZE_STRING);
$participantORNumber = filter_input(INPUT_POST, 'participantORNumber', FILTER_SANITIZE_STRING);
$participantDatePaid = filter_input(INPUT_POST, 'participantDatePaid', FILTER_SANITIZE_STRING);
$participantRemarks = filter_input(INPUT_POST, 'participantRemarks', FILTER_SANITIZE_STRING);

if (!$participantID) {
  echo json_encode(['success' => false, 'message' => 'Participant ID is required']);
  exit;
}

try {
  // Start transaction
  $conn->begin_transaction();

  $updateStmt = $conn->prepare("
    UPDATE oldtraining_participants 
    SET 
      oldpaxName = ?, 
      oldpaxAgency = ?, 
      oldpaxStatus = ?, 
      oldpaxORNumber = ?, 
      oldpaxDatePaid = ?, 
      oldpaxRemarks = ? 
    WHERE oldpaxID = ?
  ");

  if (!$updateStmt) {
    throw new Exception("Failed to prepare statement: " . $conn->error);
  }

  $updateStmt->bind_param(
    "sssssss", 
    $participantName, 
    $participantAgency, 
    $participantStatus, 
    $participantORNumber, 
    $participantDatePaid, 
    $participantRemarks, 
    $participantID
  );

  if ($updateStmt->execute()) {
    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true]);
  } else {
    throw new Exception("Failed to execute statement: " . $updateStmt->error);
  }

  $updateStmt->close();
} catch (Exception $e) {
  // Rollback transaction on error
  $conn->rollback();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>