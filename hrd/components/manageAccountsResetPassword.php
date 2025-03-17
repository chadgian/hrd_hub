<?php
include '../../components/processes/db_connection.php';

$userID = $_POST['userID'];
$defaultPassword = "@LingkodBayani";

$conn->begin_transaction();

$resetPswrdStmt = $conn->prepare("UPDATE user SET password = ? WHERE userID = ?");
$resetPswrdStmt->bind_param("si", $defaultPassword, $userID);
if ($resetPswrdStmt->execute()) {
  $conn->commit();
  echo "ok";
} else {
  $conn->rollback();
  echo "Password reset failed!";
}
$resetPswrdStmt->close();
