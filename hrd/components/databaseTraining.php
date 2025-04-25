<?php
include "../components/processes/db_connection.php";

$trainingID = $_GET['t'] ?? null;

$getTrainingStmt = $conn->prepare("SELECT * FROM oldtraining_details WHERE oldTrainingID = ?");
$getTrainingStmt->bind_param("s", $trainingID);
$getTrainingStmt->execute();
$trainingResult = $getTrainingStmt->get_result();

if ($trainingResult->num_rows == 0) {
  echo "Training not found.";
} else {
  $trainingDetails = $trainingResult->fetch_assoc();

  // echo $trainingDetails['oldTrainingName'];
  $startDate = new DateTime($trainingDetails['oldStartDate']);
  $endDate = new DateTime($trainingDetails['oldEndDate']);
  if ($startDate == $endDate) {
    $trainingDate = $startDate->format("F j, Y");
  } else {
    $trainingDate = $startDate->format("F j") . "-" . $endDate->format("j, Y");
  }
}

?>
<style>
  .oldTraining-header {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
</style>
<div>
  <div class="container mt-1 oldTraining-header">
    <h3 class=""><?php echo htmlspecialchars($trainingDetails['oldTrainingName']); ?></h3>
    <h5 class=""><?php echo $trainingDate; ?></h5>
  </div>
  <div class="container mt-3">
    <div class="mb-3">
      <input type="text" id="searchInput" class="form-control" placeholder="Search participants by name or agency">
    </div>
    <div class="table-container">
      <table class="table table-bordered table-hover table-striped">
        <thead class="thead-dark sticky-top">
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Name</th>
            <th scope="col">Agency</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody id="participantsTableBody">
          <?php
          $getParticipantsStmt = $conn->prepare("SELECT * FROM oldtraining_participants WHERE oldTrainingID = ?");
          $getParticipantsStmt->bind_param("s", $trainingID);
          $getParticipantsStmt->execute();
          $participantsResult = $getParticipantsStmt->get_result();

          if ($participantsResult->num_rows > 0) {
            while ($participant = $participantsResult->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($participant['oldPaxNumber']) . "</td>";
              echo "<td>" . htmlspecialchars($participant['oldpaxName']) . "</td>";
              echo "<td>" . htmlspecialchars($participant['oldpaxAgency']) . "</td>";
              echo "<td>";
              echo "<div class='d-flex align-items-center'>";
              if ($participant['oldpaxStatus'] === 'completed') {
                echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='green' class='mr-2' viewBox='0 0 16 16' title='Complete'>
                    <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.293 10.293a1 1 0 0 0 1.414 0L12 6.707l-1.414-1.414L7 8.586 5.707 7.293 4.293 8.707l2 2z'/>
                    </svg>";
              } else {
                echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='mr-2' viewBox='0 0 16 16' title='Incomplete'>
                        <path d='M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708z'/>
                      </svg>";
              }
              echo "<button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#participantModal' 
                      data-id='" . htmlspecialchars($participant['oldpaxID']) . "' 
                      data-name='" . htmlspecialchars($participant['oldpaxName']) . "' 
                      data-agency='" . htmlspecialchars($participant['oldpaxAgency']) . "' 
                      data-status='" . htmlspecialchars($participant['oldpaxStatus']) . "' 
                      data-ornumber='" . htmlspecialchars($participant['oldpaxORNumber']) . "' 
                      data-datepaid='" . htmlspecialchars($participant['oldpaxDatePaid']) . "' 
                      data-remarks='" . htmlspecialchars($participant['oldpaxRemarks']) . "'>View</button>";
              echo "</div>";
              echo "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='4' class='text-center'>No participants found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <style>
      .table-container {
        max-height: 400px;
        overflow-y: auto;
      }

      .table tbody tr {
        height: 30px;
      }

      .table td,
      .table th {
        padding: 5px;
      }

      .table td:first-child,
      .table th:first-child {
        text-align: center;
      }

      svg {
        margin-right: 5px;
        vertical-align: middle;
      }

      .d-flex {
        display: flex;
        align-items: center;
      }
    </style>
  </div>
</div>

<script>
  document.getElementById('searchInput').addEventListener('input', function () {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#participantsTableBody tr');

    rows.forEach(function (row) {
      var name = row.cells[1].textContent.toLowerCase();
      var agency = row.cells[2].textContent.toLowerCase();

      if (name.includes(filter) || agency.includes(filter)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
</script>

<!-- Modal -->
<div class="modal fade" id="participantModal" tabindex="-1" aria-labelledby="participantModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participantModalLabel">Participant Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateParticipantForm">
          <input type="hidden" id="participantID" name="participantID">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="participantName" class="form-label">Name</label>
              <input type="text" class="form-control" id="participantName" name="participantName" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label for="participantAgency" class="form-label">Agency</label>
              <input type="text" class="form-control" id="participantAgency" name="participantAgency" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="participantStatus" class="form-label">Status</label>
              <select class="form-control" id="participantStatus" name="participantStatus" disabled readonly>
                <option value="incomplete">Incomplete</option>
                <option value="completed">Completed</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="participantORNumber" class="form-label">OR Number</label>
              <input type="text" class="form-control" id="participantORNumber" name="participantORNumber" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="participantDatePaid" class="form-label">Date Paid</label>
            <input type="text" class="form-control" id="participantDatePaid" name="participantDatePaid" readonly>
          </div>
          <div class="mb-3">
            <label for="participantRemarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="participantRemarks" name="participantRemarks" rows="3"
              readonly></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-warning" id="editParticipantButton">Edit</button>
        <button type="button" class="btn btn-primary" id="saveParticipantButton" disabled>Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script>
  var participantModal = document.getElementById('participantModal');
  participantModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var name = button.getAttribute('data-name');
    var agency = button.getAttribute('data-agency');
    var status = button.getAttribute('data-status');
    var orNumber = button.getAttribute('data-ornumber');
    var datePaid = button.getAttribute('data-datepaid');
    var remarks = button.getAttribute('data-remarks');

    var modalID = participantModal.querySelector('#participantID');
    var modalName = participantModal.querySelector('#participantName');
    var modalAgency = participantModal.querySelector('#participantAgency');
    var modalStatus = participantModal.querySelector('#participantStatus');
    var modalORNumber = participantModal.querySelector('#participantORNumber');
    var modalDatePaid = participantModal.querySelector('#participantDatePaid');
    var modalRemarks = participantModal.querySelector('#participantRemarks');

    modalID.value = id;
    modalName.value = name;
    modalAgency.value = agency;
    modalStatus.value = status;
    modalORNumber.value = orNumber;
    modalDatePaid.value = datePaid;
    modalRemarks.value = remarks;

    // Disable save button initially
    document.getElementById('saveParticipantButton').disabled = true;
  });

  document.getElementById('editParticipantButton').addEventListener('click', function () {
    var form = document.getElementById('updateParticipantForm');
    Array.from(form.elements).forEach(function (element) {
      if (element.id !== 'participantID') {
        if (element.hasAttribute('readonly')) {
          element.removeAttribute('readonly');
          element.removeAttribute('disabled');
          document.getElementById('editParticipantButton').innerHTML = 'Undo Edit';
          document.getElementById('editParticipantButton').classList.remove('btn-warning');
          document.getElementById('editParticipantButton').classList.add('btn-danger');
        } else {
          element.setAttribute('readonly', true);
          element.setAttribute('disabled', true);
          document.getElementById('editParticipantButton').innerHTML = 'Edit';
          document.getElementById('editParticipantButton').classList.remove('btn-danger');
          document.getElementById('editParticipantButton').classList.add('btn-warning');
        }
      }
    });
    document.getElementById('saveParticipantButton').disabled = false;
  });

  document.getElementById('saveParticipantButton').addEventListener('click', function () {
    var form = document.getElementById('updateParticipantForm');
    var formData = new FormData(form);

    fetch('components/updateOldParticipant.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Participant updated successfully.');
          location.reload();
        } else {
          alert('Failed to update participant.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the participant.');
      });
  });
</script>