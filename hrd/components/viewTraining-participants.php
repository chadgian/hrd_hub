<div class="participantTable-container">
  <h3 style="font-weight: bold; color: #57000; display: flex; align-items: center;">Participants
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person"
      viewBox="0 0 16 16">
      <path
        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
    </svg>
  </h3>
  <div class="participantTable-menu">
    <div class="d-flex gap-2">
      <div class="dropdown">
        <button class="dropdown-toggle addParticipantBtn" data-bs-toggle="dropdown" aria-expanded="false">
          Menu
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPaxModal">Add Participant</a>
          </li>
          <li><a class="dropdown-item" href="#" onclick="finalizeParticipants(<?php echo $id; ?>)">Finalized
              Participants</a>
          </li>
          <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#generateIDModal" href="#">Generate ID</a>
          </li>
          <li><a class="dropdown-item" href="#">Export Attendance</a></li>
        </ul>
      </div>
      <div>
        <input type="search" placeholder="Search" class="searchParticipant" id="searchParticipant">
      </div>
    </div>
    <div class="switchParticipantView" onclick="switchTable('0')">View Attendance</div>
  </div>

  <table id="participantTable" style="width: 100%;">
    <thead>
      <tr>
        <th>No.</th>
        <th>Name</th>
        <th>Agency</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody id="participantTable-body">

    </tbody>
  </table>

  <!-- add pax modal -->
  <div class="modal fade" id="addPaxModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addPaxModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addPaxModalLabel">Add Participant</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="selectAdd">
            <button class="newParticipant btn btn-primary" onclick="selectAddEmployee(0)">Register New Employee</button>
            <button class="oldParticipant btn btn-primary" onclick="selectAddEmployee(1)">Select existing
              Employee</button>
          </div>
          <div class="addNewEmployee">
            Add New Employee
            <button class="btn btn-primary" onclick="selectAddEmployee(2)">Back</button>
          </div>
          <div class="selectExistingEmployee">
            <div class="d-flex flex-column">
              Select Existing Employee
              <input type="search" id="searchExistingEmployee">
              <div style="max-height: 300px; overflow-y: scroll; overflow-x: auto;">
                <table class="table table-striped">
                  <thead style="position: sticky; z-index: 1; top: 0;">
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Agency</th>
                      <th scope="col">Select</th>
                    </tr>
                  </thead>
                  <tbody id="selectPaxTableBody">

                  </tbody>
                </table>
              </div>
              <div>
                <label for="manualAddConfSlip" class="form-label">Confirmation Slip:</label>
                <input type="file" class="form-control" id="manualAddConfSlip">
              </div>
              <button class="btn btn-primary mt-3" onclick="selectAddEmployee(2)">Back</button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
            onclick="selectAddEmployee(-1)">Cancel</button>
          <button type="button" id="saveAddPaxBtn" class="btn btn-primary" onclick="addPax()">Save</button>
        </div>
      </div>
    </div>
  </div>

  <!-- genate id modal -->
  <div class="modal fade" id="generateIDModal" tabindex="-1" aria-labelledby="generateIDModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="generateIDModalLabel">Generate ID</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="generateID-group mb-2">
            <label for="numberofline">Training Name:</label>
            <select name="numberofline" id="numberofline">
              <option value="1">One Line</option>
              <option value="2">Two Lines</option>
            </select>
          </div>
          <div id="line1-container" class="generateID-group">
            <label for="line1">First Line:</label>
            <input type="text" id="line1" name="line1">
          </div>
          <div id="line2-container" class="generateID-group">
            <label for="line2">Second Line:</label>
            <input type="text" id="line2" name="line2">
          </div>
          <span style="text-align: center; width: 100%;">
            <?php
            $generatedID = "assets/generated_ids/training-$id.docx";

            if (file_exists($generatedID)) {
              echo "<a href='$generatedID' download>Download all generated IDs.</a>";
            } else {
              echo "No generated ID yet.";
            }
            ?>
          </span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="generateID(<?php echo $id; ?>)">Generate ID</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="updateStatusModal" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header p-2 px-3">
          <div class="d-flex flex-column">
            <h1 class="modal-title fs-5" id="updateStatusModalLabel"><span id="updateStatus-name">Modal title</span>
            </h1>
            <small id="updateStatus-agency">Subtitle</small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body updateStatusModal-body">
          <div id="updateStatusContent"
            style="display: flex;justify-content: center;align-items: center;width: 100%;flex-direction: column;">
            <div class="status-list">
              <div class="status-list-title">
                ATTENDANCE
              </div>
              <div class="status-list-toggle">
                <div class="attendance-radio">
                  <input type="radio" id="complete" name="attendanceStatus" value="1">
                  <label for="complete" style="border-radius: 10px 0 0 10px;">Complete</label>
                </div>
                <div class="attendance-radio">
                  <input type="radio" id="incomplete" name="attendanceStatus" value="0">
                  <label for="incomplete" style="border-radius: 0 10px 10px 0;">Incomplete</label>
                </div>
              </div>
            </div>
            <div class="status-list" id="attendanceRemark-container">
              <div class="status-list-title">Remark:</div>
              <div class="status-list-toggle d-flex flex-column gap-2">
                <select name="attendanceRemark" id="attendanceRemark">
                  <option value="0">Replaced</option>
                  <option value="1">Valid Cancellation</option>
                  <option value="2">Invalid Cancellation</option>
                  <option value="3">Lack of training hours</option>
                  <option value="4">Absent/No Show</option>
                  <option value="5">Others</option>
                </select>
                <input type="text" name="other-attendanceRemark" id="other-attendanceRemark">
              </div>
            </div>

            <?php
            $trainingOutputStmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
            $trainingOutputStmt->bind_param("i", $id);

            if ($trainingOutputStmt->execute()) {
              $trainingOutputResult = $trainingOutputStmt->get_result();

              if ($trainingOutputResult->num_rows > 0) {
                $trainingOutputData = $trainingOutputResult->fetch_assoc();
                $requiredOutput = $trainingOutputData['requiredDocs'];

                if ($requiredOutput == "1") {
                  echo "
                  <div class='status-list' id='output-status'>
                    <div class='status-list-title'>
                      OUTPUT
                    </div>
                    <div class='status-list-toggle'>
                      <div class='output-radio'>
                        <input type='radio' id='submitted' name='outputStatus' value='1'>
                        <label for='submitted' style='border-radius: 10px 0 0 10px;'>Submitted</label>
                      </div>
                      <div class='output-radio'>
                        <input type='radio' id='unsubmitted' name='outputStatus' value='0'>
                        <label for='unsubmitted' style='border-radius: 0 10px 10px 0;'>Unsubmitted</label>
                      </div>
                    </div>
                  </div>
                ";
                } else {
                  echo "";
                }
              } else {
                echo "Training ID not found.";
              }
            } else {
              echo $trainingOutputStmt->error;
            }
            ?>
            <div class="status-list">
              <div class="status-list-title">
                PAYMENT
              </div>
              <div class="status-list-toggle">
                <div class="payment-radio">
                  <input type="radio" id="paid" name="paymentStatus" value="1">
                  <label for="paid" style="border-radius: 10px 0 0 10px;" onclick="showPaymentDetails(1)">Paid</label>
                </div>
                <div class="payment-radio">
                  <input type="radio" id="unpaid" name="paymentStatus" value="0">
                  <label for="unpaid" style="border-radius: 0 10px 10px 0;"
                    onclick="showPaymentDetails(0)">Unpaid</label>
                </div>
              </div>
            </div>
            <div class="payment-details">
              <input type="hidden" id="participantID">
              <div class="row">
                <div class="col-md-6">
                  <span>OR Number:</span>
                  <input type="text" name="orNumber" id="orNumber" disabled>
                </div>
                <div class="col-md-6">
                  <span>Date of Payment:</span>
                  <input type="date" name="paymentDate" id="paymentDate" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <span>Field Office:</span>
                  <select name="fo" id="fo" disabled>
                    <option value="0">Choose field office...</option>
                    <option value="csc">Regional Office VI</option>
                    <option value="aklan">FO - Aklan</option>
                    <option value="antique">FO - Antique</option>
                    <option value="capiz">FO - Capiz</option>
                    <option value="guimaras">FO - Guimaras</option>
                    <option value="iloilo">FO - Iloilo</option>
                    <option value="negros">FO - Negros Occidental</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <span>Collecting Officer:</span>
                  <select name="co" id="co" disabled>
                    <option value="0">Choose collecting officer...</option>
                    <option value="chad">Chad Gian</option>
                    <option value="nicca">Nicca Mae</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <span>Amount Paid:</span>
                  <input type="number" name="amount" id="amount" disabled>
                </div>
                <div class="col-md-6">
                  <span>Discount:</span>
                  <input type="number" name="discount" id="discount" disabled>
                </div>
              </div>
              <div class="row" id="editPaymentDetails">
                <button onclick="editPaymentDetails(1)" class="editPaymentDetails-btn">Edit
                  Payment Status</button>
              </div>
              <div class="row" id="savePaymentDetails" style="display: none; margin-top: 1em;">
                <button onclick="savePaymentDetails()" class="savePayment-btn">Save Details</button>
                <button id="paymentDetails-close-btn" class='closePayment-btn'
                  onclick="editPaymentDetails(0)">Cancel</button>
              </div>
            </div>
            <div class="status-list d-flex flex-column gap-2 mt-2" style="width: 100%;">
              <div class="status-list-title">
                REMARKS
              </div>
              <div class="status-list-toggle">
                <textarea name="participantRemarks" id="participantRemarks" style="width: 100%;"></textarea>
              </div>
            </div>
          </div>
          <div id="updateStatusLoading" class="d-flex align-items-center justify-content-center">
            <img src="assets/images/loading2.gif" alt="" width="50%">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
            onclick="participantStatusClose()">Close</button>
          <button type="button" class="btn btn-primary" onclick="updateParticipantStatus()">Save changes</button>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="attendanceTable-container">
  <h3 style="font-weight: bold; color: #57000; display: flex; align-items: center;">Attendance
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-lines-fill"
      viewBox="0 0 16 16">
      <path
        d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
    </svg>
  </h3>
  <div class="participantTable-menu">
    <div class="d-flex gap-2">
      <div>
        <label for="sortAttendance">Sort by:</label>
        <select name="sortAttendance" id="sortAttendance">
          <option value="2">No.</option>
          <option value="3">Name</option>
          <option value="4">Login</option>
          <option value="5">Logout</option>
        </select>
      </div>
      <div>
        <input type="search" placeholder="Search" class="searchAttendance" id="searchAttendance">
      </div>
    </div>
    <div class="d-flex gap-2">
      <?php
      $getTrainingDayStmt = $conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
      $getTrainingDayStmt->bind_param("s", $id);

      if ($getTrainingDayStmt->execute()) {
        $getTrainingDayResult = $getTrainingDayStmt->get_result();

        if ($getTrainingDayResult->num_rows > 0) {
          while ($getTrainingDayData = $getTrainingDayResult->fetch_assoc()) {
            $startDate = new DateTime($getTrainingDayData['startDate']);
            $endDate = new DateTime($getTrainingDayData['endDate']);

            $numberOfDaysRaw = $endDate->diff($startDate);

            $numberOfDays = $numberOfDaysRaw->days + 1;

            for ($i = 1; $i <= $numberOfDays; $i++) {
              echo "
              <div class='attendanceDay' id='attendanceDay-$i'>Day $i</div>
              ";
            }
          }
        }
      }
      ?>
    </div>
    <div class="switchParticipantView" onclick="switchTable('1')">View Participants</div>
  </div>
  <table id="attendanceTable" style="width: 100%">
    <thead>
      <tr>
        <th>No.</th>
        <th>Name</th>
        <th>Login</th>
        <th>Logout</th>
        <th>Action </th>
      </tr>
    </thead>
    <tbody id="attendanceTable-body">

    </tbody>
  </table>


</div>

<!-- Generating ID Modal -->
<div class="modal fade" id="generatingIDModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="generatingIDModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5>Generating ID</h5>
        <div id="response"></div>
        <button data-bs-dismiss="modal" aria-label="Close" id="generateID-close">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script> -->

<script>
  $(document).ready(function () {
    populateParticipantTable();
    populateAttendanceTable('0', '1', '');
    $(".attendanceTable-container").hide();

    const day1 = document.getElementById("attendanceDay-1");

    day1.classList.add("selected");
    day1.style.borderBottom = '2px solid black';

    let selectedElement = document.getElementById("attendanceDay-1");

    // Get all elements with the class 'item'
    const items = document.querySelectorAll('.attendanceDay');

    // Add event listener to each item
    items.forEach(item => {
      item.addEventListener('click', function () {
        // If there's a previously selected element, reset its border
        if (selectedElement) {
          selectedElement.classList.remove('selected');
          selectedElement.style.borderBottom = '1px solid grey';
        }

        // Set the new selected element's border to 2px
        this.classList.add('selected');
        this.style.borderBottom = '2px solid black';

        // Update the selectedElement variable
        selectedElement = this;

        const currentID = this.id.split("-")[1];

        const searchQuery = $("#searchAttendance").val();

        populateAttendanceTable($("#sortAttendance").val(), currentID, searchQuery);
      });
    });
  });

  document.getElementById("searchParticipant").addEventListener("input", function (e) {
    var search = e.target.value.toLowerCase();

    $.ajax({
      url: 'components/fetchParticipantTable.php',
      type: 'POST',
      data: { id: <?php echo $id; ?>, search: search },
      success: function (data) {
        $("#participantTable-body").html(data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('checking employee record error: ', textStatus, errorThrown);
      }
    });
  });

  function switchTable(table) {
    //0 is for attendance. 1 is for participant
    switch (table) {
      case '0':
        $(".attendanceTable-container").show();
        $(".participantTable-container").hide();
        break;

      default:
        $(".attendanceTable-container").hide();
        $(".participantTable-container").show();
        break;
    }
  }

  function showOR(show, id) {
    if (show == 1) {
      document.getElementById("receiptNumber-" + id).style.display = "flex";
    } else {
      document.getElementById("receiptNumber-" + id).style.display = "none";
    }
  }

  function autoResize(id) {
    const textarea = document.getElementById('remarks-' + id);
    textarea.style.height = 'auto'; // Reset the height
    textarea.style.height = textarea.scrollHeight + 'px'; // Set it to the scroll height
  }

  function fillStatusField(parsedData) {
    if (parsedData.attendance == 1) {
      $("#complete").prop('checked', true);
      $("#attendanceRemark-container").hide();
    } else {
      $("#incomplete").prop('checked', true);
      $("#attendanceRemark-container").show();
    }

    if (parsedData.outputs == 1) {
      $('#submitted').prop('checked', true);
    } else {
      $('#unsubmitted').prop('checked', true);
    }

    if (parsedData.payment == 1) {
      $('#paid').prop('checked', true);
      $('.payment-details').show();
    } else {
      $('#unpaid').prop('checked', true);
      $('.payment-details').hide();
    }

    let attendanceRemark = parsedData.attendanceRemark.split("::")[0];
    if (attendanceRemark == "5") {
      $("#other-attendanceRemark").val(parsedData.attendanceRemark.split("::")[1]);
      $("#other-attendanceRemark").show();
    } else {
      $("#other-attendanceRemark").hide();
    }

    $("#attendanceRemark").val(attendanceRemark);

    $('#orNumber').prop('value', parsedData.receiptNumber);
    $('#co').prop('value', parsedData.co);
    $('#fo').prop('value', parsedData.fo);
    $('#paymentDate').prop('value', parsedData.paymentDate);
    $('#amount').prop('value', parsedData.amount);
    $('#discount').prop('value', parsedData.discount);
    $('#participantID').prop('value', parsedData.participantID);
    $("#participantRemarks").html(parsedData.remarks);

    $("#attendanceRemark").change(function () {
      if ($("#attendanceRemark").val() == "5") {
        $("#other-attendanceRemark").show();
      } else {
        $("#other-attendanceRemark").val('');
        $("#other-attendanceRemark").hide();
      }
    });

    $("input[name='attendanceStatus']").change(function () {
      if ($(this).val() == "0") {
        $("#attendanceRemark-container").show();
      } else {
        $("#attendanceRemark-container").hide();
      }
    });

    $("#updateStatus-name").html(parsedData.name);
    $("#updateStatus-agency").html(parsedData.agency);

  }

  function showStatusDetails(participantID) {
    console.log(participantID);
    $("#updateStatusContent").hide();
    $("#updateStatusLoading").removeClass("d-none");

    $.ajax({
      url: 'components/fetchParticipantStatus.php',
      type: 'POST',
      data: { id: participantID },
      success: function (data) {
        try {

          const parsedData = JSON.parse(data);
          paymentDetailsValue = {};
          $("#updateStatusLoading").addClass("d-none");
          fillStatusField(parsedData);
          $("#updateStatusContent").show();

        } catch (e) {
          console.error(e);
          console.log(data);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('checking employee record error: ', textStatus, errorThrown);
      }
    });

  }

  function showPaymentDetails(paymentStatus) {
    if (paymentStatus == 1) {
      $('.payment-details').show();
    } else {
      $('.payment-details').hide();
    }
  }

  function updateStatus(participantID) {
    //statusType -
    //  0: attendance
    //  1: payment
    //  2: output

    const attendanceRadios = document.getElementsByName("attendance-radio-" + participantID);
    const paymentRadios = document.getElementsByName("payment-radio-" + participantID);
    let attendance = "";
    let payment = "";

    for (const radio of attendanceRadios) {
      if (radio.checked) {
        attendance = radio.value;
        break;
      }
    }

    for (const radio of paymentRadios) {
      if (radio.checked) {
        payment = radio.value;
        break;
      }
    }


    console.log(`attendance = ${attendance}, payment = ${payment}`);

    // const status = ['attendance', 'payment', 'outputs'];

    // console.log(`id = ${participantID}, status = ${status[statusType]}, value = ${value}`);

    // $.ajax({
    //   url: 'components/changeStatus.php',
    //   type: 'POST',
    //   data: {
    //     type: status[statusType],
    //     participantID: participantID,
    //     value: value
    //   },
    //   success: function (data) {
    //     if (data != "ok") {
    //       console.log(data);
    //     } else {
    //       populateParticipantTable();
    //       console.log(data);
    //     }
    //   },
    //   error: function (jqXHR, textStatus, errorThrown) {
    //     console.error('checking employee record error: ', textStatus, errorThrown);
    //   }
    // });
  }

  function populateParticipantTable() {
    $.ajax({
      url: 'components/fetchParticipantTable.php',
      type: 'POST',
      data: { id: <?php echo $id; ?> },
      success: function (data) {
        $("#participantTable-body").html(data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('error: ', textStatus, errorThrown);
      }
    });
  }

  function populateAttendanceTable(type, day, search) {
    $.ajax({
      url: 'components/fetchAttendanceTable.php',
      type: 'POST',
      data: {
        id: <?php echo $id; ?>,
        day: day,
        type: type,
        search: search
      },
      success: function (data) {
        $("#attendanceTable-body").html(data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('error: ', textStatus, errorThrown);
      }
    });
  }

  let paymentDetailsValue = {};

  function editPaymentDetails(toggle) {
    if (toggle == 1) {
      $("#payment-details").css("background-color", "#effaf2");
      $(".payment-details").css("border", "1px solid #00A52E")
      $(".payment-details").css("border-top", "2em solid #00A52E");
      $('#savePaymentDetails').show();
      $('#editPaymentDetails').hide();

      paymentDetailsValue['orNumber'] = $('#orNumber').val();
      paymentDetailsValue['co'] = $('#co').val();
      paymentDetailsValue['fo'] = $('#fo').val();
      paymentDetailsValue['paymentDate'] = $('#paymentDate').val();
      paymentDetailsValue['amount'] = $('#amount').val();
      paymentDetailsValue['discount'] = $('#discount').val();

      $("#orNumber").prop('disabled', false);
      $("#co").prop('disabled', false);
      $("#fo").prop('disabled', false);
      $("#paymentDate").prop('disabled', false);
      $("#amount").prop('disabled', false);
      $("#discount").prop('disabled', false);
    } else {
      $("#payment-details").css("background-color", "#eff5ff");
      $(".payment-details").css("border", "1px solid #093980")
      $(".payment-details").css("border-top", "2em solid #093980");
      $('#savePaymentDetails').hide();
      $('#editPaymentDetails').show();

      $('#orNumber').prop('value', paymentDetailsValue['orNumber']);
      $('#co').prop('value', paymentDetailsValue['co']);
      $('#fo').prop('value', paymentDetailsValue['fo']);
      $('#paymentDate').prop('value', paymentDetailsValue['paymentDate']);
      $('#amount').prop('value', paymentDetailsValue['amount']);
      $('#discount').prop('value', paymentDetailsValue['discount']);

      $("#orNumber").prop('disabled', true);
      $("#co").prop('disabled', true);
      $("#fo").prop('disabled', true);
      $("#paymentDate").prop('disabled', true);
      $("#amount").prop('disabled', true);
      $("#discount").prop('disabled', true);
    }
  }

  function participantStatusClose() {
    document.getElementById("paymentDetails-close-btn").click();
  }

  function savePaymentDetails() {
    const participantID = $('#participantID').val();
    const orNumber = $('#orNumber').val();
    const co = $('#co').val();
    const fo = $('#fo').val();
    const paymentDate = $('#paymentDate').val();
    const amount = $('#amount').val();
    const discount = $('#discount').val();

    $.ajax({
      url: 'components/updatePaymentDetails.php',
      type: 'POST',
      data: {
        id: participantID,
        orNumber: orNumber,
        co: co,
        fo: fo,
        paymentDate: paymentDate,
        amount: amount,
        discount: discount
      },
      success: function (data) {
        if (data == 'ok') {
          $('#savePaymentDetails').hide();
          $('#editPaymentDetails').show();

          $("#orNumber").prop('disabled', true);
          $("#co").prop('disabled', true);
          $("#fo").prop('disabled', true);
          $("#paymentDate").prop('disabled', true);
          $("#amount").prop('disabled', true);
          $("#discount").prop('disabled', true);
        } else {
          alert('Error saving payment details: ' + data);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('checking employee record error: ', textStatus, errorThrown);
      }
    });
  }

  function updateParticipantStatus() {
    const participantID = $('#participantID').val();

    const attendanceRadios = document.getElementsByName("attendanceStatus");
    const outputRadios = document.getElementsByName("outputStatus");
    const paymentRadios = document.getElementsByName("paymentStatus");
    let attendance = "";
    let outputs = "";
    let payment = "";

    for (const radio of attendanceRadios) {
      if (radio.checked) {
        attendance = radio.value;
        break;
      }
    }

    for (const radio of outputRadios) {
      if (radio.checked) {
        outputs = radio.value;
        break;
      }
    }

    for (const radio of paymentRadios) {
      if (radio.checked) {
        payment = radio.value;
        break;
      }
    }

    let attendanceRemark;
    if (attendance == "0") {
      attendanceRemark = ($("#attendanceRemark").val() == "5") ? "5::" + $("#other-attendanceRemark").val() : $("#attendanceRemark").val();
    } else {
      attendanceRemark = "";
    }


    console.log("id: " + participantID + ", attendance: " + attendance + ", outputs: " + outputs + ", payment: " + payment);

    $.ajax({
      url: 'components/updateParticipantStatus.php',
      type: 'POST',
      data: {
        participantID: participantID,
        attendance: attendance,
        attendanceRemark: attendanceRemark,
        outputs: outputs,
        payment: payment,
        remarks: $("#participantRemarks").val()
      },
      success: function (data) {
        if (data !== "ok") {
          alert(data);
        } else {
          populateParticipantTable();
          $('#updateStatusModal').modal('toggle');
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error('checking employee record error: ', textStatus, errorThrown);
      }
    });
  }

  function editAttendance(attendanceID, login, logout) {
    $("#attendance-edit-" + attendanceID).hide();
    $("#attendance-save-" + attendanceID).show();
    $("#attendance-close-" + attendanceID).show();

    $("#login-" + attendanceID).html(`<input type='time' id='newLogin-${attendanceID}' value='${login}' class='attendance-time-input'>`);
    $("#logout-" + attendanceID).html(`<input type='time' id='newLogout-${attendanceID}' value='${logout}' class='attendance-time-input'>`);

    $(".attendance-time-input").on("keydown", function (e) {
      const attendanceID = this.id.split("-")[1];
      if (e.key === "Enter") {
        updateAttendance(attendanceID, getCurrentDay());
      } else if (e.key === "Escape") {
        cancelUpdateAttendance(attendanceID);
      }
    });
  }

  function updateAttendance(attendanceID, day) {
    $("#attendance-edit-" + attendanceID).show();
    $("#attendance-save-" + attendanceID).hide();
    $("#attendance-close-" + attendanceID).hide();

    const newLogin = ($("#newLogin-" + attendanceID).val() == "00:00") ? "" : $("#newLogin-" + attendanceID).val();
    const newLogout = ($("#newLogout-" + attendanceID).val() == "00:00") ? "" : $("#newLogout-" + attendanceID).val();

    $.ajax({
      url: 'components/updateAttendance.php',
      type: 'POST',
      data: {
        attendanceID: attendanceID,
        newLogin: newLogin,
        newLogout: newLogout
      },
      success: function (data) {
        if (data == "ok") {
          if (newLogin == '') {
            $("#login-" + attendanceID).html("-----------");
          } else {
            $("#login-" + attendanceID).html(newLogin);
          }

          if (newLogout == '') {
            $("#logout-" + attendanceID).html("-----------");
          } else {
            $("#logout-" + attendanceID).html(newLogout);
          }

          $("#attendance-edit-" + attendanceID).attr("onclick", `editAttendance(${attendanceID}, '${newLogin}', '${newLogout}')`);
        } else {
          alert(data);
        }
      }
    });
  }

  function cancelUpdateAttendance(attendanceID) {
    $("#attendance-edit-" + attendanceID).show();
    $("#attendance-save-" + attendanceID).hide();
    $("#attendance-close-" + attendanceID).hide();

    const login = ($("#newLogin-" + attendanceID).val() == "00:00") ? "" : $("#newLogin-" + attendanceID).val();
    const logout = ($("#newLogout-" + attendanceID).val() == "00:00") ? "" : $("#newLogout-" + attendanceID).val();

    if (login == '') {
      $("#login-" + attendanceID).html("-----------");
    } else {
      $("#login-" + attendanceID).html(login);
    }

    if (logout == '') {
      $("#logout-" + attendanceID).html("-----------");
    } else {
      $("#logout-" + attendanceID).html(logout);
    }
  }

  $("#searchAttendance").on("input", function (e) {
    // Get all elements with the class 'item'


    populateAttendanceTable("1", getCurrentDay(), this.value);
  })

  function getCurrentDay() {
    const items = document.querySelectorAll('.attendanceDay');

    let day = 1;
    // Add event listener to each item
    items.forEach(item => {
      // If there's a previously selected element, reset its border
      if (item.classList.contains('selected')) {
        day = item.id.split("-")[1];
      }
    });

    return day;
  }

  $("#sortAttendance").change(function () {
    populateAttendanceTable(this.value, getCurrentDay(), $("#searchAttendance").val());
  });

  function generateID(trainingID) {
    $("#generatingIDModal").modal("show");
    $("#generateIDModal").modal("toggle");
    $("#generateID-close").hide();
    $("#response").hide();

    let trainingName = "";

    switch ($("#numberofline").val()) {
      case "1":
        trainingName = $("#line1").val();
        break;

      case "2":
        trainingName = $("#line1").val() + "//" + $("#line2").val();
        break;

      case "3":
        trainingName = $("#line1").val() + "//" + $("#line2").val() + "//" + $("#line3").val();
        break;
    }
    console.log(trainingName);
    $.ajax({
      url: 'components/generateID.php',
      type: 'POST',
      data: { trainingID: trainingID, trainingName: trainingName },
      success: function (response) {
        if (response == "ok") {
          $("#generatingIDModal").modal("hide");
        } else {
          $("#generateID-close").show();
          $("#response").show();
          $("#response").html(response);
        }
      }
    })
  }

  $("#numberofline").change(displayTrainingNameLine);
  displayTrainingNameLine();

  function displayTrainingNameLine() {
    const lines = $("#numberofline").val();
    console.log(lines);
    switch (lines) {
      case "1":
        $("#line1-container").show();
        $("#line2-container").hide();
        $("#line3-container").hide();
        break;

      case "2":
        $("#line1-container").show();
        $("#line2-container").show();
        $("#line3-container").hide();
        break;

      case "3":
        $("#line1-container").show();
        $("#line2-container").show();
        $("#line3-container").show();
        break;
    }
  }

  function finalizeParticipants(trainingID) {
    console.log("Finalizing partiicpats");
    $.ajax({
      url: 'components/finalizeParticipants.php',
      type: 'POST',
      data: { id: trainingID },
      success: function (response) {
        if (response == "ok") {
          alert("Done Finalizing Participants");
          populateParticipantTable();
        } else {
          alert(response);
        }
      }
    })
  }

  selectAddEmployee(-1);
  function selectAddEmployee(type) {
    const sections = {
      addNewEmployee: $(".addNewEmployee"),
      selectAdd: $(".selectAdd"),
      selectExistingEmployee: $(".selectExistingEmployee")
    };

    // Hide all sections initially
    Object.values(sections).forEach(section => section.hide());

    switch (type) {
      case 0:
        // Display add new employee section
        sections.addNewEmployee.show();
        $("#saveAddPaxBtn").prop("disabled", false);
        break;
      case 1:
        // Display existing employee section
        sections.selectExistingEmployee.show();
        getEmployeesForSelect();
        break;
      case 2:
        // Display selection section
        sections.selectAdd.show();
        $("#saveAddPaxBtn").prop("disabled", true);
        break;
      default:
        // Default to showing the selection section
        sections.selectAdd.show();
        $("#saveAddPaxBtn").prop("disabled", true);
        break;
    }
  }

  document.getElementById("searchExistingEmployee").addEventListener("input", getEmployeesForSelect);

  function getEmployeesForSelect() {
    $("#saveAddPaxBtn").prop("disabled", true);
    const employeeID = "0";
    const search = $("#searchExistingEmployee").val();
    const trainingID = <?php echo $id; ?>;
    console.log("training ID: " + trainingID);
    $.ajax({
      url: 'components/getAllEmployees.php',
      type: 'GET',
      data: { employeeID: employeeID, search: search, trainingID: trainingID },
      success: function (data) {

        // if (Array.isArray(data)) {
        //   console.log("OKAY");
        // } else {
        //   console.log("NOT OKAY");
        // }

        console.log(data);
        const parsedData = JSON.parse(data);

        const employeeList = Object.values(parsedData);
        // const employeeList = data;

        console.log(employeeList);
        const tbody = document.getElementById("selectPaxTableBody");
        tbody.innerHTML = "";

        employeeList.forEach(employee => {
          console.log(employee.fullName);

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
          radioButton.name = "selectAddEmployee";
          radioButton.value = employee.employeeID;
          cellSelect.appendChild(radioButton);
          row.appendChild(cellSelect);

          row.addEventListener("click", () => {
            radioButton.checked = true;
            $("#saveAddPaxBtn").prop("disabled", false);
          });

          tbody.appendChild(row);

        });
      }
    })
  }

  function addPax() {
    const sections = {
      addNewEmployee: $(".addNewEmployee"),
      selectAdd: $(".selectAdd"),
      selectExistingEmployee: $(".selectExistingEmployee")
    };

    var visibleElement;

    for (const [key, section] of Object.entries(sections)) {
      if (section.is(":visible")) {
        visibleElement = key;
      }
    }
    const formData = new FormData();
    // console.log(visibleElement);
    const selectedEmployee = document.querySelector('input[name="selectAddEmployee"]:checked');
    const employeeID = selectedEmployee.value;
    const trainingID = <?php echo $id; ?>;
    const fileInput = document.getElementById("manualAddConfSlip");
    const confSlip = fileInput.files[0];

    formData.append('confSlip', confSlip);
    formData.append('addPaxType', visibleElement);
    formData.append('employeeID', employeeID);
    formData.append('trainingID', trainingID);
    formData.append('admin', "<?php echo $_SESSION['userID'] ?>");


    $.ajax({
      type: "POST",
      url: "components/addPaxTraining.php",
      data: formData,
      processData: false,  // Prevent jQuery from automatically transforming the data into a query string
      contentType: false,  // Prevent jQuery from setting a content type
      success: function (data) {
        console.log(data);
        if (data == "ok"){
          
        }
      }
    })
  }
</script>