<?php
include "../../components/processes/db_connection.php";
session_start();

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

  // echo "$trainingName, $trainingVenue, $startString, $endString, $trainingAdmin, $trainingFee, $trainingHours, $totalPax, $trainingMode, $currArea, $docs, $trainingType, $trainingDetails";


  $addTrainingStmt = $conn->prepare("UPDATE training_details SET trainingName = ?, training_admin = ?, startDate = ?, endDate = ?, venue = ?, mode = ?, fee = ?, trainingHours = ?, targetPax = ?, details = ?, currArea = ?, requiredDocs = ?, trainingType = ?, lastUpdateBy = ? WHERE trainingID = ?");
  $addTrainingStmt->bind_param("sssssssssssssss", $trainingName, $trainingAdmin, $startString, $endString, $trainingVenue, $trainingMode, $trainingFee, $trainingHours, $totalPax, $trainingDetails, $currArea, $docs, $trainingType, $_SESSION['userID'], $trainingID);

  if ($addTrainingStmt->execute()) {
    header("Location: ../index.php?t=$trainingID");
  } else {
    echo (string) $addTrainingStmt->error;
  }
}