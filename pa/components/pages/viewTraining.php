<div class="homepage-viewTraining">
  <div class="homepage-viewTraining-content">
    <?php
    include __DIR__ . "/../../../components/processes/db_connection.php";

    $trainingID = $_GET['trainingID'];

    $result = getTrainings();

    function getTrainings()
    {
      global $conn;
      global $trainingID;
      $stmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
      $stmt->bind_param("s", $trainingID);
      $stmt->execute();
      return $stmt->get_result();
    }

    $data = $result->fetch_assoc();
    if ($result->num_rows > 0) {
      echo "<h3 class='text-center'>{$data['trainingName']}</h3>";

    } else {
      echo "<h3 class='text-center'>No training found.</h3>";
      exit();
    }
    ?>

    <div class="homepage-viewTraining-content-participants">
      <div class="participant-header">
        <div class="participant-title">Participants</div>
        <div class="participant-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person"
            viewBox="0 0 20 20">
            <path
              d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
          </svg>
        </div>
        <div class="participant-search">
          <label for="participant-search-input" class="searchIcon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
              viewBox="0 0 16 16">
              <path
                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
            </svg>
          </label>
          <input type="search" id="participant-search-input" name="participant-search-input"
            placeholder="Search participants">
        </div>
      </div>
      <div class="participant-content">

      </div>
    </div>
  </div>
  <div class="homepage-viewTraining-sidecontent">
    <div class="sideContent-title text-center">Training Details</div>
    <div class="sideContent-main">
      <div class="item">
        <div class="item-title">Date:</div>
        <div class="item-content">
          <?php echo (new DateTime($data['startDate']))->format("M j") . "-" . (new DateTime($data['endDate']))->format("j, Y"); ?>
        </div>
      </div>
      <div class="item">
        <div class="item-title">Venue:</div>
        <div class="item-content"><?php echo $data['venue']; ?></div>
      </div>
      <div class="item">
        <div class="item-title">Hours:</div>
        <div class="item-content">
          <?php echo $data['trainingHours'] > "1" ? "{$data['trainingHours']} hours" : "{$data['trainingHours']} hour"; ?>
        </div>
      </div>
      <div class="item">
        <div class="item-title">Admin:</div>
        <div class="item-content">
          <?php echo $data['training_admin']; ?>
        </div>
      </div>
      <div class="item">
        <div class="item-title">Fee:</div>
        <div class="item-content">₱
          <?php echo $data['fee']; ?>
        </div>
      </div>
      <div class="item">
        <div class="item-title">Type:</div>
        <div class="item-content">
          <?php echo ucfirst($data['trainingType']); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Participant Status Modal -->
<div class="modal fade" id="viewParticipantStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="viewParticipantStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="viewParticipantStatusModalLabel">Participant Status</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="attendance-status mb-3">
          <span>Attendance:</span>
          <span id="attendance-status-content"></span>
        </div>
        <div class="attendance-status-remark mb-3">
          <span>Attendance Remark:</span>
          <span id="attendance-status-remark"></span>
        </div>
        <div class="paymentSection">
          <div>
            <div class="paymentStatus-title">Payment Status</div>
            <div class="payment-toggle">
              <input type="checkbox" value="1" id="payment-checkbox" name="payment-checkbox">
              <label for="payment-checkbox" id="payment-label"></label>
            </div>
          </div>
          <div id="payment-field">
            <h4 class="text-center" style="width: 100%;">Payment field</h4>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Participant Details Modal -->
<div class="modal fade" id="viewParticipantDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false"
  tabindex="-1" aria-labelledby="viewParticipantDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="viewParticipantDetailsModalLabel">Registration Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 class="regDetails-trainingName"></h5>
        <div class="row mb-3">
          <div class="col-md-12">
            <span class="regDetails-labels participantName">Participant Name:</span>
            <input class="form-control" type="text" value="" id="participantName" disabled readonly>
            <input type="hidden" id="employeeID">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <span class="regDetails-labels">Sex:</span>
            <input class="form-control" type="text" value="" id="participantSex" disabled readonly>
          </div>
          <div class="col-md-4">
            <span class="regDetails-labels">Age:</span>
            <input class="form-control" type="text" value="" id="participantAge" disabled readonly>
          </div>
          <div class="col-md-4">
            <span class="regDetails-labels">Civil Status:</span>
            <input class="form-control" type="text" value="" id="participantCivilStatus" disabled readonly>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <span class="regDetails-labels">Phone Number:</span>
            <input class="form-control" type="text" value="" id="participantNumber" disabled readonly>
          </div>
          <div class="col-md-6">
            <span class="regDetails-labels">Email Address:</span>
            <input class="form-control" type="text" value="" id="participantEmail" disabled readonly>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <span class="regDetails-labels">Position:</span>
            <input class="form-control" type="text" value="" id="participantPosition" disabled readonly>
          </div>
          <div class="col-md-6">
            <span class="regDetails-labels">Sector:</span>
            <input class="form-control" type="text" value="" id="participantSector" disabled readonly>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-12">
            <span class="regDetails-labels">Name of Agency:</span>
            <input class="form-control" type="text" value="" id="participantAgency" disabled readonly>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-12">
            <span class="regDetails-labels">Field Office:</span>
            <input class="form-control" type="text" value="" id="participantFO" disabled readonly>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-12">
            <span class="regDetails-labels">Food Restrictions:</span>
            <input class="form-control" type="text" value="" id="participantFoodRestrictions" disabled readonly>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <span class="regDetails-labels">Date Registered:</span>
            <input class="form-control" type="text" value="" id="participantDate" disabled readonly>
          </div>
          <div class="col-md-6">
            <span class="regDetails-labels">Confirmation Slip:</span>
            <a class="form-control" type="text" value="" id="participantComfirmationSlip" href="" download>Confirmation
              Slip</a>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>