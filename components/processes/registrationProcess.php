<?php

include_once "db_connection.php";
date_default_timezone_set('Asia/Manila');
$currentDayTime = date("Y-m-d H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Saving variables
  $prefix = trim($_POST['prefix']) . " ";
  $firstName = trim($_POST["firstName"]);
  $middleInitial = " " . trim(trimMiddleInitial($_POST['middleInitial']) . ".");
  $lastName = trim($_POST['lastName']);
  $suffix = $_POST['suffix'];
  $nickname = trim($_POST['nickname']);
  $sex = $_POST['sex'];
  $age = $_POST['age'];
  $civilStatus = $_POST['civilStatus'];
  $phoneNumber = $_POST['phoneNumber'];
  $email = $_POST['email'];
  $altEmail = $_POST['altEmail'];
  $position = trim($_POST['position']);
  $sector = $_POST['sector'];
  $agencyName = $_POST['agencyName'];
  $fo = $_POST['fo'];
  $foodRestriction = trim($_POST['foodRestrictions']);
  $userID = $_POST['userID'];

  $trainingID = $_POST['trainingID'];

  // $sql = "SELECT MAX(registrationID) AS highest_registrationID FROM registration_details";
  // $result = $conn->query($sql);

  // uploading the confirmation slip

  $slipFolder = "../../assets/conf_slips/$trainingID/";

  if (!file_exists($slipFolder)) {
    mkdir($slipFolder, 0777, true); // Create directory with full permissions (0777)
  }

  $status = "ok";

  $slipFile = $slipFolder . basename($_FILES["confirmationSlip"]["name"]);
  $imageFileType = strtolower(pathinfo($slipFile, PATHINFO_EXTENSION));

  //check if it is an actual image
  $check = getimagesize($_FILES['confirmationSlip']['tmp_name']);
  if ($check === false) {
    // $status += ", not_image";
  }

  // Check file size
  if ($_FILES['confirmationSlip']['size'] > 5000000) {
    // $status += ", file_too_large";
  }

  // check image format
  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    // $status += ", wrong_format";
  }



  if ($status === "ok") {

    if ($userID > 0) {
      $getEmployeeID = $conn->prepare("SELECT employeeID FROM employee WHERE userID = ?");
      $getEmployeeID->bind_param("s", $userID);

      if ($getEmployeeID->execute()) {
        $getEmployeeIDResult = $getEmployeeID->get_result();
        $employeeID = $getEmployeeIDResult->fetch_assoc()['employeeID'];
      } else {
        echo "Get employee ID error: {$getEmployeeID->error}";
      }

      $registrationID = saveRegistration($trainingID, $employeeID, $userID);

      $slipFileName = "$registrationID.$imageFileType";
      $targetFile = "$slipFolder$slipFileName";

      if (move_uploaded_file($_FILES["confirmationSlip"]["tmp_name"], $targetFile)) {
        $status = "ok";
        echo $status;
      } else {
        $status += ", error_upload";
        echo $status;
      }
      $getEmployeeID->close();
      $conn->close();

    } else {

      $userID = createUserAccount($firstName, $lastName, $suffix, $middleInitial, $position, $agencyName, $email);

      $regStmt = $conn->prepare("INSERT INTO employee (userID, prefix, firstName, lastName, middleInitial, suffix, nickname, age, sex, civilStatus, phoneNumber, email, altEmail, position, agencyName, sector, fo, foodRestriction) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $regStmt->bind_param("ssssssssssssssssss", $userID, $prefix, $firstName, $lastName, $middleInitial, $suffix, $nickname, $age, $sex, $civilStatus, $phoneNumber, $email, $altEmail, $position, $agencyName, $sector, $fo, $foodRestriction);


      if ($regStmt->execute()) {

        $getEmployeeID = $conn->prepare("SELECT employeeID FROM employee WHERE userID = ?");
        $getEmployeeID->bind_param("s", $userID);

        if ($getEmployeeID->execute()) {
          $getEmployeeIDResult = $getEmployeeID->get_result();
          $employeeID = $getEmployeeIDResult->fetch_assoc()['employeeID'];
        } else {
          echo "Get employee ID error: {$getEmployeeID->error}";
        }

        $registrationID = saveRegistration($trainingID, $employeeID, $userID);

        $slipFileName = $registrationID . "." . $imageFileType;
        $targetFile = "$slipFolder$slipFileName";

        if (move_uploaded_file($_FILES["confirmationSlip"]["tmp_name"], $targetFile)) {
          $status = "ok";
          echo $status;
        } else {
          $status += ", error_upload";
          echo $status;
        }
      }
      $regStmt->close();
      $conn->close();
    }
  } else {
    echo $status;
  }
} else {
  echo "NOT OKAY";
}

function saveRegistration($trainingID, $employeeID, $userID)
{
  include "db_connection.php";
  $regTrainingStmt = $conn->prepare("INSERT INTO registration_details (trainingID, employeeID, userID) VALUES (?, ?, ?)");
  $regTrainingStmt->bind_param("sss", $trainingID, $employeeID, $userID);

  if ($regTrainingStmt->execute()) {
    $registrationID = $conn->insert_id;
    return $registrationID;
  } else {
    echo "err: {$regTrainingStmt->error}";
  }
}

function createUserAccount($firstName, $lastName, $suffix, $middleInitial, $position, $agency, $email)
{
  include "db_connection.php";

  $password = generateRandomPassword();

  $createAccountStmt = $conn->prepare("INSERT INTO user (role, firstname, lastName, suffix, middleInitial, position, agency, username, password) VALUES ('general', ?, ?, ?, ?, ?, ?, ?, ?)");
  $createAccountStmt->bind_param("ssssssss", $firstName, $lastName, $suffix, $middleInitial, $position, $agency, $email, $password);

  if ($createAccountStmt->execute()) {
    $userID = $conn->insert_id;
    return $userID;
  } else {
    echo $createAccountStmt->error;
  }
}

function trimMiddleInitial($string)
{
  $charactersToTrim = " \t\n\r\0\x0B";
  return trim($string, $charactersToTrim);
}

function generateRandomPassword()
{
  $length = 8;
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomPassword = '';

  for ($i = 0; $i < $length; $i++) {
    $randomPassword .= $characters[rand(0, $charactersLength - 1)];
  }

  return $randomPassword;
}