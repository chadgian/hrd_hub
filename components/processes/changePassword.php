<?php

include "db_connection.php";

$userID = $_POST['userID'];
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

if ((strlen($newPassword) > 7 && strlen($confirmPassword) > 7) && $newPassword === $confirmPassword) {
  $changePasswordStmt = $conn->prepare("UPDATE user SET password = ? WHERE password = ? AND userID = ?");

  $changePasswordStmt->bind_param("sss", $newPassword, $oldPassword, $userID);

  if ($changePasswordStmt->execute()) {
    echo "ok";
  } else {
    echo $changePasswordStmt->error;
  }
} else {
  echo "not ok";
}