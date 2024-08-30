<?php

include '../../components/processes/db_connection.php';

$newLogin = $_POST['newLogin'] == '' ? null : $_POST['newLogin'];
$newLogout = $_POST['newLogout'] == '' ? null : $_POST['newLogout'];
$attendanceID = $_POST['attendanceID'];

$updateAttendanceStmt = $conn->prepare("UPDATE attendance SET login = ?, logout = ? WHERE attendanceID = ?");
$updateAttendanceStmt->bind_param("sss", $newLogin, $newLogout, $attendanceID);

if ($updateAttendanceStmt->execute()) {
  echo "ok";
} else {
  echo $updateAttendanceStmt->error;
}