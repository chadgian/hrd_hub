<?php

include "../../components/processes/db_connection.php";

date_default_timezone_set('Asia/Manila');

$title = $_POST['noticeTitle'];
$body = $_POST['noticeBody'];
$author = $_POST['author'];
$timestamp = (new DateTime())->format("Y-m-d H:i:s");

$newNoticeStmt = $conn->prepare("INSERT INTO notices (noticeTitle, noticeBody, userID, timeDate) VALUES (?, ?, ?, ?)");
$newNoticeStmt->bind_param("ssss", $title, $body, $author, $timestamp);
if ($newNoticeStmt->execute()) {
  echo "ok";
} else {
  echo $newNoticeStmt->error;
}
$newNoticeStmt->close();

