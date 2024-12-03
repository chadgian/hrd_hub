<?php

include '../../components/processes/db_connection.php';

$noticeID = $_POST['noticeID'];

$stmt = $conn->prepare("DELETE FROM notices WHERE noticeID = ?");
$stmt->bind_param("i", $noticeID);
if ($stmt->execute()) {
  echo 'ok';
}