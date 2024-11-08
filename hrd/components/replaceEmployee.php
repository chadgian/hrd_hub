<?php

include "../../components/processes/db_connection.php";
date_default_timezone_set('Asia/Manila');

// Disable autocommit
$conn->autocommit(false);

// Start a transaction
$conn->begin_transaction();

// Get current timestamp
$now = (new DateTime())->format("Y-m-d H:i:s");

// Retrieve POST data and validate it
$newEmployee = filter_input(INPUT_POST, 'employeeID', FILTER_SANITIZE_STRING);
$oldEmployee = filter_input(INPUT_POST, 'oldEmployee', FILTER_SANITIZE_STRING);
$trainingID = filter_input(INPUT_POST, 'trainingID', FILTER_SANITIZE_STRING);
$adminID = filter_input(INPUT_POST, 'admin', FILTER_SANITIZE_STRING);

// Ensure that the required data is present
if (empty($newEmployee) || empty($oldEmployee)) {
  die("Employee IDs are required.");
}

// Get userID for the new employee
$userID = getUserID($newEmployee, $conn);

if ($userID === null) {
  die("User ID not found for the new employee.");
}

// Prepare the SQL statement
$registrationDetails = $conn->prepare("UPDATE registration_details SET employeeID = ?, userID = ?, timeDate = ? WHERE employeeID = ?");
if ($registrationDetails === false) {
  die("Failed to prepare SQL statement: {$conn->error}");
}

// Bind parameters
$registrationDetails->bind_param("ssss", $newEmployee, $userID, $now, $oldEmployee);

// Execute the statement and handle errors
if ($registrationDetails->execute()) {

  //replace row in training_participants table
  if (replaceTrainingParticipants($oldEmployee, $newEmployee, $trainingID) == "ok") {
    //replace row in attendance
    // $conn->commit();
    if (replaceAttendance($oldEmployee, $newEmployee, $trainingID) == "ok") {
      //insert new data in activities
      if (insertActivities($oldEmployee, $newEmployee, $trainingID, $adminID) == "ok") {
        // Commit the transaction
        $conn->commit();
      } else {
        // Rollback the transaction
        echo "error3";
        $conn->rollback();
      }
    } else {
      echo "error2";
      $conn->rollback();
    }
  } else {
    echo "error1: " . replaceTrainingParticipants($oldEmployee, $newEmployee, $trainingID);
    $conn->rollback();
  }
  $conn->close();
  echo "ok";
} else {
  // Rollback in case of error
  $conn->rollback();
  echo "Error updating registration details: {$registrationDetails->error}";
}

/**
 * Function to get the user ID based on employee ID
 *
 * @param string $employeeID
 * @param mysqli $conn
 * @return string|null
 */
function getUserID($employeeID, $conn)
{
  $getUserID = $conn->prepare("SELECT userID FROM employee WHERE employeeID = ?");
  if ($getUserID === false) {
    die("Failed to prepare SQL statement: {$conn->error}");
  }

  // Bind and execute the statement
  $getUserID->bind_param("s", $employeeID);
  $getUserID->execute();
  $result = $getUserID->get_result();

  // Fetch the user ID
  if ($row = $result->fetch_assoc()) {
    $getUserID->close();
    return $row['userID'];
  }

  return null; // Return null if no user ID found
}

function replaceTrainingParticipants($oldEmployee, $newEmployee, $trainingID)
{
  global $conn;

  $stmt = $conn->prepare("UPDATE training_participants SET employeeID = ? WHERE employeeID = ? AND trainingID = ?");
  $stmt->bind_param("iii", $newEmployee, $oldEmployee, $trainingID);
  if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
      $stmt->close();
      return "ok";
    } else {
      return "no affected rows";
    }
  }
}

function replaceAttendance($oldEmployee, $newEmployee, $trainingID)
{
  global $conn;

  $stmt = $conn->prepare("UPDATE attendance SET employeeID = ? WHERE employeeID = ? AND trainingID = ?");
  $stmt->bind_param("sss", $newEmployee, $oldEmployee, $trainingID);
  if ($stmt->execute()) {
    $stmt->close();
    return "ok";
  }
}

function insertActivities($oldEmployee, $newEmployee, $trainingID, $adminID)
{
  global $conn;
  $activityType = "3a";
  $stmt = $conn->prepare("INSERT INTO activities (trainingID, userID, activityType, newData, oldData) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $trainingID, $adminID, $activityType, $newEmployee, $oldEmployee);
  if ($stmt->execute()) {
    $stmt->close();
    return "ok";
  }
}