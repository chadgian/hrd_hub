<div class="homepage">
  <div class="searchHomepage" id="searchHomepage">
    <label for="searchHomepageInput" class="searchIcon">
      <svg width="23" height="23" viewBox="0 0 23 23" aria-hidden="true" class="DocSearch-Search-Icon">
        <path
          d="M14.386 14.386l4.0877 4.0877-4.0877-4.0877c-2.9418 2.9419-7.7115 2.9419-10.6533 0-2.9419-2.9418-2.9419-7.7115 0-10.6533 2.9418-2.9419 7.7115-2.9419 10.6533 0 2.9419 2.9418 2.9419 7.7115 0 10.6533z"
          stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </label>
    <input type="search" id="searchHomepageInput" name="searchHomepageInput" placeholder="Search training name...">
  </div>
  <div class="homepage-loading" style="display: none;">
    <div style='display: flex; width: 100%; justify-content: center;'>
      <img src='assets/images/loading.svg' width='20%'>
    </div>
  </div>
  <div class="homepage-trainings">
    <div class="homepage-recentTrainings">
      <div class="homepage-recentTrainings-title">
        RECENT TRAININGS
      </div>

      <?php
      include __DIR__ . '\..\..\..\components\processes\db_connection.php';

      date_default_timezone_set('Asia/Manila');

      $now = (new DateTime())->format("Y-m-d");

      getRecentTrainingsHomepage($now);
      function getRecentTrainingsHomepage($now)
      {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM training_details WHERE endDate <= ? ORDER BY endDate DESC LIMIT 2");
        $stmt->bind_param("s", $now);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($data = $result->fetch_assoc()) {
            $trainingName = $data['trainingName'];
            $trainingID = $data['trainingID'];

            $trainingDate = (new DateTime($data['startDate']))->format("M d") . "-" . (new DateTime($data['endDate']))->format("d");

            echo "
            <div class='homepage-recentTrainings-training'>
              <div class='homepage-recentTrainings-trainingDate'>$trainingDate</div>
              <div class='homepage-recentTrainings-trainingName'>
                  $trainingName
              </div>
              <div class='homepage-recentTrainings-action' onclick='viewTrainingDetails($trainingID)'>
                View
              </div>
            </div>
          ";
          }
        } else {
          echo "<i style='text-align: center; font-size: large;'>No recent trainings available</i>";
        }
      }
      ?>

    </div>

    <div class="homepage-recentTrainings">
      <div class="homepage-recentTrainings-title">
        UPCOMING TRAININGS
      </div>
      <?php
      getUpcomingTrainingsHomepage($now);
      function getUpcomingTrainingsHomepage($now)
      {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM training_details WHERE endDate >= ? ORDER BY endDate ASC");
        $stmt->bind_param("s", $now);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($data = $result->fetch_assoc()) {

            $trainingName = $data['trainingName'];
            $trainingID = $data['trainingID'];

            $trainingDate = (new DateTime($data['startDate']))->format("M d") . "-" . (new DateTime($data['endDate']))->format("d");

            echo "
            <div class='homepage-recentTrainings-training'>
              <div class='homepage-recentTrainings-trainingDate'>$trainingDate</div>
              <div class='homepage-recentTrainings-trainingName'>
                  $trainingName
              </div>
              <div class='homepage-recentTrainings-action' onclick='viewTrainingDetails($trainingID)'>
                View
              </div>
            </div>
          ";
          }
        } else {
          echo "<i style='text-align: center; font-size: large;'>No recent trainings available</i>";
        }
      }
      ?>
    </div>
  </div>

  <div class="homepage-searchResult" style="display: none;">
    <div class="homepage-recentTrainings-title">
      SEARCH RESULTS
    </div>
    <div class="homepage-searchResult-content d-flex flex-column gap-2"></div>
  </div>
</div>

<!-- <script src="assets/scripts/homepage.js"></script> -->