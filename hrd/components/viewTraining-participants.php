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
      <small class="addParticipantBtn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
          fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
          <path
            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
          <path
            d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
        </svg> Add</small>
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
          <div class="status-list" id="output-status">
            <div class="status-list-title">
              OUTPUT
            </div>
            <div class="status-list-toggle">
              <div class="output-radio">
                <input type="radio" id="submitted" name="outputStatus" value="1">
                <label for="submitted" style="border-radius: 10px 0 0 10px;">Submitted</label>
              </div>
              <div class="output-radio">
                <input type="radio" id="unsubmitted" name="outputStatus" value="0">
                <label for="unsubmitted" style="border-radius: 0 10px 10px 0;">Unsubmitted</label>
              </div>
            </div>
          </div>
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
                <label for="unpaid" style="border-radius: 0 10px 10px 0;" onclick="showPaymentDetails(0)">Unpaid</label>
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
                <span>Field Office:</span>
                <select name="fo" id="fo" disabled>
                  <option value="0">Choose field office...</option>
                  <option value="aklan">FO - Aklan</option>
                  <option value="antique">FO - Antique</option>
                  <option value="capiz">FO - Capiz</option>
                  <option value="guimaras">FO - Guimaras</option>
                  <option value="iloilo">FO - Iloilo</option>
                  <option value="negros">FO - Negros Occidental</option>
                </select>
              </div>
              <div class="col-md-6">
                <span>Date of Payment:</span>
                <input type="date" name="paymentDate" id="paymentDate" disabled>
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
              <button onclick="editPaymentDetails(1)">Edit Payment Status</button>
            </div>
            <div class="row" id="savePaymentDetails" style="display: none;">
              <button onclick="savePaymentDetails()">Save Details</button>
              <button id="paymentDetails-close-btn" onclick="editPaymentDetails(0)">Cancel</button>
            </div>
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
      <small class="addParticipantBtn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
          fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
          <path
            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
          <path
            d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
        </svg> Add</small>
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
              <div class='attendanceDay' id='attendanceDay$i' onclick='populateAttendanceTable($i)'>Day $i</div>
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

<!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script> -->

<script>
  $(document).ready(function () {
    populateParticipantTable();
    populateAttendanceTable('1');
    $(".attendanceTable-container").hide();

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
    } else {
      $("#incomplete").prop('checked', true);
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

    $('#orNumber').prop('value', parsedData.receiptNumber);
    $('#co').prop('value', parsedData.co);
    $('#fo').prop('value', parsedData.fo);
    $('#paymentDate').prop('value', parsedData.paymentDate);
    $('#amount').prop('value', parsedData.amount);
    $('#discount').prop('value', parsedData.discount);
    $('#participantID').prop('value', parsedData.participantID);
  }

  function showStatusDetails(participantID) {
    console.log(participantID);

    $.ajax({
      url: 'components/fetchParticipantStatus.php',
      type: 'POST',
      data: { id: participantID },
      success: function (data) {
        try {

          const parsedData = JSON.parse(data);
          paymentDetailsValue = {};
          fillStatusField(parsedData);

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

  function populateAttendanceTable(day) {
    let selectedElement = null;

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
      });
    });

    $.ajax({
      url: 'components/fetchAttendanceTable.php',
      type: 'POST',
      data: { id: <?php echo $id; ?>, day: day },
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

    console.log("id: " + participantID + ", attendance: " + attendance + ", outputs: " + outputs + ", payment: " + payment);

    $.ajax({
      url: 'components/updateParticipantStatus.php',
      type: 'POST',
      data: {
        participantID: participantID,
        attendance: attendance,
        outputs: outputs,
        payment: payment
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

  function updateAttendance(attendanceID, day) {
    $.ajax({
      url: 'components/editAttendance.php',
      type: 'POST',
      data: { attendanceID: attendanceID },
      success: function (data) {
        if (data == "ok") {
          populateAttendanceTable(day);
        }
      }
    });
  }
</script>