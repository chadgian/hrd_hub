<?php

include "../../components/processes/db_connection.php";

$registrationID = $_POST['registrationID'];
// $id = explode("-", getEmployeeTrainingID($registrationID));
// $employeeID = $id[0];
// $trainignID = $id[1];

[$employeeID, $trainingID] = getEmployeeTrainingID($registrationID);

$conn->autocommit(false);

// Start a transaction
$conn->begin_transaction();

if (deleteParticipant($registrationID) == "ok") {

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

  return [$employeeID, $trainingID];
}

function deleteParticipant($registrationID)
{
  // Use global connection variable
  global $conn;

  // Prepare the SQL statement
  $stmt = $conn->prepare("DELETE FROM participants WHERE registration_id = ?");

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
      return "no";
    }
  } else {
    // Close the statement
    $stmt->close();
    return "error: " . htmlspecialchars($stmt->error);
  }

}

function deleteActivity()
{
  global $conn;

  
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