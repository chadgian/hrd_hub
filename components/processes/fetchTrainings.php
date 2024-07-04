<?php
include_once ('db_connection.php');

$today = new DateTime();
$todayFormatted = $today->format('Y-m-d');

$trainingStmt = $conn->prepare("SELECT * FROM training_details WHERE endDate >= $todayFormatted");
$trainingStmt->execute();
$trainingResult = $trainingStmt->get_result();

$numOfTrainings = 0;

if ($trainingResult->num_rows > 0) {
  while ($trainingData = $trainingResult->fetch_assoc()) {
    $numOfTrainings++;
    $trainingName = $trainingData['trainingName'];

    $startDate = new DateTime($trainingData['startDate']);
    $endDate = new DateTime($trainingData['endDate']);

    if ($startDate->format('j') == $endDate->format('j')) {
      $trainingDate = $startDate->format('F j, Y');
    } else {
      $trainingDate = $startDate->format('F j') . "-" . $endDate->format('j, Y');
    }



    $venue = $trainingData['venue'];
    $mode = $trainingData['mode'];
    $regFee = $trainingData['fee'];
    $trainingHours = $trainingData['trainingHours'];
    $slots = $trainingData['targetPax'] - $trainingData['registeredPax'];
    $trainingID = $trainingData['trainingID'];

    echo "<tr>";
    echo "
      <td>$trainingName</td>
      <td>$trainingDate</td>
      <td class='register-body'><span class='register-btn' data-bs-toggle='modal' onclick='trainingDetails($trainingID)' data-bs-target='#staticBackdrop'>Register</span></td>
    ";
    echo "</tr>";
  }
}

$trainingStmt->close();
$conn->close();

?>