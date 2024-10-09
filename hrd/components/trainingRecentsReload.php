<?php
include '../../components/processes/db_connection.php';
if (isset($_GET['trainingID'])) {
  $id = $_GET['trainingID'];

  $trainingRecentQuery = "SELECT 
    trainingActID AS id, 
    trainingID, 
    trainingActivityType, 
    NULL AS activityType,
    relationID, 
    timestamp, 
    activityRead,
    NULL AS activityID,
    NULL AS userID,
    NULL AS newData,
    NULL AS oldData
  FROM 
    training_activities
  WHERE
    trainingID = $id
  UNION ALL
  SELECT 
    NULL AS id,
    trainingID,
    NULL AS trainingActivityType, 
    activityType, 
    NULL AS relationID,
    timestamp, 
    activityRead,
    activityID, 
    userID, 
    newData, 
    oldData
  FROM 
    activities
  WHERE
    trainingID = $id
  ORDER BY 
    timestamp DESC;";

  $trainingRecentStmt = $conn->prepare($trainingRecentQuery);
  if ($trainingRecentStmt->execute()) {
    $trainingRecentResult = $trainingRecentStmt->get_result();

    if ($trainingRecentResult->num_rows > 0) {
      while ($trainingRecentData = $trainingRecentResult->fetch_assoc()) {

        if ($trainingRecentData['id'] == NULL) {
          // admin activity

          $trainingRecentUserStmt = $conn->prepare("SELECT * FROM user WHERE userID = ?");
          $trainingRecentUserStmt->bind_param("i", $trainingRecentData['userID']);
          if ($trainingRecentUserStmt->execute()) {
            $trainingRecentUserResult = $trainingRecentUserStmt->get_result();

            if ($trainingRecentUserResult->num_rows > 0) {
              $trainingRecentUserData = $trainingRecentUserResult->fetch_assoc();
              $trainingRecentUserInitials = $trainingRecentUserData['initials'];
            } else {
              $trainingRecentUserInitials = "Unknown User";
            }
          }

          switch ($trainingRecentData['activityType']) {
            case '0':
              $adminActivityContent = "<b>$trainingRecentUserInitials </b> has <u>updated the details</u>.";
              break;

            case '1':
              $adminActivityContent = "<b>$trainingRecentUserInitials</b> has <u>updated the venue</u>.";
              break;

            case '2':
              $adminActivityContent = "<b>$trainingRecentUserInitials</b> has <u>created</u> this training.";
              break;

            case '3':
              $adminActivityContent = "<b>$trainingRecentUserInitials</b> has <u>updated the participants</u>.";
              break;

            case '4':
              $regStatus = $trainingRecentData['newData'] == "0" ? "closed" : "opened";
              $adminActivityContent = "<b>$trainingRecentUserInitials</b> has <u>$regStatus the registration</u>.";
              break;

            case '5':
              $adminActivityContent = "<b>$trainingRecentUserInitials</b> has <u>updated the training date</u>.";
              break;

            default:
              $adminActivityContent = "<i>Unknown activity type.</i>";
              break;
          }

          date_default_timezone_set('Asia/Manila');
          $today = new DateTime();
          $recentDate = new DateTime($trainingRecentData['timestamp']);

          $recentAge = $today->diff($recentDate);

          if ($recentAge->y > 0) {

            $trainingRecentAgeContent = "{$recentAge->y} years ago.";
          } elseif ($recentAge->m > 0) {
            $trainingRecentAgeContent = "{$recentAge->m} months ago.";
          } elseif ($recentAge->d >= 7) {
            $weeks = floor($recentAge->d / 7);
            $trainingRecentAgeContent = "$weeks weeks ago.";
          } elseif ($recentAge->d > 0) {
            $trainingRecentAgeContent = "{$recentAge->d} days ago.";
          } elseif ($recentAge->h > 0) {
            $trainingRecentAgeContent = "{$recentAge->h} hours ago.";
          } elseif ($recentAge->i > 0) {
            $trainingRecentAgeContent = "{$recentAge->i} minutes ago.";
          } else {
            $trainingRecentAgeContent = "{$recentAge->s} seconds ago.";
          }

          $activityRead = $trainingRecentData['activityRead'] == 0 ? "style='color: #0e183f;'" : "style='color: #0e183fa2;'";

          echo "
          <div class='recent-act' $activityRead>
            <div class='recent-img'><img src='assets/images/default-profile.png' alt=''></div>
            <div class='recent-body'>
              <div class='recent-act-content'>
                $adminActivityContent
              </div>
              <div class='recent-age'>$trainingRecentAgeContent</div>
            </div>
          </div>
          ";
        } else {
          // training activity

          $trainingActivityType = $trainingRecentData['trainingActivityType'];
          $registrationID = $trainingRecentData['relationID'];

          $registrationDetailStmt = $conn->prepare("SELECT rd.*, e.*, e.agencyName FROM registration_details AS rd INNER JOIN employee as e ON rd.employeeID = e.employeeID WHERE rd.registrationID = ?");
          $registrationDetailStmt->bind_param("s", $registrationID);

          if ($registrationDetailStmt->execute()) {
            $registrationDetailResult = $registrationDetailStmt->get_result();
            $registrationDetailData = $registrationDetailResult->fetch_assoc();
            $registrationAgency = $registrationDetailData['agencyName'];

            date_default_timezone_set('Asia/Manila');
            $today = new DateTime();
            $recentDate = new DateTime($trainingRecentData['timestamp']);
            $activityRead = $trainingRecentData['activityRead'] == "0" ? "style='color: #0e183f;'" : "style='color: #0e183fa2;'";

            $recentAge = $today->diff($recentDate);

            if ($recentAge->y > 0) {

              $trainingRecentAgeContent = "{$recentAge->y} years ago.";
            } elseif ($recentAge->m > 0) {
              $trainingRecentAgeContent = "{$recentAge->m} months ago.";
            } elseif ($recentAge->d >= 7) {
              $weeks = floor($recentAge->d / 7);
              $trainingRecentAgeContent = "$weeks weeks ago.";
            } elseif ($recentAge->d > 0) {
              $trainingRecentAgeContent = "{$recentAge->d} days ago.";
            } elseif ($recentAge->h > 0) {
              $trainingRecentAgeContent = "{$recentAge->h} hours ago.";
            } elseif ($recentAge->i > 0) {
              $trainingRecentAgeContent = "{$recentAge->i} minutes ago.";
            } else {
              $trainingRecentAgeContent = "{$recentAge->s} seconds ago.";
            }

            if ($trainingActivityType == "0") {
              $adminActivityContent = "<u>New registration</u> from the agency <b>$registrationAgency</b>";
              echo "
                <div class='recent-act' onclick='getRegDetails($registrationID)' $activityRead>
                  <div class='recent-img'><img src='assets/images/default-profile.png' alt=''></div>
                  <div class='recent-body'>
                    <div class='recent-act-content'>
                      $adminActivityContent
                    </div>
                    <div class='recent-age'>$trainingRecentAgeContent</div>
                  </div>
                </div>
              ";
            }
          }
        }
      }
    } else {
      echo "<i class='text-align: center;>No recent activities</i>";
    }
  } else {
    echo "Error: {$trainingRecentStmt->error}";
  }
} else {
  echo "<i>Training ID not found.</i>";
}