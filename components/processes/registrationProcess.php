<?php

include_once "db_connection.php";
date_default_timezone_set('Asia/Manila');
$currentDayTime = date("Y-m-d H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Saving variables
  $firstName = $_POST["firstName"];
  $middleInitial = $_POST['middleInitial'];
  $lastName = $_POST['lastName'];
  $gender = $_POST['gender'];
  $age = $_POST['age'];
  $civilStatus = $_POST['civilStatus'];
  $phoneNumber = $_POST['phoneNumber'];
  $personalEmail = $_POST['personalEmail'];
  $position = $_POST['position'];
  $sector = $_POST['sector'];
  $fo = $_POST['fo'];
  $agencyName = $_POST['agencyName'];
  $foodRestriction = $_POST['foodRestrictions'];

  $trainingID = $_POST['trainingID'];

  $sql = "SELECT MAX(registrationID) AS highest_registrationID FROM registration_details";
  $result = $conn->query($sql);

  // uploading the confirmation slip

  $slipFolder = "../../assets/conf_slips/$trainingID/";

  if (!file_exists($slipFolder)) {
    mkdir($slipFolder, 0777, true); // Create directory with full permissions (0777)
  }

  $status = "ok";

  $slipFile = $slipFolder . basename($_FILES["uploadFile"]["name"]);
  $imageFileType = strtolower(pathinfo($slipFile, PATHINFO_EXTENSION));

  //check if it is an actual image
  $check = getimagesize($_FILES['uploadFile']['tmp_name']);
  if ($check === false) {
    // $status += ", not_image";
  }

  // Check file size
  if ($_FILES['uploadFile']['size'] > 5000000) {
    // $status += ", file_too_large";
  }

  // check image format
  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    // $status += ", wrong_format";
  }

  if ($status === "ok") {
    $confirmationSlip = "confirmed";
    $regStmt = $conn->prepare("INSERT INTO registration_details (trainingID, fname, lname, minitial, age, gender, civilStatus, phoneNumber, personalEmail, position, agencyName, sector, fo, foodRestriction, confirmationSlip, timeDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $regStmt->bind_param("ssssssssssssssss", $trainingID, $firstName, $lastName, $middleInitial, $age, $gender, $civilStatus, $phoneNumber, $personalEmail, $position, $agencyName, $sector, $fo, $foodRestriction, $confirmationSlip, $currentDayTime);


    if ($regStmt->execute()) {
      $registrationID = $conn->insert_id;

      $slipFileName = $registrationID . "." . $imageFileType;
      $targetFile = $slipFolder . $slipFileName;

      if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $targetFile)) {
        $status = "ok";
        echo $status;
        header("Location: ../../index.php");
        exit;
      } else {
        $status += ", error_upload";
        echo $status;
      }
    }
  } else {
    echo $status;
  }

  // Save the data to the database




}



?>