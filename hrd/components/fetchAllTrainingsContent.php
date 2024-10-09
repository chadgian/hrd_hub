<?php

include '../../components/processes/db_connection.php';

$year = $_POST['year'];

switch ($year) {
  case 0:
    $getMonthStmt = $conn->prepare("SELECT * FROM training_details ORDER BY startDate DESC;");
    $getMonthStmt->execute();
    $getMonthResult = $getMonthStmt->get_result();
    while ($row = $getMonthResult->fetch_assoc()) {
      $rawStartDate = new DateTime($row['startDate']);
      $rawEndDate = new DateTime($row['endDate']);
      $trainingMonth = $rawStartDate->format('M');
      $trainingDay = $rawStartDate->format('d') . "-" . $rawEndDate->format('d');
      $trainingName = $row['trainingName'];
      $trainingID = $row['trainingID'];

      echo "
        <div class='training-item'>
          <div class='date-training'>
            <div class='date-content'>
              <div style='padding-left: 5px; padding-right: 5px;'>$trainingMonth</div>
              <div style='font-size: 10px;'>$trainingDay</></div>
            </div>
          </div>
          <div class='training-name'><span>$trainingName</span></div>
          <div class='view-training'>
            <a href='../hrd/index.php?p=7&t=$trainingID' class='view-training-btn'>View</a>
          </div>
        </div>
      ";
    }
    $getMonthStmt->close();
    break;
  default:
    $getMonthStmt = $conn->prepare("SELECT DISTINCT MONTH(startDate) AS month FROM training_details WHERE YEAR(startDate) = ? ORDER BY month DESC;");
    $getMonthStmt->bind_param("i", $year);
    $getMonthStmt->execute();
    $getMonthResult = $getMonthStmt->get_result();
    while ($row = $getMonthResult->fetch_assoc()) {
      echo "<div class='monthContainer'>";
      echo "<h5 class='monthTitle'>" . getMonthName($row['month']) . "</h5>";

      $getTrainingStmt = $conn->prepare("SELECT * FROM training_details WHERE MONTH(startDate) = ? AND YEAR(startDate) = ?");
      $getTrainingStmt->bind_param("ii", $row['month'], $year);
      $getTrainingStmt->execute();
      $getTrainingResult = $getTrainingStmt->get_result();
      while ($trainingRow = $getTrainingResult->fetch_assoc()) {
        echo "<div class='trainingContainer'>";
        echo "<h6>" . $trainingRow['trainingName'] . "</h6>";
        echo "<button class='allTrainingView-btn' onclick='{$trainingRow['trainingID']}'>View</button>";
        echo "</div>";
      }

      echo "</div>";

      $getTrainingStmt->close();
    }
    $getMonthStmt->close();
    break;

}

function getMonthName($monthNumber)
{
  // Add 1 to monthNumber since DateTime months are 1-based
  $date = DateTime::createFromFormat('!m', $monthNumber);
  return $date->format('F');
}

// Example usage:
// echo getMonthName(0); // Output: January
// echo getMonthName(9); // Output: October
// echo getMonthName(10); // Output: November
