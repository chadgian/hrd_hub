<?php

include '../../components/processes/db_connection.php';
include '../../components/classes/participantDetails.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$trainingID = $data['trainingID'];
$trainingDay = $data['day'];
$participants = $data['participants'];
$inorout = "log{$data['inorout']}";

echo $inorout;

$status = false;
foreach ($participants as $participant) {
  $idNumber = $participant['numID'];
  $name = $participant['name'];
  $timestamp = $participant['timestamp'];

  $participantDetails = new participantDetails('idNumber', $idNumber, $trainingID);

  if ($participantDetails->updateAttendance($trainingDay, $timestamp, $inorout, $trainingID)) {
    $status = true;
  } else {
    $status = false;
  }
}

echo $status;