<?php
include '../../components/functions/security.php';
requirePostMethod();
requireRole('admin');

include '../../components/processes/db_connection.php';

function generateTemporaryPassword(int $length = 10): string
{
  $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*';
  $maxIndex = strlen($alphabet) - 1;
  $password = '';

  for ($i = 0; $i < $length; $i++) {
    $password .= $alphabet[random_int(0, $maxIndex)];
  }

  return $password;
}

$userID = (int) ($_POST['userID'] ?? 0);
if ($userID <= 0) {
  jsonResponse(400, ['ok' => false, 'message' => 'Invalid user ID']);
}

$newPassword = generateTemporaryPassword();
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

$conn->begin_transaction();

$resetPswrdStmt = $conn->prepare('UPDATE user SET password = ? WHERE userID = ?');
$resetPswrdStmt->bind_param('si', $newPasswordHash, $userID);
if ($resetPswrdStmt->execute()) {
  $conn->commit();
  jsonResponse(200, ['ok' => true, 'newPassword' => $newPassword]);
}

$conn->rollback();
jsonResponse(500, ['ok' => false, 'message' => 'Password reset failed']);
