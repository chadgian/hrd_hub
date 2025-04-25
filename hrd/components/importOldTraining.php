<?php
include "../../components/processes/db_connection.php";
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$conn->begin_transaction();
$conn->autocommit(false);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    // Check if the file is an Excel file
    $allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
    if (in_array($fileType, $allowedTypes)) {
      // Load the spreadsheet
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpName);
      $trainingData = $spreadsheet->getSheetByName("Details")->toArray(null, true, true, true);

      foreach ($trainingData as $row) {
        if (!empty($row['A']) && !empty($row['B'])) {
          $trainingInfo[$row['A']] = $row['B'];
          echo $row['A'] . " : " . $row['B'] . "<br>";
        }
      }

      $trainingName = $trainingInfo['Training Name'] ?? null;
      $startDate = (new DateTime($trainingInfo['Start Date']))->format("Y-m-d") ?? null;
      $endDate = (new DateTime($trainingInfo['End Date']))->format("Y-m-d") ?? null;


      if (empty($trainingName) || empty($startDate) || empty($endDate)) {
        echo "Training name, start date, or end date is missing.";
        exit;
      }
      // Check if the training already exists
      if (trainingExists($conn, $trainingName, $startDate, $endDate) == '0') {

        $trainingstmt = $conn->prepare("INSERT INTO oldtraining_details (oldTrainingName, oldStartDate, oldEndDate) VALUES (?, ?, ?)");

        $trainingstmt->bind_param("sss", $trainingName, $startDate, $endDate);
        if (!$trainingstmt->execute()) {
          echo "Error: " . $trainingstmt->error;
          $conn->rollback();
          exit;
        } else {
          $trainingID = $conn->insert_id;
        }
      } else {
        $trainingID = trainingExists($conn, $trainingName, $startDate, $endDate);
      }

      $participantData = $spreadsheet->getSheetByName("Participants")->toArray(null, true, true, true);
      // Prepare the SQL statement for participants
      $participantstmt = $conn->prepare("INSERT INTO oldtraining_participants (oldPaxNumber, oldTrainingID, oldpaxName, oldpaxAgency, oldpaxStatus, oldpaxORNumber, oldpaxDatePaid, oldpaxRemarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

      $participantData = $spreadsheet->getSheetByName("Participants")->toArray(null, true, true, true);

      $header = true;
      foreach ($participantData as $row) {
        //Skip header row
        if ($header) {
          $header = false;
          continue;
        }
        // Handle date formatting with try-catch to avoid exceptions
        try {
          $datePaid = !empty($row['F']) ? (new DateTime($row['F']))->format("Y-m-d") : null;
        } catch (Exception $e) {
          $datePaid = null; // Handle invalid date
        }
        // Bind parameters and execute the statement
        $participantstmt->bind_param("ssssssss", $row['A'], $trainingID, $row['B'], $row['C'], $row['D'], $row['E'], $datePaid, $row['G']);
        if (!$participantstmt->execute()) {
          echo "Error: " . $participantstmt->error;
          $conn->rollback();
          exit;
        }
      }

      $conn->commit();
      echo "File imported successfully!";
    } else {
      echo "Invalid file type. Please upload an Excel file.";
    }
  } else {
    echo "No file uploaded.";
  }
}

function trainingExists($conn, $trainingName, $startDate, $endDate)
{
  $stmt = $conn->prepare("SELECT * FROM oldtraining_details WHERE oldTrainingName = ? AND oldStartDate = ? AND oldEndDate = ?");
  $stmt->bind_param("sss", $trainingName, $startDate, $endDate);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $trainingID = $result->fetch_assoc()['oldTrainingID'];
    return $trainingID;
  } else {
    return '0';
  }
}