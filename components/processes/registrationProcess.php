<?php

include_once "db_connection.php";
date_default_timezone_set('Asia/Manila');
$currentDayTime = date("Y-m-d H:i:s");

// Disable autocommit
$conn->autocommit(false);

// Start a transaction
$conn->begin_transaction();
$registrationID = null;
$employeeID = null;
$trainingID = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  try {

    // Saving variables
    $prefix = trim($_POST['prefix']) . " ";
    $firstName = trim($_POST["firstName"]);
    $middleInitial = trim($_POST['middleInitial']);
    $middleInitial = $middleInitial === "" ? "" : trim($middleInitial) . ".";
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
    $agencyAddress = $_POST['agencyAddress'];
    $fo = $_POST['fo'];
    $foodRestriction = trim($_POST['foodRestrictions']);
    $userID = $_POST['userID'];

    $trainingID = $_POST['trainingID'];

    // $sql = "SELECT MAX(registrationID) AS highest_registrationID FROM registration_details";
    // $result = $conn->query($sql);

    // uploading the confirmation slip

    $slipFolder = "../../assets/conf_slips/$trainingID/";

    $agencyID = getAgencyID($agencyName, $sector, $fo, $agencyAddress);

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

        $registrationID = saveRegistration($userID);

        $slipFileName = "$registrationID.$imageFileType";
        $targetFile = "$slipFolder$slipFileName";

        if (move_uploaded_file($_FILES["confirmationSlip"]["tmp_name"], $targetFile)) {
          $status = "ok";

          if (addParticipant() && updateRegisteredPax() && updateTrainingActivities()) {
            $status = "ok";
            $conn->commit();
            echo $status;
          } else {
            $status += ", error_adding_participant";
            $conn->rollback();
            echo $status;
          }
        } else {
          $status += ", error_upload";
          $conn->rollback();
          echo $status;
        }
        $getEmployeeID->close();
        $conn->close();

      } else {

        $userAccount = explode("::", createUserAccount($prefix, $firstName, $lastName, $suffix, $middleInitial, $position, $agencyName, $email));

        $userID = $userAccount[0];
        $password = $userAccount[1];

        $regStmt = $conn->prepare("INSERT INTO employee (userID, prefix, firstName, lastName, middleInitial, suffix, nickname, age, sex, civilStatus, phoneNumber, email, altEmail, position, agency, foodRestriction) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $regStmt->bind_param("ssssssssssssssss", $userID, $prefix, $firstName, $lastName, $middleInitial, $suffix, $nickname, $age, $sex, $civilStatus, $phoneNumber, $email, $altEmail, $position, $agencyID, $foodRestriction);


        if ($regStmt->execute()) {

          $getEmployeeID = $conn->prepare("SELECT employeeID FROM employee WHERE userID = ?");
          $getEmployeeID->bind_param("s", $userID);

          if ($getEmployeeID->execute()) {
            $getEmployeeIDResult = $getEmployeeID->get_result();
            $employeeID = $getEmployeeIDResult->fetch_assoc()['employeeID'];
          } else {
            $conn->rollback();
            echo "Get employee ID error: {$getEmployeeID->error}";
          }

          $registrationID = saveRegistration($userID);

          $slipFileName = $registrationID . "." . $imageFileType;
          $targetFile = "$slipFolder$slipFileName";

          if (move_uploaded_file($_FILES["confirmationSlip"]["tmp_name"], $targetFile)) {
            $status = "ok::$password";

            if (addParticipant() && updateRegisteredPax() && updateTrainingActivities()) {
              $status = "ok::$password";
              $conn->commit();
              echo $status;
            } else {
              $status += ", error_adding_participant";
              $conn->rollback();
              echo $status;
            }
          } else {
            $status += ", error_upload";
            $conn->rollback();
            echo $status;
          }
        }
        $regStmt->close();
        $conn->close();
      }
    } else {
      // $conn->commit();
      $conn->rollback();
      echo $status;
    }
  } catch (\Throwable $th) {
    //throw $th;
    $conn->rollback();
    echo "NOT OKAY - " . $th;
  }
} else {
  $conn->rollback();
  echo "NOT OKAY - outside";
}

function saveRegistration($userID)
{
  global $conn, $employeeID, $trainingID;

  $regTrainingStmt = $conn->prepare("INSERT INTO registration_details (trainingID, employeeID, userID) VALUES (?, ?, ?)");
  $regTrainingStmt->bind_param("sss", $trainingID, $employeeID, $userID);

  if ($regTrainingStmt->execute()) {
    $registrationID = $conn->insert_id;
    return $registrationID;
  } else {
    $conn->rollback();
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
    $conn->rollback();
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

function getAgencyID($agencyName, $sector, $province, $agencyAddress)
{
  global $conn;

  $getEmployeeID = $conn->prepare("SELECT * FROM agency WHERE agencyName = ? AND sector = ? AND province = ?");
  $getEmployeeID->bind_param("sss", $agencyName, $sector, $province);

  if ($getEmployeeID->execute()) {
    $result = $getEmployeeID->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      if (trim($row['address']) == "") {
        $updateStmt = $conn->prepare("UPDATE agency SET address = ? WHERE agencyID = ?");
        $updateStmt->bind_param("ss", $agencyAddress, $row['agencyID']);
        $updateStmt->execute();
      }

      return $row['agencyID'];
    } else {
      $saveAgency = $conn->prepare("INSERT INTO agency (agencyName, sector, province, address) VALUES (?, ?, ?, ?)");
      $saveAgency->bind_param("ssss", $agencyName, $sector, $province, $agencyAddress);
      $saveAgency->execute();
      return $conn->insert_id;
    }
  } else {
    $conn->rollback();
  }
}

function addParticipant()
{
  global $registrationID, $trainingID, $employeeID, $conn;

  $getLastIDStmt = $conn->prepare("SELECT idNumber FROM training_participants WHERE trainingID = ? ORDER BY participantID DESC LIMIT 1");
  $getLastIDStmt->bind_param("s", $trainingID);
  $getLastIDStmt->execute();
  $getLastIDStmt->bind_result($lastID);
  $idNumber = ($getLastIDStmt->fetch()) ? $lastID + 1 : 1;
  $getLastIDStmt->close();

  $addParticipantStmt = $conn->prepare("INSERT INTO training_participants (employeeID, registrationID, trainingID, idNumber) VALUES (?, ?, ?, ?)");
  $addParticipantStmt->bind_param("ssss", $employeeID, $registrationID, $trainingID, $idNumber);
  if ($addParticipantStmt->execute()) {
    if (addAttendance()) {
      return true;
    } else {
      return false;
    }
  }
  $addParticipantStmt->close();
}

function updateRegisteredPax()
{
  global $conn, $trainingID;

  $countPaxStmt = $conn->prepare("SELECT * FROM attendance WHERE trainingID = ? AND day = 1");
  $countPaxStmt->bind_param("s", $trainingID);
  $countPaxStmt->execute();
  $result = $countPaxStmt->get_result();

  $totalPax = ($result->num_rows > 0) ? $result->num_rows : 0; // Ensure it always has a value

  $countPaxStmt->close();

  $setRemainingPaxStmt = $conn->prepare("UPDATE training_details SET registeredPax = ? WHERE trainingID = ?");
  $setRemainingPaxStmt->bind_param("ii", $totalPax, $trainingID);
  if ($setRemainingPaxStmt->execute()) {
    return true;
  }

  $setRemainingPaxStmt->close();
}

function updateTrainingActivities()
{
  global $conn, $trainingID, $registrationID;

  $updateActStmt = $conn->prepare("INSERT INTO training_activities (trainingID, trainingActivityType, relationID) VALUES (?, '0', ?)");
  $updateActStmt->bind_param("ss", $trainingID, $registrationID);
  if ($updateActStmt->execute()) {
    return true;
  }
  $updateActStmt->close();
}

function addAttendance()
{
  global $conn, $trainingID, $employeeID;

  $stmt = $conn->prepare("SELECT startDate, endDate FROM training_details WHERE trainingID = ?");
  $stmt->bind_param("s", $trainingID);
  $stmt->execute();
  $stmt->bind_result($startDate, $endDate);
  $stmt->fetch();
  $stmt->close();

  if ($startDate && $endDate) {
    // Convert to DateTime objects
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    // Calculate the number of days (including start and end date)
    $interval = $start->diff($end);
    $days = $interval->days + 1; // Add 1 to include both start and end date
  }

  $stmt2 = $conn->prepare("SELECT participantID FROM training_participants WHERE trainingID = ? AND employeeID = ?");
  $stmt2->bind_param("ss", $trainingID, $employeeID);
  $stmt2->execute();
  $stmt2->bind_result($participantID);
  $stmt2->fetch();
  $stmt2->close();

  $status = "ok";

  for ($i = 1; $i <= $days; $i++) {
    $addAttendanceStmt = $conn->prepare("INSERT INTO attendance (employeeID, trainingID, participantID, day) VALUES (?, ?, ?, ?)");
    $addAttendanceStmt->bind_param("ssss", $employeeID, $trainingID, $participantID, $i);
    if ($addAttendanceStmt->execute()) {
      continue;
    } else {
      $status = "error";
    }
    $addAttendanceStmt->close();
  }

  if ($status == "ok") {
    return true;
  } else {
    return false;
  }
}