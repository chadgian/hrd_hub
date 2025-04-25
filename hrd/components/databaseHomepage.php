<?php
include "../components/processes/db_connection.php";
$getOldTrainingsStmt = $conn->prepare("SELECT * FROM oldtraining_details ORDER BY oldStartDate DESC");
$getOldTrainingsStmt->execute();
$oldTrainingsResult = $getOldTrainingsStmt->get_result();
$oldTrainings = [];
if ($oldTrainingsResult->num_rows > 0) {
  while ($row = $oldTrainingsResult->fetch_assoc()) {
    $oldTrainings[] = $row;
  }
} else {
  $oldTrainings = null;
}

?>

<div class="database_homepage">
  <div class="db_header mb-4">
    <h3 class="text-center">Old Training Database</h3>
    <div class="d-flex justify-content-between mb-3">
      <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importTrainingModal">Import Training</span>
      <div class="search-oldtraining">
        <input type="text" class="form-control" placeholder="Search Training" aria-label="Search Training"
          id="search-oldtraining" onkeyup="searchTraining()">
      </div>
    </div>
  </div>
  <div class="trainingList">
    <div class="trainingList-content">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">Training Name</th>
            <th scope="col">Date</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody id="trainingTableBody">
          <?php foreach ($oldTrainings as $training): ?>
            <tr>
              <?php if ($training['oldStartDate'] == $training['oldEndDate']) {
                $trainingDate = (new DateTime($training['oldStartDate']))->format("F j, Y");
              } else {
                $trainingDate = (new DateTime($training['oldStartDate']))->format("F j") . "-" . (new DateTime($training['oldEndDate']))->format("j, Y");
              } ?>
              <td><?php echo htmlspecialchars($training['oldTrainingName']); ?></td>
              <td><?php echo htmlspecialchars($trainingDate); ?></td>
              <td class="d-flex gap-2">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTrainingModal"
                  onclick="populateEditModal(<?php echo htmlspecialchars(json_encode($training)); ?>)">Edit</button>
                <a href="index.php?p=7&t=<?php echo $training['oldTrainingID']; ?>"
                  class="btn btn-primary btn-sm">Open</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function searchTraining() {
    const searchInput = document.getElementById('search-oldtraining').value.toLowerCase();
    const tableRows = document.querySelectorAll('#trainingTableBody tr');

    tableRows.forEach(row => {
      const trainingName = row.querySelector('td:first-child').textContent.toLowerCase();
      if (trainingName.includes(searchInput)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }
</script>

<!-- Edit Training Modal -->
<div class="modal fade" id="editTrainingModal" tabindex="-1" aria-labelledby="editTrainingModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editTrainingModalLabel">Edit Training</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editTrainingForm">
          <div class="mb-3">
            <label for="editTrainingName" class="form-label">Training Name</label>
            <input type="text" class="form-control" id="editTrainingName" name="editTrainingName" required>
          </div>
          <div class="mb-3">
            <label for="editTrainingStartDate" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="editTrainingStartDate" name="editTrainingStartDate" required>
          </div>
          <div class="mb-3">
            <label for="editTrainingEndDate" class="form-label">End Date</label>
            <input type="date" class="form-control" id="editTrainingEndDate" name="editTrainingEndDate" required>
          </div>
          <input type="hidden" id="editTrainingID" name="editTrainingID">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
          onclick="resetEditModal()">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveTraining()">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
  function populateEditModal(training) {
    document.getElementById('editTrainingName').value = training.oldTrainingName;
    document.getElementById('editTrainingStartDate').value = training.oldStartDate;
    document.getElementById('editTrainingEndDate').value = training.oldEndDate;
    document.getElementById('editTrainingID').value = training.oldTrainingID;
  }

  function resetEditModal() {
    document.getElementById('editTrainingForm').reset();
    document.getElementById('editTrainingID').value = '';
  }

  function saveTraining() {
    const trainingID = document.getElementById('editTrainingID').value;
    const trainingName = document.getElementById('editTrainingName').value;
    const startDate = document.getElementById('editTrainingStartDate').value;
    const endDate = document.getElementById('editTrainingEndDate').value;

    if (!trainingName || !startDate || !endDate) {
      alert('Please fill in all fields.');
      return;
    }

    $.ajax({
      url: 'components/updateOldTraining.php',
      type: 'POST',
      data: {
      trainingID: trainingID,
      trainingName: trainingName,
      startDate: startDate,
      endDate: endDate
      },
      success: function (response) {
      console.log(response);
      alert('Training updated successfully.');
      window.location.reload();
      },
      error: function (xhr, status, error) {
      console.error('Error:', error);
      console.error('Status:', status);
      console.error('Response:', xhr.responseText);
      alert('An error occurred while updating the training.');
      }
    });
  }
</script>

<div class="modal fade" id="importTrainingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="importTrainingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="importTrainingModalLabel">Import Training</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <span>
            <strong>Note:</strong> Please ensure that the file you are importing is in the correct format. The file
            should be a CSV or Excel file.
          </span>
        </div>
        <input type="file" class="form-control" id="importTrainingFile" accept=".csv, .xlsx, .xls">
        <div class="mt-3 text-center">
          <button type="button" class="btn btn-primary" id="importTrainingBtn"
            onclick="importOldTraining()">Import</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function importOldTraining() {
    const importTrainingBtn = document.getElementById("importTrainingBtn");
    importTrainingBtn.innerHTML = "Importing...";
    importTrainingBtn.disabled = true;
    const fileInput = document.getElementById("importTrainingFile");
    const file = fileInput.files[0];
    if (!file) {
      alert("Please select a file to import.");
      importTrainingBtn.innerHTML = "Import";
      importTrainingBtn.disabled = false;
      return;
    }
    const formData = new FormData();
    formData.append("file", file);
    $.ajax({
      url: "components/importOldTraining.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        console.log(response);
        importTrainingBtn.innerHTML = "Import";
        importTrainingBtn.disabled = false;
        window.location.reload(); // Reload the page to see the new data
      },
      error: function () {
        alert("An error occurred while importing the file.");
        importTrainingBtn.innerHTML = "Import";
        importTrainingBtn.disabled = false;
      }
    });
  }
</script>