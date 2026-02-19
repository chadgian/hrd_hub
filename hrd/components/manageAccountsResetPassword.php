<?php
include '../../components/functions/security.php';
requirePostMethod();
requireRole('admin');

include '../../components/processes/db_connection.php';

$userID = (int) ($_POST['userID'] ?? 0);
$defaultPassword = '@LingkodBayani';
$defaultPasswordHash = password_hash($defaultPassword, PASSWORD_DEFAULT);

$conn->begin_transaction();

$resetPswrdStmt = $conn->prepare('UPDATE user SET password = ? WHERE userID = ?');
$resetPswrdStmt->bind_param('si', $defaultPasswordHash, $userID);
if ($resetPswrdStmt->execute()) {
  $conn->commit();
  echo 'ok';
  exit();
}

$conn->rollback();
echo 'Password reset failed';
