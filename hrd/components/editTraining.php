<?php
include "../../components/processes/db_connection.php";
session_start();
$conn->begin_transaction();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $trainingID = $_POST['trainingID'];
  $trainingName = $_POST['trainingName'];
  $trainingVenue = $_POST['trainingVenue'];
  $trainingStart = new DateTime($_POST['trainingStart']);
  $trainingEnd = new DateTime($_POST['trainingEnd']);
  $trainingAdmin = $_POST['trainingAdmin'];
  $trainingFee = $_POST['trainingFee'];
  $trainingHours = $_POST['trainingHours'];
  $totalPax = $_POST['totalPax'];
  $trainingMode = $_POST['trainingMode'];
  $currArea = $_POST['currArea'];
  $docs = isset($_POST['docs']) ? '1' : '0';
  $trainingType = $_POST['trainingType'];
  $trainingDetails = $_POST['trainingDetails'];

  $startString = $trainingStart->format("Y-m-d");
  $endString = $trainingEnd->format("Y-m-d");

  $newTrainingDetails = [
    'trainingName' => $trainingName,
    'training_admin' => $trainingAdmin,
    'startDate' => $startString,
    'endDate' => $endString,
    'venue' => $trainingVenue,
    'mode' => $trainingMode,
    'fee' => $trainingFee,
    'trainingHours' => $trainingHours,
    'targetPax' => $totalPax,
    'details' => $trainingDetails,
    'currArea' => $currArea,
    'requiredDocs' => $docs,
    'trainingType' => $trainingType,
  ];

  $changedDetails = getChangedTrainingDetails($conn, $trainingID, $newTrainingDetails);


  // echo "$trainingName, $trainingVenue, $startString, $endString, $trainingAdmin, $trainingFee, $trainingHours, $totalPax, $trainingMode, $currArea, $docs, $trainingType, $trainingDetails";


  $addTrainingStmt = $conn->prepare("UPDATE training_details SET trainingName = ?, training_admin = ?, startDate = ?, endDate = ?, venue = ?, mode = ?, fee = ?, trainingHours = ?, targetPax = ?, details = ?, currArea = ?, requiredDocs = ?, trainingType = ?, lastUpdateBy = ? WHERE trainingID = ?");
  $addTrainingStmt->bind_param("sssssssssssssss", $trainingName, $trainingAdmin, $startString, $endString, $trainingVenue, $trainingMode, $trainingFee, $trainingHours, $totalPax, $trainingDetails, $currArea, $docs, $trainingType, $_SESSION['userID'], $trainingID);

  if ($addTrainingStmt->execute()) {
    if (addAdminAct($conn, $changedDetails, $trainingID)) {
      $conn->commit();
      header("Location: ../index.php?t=$trainingID");
    } else {
      $conn->rollback();
      echo "Error adding activity";
    }
  } else {
    $conn->rollback();
    echo (string) $addTrainingStmt->error;
  }
}

function getChangedTrainingDetails($conn, $trainingID, $newTrainingDetails)
{
  // Get the old training details from the database
  $oldTrainingStmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
  $oldTrainingStmt->bind_param("s", $trainingID);
  $oldTrainingStmt->execute();
  $oldTrainingResult = $oldTrainingStmt->get_result();
  $oldTrainingDetails = $oldTrainingResult->fetch_assoc();

  // Define an array to store the changed details
  $changedDetails = [];

  // Define the training details columns
  $trainingDetailsColumns = [
    'trainingName',
    'training_admin',
    'startDate',
    'endDate',
    'venue',
    'mode',
    'fee',
    'trainingHours',
    'targetPax',
    'details',
    'currArea',
    'requiredDocs',
    'trainingType',
  ];

  // Compare the old values with the new values
  foreach ($trainingDetailsColumns as $column) {
    if ($oldTrainingDetails[$column] != $newTrainingDetails[$column]) {
      $changedDetails[$column] = [
        'old' => $oldTrainingDetails[$column],
        'new' => $newTrainingDetails[$column],
      ];
    }
  }

  return $changedDetails;
}

function addAdminAct($conn, $changedDetails, $trainingID)
{
  try {

    if (isset($changedDetails['venue'])) {
      $addAdminActStmt = $conn->prepare("INSERT INTO activities (trainingID, userID, newData, oldData, activityType) VALUES (?, ?, ?, ?, '1')");
      $addAdminActStmt->bind_param("ssss", $trainingID, $_SESSION["userID"], $changedDetails['venue']['new'], $changedDetails['venue']['old']);
      $addAdminActStmt->execute();
    } elseif (isset($changedDetails['startDate']) || isset($changedDetails['endDate'])) {
      $addAdminActStmt = $conn->prepare("INSERT INTO activities (trainingID, userID, activityType) VALUES (?, ?, '5')");
      $addAdminActStmt->bind_param("ss", $trainingID, $_SESSION["userID"]);
      $addAdminActStmt->execute();
      adjustPaxAttendance($conn, $trainingID);

    } else {
      $addAdminActStmt = $conn->prepare("INSERT INTO activities (trainingID, userID, activityType) VALUES (?, ?, '0')");
      $addAdminActStmt->bind_param("ss", $trainingID, $_SESSION["userID"]);
      $addAdminActStmt->execute();
    }

    return true;

  } catch (\Throwable $th) {
    $conn->rollback();
    return "error: " . htmlspecialchars($th->getMessage());
  }
}

function adjustPaxAttendance($conn, $trainingID)
{
  $getTrainingStmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
  $getTrainingStmt->bind_param("s", $trainingID);
  $getTrainingStmt->execute();
  $trainingResult = $getTrainingStmt->get_result();
  $training = $trainingResult->fetch_assoc();
  $totalPax = $training['registeredPax'];

  $startDate = new DateTime($training['startDate']);
  $endDate = new DateTime($training['endDate']);

  $totalDays = $endDate->diff($startDate)->days + 1;

  $participants = getParticipantData($conn, $trainingID);

  foreach ($participants as $participant) {
    for ($day = 1; $day <= $totalDays; $day++) {
      $checkAttendanceStmt = $conn->prepare("SELECT * FROM attendance WHERE trainingID = ? AND employeeID = ? AND participantID = ? AND day = ?");
      $checkAttendanceStmt->bind_param("ssss", $trainingID, $participant['employeeID'], $participant['participantID'], $day);
      $checkAttendanceStmt->execute();
      $attendanceResult = $checkAttendanceStmt->get_result();
      if ($attendanceResult->num_rows == 0) {
        $insertAttendanceStmt = $conn->prepare("INSERT INTO attendance (employeeID, trainingID, participantID, day) VALUES (?, ?, ?, ?)");
        $insertAttendanceStmt->bind_param("ssss", $participant['employeeID'], $trainingID, $participant['participantID'], $day);
        $insertAttendanceStmt->execute();
      } else {
        continue;
      }
    }
  }

  $checkAttendanceDaysStmt = $conn->prepare("DELETE FROM attendance WHERE trainingID = ? AND day > ?");
  $checkAttendanceDaysStmt->bind_param("ss", $trainingID, $totalDays);
  $checkAttendanceDaysStmt->execute();
}

function getParticipantData($conn, $trainingID)
{
  $getParticipantStmt = $conn->prepare("SELECT * FROM attendance WHERE trainingID = ? and day = 1");
  $getParticipantStmt->bind_param("s", $trainingID);
  $getParticipantStmt->execute();
  $participantResult = $getParticipantStmt->get_result();
  $participants = [];
  while ($row = $participantResult->fetch_assoc()) {
    $participants[] = $row;
  }
  return $participants;
}