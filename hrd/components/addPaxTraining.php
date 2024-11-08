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
          // Commit the transaction if everything is okay
          $conn->commit();
          echo "ok";
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