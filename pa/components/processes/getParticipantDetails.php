<?php
include '../../../components/processes/db_connection.php';

$participantID = $_GET['participantID'];

$query = "SELECT * FROM training_participants as tp 
          INNER JOIN employee as e
            ON tp.employeeID = e.employeeID
          INNER JOIN agency as a
            ON e.agency = a.agencyID
          INNER JOIN registration_details as rd
            ON tp.registrationID = rd.registrationID
          WHERE tp.participantID = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $participantID);
$stmt->execute();
$result = $stmt->get_result();
$allData = [];

while ($participant = $result->fetch_assoc()) {
  $allData = $participant;

  $file = searchFile("../../../assets/conf_slips/{$participant['trainingID']}", $participant['registrationID'], $participant['trainingID']);
  $allData['file'] = $file;
}


header('Content-Type: application/json');
echo json_encode($allData);

function searchFile($directory, $filename, $trainingID)
{
  // Ensure the directory path ends with a slash
  if (substr($directory, -1) !== DIRECTORY_SEPARATOR) {
    $directory .= DIRECTORY_SEPARATOR;
  }

  // Get all files in the directory
  $files = scandir($directory);

  // Iterate through files to find the matching filename
  foreach ($files as $file) {
    $currentFile = pathinfo($file, PATHINFO_FILENAME);
    if ($currentFile == $filename) {
      // Get the file extension
      $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
      return "../assets/conf_slips/$trainingID/$filename.$fileExtension";
    }
  }

  // If the file is not found
  return null;
}