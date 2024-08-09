<div class='container-fluid body-content'>
  <div class='row body-content-row'>
    <div class='col-md-2 left-side'>
      <?php include 'components/navbar.php'; ?>
    </div>
    <div class='col-md-7 middle-content'>
      <?php
      $id = $_GET['t'];

      include '../components/processes/db_connection.php';

      $getTrainingDataStmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
      $getTrainingDataStmt->bind_param("i", $id);
      if ($getTrainingDataStmt->execute()) {
        $getTrainingDataResult = $getTrainingDataStmt->get_result();
        if ($getTrainingDataResult->num_rows > 0) {
          $trainingData = $getTrainingDataResult->fetch_assoc();

          $trainingNameView = $trainingData['trainingName'];
          $trainingDetailsView = $trainingData['details'];
          $trainingStartDateView = new DateTime($trainingData['startDate']);
          $trainingEndDateView = new DateTime($trainingData['endDate']);
          $trainingModeView = $trainingData['mode'];
          $trainingVenueView = $trainingData['venue'];
          $trainingHoursView = $trainingData['trainingHours'];
          $trainingFeeView = $trainingData['fee'];
          $trainingAdminView = $trainingData['training_admin'];
          $trainingPaxView = $trainingData['targetPax'];
          $trainingAreaView = $trainingData['currArea'];
          $trainingDocsView = $trainingData['requiredDocs'];
          $trainingTypeView = $trainingData['trainingType'];
          $trainingOpenRegView = $trainingData['openReg'];

          $trainingDateView = $trainingStartDateView == $trainingEndDateView ? $trainingStartDateView->format("F d, Y") : $trainingStartDateView->format("F d") . '-' . $trainingEndDateView->format("d, Y");

        }
      }
      ?>

      <h4 class="viewTraining-title"><?php echo $trainingNameView; ?></h4>
      <div class="viewTraining-info">
        <div class="viewTraining-detail">
          <p class="viewTraining-info-title" style="margin: 0;">Training Detail:</p>
          <p class="viewTraining-info-content" style="margin: 2px;"><?php echo $trainingDetailsView; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Training Date:</p>
          <p class="viewTraining-info-content"><?php echo $trainingDateView; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Training Mode:</p>
          <p class="viewTraining-info-content"><?php echo $trainingModeView; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Training Venue:</p>
          <p class="viewTraining-info-content"><?php echo $trainingVenueView; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Training Hours:</p>
          <p class="viewTraining-info-content"><?php echo $trainingHoursView; ?> Hours</p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Registration Fee:</p>
          <p class="viewTraining-info-content">Php <?php echo $trainingFeeView; ?>.00</p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Training Admin:</p>
          <p class="viewTraining-info-content"><?php echo $trainingAdminView; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Expected Pax:</p>
          <p class="viewTraining-info-content"><?php echo $trainingPaxView; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Requires Output:</p>
          <p class="viewTraining-info-content"><?php echo $trainingDocsView == 1 ? "Required" : "Not required"; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Training Type:</p>
          <p class="viewTraining-info-content"><?php echo $trainingTypeView; ?></p>
        </div>
        <div class=" viewTraining-item">
          <p class="viewTraining-info-title">Registration:</p>
          <p class="viewTraining-info-content">
            <a href="components/changeRegistrationStatus.php?id=<?php echo $id ?>&status=<?php echo $trainingOpenRegView ?>"
              class="openReg-btn"
              style="background-color: <?php echo $trainingOpenRegView == 0 ? "#CE2F2F;" : "#049B01;"; ?>"><?php echo $trainingOpenRegView == 0 ? "Close" : "Open"; ?></a>
            <small style="font-size: 10px;">(Click to
              <?php echo $trainingOpenRegView == 0 ? "open" : "close"; ?>)</small>

          </p>
        </div>
        <div class="viewTraining-edit">
          <div type="button" data-bs-toggle="modal" data-bs-target="#editTraining">
            <small>Edit Training Details <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                  d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                  d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
              </svg></small>
          </div>
        </div>
      </div>
      <div class="vieTraining-participants">
        <?php include "components/viewTraining-participants.php"; ?>
      </div>
    </div>
    <div class='col-md-3 right-side'>
      <?php include 'components/trainingRecents.php'; ?>
    </div>
  </div>
</div>

<div class="modal fade" id="editTraining" tabindex="-1" aria-labelledby="editTrainingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content register-modal">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="registerBackdropLabel">EDIT - <small><?php echo $trainingNameView; ?></small>
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
            class="bi bi-x-lg"></i></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="components/editTraining.php">
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Name:</label>
              <input type="text" class="form-control" id="trainingName" name="trainingName"
                value="<?php echo $trainingNameView; ?>" required>
              <input type="hidden" name="trainingID" id="trainingID" value="<?php echo $id; ?>">
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Venue:</label>
              <input type="text" class="form-control" id="trainingVenue" name="trainingVenue"
                value="<?php echo $trainingVenueView; ?>" required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <div class="d-flex flex-row gap-2 align-items-end">
                <div style="width:40%;">
                  <label for="" class="form-label">Start Date:</label>
                  <input type="date" class="form-control" id="trainingStart" name="trainingStart"
                    value="<?php echo $trainingStartDateView->format("Y-m-d"); ?>" required>
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
                  <input type="date" class="form-control" id="trainingEnd" name="trainingEnd"
                    value="<?php echo $trainingEndDateView->format("Y-m-d"); ?>" required>
                </div>
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Admin:</label>
              <input type="text" class="form-control" id="trainingAdmin" name="trainingAdmin"
                value="<?php echo $trainingAdminView; ?>" required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Fee:</label>
              <input type="number" class="form-control" id="trainingFee" name="trainingFee"
                value="<?php echo $trainingFeeView; ?>" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Hours:</label>
              <input type="number" class="form-control" id="trainingHours" name="trainingHours"
                value="<?php echo $trainingHoursView; ?>" required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Total Pax:</label>
              <input type="number" class="form-control" id="totalPax" name="totalPax"
                value="<?php echo $trainingPaxView; ?>" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Training Mode:</label>
              <input type="text" class="form-control" id="trainingMode" name="trainingMode"
                value="<?php echo $trainingModeView; ?>" required>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-6">
              <label for="" class="form-label">Curriculum Area:</label>
              <input type="text" class="form-control" id="currArea" name="currArea"
                value="<?php echo $trainingAreaView; ?>" required>
            </div>
            <div class="mb-3 col-md-6 d-flex flex-column justify-content-end align-items-start gap-2">
              <div class="d-flex gap-2">
                <label for="docs">Required documents</label><input type="checkbox" id="docs" name="docs" <?php echo $trainingDocsView == 1 ? "checked" : ""; ?>>
              </div>
              <div>
                <label for="trainingType">Type of training: </label>
                <select name="trainingType" id="trainingType">
                  <option value="external" <?php echo $trainingTypeView == "external" ? "selected" : ""; ?>>External
                  </option>
                  <option value="internal" <?php echo $trainingTypeView == "internal" ? "selected" : ""; ?>>Internal
                  </option>
                  <option value="both">Both</option>
                </select>
              </div>
            </div>
          </div>
          <div class="mb-1 row">
            <div class="mb-3 col-md-12">
              <label for="trainingDetails" class="form-label">Training Details:</label>
              <textarea name="trainingDetails" id="trainingDetails" class="form-control"
                requireds><?php echo htmlspecialchars($trainingDetailsView); ?></textarea>
            </div>
          </div>
          <center><button type="submit" class="btn addTraining-btn col-md-3">Save</button></center>
        </form>
      </div>
      <div class="modal-footer">
        <span class="footer-msg"></span>
      </div>
    </div>
  </div>
</div>