<?php
include '../../components/processes/db_connection.php';

$recentStmt = $conn->prepare("
  SELECT 
      a.activityID, 
      a.trainingID, 
      a.newData,
      td.trainingName,
      td.openReg,
      a.userID, 
      u.initials,
      a.activityType, 
      a.activityRead, 
      a.timestamp
  FROM 
      activities a
  JOIN 
      training_details td ON a.trainingID = td.trainingID
  JOIN 
      user u ON a.userID = u.userID
  ORDER BY a.activityID DESC;
  ");

if ($recentStmt->execute()) {
  $recentResult = $recentStmt->get_result();
  if ($recentResult->num_rows > 0) {
    while ($recentData = $recentResult->fetch_assoc()) {
      $trainingID = $recentData['trainingID'];
      $userInitial = $recentData['initials'];
      $trainingName = $recentData['trainingName'];

      switch ($recentData['activityType']) {
        case "0":
          $content = "<b>$userInitial</b> has <u>updated the details</u> of <b>$trainingName</b>";
          break;
        case "1":
          $content = "<b>$userInitial</b> has <u>updated the venue</u> of <b>$trainingName</b>";
          break;
        case "2":
          $content = "<b>$userInitial</b> has added a <u>new training</u>: <b>$trainingName</b>";
          break;
        case "3":
          $content = "<b>$userInitial</b> has <u>updated the participants</u> of <b>$trainingName</b>";
          break;
        case "3a":
          $content = "<b>$userInitial</b> has <u>replaced a participant</u> in the training <b>$trainingName</b>";
          break;
        case "3b":
          $content = "<b>$userInitial</b> has <u>deleted a participant</u> in the training <b>$trainingName</b>";
          break;
        case "4":
          if ($recentData['newData'] == 1) {
            $content = "<b>$userInitial</b> has <u>opened the registration</u> of <b>$trainingName</b>";
          } else {
            $content = "<b>$userInitial</b> has <u>closed the registration</u> of <b>$trainingName</b>";
          }
          break;
        case "5":
          $content = "<b>$userInitial</b> has <u>updated the date</u> of <b>$trainingName</b>";
          break;
        default:
          $content = "<i>Unknown content</i>";
          break;
      }

      date_default_timezone_set('Asia/Manila');
      $today = new DateTime();
      $recentDate = new DateTime($recentData['timestamp']);

      $recentAge = $today->diff($recentDate);

      if ($recentAge->y > 0) {
        $recentAgeContent = "{$recentAge->y} years ago.";
      } elseif ($recentAge->m > 0) {
        $recentAgeContent = "{$recentAge->m} months ago.";
      } elseif ($recentAge->d >= 7) {
        $weeks = floor($recentAge->d / 7);
        $recentAgeContent = "$weeks weeks ago.";
      } elseif ($recentAge->d > 0) {
        $recentAgeContent = "{$recentAge->d} days ago.";
      } elseif ($recentAge->h > 0) {
        $recentAgeContent = "{$recentAge->h} hours ago.";
      } elseif ($recentAge->i > 0) {
        $recentAgeContent = "{$recentAge->i} minutes ago.";
      } else {
        $recentAgeContent = "{$recentAge->s} seconds ago.";
      }


      echo "
        <a class='recent-act' href='index.php?t=$trainingID'>
          <div class='recent-img'><img src='assets/images/default-profile.png' alt=''></div>
          <div class='recent-body'>
            <div class='recent-act-content'>
              $content
            </div>
            <div class='recent-age'>$recentAgeContent</div>
          </div>
        </a>
      ";
    }
  } else {
    echo "<i class='text-align: center;>No recent activities</i>";
  }
} else {
  echo "{$recentStmt->error}";
}