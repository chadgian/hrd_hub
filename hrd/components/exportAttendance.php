<?php
require '../../vendor/autoload.php';
include '../../components/processes/db_connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


$trainingID = $_POST['trainingID'];
// $trainingID = "22";

// Fill cells with training details
$trainingDetails = getTrainingDetails($trainingID);

$trainingStart = (new DateTime($trainingDetails['startDate']))->format("Y-m-d");
$trainingEnd = (new DateTime($trainingDetails['endDate']))->format("Y-m-d");

$numDaysRaw = (new DateTime($trainingEnd))->diff(new DateTime($trainingStart));

$numDays = ((int) $numDaysRaw->days) + 1;

if ($numDays == 1) {
  // Load the existing Excel file
  $inputFileName = '../assets/sources/attendance_template_1day.xlsx';
} elseif ($numDays == 2) {
  // Load the existing Excel file
  $inputFileName = '../assets/sources/attendance_template_2days.xlsx';
} elseif ($numDays == 3) {
  // Load the existing Excel file
  $inputFileName = '../assets/sources/attendance_template_3days.xlsx';
} else {
  die("Error: More than 3 number of days.");
}

$spreadsheet = IOFactory::load($inputFileName);

// Get the active sheet (or select a specific sheet if needed)
$sheet = $spreadsheet->getActiveSheet();

//fill training name
$sheet->setCellValue('A8', $trainingDetails['trainingName']);

//fill training date
if ($trainingDetails['startDate'] == $trainingDetails['endDate']) {
  $trainingDate = $trainingDetails['startDate'];
} else {
  $trainingDate = (new DateTime($trainingDetails['startDate']))->format("F j") . "-" . (new DateTime($trainingDetails['endDate']))->format("j, Y");
}
$sheet->setCellValue('A9', $trainingDate);

//fill training venue
$sheet->setCellValue('A10', $trainingDetails['venue']);

// fill participant details
$paxList = getPaxList($trainingID);

$counter = 13;

foreach ($paxList as $index => $pax) {
  $sheet->insertNewRowBefore(13, 1);
  $sheet->setCellValue('A13', $pax['idNumber']);
  $sheet->setCellValue('B13', getFullName($pax));
  $sheet->setCellValue('C13', $pax['agencyName']);
  $counter++;
}

$styleArray = [
  'fill' => [
    'fillType' => Fill::FILL_NONE,
    'startColor' => [
      'argb' => 'FFFFFF',
    ],
  ],
  'font' => [
    'bold' => false,
  ]
];

$styleForB = [
  'alignment' => [
    'horizontal' => Alignment::HORIZONTAL_LEFT,
  ]
];

$sheet->getStyle("B13:B$counter")->applyFromArray($styleForB);

$range = "A13:E$counter";
$sheet->getStyle($range)->applyFromArray($styleArray);

// $sheet->removeRow(13); // The second parameter (1) specifies the number of rows to delete

// Save the modified file
$writer = new Xlsx($spreadsheet);
$outputFileName = "../assets/sources/attendance_sheets/{$trainingID}_attendance_sheet.xlsx";
$writer->save($outputFileName);

echo "ok";

function getPaxList($trainingID)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM training_participants as tp INNER JOIN employee as e ON tp.employeeID = e.employeeID INNER JOIN agency as a ON e.agency = a.agencyID WHERE tp.trainingID = ? ORDER BY idNumber DESC");
  $stmt->bind_param("s", $trainingID);

  if ($stmt->execute()) {
    $result = $stmt->get_result();

    $paxList = [];

    while ($data = $result->fetch_assoc()) {
      $paxList[] = $data;
    }

    return $paxList;
  }
}

function getTrainingDetails($trainingID)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
  $stmt->bind_param("s", $trainingID);

  if ($stmt->execute()) {
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
}

function getFullName($names)
{

  $fullname = "";

  if ($names['prefix'] !== "") {
    $fullname .= $names['prefix'] . " ";
  }

  $fullname .= $names['firstName'] . " ";

  if ($names['middleInitial'] !== "") {
    $fullname .= trim($names['middleInitial'] . " ");
  }

  $fullname .= $names['lastName'];

  if ($names['suffix'] !== "") {
    if (trim($names['suffix']) == "Jr." || trim($names['suffix']) == "Sr.") {
      $fullname .= ", " . trim($names['suffix']);
    } else {
      $fullname .= " " . trim($names['suffix']);
    }
  }

  return trim($fullname);
}