<?php

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];

  $checkEmailStmt = $conn->prepare("SELECT * FROM employee WHERE email = ?");
  $checkEmailStmt->bind_param("s", $email);

  if ($checkEmailStmt->execute()) {
    $checkEmailResult = $checkEmailStmt->get_result();
    $checkEmailRow = $checkEmailResult->fetch_assoc();
    if (!$checkEmailRow) {
      echo "ok";
    } else {
      echo "exist";
    }
  } else {
    echo "Error: " . $checkEmailStmt->error;
  }
}