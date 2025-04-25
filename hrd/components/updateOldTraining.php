<?php
include '../../components/processes/db_connection.php';

header('Content-Type: application/json');

$trainingID = $_POST['trainingID'] ?? null;
$trainingName = $_POST['trainingName'] ?? null;
$startDate = (new DateTime($_POST['startDate']))->format("Y-m-d") ?? null;
$endDate = (new DateTime($_POST['endDate']))->format("Y-m-d") ?? null;

if (!$trainingID) {
  echo json_encode(['success' => false, 'message' => 'Training ID is required']);
  exit;
}

$updateStmt = $conn->prepare("UPDATE oldtraining_details SET oldTrainingName = ?, oldStartDate = ?, oldEndDate = ? WHERE oldTrainingID = ?");
$updateStmt->bind_param("ssss", $trainingName, $startDate, $endDate, $trainingID);

if ($updateStmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => $updateStmt->error]);
}
?>