<?php

include "db_connection.php";

$userID = (int) ($_POST['userID'] ?? 0);
$oldPassword = $_POST['oldPassword'] ?? '';

$checkPasswordStmt = $conn->prepare('SELECT password FROM user WHERE userID = ?');
$checkPasswordStmt->bind_param('i', $userID);

if ($checkPasswordStmt->execute()) {
  $result = $checkPasswordStmt->get_result();

  if ($result->num_rows > 0) {
    $storedPassword = $result->fetch_assoc()['password'];
    $isLegacyPlaintext = !preg_match('/^\$2y\$|^\$argon2/', $storedPassword);
    $isValid = password_verify($oldPassword, $storedPassword) || ($isLegacyPlaintext && hash_equals($storedPassword, $oldPassword));

    if ($isValid) {
      echo 'ok';
    } else {
      echo 'wrong password';
    }
  } else {
    echo 'wrong password';
  }
} else {
  echo $checkPasswordStmt->error;
}
