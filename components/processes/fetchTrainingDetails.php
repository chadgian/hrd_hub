<?php
include_once 'db_connection.php';

if (!empty($_GET['id'])) {
  $trainingID = $_GET['id'];

  $trainingDetailsStmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = $trainingID");
  $trainingDetailsStmt->execute();
  $trainingDetailsResult = $trainingDetailsStmt->get_result();

  if ($trainingDetailsResult->num_rows > 0) {
    $trainingDetailsData = $trainingDetailsResult->fetch_assoc();
  }

  $trainingDetailsStmt->close();
  $conn->close();

  echo json_encode($trainingDetailsData);
}

?>