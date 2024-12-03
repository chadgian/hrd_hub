<div class="homepage-viewTraining">
  <div class="homepage-viewTraining-content">
    <div class="back-header">
      <div class="back-button" onclick="backButton()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
          class="bi bi-arrow-return-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
        </svg>
      </div>
    </div>
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
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="viewParticipantStatusModalLabel">Participant Status</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="attendance-status mb-3 d-flex">
          <div style="flex: 1;">Attendance:</div>
          <div id="attendance-status-content" style="flex: 1;">
            Complete
          </div>
        </div>
        <div class="attendance-status-remark mb-3 d-flex">
          <div style="flex: 1;">Attendance Remark:</div>
          <div id="attendance-status-remark" style="flex: 1;">
            N/A
          </div>
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
            <input type="hidden" id="participantID">
            <div class="row mb-3">
              <div class="col-md-6 d-flex flex-column">
                <label class="form-label" for="orNumber">OR Number</label>
                <input class="form-control" type="text" id="orNumber" name="orNumber">
              </div>
              <div class="col-md-6 d-flex flex-column">
                <label class="form-label" for="paymentDate">Date of Payment</label>
                <input class="form-control" type="date" id="paymentDate" name="paymentDate">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 d-flex flex-column">
                <label class="form-label" for="fieldOffice">Field Office</label>
                <select name="fieldOffice" id="fieldOffice" class="form-control">
                  <option value="0">Select field office...</option>
                  <option value="csc">Regional Office</option>
                  <option value="aklan">FO - Aklan</option>
                  <option value="antique">FO - Antique</option>
                  <option value="guimaras">FO - Guimaras</option>
                  <option value="iloilo">FO - Iloilo</option>
                  <option value="negros">FO - Negros Occidental</option>
                </select>
              </div>
              <div class="col-md-6 d-flex flex-column">
                <label class="form-label" for="collectingOfficer">Collecting Officer</label>
                <input class="form-control" type="text" id="collectingOfficer" name="collectingOfficer">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 d-flex flex-column">
                <label class="form-label" for="amount">Amount</label>
                <input class="form-control" type="number" id="amount" name="amount">
              </div>
              <div class="col-md-6 d-flex flex-column">
                <label class="form-label" for="discount">Discount</label>
                <input class="form-control" type="text" id="discount" name="discount">
              </div>
            </div>
            <div class="row mb-3">
              <div class="editPayment justify-content-center align-items-center">
                <button id="editPayment-btn">Edit payment status</button>
              </div>
              <div class="savePayment justify-content-center align-items-center" style="display: none;">
                <button id="savePayment-btn">Save payment status</button>
                <button id="cancelPayment-btn">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="savePaymentDetails()"
          data-bs-dismiss="modal">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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