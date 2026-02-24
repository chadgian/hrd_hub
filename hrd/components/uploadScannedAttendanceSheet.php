<?php
include '../../components/functions/security.php';
requirePostMethod();
requireRole('admin');

include '../../components/config/app.php';
$config = appConfig();

$scannedAttendanceSheet = $_FILES['scannedAttendanceSheet'] ?? null;
$trainingID = $_POST['trainingID'] ?? '';

if (!$scannedAttendanceSheet || !preg_match('/^[0-9]+$/', (string) $trainingID)) {
  jsonResponse(400, ['ok' => false, 'message' => 'Invalid upload request']);
}

if (($scannedAttendanceSheet['size'] ?? 0) > $config['upload']['max_bytes']) {
  jsonResponse(400, ['ok' => false, 'message' => 'File too large']);
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($scannedAttendanceSheet['tmp_name']);
if ($mimeType !== 'application/pdf') {
  jsonResponse(400, ['ok' => false, 'message' => 'Invalid file type. Only PDF files are allowed.']);
}

$targetDir = dirname(__DIR__) . '/storage/scanned_attendance_sheets/';
if (!is_dir($targetDir)) {
  mkdir($targetDir, 0750, true);
}

$filename = $trainingID . '_attendance_sheet_' . bin2hex(random_bytes(8)) . '.pdf';
$targetFile = $targetDir . $filename;

if (move_uploaded_file($scannedAttendanceSheet['tmp_name'], $targetFile)) {
  echo 'ok';
  exit();
}

echo 'There was an error uploading the file.';
