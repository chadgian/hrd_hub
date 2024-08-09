<div class="participantTable-container">
  <h3 style="font-weight: bold; color: #57000; display: flex; align-items: center;">Participants
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person"
      viewBox="0 0 16 16">
      <path
        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
    </svg>
  </h3>
  <div class="participantTable-menu">
    <small class="addParticipantBtn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
        class="bi bi-person-add" viewBox="0 0 16 16">
        <path
          d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
        <path
          d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
      </svg> Add</small>
    <div>
      <input type="search" placeholder="Search" class="searchParticipant" id="searchParticipant">
    </div>
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
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="updateStatusModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- <ul class='dropdown-menu' style='text-align: center;'>
    <div class='status-list'>
      <div class='status-list-title'>ATTENDANCE</div>
      <div class='status-list-toggle'>
        <input type='radio' class='attendance-toggle' id='attendance-complete-$participantID'
          name='attendance-radio-$participantID' value='1' $attendanceComplete>
        <label for='attendance-complete-$participantID'>
          Complete
        </label>
        <input type='radio' class='attendance-toggle' id='attendance-incomplete-$participantID'
          name='attendance-radio-$participantID' value='0' $attendanceIncomplete>
        <label for='attendance-incomplete-$participantID'>
          Incomplete
        </label>
      </div>
    </div>
    <div class='status-list'>
      <div class='status-list-title'>PAYMENT</div>
      <div class='status-list-toggle'>
        <input type='radio' class='payment-toggle' id='payment-complete-$participantID'
          name='payment-radio-$participantID' value='1' onclick='showOR(1, $participantID)' $paymentComplete>
        <label for='payment-complete-$participantID'>
          Complete
        </label>
        <input type='radio' class='payment-toggle' id='payment-incomplete-$participantID'
          name='payment-radio-$participantID' value='0' onclick='showOR(0, $participantID)' $paymentIncomplete>
        <label for='payment-incomplete-$participantID'>
          Incomplete
        </label>
      </div>
    </div>
    <div class='status-list' id='receiptNumber-$participantID' $showORinput>
      <div class='status-list-title'>OR NUMBER</div>
      <div class='status-list-toggle'>
        <input type='text' id='orNumber' name='orNumber' class='orNumber' value='$ORnumber'>
      </div>
    </div>

    <div class='status-list'>
      <div class='status-list-title'>REMARKS</div>
      <div class='status-list-toggle'>
        <textarea id='remarks-$participantID' name='remarks-$participantID' class='remarks-$participantID' row='1'
          cols='23' oninput='autoResize($participantID)'>$remarks</textarea>
      </div>
    </div>

    <button class='mt-2' onclick='updateStatus($participantID)'>Update</button>
  </ul> -->

</div>

<!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script> -->

<script>
  $(document).ready(function () {
    populateParticipantTable();
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
        console.error('checking employee record error: ', textStatus, errorThrown);
      }
    });
  }
</script>