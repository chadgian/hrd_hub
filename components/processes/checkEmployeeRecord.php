<?php

try {
  include '../../components/processes/db_connection.php';

  $prefix = trim($_POST['prefix']) . " ";
  $firstName = trim($_POST["firstName"]);
  $middleInitial = " " . trim(trimMiddleInitial($_POST['middleInitial']) . ".");
  $lastName = trim($_POST['lastName']);
  $suffix = $_POST['suffix'];
  $agency = $_POST['agency'];
  $phoneNumber = $_POST['phoneNumber'];
  $sex = $_POST['sex'];

  $checkEmployeeStmt = $conn->prepare("SELECT * FROM employee WHERE prefix = ? AND firstName = ? AND middleInitial = ? AND lastName = ? AND suffix = ? AND sex = ?");
  $checkEmployeeStmt->bind_param("ssssss", $prefix, $firstName, $middleInitial, $lastName, $suffix, $sex);

  if ($checkEmployeeStmt->execute()) {
    $checkEmployeeResult = $checkEmployeeStmt->get_result();
    if ($checkEmployeeResult->num_rows > 0) {
      $checkEmployeeData = $checkEmployeeResult->fetch_assoc();
      $oldAgency = $checkEmployeeData['agencyName'];

      if ($agency == $oldAgency) {
        //employee exists with the same agency
        echo "2::";
      } else {
        //employee exists but different agency
        echo "1:: $oldAgency";
      }
    } else {
      //employee don't exist
      echo "0::";
    }
  } else {
    //error
    echo "3:: {$checkEmployeeStmt->error}";
  }
} catch (\Throwable $th) {
  //error
  echo "3:: $th";
}
function trimMiddleInitial($string)
{
  $charactersToTrim = " \t\n\r\0\x0B";
  return trim($string, $charactersToTrim);
}