<?php

include "../../components/processes/db_connection.php";

$type = $_POST['type'];
$adminID = $_POST['adminID'];

if ($type == "getData") {
  getData();
} else if ($type == "saveData") {
  saveData();
}

function getData()
{
  global $conn, $adminID;

  $getAdminAccounts = $conn->prepare("SELECT * FROM user WHERE userID = ?");
  $getAdminAccounts->bind_param("i", $adminID);
  $getAdminAccounts->execute();
  $resultAdminAccounts = $getAdminAccounts->get_result();
  $adminAccounts = $resultAdminAccounts->fetch_assoc();

  echo json_encode($adminAccounts);
}

function saveData()
{
  global $conn, $adminID;
  $conn->begin_transaction();

  try {
    //code...
    $adminPrefix = $_POST['adminPrefix'];
    $adminFname = $_POST['adminFname'];
    $adminMname = $_POST['adminMname'];
    $adminLname = $_POST['adminLname'];
    $adminSuffix = $_POST['adminSuffix'];
    $adminRole = $_POST['adminRole'];
    $adminPosition = $_POST['adminPosition'];
    $adminInitials = $_POST['adminInitials'];
    $adminUsername = $_POST['adminUsername'];

    $updateAdmin = $conn->prepare("UPDATE user 
      SET role = ?,
          prefix = ?,
          firstName = ?,
          lastName = ?,
          suffix = ?,
          middleInitial = ?,
          position = ?,
          initials = ?,
          username = ?
      WHERE userID = ?");

    $updateAdmin->bind_param("sssssssssi", $adminRole, $adminPrefix, $adminFname, $adminLname, $adminSuffix, $adminMname, $adminPosition, $adminInitials, $adminUsername, $adminID);

    if ($updateAdmin->execute()) {
      $conn->commit();

      include '../../components/classes/userSession.php';
      $userSession = new UserSession();
      $userSession->saveSessionData();

      echo "ok";
    } else {
      $conn->rollback();
      echo "error";
    }
  } catch (\Throwable $th) {
    $conn->rollback();
    echo "error";
  }
}