<?php

include "../../components/processes/db_connection.php";

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$trainingID = (string) $_POST['trainingID'];

$trainingDetailsStmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
$trainingDetailsStmt->bind_param("s", $trainingID);
$trainingDetailsStmt->execute();
$trainingDetailsResult = $trainingDetailsStmt->get_result();
$trainingDetails = $trainingDetailsResult->fetch_assoc();

$trainingName = $trainingDetails['trainingName'];
$startDate = new DateTime($trainingDetails['startDate']);
$endDate = new DateTime($trainingDetails['endDate']);
$trainingDays = $endDate->diff($startDate)->days + 1;

// Create an empty array to store the contents of each CSV file
$csvFiles = array();

// Loop through each day of the training period
for ($i = 0; $i < $trainingDays; $i++) {
  $currentDate = clone $startDate;
  $currentDate->modify("+$i day");
  $formattedDate = $currentDate->format("Y-m-d");

  $currentDay = (string) ($i + 1);

  // Prepare the SQL statement to fetch attendance data for the current date
  $attendanceStmt = $conn->prepare("SELECT * FROM attendance as a INNER JOIN employee as e ON a.employeeID = e.employeeID INNER JOIN training_participants as tp ON a.participantID = tp.participantID INNER JOIN agency as ag ON e.agency = ag.agencyID WHERE a.trainingID = ? AND a.day = ? ORDER BY tp.idNumber ASC");
  $attendanceStmt->bind_param("ss", $trainingID, $currentDay);
  $attendanceStmt->execute();
  $attendanceResult = $attendanceStmt->get_result();

  if ($attendanceResult->num_rows > 0) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'No.');
    $sheet->setCellValue('B1', 'Last Name');
    $sheet->setCellValue('C1', 'First Name');
    $sheet->setCellValue('D1', 'Middle Name');
    $sheet->setCellValue('E1', 'Agency');
    $sheet->setCellValue('F1', 'Login');
    $sheet->setCellValue('G1', 'Logout');
    $sheet->setCellValue('H1', 'Status');
    $sheet->setCellValue('I1', 'OR Number');
    $sheet->setCellValue('J1', 'Date Paid');
    $sheet->setCellValue('K1', 'Remarks');

    $rowCount = 2;

    while ($row = $attendanceResult->fetch_assoc()) {
      $loginTime = ($row['login'] == !NULL) ? date('H:i', strtotime($row['login'])) : "";
      $logoutTime = ($row['logout'] == !NULL) ? date('H:i', strtotime($row['logout'])) : "";
      $status = ($row['attendance'] == 1 && $row['payment'] == 1) ? "Completed" : "Incomplete";
      $orNumber = ($row['receiptNumber'] == !NULL) ? $row['receiptNumber'] : "";
      $datePaid = ($row['paymentDate'] == !NULL) ? date('Y-m-d', strtotime($row['paymentDate'])) : "";
      $remarks = ($row['remarks'] == !NULL) ? $row['remarks'] : "";
      $remarks = ($row['attendanceRemarks'] == !NULL) ? $remarks . " :: " . $row['attendanceRemarks'] : $remarks;

      $sheet->setCellValue('A' . $rowCount, $rowCount - 1);
      $sheet->setCellValue('B' . $rowCount, $row['lastName']);
      $sheet->setCellValue('C' . $rowCount, $row['firstName']);
      $sheet->setCellValue('D' . $rowCount, $row['middleInitial']);
      $sheet->setCellValue('E' . $rowCount, $row['agencyName']);
      $sheet->setCellValue('F' . $rowCount, $loginTime);
      $sheet->setCellValue('G' . $rowCount, $logoutTime);
      $sheet->setCellValue('H' . $rowCount, $status);
      $sheet->setCellValue('I' . $rowCount, $orNumber);
      $sheet->setCellValue('J' . $rowCount, $datePaid);
      $sheet->setCellValue('K' . $rowCount, $remarks);

      $rowCount++;
    }

    $writer = new Xlsx($spreadsheet);
    ob_start();
    $writer->save('php://output');
    $excelData = ob_get_clean();

    $excelFiles["{$trainingName}-Day-{$currentDay}.xlsx"] = $excelData;
  }
}

// Create a ZIP archive
$zipFileName = "[ATTENDANCE]{$trainingName}.zip";
$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
  // Add each CSV file to the ZIP archive
  foreach ($excelFiles as $filename => $content) {
    $zip->addFromString($filename, $content);
  }
  $zip->close();

  // Send the ZIP archive to the browser for download
  header('Content-Type: application/zip');
  header("Content-Disposition: attachment; filename=$zipFileName");
  readfile($zipFileName);

  // Delete the ZIP file after sending it
  unlink($zipFileName);

  echo "ok";
} else {
  echo "Failed to create ZIP file.";
}

// Close connection
$conn->close();