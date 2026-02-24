<?php
include '../functions/security.php';
requirePostMethod();
requireAuthenticatedSession();

include 'db_connection.php';

$userID = (int) ($_POST['userID'] ?? 0);
$oldPassword = $_POST['oldPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

if ($userID !== (int) $_SESSION['userID']) {
  echo 'not ok';
  exit();
}

if ((strlen($newPassword) > 7 && strlen($confirmPassword) > 7) && $newPassword === $confirmPassword) {
  $getStmt = $conn->prepare('SELECT password FROM user WHERE userID = ?');
  $getStmt->bind_param('i', $userID);
  $getStmt->execute();
  $result = $getStmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user) {
    echo 'not ok';
    exit();
  }

  $storedPassword = $user['password'];
  $isLegacyPlaintext = !preg_match('/^\$2y\$|^\$argon2/', $storedPassword);
  $oldPasswordValid = password_verify($oldPassword, $storedPassword) || ($isLegacyPlaintext && hash_equals($storedPassword, $oldPassword));

  if (!$oldPasswordValid) {
    echo 'not ok';
    exit();
  }

  $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
  $changePasswordStmt = $conn->prepare('UPDATE user SET password = ? WHERE userID = ?');
  $changePasswordStmt->bind_param('si', $newPasswordHash, $userID);

  if ($changePasswordStmt->execute()) {
    echo 'ok';
    exit();
  }

  echo 'not ok';
}

echo 'not ok';
