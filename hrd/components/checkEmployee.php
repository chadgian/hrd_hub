<?php

include '../../components/processes/db_connection.php';

$firstName = $_POST['firstName'];
$middleInitial = $_POST['middleInitial'];
$lastName = $_POST['lastName'];
$gender = $_POST['gender'];
$suffix = $_POST['suffix'];

$checkEmployeeStmt = $conn->prepare("SELECT * FROM employee WHERE firstname = ?, lastName = ?, middleInitial = ?, gender = ?, suffix = ?");
$checkEmployeeStmt->bind_param("sssss", $firstName, $lastName, $middleInitial, $gender, $suffix);

if ($checkEmployeeStmt->execute()) {
  $checkEmployeeResult = $checkEmployeeStmt->get_result();
  if ($checkEmployeeResult->num_rows > 0) {
    echo "1";
  } else {
    echo "0";
  }
} else {
  echo "Error: {$checkEmployeeStmt->error}";
}