<div class='recent-acts'>
  <div class='recent-header'>
    <span class="recent-header-title">Admin Activities</span>
    <span class="recent-refresh" id="recent-refresh" style="cursor: pointer;">Refresh</span>
  </div>
  <div class='recent-content'>
    <?php
    include '../components/processes/db_connection.php';

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

          $userInitial = $recentData['initials'];
          $trainingName = $recentData['trainingName'];

          switch ($recentData['activityType']) {
            case 0:
              $content = "<b>$userInitial</b> has <u>updated the details</u> of <b>$trainingName</b>";
              break;

            case 1:
              $content = "<b>$userInitial</b> has <u>updated the venue</u> of <b>$trainingName</b>";
              break;

            case 2:
              $content = "<b>$userInitial</b> has added a <u>new training</u>: <b>$trainingName</b>";
              break;

            case 3:
              $content = "<b>$userInitial</b> has <u>updated the participants</u> of <b>$trainingName</b>";
              break;

            case 4:
              if ($recentData['newData'] == 1) {
                $content = "<b>$userInitial</b> has <u>opened the registration</u> of <b>$trainingName</b>";
              } else {
                $content = "<b>$userInitial</b> has <u>closed the registration</u> of <b>$trainingName</b>";
              }
              break;

            case 5:
              $content = "<b>$userInitial</b> has <u>updated the date</u> of <b>$trainingName</b>";
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
            <div class='recent-act'>
              <div class='recent-img'><img src='assets/images/default-profile.png' alt=''></div>
              <div class='recent-body'>
                <div class='recent-act-content'>
                  $content
                </div>
                <div class='recent-age'>$recentAgeContent</div>
              </div>
            </div>
          ";
        }
      } else {
        echo "<i>No recent activities</i>";
      }
    }
    ?>
  </div>
  <div class='recent-footer'>
    <div class="recent-duration">See all</div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<script>
  $('#recent-refresh').click(function () {
    $.ajax({
      url: 'components/load_recent_activities.php',
      success: function (data) {
        $('#recent-content').html(data);
      },
      error: function (xhr, status, error) {
        console.error('AJAX request failed:', status, error);
      }
    });
  });
</script>