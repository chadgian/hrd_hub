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

  $agencyID = getAgencyID($agencyName, $sector, $fo);

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

      $userAccount = explode("::", createUserAccount($prefix, $firstName, $lastName, $suffix, $middleInitial, $position, $agencyName, $email));

      $userID = $userAccount[0];
      $password = $userAccount[1];

      $regStmt = $conn->prepare("INSERT INTO employee (userID, prefix, firstName, lastName, middleInitial, suffix, nickname, age, sex, civilStatus, phoneNumber, email, altEmail, position, agency, foodRestriction) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $regStmt->bind_param("ssssssssssssssss", $userID, $prefix, $firstName, $lastName, $middleInitial, $suffix, $nickname, $age, $sex, $civilStatus, $phoneNumber, $email, $altEmail, $position, $agencyID, $foodRestriction);


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
          $status = "ok::$password";
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
  global $conn;

  $regTrainingStmt = $conn->prepare("INSERT INTO registration_details (trainingID, employeeID, userID) VALUES (?, ?, ?)");
  $regTrainingStmt->bind_param("sss", $trainingID, $employeeID, $userID);

  if ($regTrainingStmt->execute()) {
    $registrationID = $conn->insert_id;
    return $registrationID;
  } else {
    echo "err: {$regTrainingStmt->error}";
  }
}

function createUserAccount($prefix, $firstName, $lastName, $suffix, $middleInitial, $position, $agency, $email)
{
  global $conn;
  global $agencyID;

  $password = generateRandomPassword();

  $createAccountStmt = $conn->prepare("INSERT INTO user (role, prefix, firstname, lastName, suffix, middleInitial, position, agency, username, password) VALUES ('general', ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $createAccountStmt->bind_param("sssssssss", $prefix, $firstName, $lastName, $suffix, $middleInitial, $position, $agencyID, $email, $password);

  if ($createAccountStmt->execute()) {
    $userID = $conn->insert_id;
    return "$userID::$password";
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

function getAgencyID($agencyName, $sector, $province)
{
  global $conn;

  $getEmployeeID = $conn->prepare("SELECT * FROM agency WHERE agencyName = ?, sector = ?, province = ?");
  $getEmployeeID->bind_param("sss", $agencyName, $sector, $province);
  $result = $getEmployeeID->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row['agencyID'];
  } else {
    $saveAgency = $conn->prepare("INSERT INTO agency (agencyName, sector, province) VALUES (?, ?, ?)");
    $saveAgency->bind_param("sss", $agencyName, $sector, $province);
    $saveAgency->execute();
    return $conn->insert_id;
  }
}