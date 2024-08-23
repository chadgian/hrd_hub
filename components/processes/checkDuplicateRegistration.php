<?php

$trainingID = $_POST['trainingID'];
$employeeID = $_POST['employeeID'];

include "db_connection.php";

$checkDuplicateStmt = $conn->prepare("SELECT * FROM training_participants WHERE employeeID = ? AND trainingID = ? ");
$checkDuplicateStmt->bind_param("ii", $employeeID, $trainingID);

if ($checkDuplicateStmt->execute()) {
  $result = $checkDuplicateStmt->get_result();

  if ($result->num_rows > 0) {
    echo "1";
  } else {
    echo "0";
  }
} else {
  echo $checkDuplicateStmt->error;
}