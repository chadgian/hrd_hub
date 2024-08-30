<?php
$trainingID = $_POST['id'];

include '../../components/processes/db_connection.php';


$finalizeStmt = $conn->prepare("SELECT * FROM employee as e INNER JOIN training_participants as tp ON e.employeeID = tp.employeeID WHERE tp.trainingID = ? ORDER BY e.firstName ASC");
$finalizeStmt->bind_param("i", $trainingID);

if ($finalizeStmt->execute()) {
  $finalizeResult = $finalizeStmt->get_result();
  if ($finalizeResult->num_rows > 0) {
    $newID = 1;
    while ($finalizeData = $finalizeResult->fetch_assoc()) {
      $participantID = $finalizeData['participantID'];

      $changeIDStmt = $conn->prepare("UPDATE training_participants SET idNumber = ? WHERE participantID = ?");
      $changeIDStmt->bind_param("ii", $newID, $participantID);
      $changeIDStmt->execute();
      $newID++;
    }

    echo "ok";
  }
} else {
  echo $finalizeStmt->error;
}