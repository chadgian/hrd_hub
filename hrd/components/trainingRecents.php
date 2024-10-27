<div class='recent-acts'>
  <div class='recent-header'>
    <span class="recent-header-title">Training Notifications</span>
    <span class="recent-filter">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel"
        viewBox="0 0 16 16" data-bs-toggle="dropdown" aria-expanded="false">
        <path
          d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z" />
      </svg>
      <ul class="dropdown-menu">
        hello
      </ul>
    </span>
  </div>
  <div class='recent-content'>
    <div id="recentLoader" style="display: flex; justify-content: center; width: 100%;">
      <img src="assets/images/loading.svg" alt="" width="25%">
    </div>
  </div>
  <div class='recent-footer'>
    <div class="recent-duration">Read all</div>
  </div>
</div>

<div class="modal fade" id="regDetailModal" tabindex="-1" aria-labelledby="regDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content regDetails-modal">
      <div class="modal-header regDetails-header">
        <h1 class="modal-title fs-5" id="regDetailModalLabel"><b>Registration Details</b></h1>
        <button type="button" class="btn-close close-regDetailsModal" data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body" id="regDetailModalContent">
        <h5 class="regDetails-trainingName"><?php echo $trainingNameView; ?></h5>
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
        <div class="row mb-3">
          <div class="col-md-6 d-flex justify-content-center align-items-center">
            <button class="pax-btn pax-btn-replace" data-bs-target="#replacePaxModal"
              data-bs-toggle="modal">Replace</button>
          </div>
          <div class="col-md-6 d-flex justify-content-center align-items-center">
            <button class="pax-btn pax-btn-delete" data-bs-target="#deletePaxModal"
              data-bs-toggle="modal">Delete</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="registrationID">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ignore</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="registrationBtn"></button>
      </div>
    </div>
  </div>
</div>

<!-- Replace Pax modal -->

<div class="modal fade" id="replacePaxModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="replacePaxModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="replacePaxModalLabel">Replace Participant</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>Replace: <span id="pax-name"></span></div>
        <div><span>Replaced by: </span><input type="text" name="searchReplace" id="searchReplace" />
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Agency</th>
                <th scope="col">Select</th>
              </tr>
            </thead>
            <tbody id="pax-table-body">

            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="replaceButton" onclick="executeReplacement()"
          disabled>Replace</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Pax Modal -->


<!-- Modal -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="loadingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered d-flex justify-content-center align-items-center">
    <img src="assets/images/loading.svg" alt="">
  </div>
</div>

<script>
  $(document).ready(function () {
    setTrainingRecent();

    setInterval(() => {
      setTrainingRecent();
    }, 10000);
  });

  function setTrainingRecent() {
    $.ajax({
      url: 'components/trainingRecentsReload.php',
      method: 'GET',
      data: { trainingID: <?php echo $id; ?> },
      success: function (response) {
        // Insert the HTML content into the div
        $('#recentLoader').hide();
        $('.recent-content').html(response);
      },
      error: function () {
        console.error('Error fetching content');
      }
    });
  }

  document.getElementById("searchReplace").addEventListener("input", getEmployees);

  function getRegDetails(id) {
    // Show the loading modal
    // $("#loadingModal").modal("show");

    const bodyDom = document.querySelector('body')
    bodyDom.style.cursor = "wait";

    // Set the registration ID value
    $("#registrationID").val(id);

    $.ajax({
      url: 'components/fetchRegistrationDetails.php',
      type: 'GET',
      data: { registrationID: id, trainingID: <?php echo $id; ?> },
      success: function (data) {
        try {
          // Parse the JSON data
          const details = JSON.parse(data);

          // Populate the form with fetched details
          $("#pax-name").text(details.name);
          $('#participantName').val(details.name);
          $('#participantSex').val(details.sex);
          $('#participantAge').val(details.age);
          $('#participantCivilStatus').val(details.civilStatus);
          $('#participantNumber').val(details.phoneNumber);
          $('#participantEmail').val(details.email);
          $('#participantPosition').val(details.position);
          $('#participantSector').val(details.sector);
          $('#participantAgency').val(details.agency);
          $('#participantFO').val(details.fo);
          $('#participantDate').val(details.timeDate);
          $('#participantFoodRestrictions').val(details.foodRestriction);
          $('#participantComfirmationSlip').attr('href', details.confirmationSlip);
          $('#employeeID').val(details.employeeID);

          // Determine the current state of activityRead and update the button accordingly
          const currentState = details.activityRead === 0;
          const newState = !currentState;
          $("#registrationBtn")
            .attr("onclick", `readActivity(${newState ? 0 : 1})`)
            .text(currentState ? "Read" : "Unread");

          $("#regDetailModal").modal("show");
        } catch (e) {
          console.error('Error parsing data:', e);
        }
        bodyDom.style.cursor = "default";

        getEmployees();
        // Hide the loading modal
        // $("#loadingModal").modal("hide");
      },
      error: function () {
        console.error('Error fetching content.');
        // $("#loadingModal").modal("hide");
      }
    });
  }

  function getEmployees() {
    const employeeID = $("#employeeID").val();
    const search = $("#searchReplace").val();
    $.ajax({
      url: 'components/getAllEmployees.php',
      type: 'GET',
      data: { employeeID: employeeID, search: search },
      success: function (data) {
        // console.log(data);
        const employeeList = JSON.parse(data);

        const tbody = document.getElementById("pax-table-body");
        tbody.innerHTML = "";

        employeeList.forEach(employee => {
          // console.log(employee.fullName);

          const row = document.createElement("tr");

          const cellName = document.createElement("td");
          cellName.textContent = employee.fullName;
          row.appendChild(cellName);

          const cellAgency = document.createElement("td");
          cellAgency.textContent = employee.agency;
          row.appendChild(cellAgency);

          const cellSelect = document.createElement("td");
          const radioButton = document.createElement("input");
          radioButton.type = "radio";
          radioButton.name = "selectEmployee";
          radioButton.value = employee.employeeID;
          cellSelect.appendChild(radioButton);
          row.appendChild(cellSelect);

          row.addEventListener("click", () => {
            radioButton.checked = true;
            $("#replaceButton").prop("disabled", false);
          });

          tbody.appendChild(row);

        });
      }
    })
  }

  function executeReplacement() {
    const selectedEmployee = document.querySelector('input[name="selectEmployee"]:checked');
    const employeeID = selectedEmployee.value;
    const oldEmployee = $('#employeeID').val();
    const trainingID = <?php echo $id; ?>;

    $.ajax({
      type: 'POST',
      url: 'replaceEmployee.php',
      data: {
        employeeID: employeeID,
        oldEmployee: oldEmployee,
        trainingID: trainingID
      },
      success: function (data) {
        console.log(data);
      }
    })

  }

  function readActivity(read) {
    const registrationID = $("#registrationID").val();

    $.ajax({
      url: 'components/changeTrainingActivityRead.php',
      type: 'GET',
      data: { registrationID: registrationID, read: read },
      success: function (data) {
        if (data != 'ok') {
          console.log(data);
        } else {
          setTrainingRecent();
        }
      }
    })
  }
</script>