<?php

include '../components/processes/db_connection.php';

$date = new DateTime();

$today = $date->format('Y-m-d');

?>

<h5 class='title'>RECENT TRAININGS</h5>
<div class='training-list'>
  <?php

  $recentTrainingStmt = $conn->prepare("SELECT * FROM training_details WHERE endDate < ? ORDER BY endDate DESC LIMIT 2");
  $recentTrainingStmt->bind_param("s", $today);

  if ($recentTrainingStmt->execute()) {

    $recentTrainingResult = $recentTrainingStmt->get_result();

    if ($recentTrainingResult->num_rows > 0) {
      while ($recentTrainingData = $recentTrainingResult->fetch_assoc()) {
        $trainingID = $recentTrainingData['trainingID'];
        $trainingName = $recentTrainingData['trainingName'];
        $start = new DateTime($recentTrainingData['startDate']);
        $end = new DateTime($recentTrainingData['endDate']);

        switch ($start) {
          case $end:
            $trainingMonth = $start->format("M");
            $trainingDay = $start->format("d");
            break;

          default:
            $trainingMonth = $start->format("M");
            $trainingDay = $start->format("d") . "-" . $end->format("d");
            break;
        }

        $recentRegistrationStmt = $conn->prepare("SELECT * FROM registration_details WHERE trainingID = ? AND accepted = 0");
        $recentRegistrationStmt->bind_param("s", $trainingID);

        if ($recentRegistrationStmt->execute()) {
          $recentRegistrationResult = $recentRegistrationStmt->get_result();
          $recentCounter = 0;
          if ($recentRegistrationResult->num_rows > 0) {
            while ($recentRegistrationData = $recentRegistrationResult->fetch_assoc()) {
              $recentCounter++;
            }
          }
        }

        $trainingBadge = $recentCounter == 0 ? "" : "<span class='training-badge'>$recentCounter</span>";

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
            <a href='../hrd/index.php?t=$trainingID' class='view-training-btn'>View</a>
            $trainingBadge
          </div>
        </div>
        ";
      }
    } else {
      echo "error";
    }
  } else {
    echo "Error: {$recentTrainingStmt->error}";
  }
  ?>
</div>
<h5 class='title'>UPCOMING TRAININGS</h5>
<div class='training-list'>

  <?php

  $upcomingTrainingStmt = $conn->prepare("SELECT * FROM training_details WHERE endDate > ? ORDER BY endDate ASC");
  $upcomingTrainingStmt->bind_param("s", $today);

  if ($upcomingTrainingStmt->execute()) {
    $upcomingTrainingResult = $upcomingTrainingStmt->get_result();
    if ($upcomingTrainingResult->num_rows > 0) {
      while ($upcomingTrainingData = $upcomingTrainingResult->fetch_assoc()) {
        $upcomingTrainingID = $upcomingTrainingData['trainingID'];
        $upcomingTrainingName = $upcomingTrainingData['trainingName'];
        $upcomingStart = new DateTime($upcomingTrainingData['startDate']);
        $upcomingEnd = new DateTime($upcomingTrainingData['endDate']);

        switch ($upcomingStart->format("M d")) {
          case $upcomingEnd->format("M d"):
            $upcomingTrainingMonth = $upcomingStart->format("M");
            $upcomingTrainingDay = $upcomingStart->format("d");
            break;
          default:
            $upcomingTrainingMonth = $upcomingEnd->format("M");
            $upcomingTrainingDay = $upcomingStart->format("d") . "-" . $upcomingEnd->format("d");
            break;
        }

        $upcomingRecent = $conn->prepare("SELECT * FROM registration_details WHERE trainingID = ? AND accepted = 0");
        $upcomingRecent->bind_param("i", $upcomingTrainingID);
        if ($upcomingRecent->execute()) {
          $upcomingRecentResult = $upcomingRecent->get_result();
          $upcomingRecentCounter = 0;
          if ($upcomingRecentResult->num_rows > 0) {
            while ($upcomingRecentData = $upcomingRecentResult->fetch_assoc()) {
              $upcomingRecentCounter++;
            }
          }
        } else {
          $upcomingRecent->error;
        }

        $upcomingTrainingBadge = $upcomingRecentCounter == 0 ? "" : "<span class='training-badge'>$upcomingRecentCounter</span>";


        echo "
          <div class='training-item'>
            <div class='date-training'>
              <div class='date-content'>
                <div style='padding-left: 5px; padding-right: 5px;'>$upcomingTrainingMonth</div>
                <div style='font-size: 10px;'>$upcomingTrainingDay</div>
              </div>
            </div>
            <div class='training-name' ><span>$upcomingTrainingName</span></div>
            <div class='view-training'>
              <a href='../hrd/index.php?t=$upcomingTrainingID' class='view-training-btn'>View</a>
              $upcomingTrainingBadge
            </div>
          </div>
        ";

      }
    }
  } else {
    $upcomingTrainingStmt->error;
  }

  ?>
</div>