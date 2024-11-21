<?php

include "../../components/processes/db_connection.php";

$registrationID = $_POST['registrationID'];
$adminID = $_POST['admin'];
// $id = explode("-", getEmployeeTrainingID($registrationID));
// $employeeID = $id[0];
// $trainignID = $id[1];

[$employeeID, $trainingID] = getEmployeeTrainingID($registrationID);

$conn->autocommit(false);

// Start a transaction
$conn->begin_transaction();

$deleteParticipantResult = deleteParticipant($registrationID);
if ($deleteParticipantResult == "ok") {
  $deleteActivityResult = deleteActivity($registrationID);

  if ($deleteActivityResult == "ok") {
    $deleteAttendanceResult = deleteAttendance($employeeID, $trainingID);

    if ($deleteAttendanceResult == "ok") {
      $addActivity = addActivity($employeeID, $trainingID, $adminID);

      if ($addActivity == "ok") {
        $conn->commit();
        echo "ok";
      } else {
        echo "Error adding activity: $addActivity";
      }
    } else {
      $conn->rollback();
      echo "Error deleting attendance: $deleteAttendanceResult";
    }
  } else {
    $conn->rollback();
    echo "Error deleting activity: $deleteActivityResult";
  }
} else {
  $conn->rollback();
  echo "Error deleting participant: $deleteParticipantResult";
}

function getEmployeeTrainingID($registrationID)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM registration_details WHERE registrationID = ?");
  $stmt->bind_param("s", $registrationID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $employeeID = $row['employeeID'];
  $trainingID = $row['trainingID'];

  // Close the statement
  $stmt->close();
  return [$employeeID, $trainingID];
}

function deleteParticipant($registrationID)
{
  // Use global connection variable
  global $conn;

  // Prepare the SQL statement
  $stmt = $conn->prepare("DELETE FROM training_participants WHERE registrationID = ?");

  // Check if the statement was prepared successfully
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  // Bind the parameter to the prepared statement
  $stmt->bind_param("i", $registrationID);

  // Execute the statement
  if ($stmt->execute()) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
      // Close the statement
      $stmt->close();
      return "ok";
    } else {
      // Close the statement
      $stmt->close();
      return "no rows";
    }
  } else {
    // Close the statement
    $stmt->close();
    return "error: " . htmlspecialchars($stmt->error);
  }

}

function deleteActivity($registrationID)
{
  global $conn;

  $activityType = "0";

  $stmt = $conn->prepare("DELETE FROM training_activities WHERE trainingActivityType = ? AND relationID = ?");
  $stmt->bind_param("ss", $activityType, $registrationID);
  if ($stmt->execute()) {
    // Close the statement
    $stmt->close();
    return "ok";
  } else {
    // Close the statement
    $stmt->close();
    return "error: " . htmlspecialchars($stmt->error);
  }
}

function deleteAttendance($employeeID, $trainignID)
{
  global $conn;

  $stmt = $conn->prepare("DELETE FROM attendance WHERE employeeID = ? AND trainingID = ?");
  $stmt->bind_param("ss", $employeeID, $trainingID);

  if ($stmt->execute()) {
    return "ok";
  } else {
    return "error: " . htmlspecialchars($stmt->error);
  }
}

function addActivity($employeeID, $trainingID, $adminID)
{
  global $conn;
  $activityType = "3b";

  $stmt = $conn->prepare("INSERT INTO activities (trainingID, userID, oldData, activityType) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $trainingID, $adminID, $employeeID, $activityType);

  if ($stmt->execute()) {
    return "ok";
  } else {
    return "error: " . htmlspecialchars($stmt->error);
  }
}

// DELETE PARTICIPANTS

// x = old registrationID
// y = employeeID
// t = trainingID

// registration_details
//  - Don't delete for history keeping

// training_participants
//  - delete WHERE registrationID == x

// training_activities
//  - delete WHERE activityType == 0
//  - delete WHERE relationID == x (registrationID)

// attendance
//  - delete WHERE employeeID == y
//  - delete WHERE trainingID == t