<?php
include '../components/processes/db_connection.php';

$trainingName = "";
$trainingVenue = "";
$trainingStart = "";
$trainingEnd = "";
$trainingAdmin = "";
$trainingFee = "";
$trainingHours = "";
$totalPax = "";
$trainingMode = "";
$currArea = "";
$docs = "";
$trainingType = "";
$trainingDetails = "";
$startString = "";
$endString = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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


  $addTrainingStmt = $conn->prepare("INSERT INTO training_details (trainingName, training_admin, startDate, endDate, venue, mode, fee, trainingHours, targetPax, details, currArea, requiredDocs, trainingType, lastUpdateBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $addTrainingStmt->bind_param("ssssssssssssss", $trainingName, $trainingAdmin, $startString, $endString, $trainingVenue, $trainingMode, $trainingFee, $trainingHours, $totalPax, $trainingDetails, $currArea, $docs, $trainingType, $_SESSION['userID']);

  if ($addTrainingStmt->execute()) {
    // echo "Training added successfully";
  } else {
    echo (string) $addTrainingStmt->error;
  }
}
?>

<div class='container-fluid body-content'>
  <div class='row body-content-row'>
    <div class='col-md-2 left-side'>
      <?php include 'components/navbar.php'; ?>
    </div>
    <div class="addTraining-body col-md-7 middle-content">
      <div class="addTraining-content">
        <h4 class="addTraining-title text-center">ADD NEW TRAINING</h4>
        <form method="POST">
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Name:</label>
              <input type="text" class="form-control" id="trainingName" name="trainingName"
                placeholder="e.g. ePRIMETime" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Venue:</label>
              <input type="text" class="form-control" id="trainingVenue" name="trainingVenue"
                placeholder="e.g. Grand Xing Imperial Hotel" required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <div class="d-flex flex-row gap-2 align-items-end">
                <div style="width:40%;">
                  <label for="" class="form-label">Start Date:</label>
                  <input type="date" class="form-control" id="trainingStart" name="trainingStart" required>
                </div>
                <div style="width:10%;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                    class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                      d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                  </svg>
                </div>
                <div style="width:40%;">
                  <label for="" class="form-label">End Date:</label>
                  <input type="date" class="form-control" id="trainingEnd" name="trainingEnd" required>
                </div>
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Admin:</label>
              <input type="text" class="form-control" id="trainingAdmin" name="trainingAdmin"
                placeholder="e.g. Grand Xing Imperial Hotel" required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Fee:</label>
              <input type="number" class="form-control" id="trainingFee" name="trainingFee" placeholder="e.g. 6000"
                required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Hours:</label>
              <input type="number" class="form-control" id="trainingHours" name="trainingHours" placeholder="e.g. 16"
                required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Total Pax:</label>
              <input type="number" class="form-control" id="totalPax" name="totalPax" placeholder="e.g. 100" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Mode:</label>
              <input type="text" class="form-control" id="trainingMode" name="trainingMode" placeholder="e.g. Online"
                required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Curriculum Area:</label>
              <input type="text" class="form-control" id="currArea" name="currArea" placeholder="e.g. Supervisory"
                required>
            </div>
            <div class="mb-3 col-md-6 d-flex flex-column justify-content-end align-items-start gap-2">
              <div class="d-flex gap-2">
                <label for="docs">Required documents</label><input type="checkbox" id="docs" name="docs">
              </div>
              <div>
                <label for="trainingType">Type of training: </label>
                <select name="trainingType" id="trainingType">
                  <option value="external">External</option>
                  <option value="internal">Internal</option>
                  <option value="both">Both</option>
                </select>
              </div>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-12">
              <label for="trainingDetails" class="form-label">Training Details:</label>
              <textarea name="trainingDetails" id="trainingDetails" class="form-control" requireds></textarea>
            </div>
          </div>
          <center><button type="submit" class="btn addTraining-btn col-md-3">Add Training</button></center>
        </form>
      </div>
    </div>
    <div class='col-md-3 right-side'>
      <?php include 'components/recents.php'; ?>
    </div>
  </div>
</div>