<?php

$scannedAttendanceSheet = $_FILES['scannedAttendanceSheet'];
$trainingID = $_POST['trainingID'];

// upload the scanned attendance sheet to the server
$targetDir = "../assets/sources/scanned_attendance_sheets/";
// rename the file to avoid conflicts
$filename = $trainingID . "_attendance_sheet.pdf";

$targetFile = $targetDir . $filename;

// Check if the file is a valid PDF
if ($scannedAttendanceSheet['type'] === 'application/pdf') {
  // Move the uploaded file to the target directory
  if (move_uploaded_file($scannedAttendanceSheet['tmp_name'], $targetFile)) {
    echo "ok";
  } else {
    echo "There was an error uploading the file.";
  }
} else {
  echo "Invalid file type. Only PDF files are allowed.";
}