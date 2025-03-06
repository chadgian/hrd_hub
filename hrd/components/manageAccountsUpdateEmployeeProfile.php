<?php

include '../../components/processes/db_connection.php';

$conn->begin_transaction();

$prefix = $_POST['prefix'];
$firstName = $_POST['firstName'];
$middleInitial = $_POST['middleInitial'];
$lastName = $_POST['lastName'];
$suffix = $_POST['suffix'];
$nickname = $_POST['nickname'];
$position = $_POST['position'];
$sex = $_POST['sex'];
$age = $_POST['age'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['personalEmail'];
$altEmail = $_POST['altEmail'];
$agencyID = $_POST['agencyID'];
$civilStatus = $_POST['civilStatus'];
$foodRestrictions = $_POST['foodRestrictions'];
$employeeID = $_POST['employeeID'];
$userID = $_POST['userID'];

$updateProfileStmt = $conn->prepare(
  "UPDATE employee SET 
                  prefix = ?, 
                  firstName = ?, 
                  middleInitial = ?, 
                  lastName = ?, 
                  suffix = ?, 
                  nickname = ?, 
                  position = ?, 
                  sex = ?,
                  age = ?,
                  phoneNumber = ?,
                  email = ?,
                  altEmail = ?,
                  agency = ?,
                  civilStatus = ?,
                  foodRestriction = ?
                  WHERE employeeID = ? AND userID = ?"
);

$updateProfileStmt->bind_param(
  "sssssssssssssssss",
  $prefix,
  $firstName,
  $middleInitial,
  $lastName,
  $suffix,
  $nickname,
  $position,
  $sex,
  $age,
  $phoneNumber,
  $email,
  $altEmail,
  $agencyID,
  $civilStatus,
  $foodRestrictions,
  $employeeID,
  $userID
);

if ($updateProfileStmt->execute()) {

  $updateUserStmt = $conn->prepare(
    "UPDATE user SET 
                  prefix = ?,
                  firstName = ?,
                  middleInitial = ?,
                  lastName = ?,
                  suffix = ?,
                  position = ?,
                  agency = ?
            WHERE userID = ?"
  );
  $updateUserStmt->bind_param(
    "ssssssss",
    $prefix,
    $firstName,
    $middleInitial,
    $lastName,
    $suffix,
    $position,
    $agencyID,
    $userID
  );

  if ($updateUserStmt->execute()) {
    $conn->commit();
    echo "ok";
  } else {
    $conn->rollback();
    echo "Error: " . $conn->error;
  }

} else {
  $conn->rollback();
  echo "Error: " . $conn->error;
}