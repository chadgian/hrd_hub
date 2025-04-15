<?php

include "../../components/processes/db_connection.php";

// $addPaxType = $_GET['addPaxType'];
// $employeeID = $_GET['employeeID'];
// $trainingID = $_GET['trainingID'];

// Disable autocommit
$conn->autocommit(false);

// Start a transaction
$conn->begin_transaction();

// Retrieve POST data and validate it
$addPaxType = filter_input(INPUT_POST, 'addPaxType', FILTER_SANITIZE_STRING);
$employeeID = filter_input(INPUT_POST, 'employeeID', FILTER_SANITIZE_STRING);
$trainingID = filter_input(INPUT_POST, 'trainingID', FILTER_SANITIZE_STRING);
$adminID = filter_input(INPUT_POST, 'admin', FILTER_SANITIZE_STRING);

if ($addPaxType == "selectExistingEmployee") {
  addExistingEmployee($employeeID, $trainingID, $_FILES["confSlip"]);
} else {
  echo "Something else...";
}

function addExistingEmployee($employeeID, $trainingID, $file)
{
  global $conn, $adminID;

  try {
    // Begin transaction
    $conn->begin_transaction();

    // Fetch employee user ID
    $employeeUserID = getEmployeeUserID($employeeID);

    $manualAddValue = $adminID;

    // Prepare and bind statement
    $stmt = $conn->prepare("INSERT INTO registration_details (trainingID, employeeID, userID, manualAdd) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $trainingID, $employeeID, $employeeUserID, $manualAddValue);

    // Execute statement
    if ($stmt->execute()) {
      // Get the last inserted registration ID
      $registrationID = $conn->insert_id;

      // Check if confSlip is set and upload it
      if (isset($file["name"]) && !empty($file["name"])) {
        if (uploadConfSlip($trainingID, $registrationID, $file) == "ok") {

          // Prepare and bind statement for updating training activities
          if (updateTrainingActivities($trainingID, $registrationID) && addParticipant($trainingID, $employeeID, $registrationID) && updateRegisteredPax($trainingID)) {
            // Commit the transaction if update is successful
            $conn->commit();
            echo "ok";
          } else {
            // Rollback the transaction if update fails
            $conn->rollback();
            throw new Exception("Update training activities failed");
          }

        } else {
          // Rollback the transaction if upload fails
          $conn->rollback();
          throw new Exception("Upload failed");
        }
      } else {
        // Handle case where confSlip is not provided
        $conn->rollback();
        throw new Exception("Confirmation slip not provided");
      }
    } else {
      // Rollback if insert fails
      $conn->rollback();
      throw new Exception("Database insert failed");
    }

  } catch (Exception $e) {
    // Log or handle the exception as needed
    error_log("Error: " . $e->getMessage());
  } finally {
    // Ensure statement and connection are closed
    if ($stmt) {
      $stmt->close();
    }
    if ($conn) {
      $conn->close();
    }
  }
}

function getEmployeeUserID($employeeID)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM employee WHERE employeeID = ?");
  $stmt->bind_param("s", $employeeID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return $row['userID'];
}

function uploadConfSlip($trainingID, $registrationID, $file)
{
  $slipFolder = "../../assets/conf_slips/$trainingID/";
  if (!file_exists($slipFolder)) {
    mkdir($slipFolder, 0777, true); // Create directory with full permissions (0777)
  }

  $slipFile = $slipFolder . basename($file["name"]);
  $imageFileType = strtolower(pathinfo($slipFile, PATHINFO_EXTENSION));
  $fileName = "$registrationID.$imageFileType";

  $targetFile = "$slipFolder$fileName";
  if (move_uploaded_file($file["tmp_name"], $targetFile)) {
    return "ok";
  } else {
    return "error";
  }
}

function updateTrainingActivities($trainingID, $registrationID)
{
  global $conn;

  $updateActStmt = $conn->prepare("INSERT INTO training_activities (trainingID, trainingActivityType, relationID) VALUES (?, '0', ?)");
  $updateActStmt->bind_param("ss", $trainingID, $registrationID);
  if ($updateActStmt->execute()) {
    return true;
  }
  $updateActStmt->close();
}

function addParticipant($trainingID, $employeeID, $registrationID)
{
  global $conn;

  $getLastIDStmt = $conn->prepare("SELECT idNumber FROM training_participants WHERE trainingID = ? ORDER BY participantID DESC LIMIT 1");
  $getLastIDStmt->bind_param("s", $trainingID);
  $getLastIDStmt->execute();
  $getLastIDStmt->bind_result($lastID);
  $idNumber = ($getLastIDStmt->fetch()) ? $lastID + 1 : 1;
  $getLastIDStmt->close();

  $addParticipantStmt = $conn->prepare("INSERT INTO training_participants (employeeID, registrationID, trainingID, idNumber) VALUES (?, ?, ?, ?)");
  $addParticipantStmt->bind_param("ssss", $employeeID, $registrationID, $trainingID, $idNumber);
  if ($addParticipantStmt->execute()) {
    if (addAttendance($trainingID, $employeeID)) {
      return true;
    } else {
      return false;
    }
  }
  $addParticipantStmt->close();
}

function updateRegisteredPax($trainingID)
{
  global $conn;

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

function addAttendance($trainingID, $employeeID)
{
  global $conn;

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