<?php
include '../../components/processes/db_connection.php';

$registrationID = $_GET['registrationID'];
$read = $_GET['read'];

$readActivity = $conn->prepare("UPDATE training_activities SET activityRead = $read WHERE trainingActivityType = 0 AND relationID = $registrationID");
if ($readActivity->execute()) {
  echo "ok";
} else {
  echo $readActivity->error;
}
$readActivity->close();