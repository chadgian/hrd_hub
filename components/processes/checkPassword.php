<?php

include "db_connection.php";

$userID = $_POST['userID'];
$oldPassword = $_POST['oldPassword'];

$checkPasswordStmt = $conn->prepare("SELECT * FROM user WHERE userID = ? AND password = ?");
$checkPasswordStmt->bind_param("is", $userID, $oldPassword);

if ($checkPasswordStmt->execute()) {
  $result = $checkPasswordStmt->get_result();

  if ($result->num_rows > 0) {
    echo "ok";
  } else {
    echo "wrong password";
  }
} else {
  echo $checkPasswordStmt->error;
}

