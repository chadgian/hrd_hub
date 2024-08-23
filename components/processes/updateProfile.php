<?php

include "db_connection.php";

$prefix = $_POST['prefix'];
$firstName = $_POST['firstName'];
$middleInitial = $_POST['middleInitial'];
$lastName = $_POST['lastName'];
$suffix = $_POST['suffix'];
$nickname = $_POST['nickname'];
$age = $_POST['age'];
$sex = $_POST['sex'];
$civilStatus = $_POST['civilStatus'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['email'];
$altEmail = $_POST['altEmail'];
$sector = $_POST['sector'];
$agencyName = $_POST['agencyName'];
$position = $_POST['position'];
$fo = $_POST['fo'];
$fopdRestriction = $_POST['foodRestriction'];
$userID = $_POST['userID'];

$updateProfileStmt = $conn->prepare("
  UPDATE employee 
  SET prefix = ?,
      firstName = ?,
      middleInitial = ?,
      lastName = ?,
      suffix = ?,
      nickname = ?,
      age = ?,
      sex = ?,
      civilStatus = ?,
      phoneNumber = ?,
      email = ?,
      altEmail = ?,
      sector = ?,
      agencyName = ?,
      position = ?,
      fo = ?,
      foodRestriction = ?
  WHERE userID = ?;
");

$updateProfileStmt->bind_param(
  "ssssssssssssssssss",
  $prefix,
  $firstName,
  $middleInitial,
  $lastName,
  $suffix,
  $nickname,
  $age,
  $sex,
  $civilStatus,
  $phoneNumber,
  $email,
  $altEmail,
  $sector,
  $agencyName,
  $position,
  $fo,
  $fopdRestriction,
  $userID
);

if ($updateProfileStmt->execute()) {

  $updateUserStmt = $conn->prepare("UPDATE user SET prefix = ?, firstName = ?, lastName = ?, suffix = ?, middleInitial = ?, position = ?, agency = ? WHERE userID = ?;");
  $updateUserStmt->bind_param("ssssssss", $prefix, $firstName, $lastName, $suffix, $middleInitial, $position, $agencyName, $userID);

  if ($updateUserStmt->execute()) {

    session_start();

    $_SESSION['prefix'] = $prefix;
    $_SESSION['firstName'] = $firstName;
    $_SESSION['middleInitial'] = $middleInitial;
    $_SESSION['lastName'] = $lastName;
    $_SESSION['suffix'] = $suffix;
    $_SESSION['agency'] = $agencyName;
    $_SESSION['position'] = $position;

    echo "ok";
  }
} else {
  echo $updateProfileStmt->error;
}