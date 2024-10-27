<?php

if (isset($_GET['id']) && isset($_GET['d']) && isset($_GET['log'])) {
  include '../components/classes/trainingDetails.php';
  $trainingID = $_GET['id'];
  $training = new getTraining($trainingID);

  $attendanceData = $training->getParticipants();
  $trainingDay = $_GET['d'];
  $inORout = $_GET['log'];

} else {
  header("Location: ../");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training Attendance</title>
  <!-- Bootstrap CSS and Javascript-->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <style>
    #reader,
    #reader__scan_region {
      max-width: 75vw !important;
      max-height: 90vh !important;
    }

    /* #reader__dashboard_section {
  opacity: 50%;
} */

    #html5-qrcode-button-camera-stop,
    #html5-qrcode-button-camera-start {
      --bs-btn-color: #fff;
      --bs-btn-bg: #0d6efd;
      --bs-btn-border-color: #0d6efd;
      --bs-btn-hover-color: #fff;
      --bs-btn-hover-bg: #0b5ed7;
      --bs-btn-hover-border-color: #0a58ca;
      --bs-btn-focus-shadow-rgb: 49, 132, 253;
      --bs-btn-active-color: #fff;
      --bs-btn-active-bg: #0a58ca;
      --bs-btn-active-border-color: #0a53be;
      --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
      --bs-btn-disabled-color: #fff;
      --bs-btn-disabled-bg: #0d6efd;
      --bs-btn-disabled-border-color: #0d6efd;

      --bs-btn-padding-x: 0.75rem;
      --bs-btn-padding-y: 0.375rem;
      --bs-btn-font-family: ;
      --bs-btn-font-size: 1rem;
      --bs-btn-font-weight: 400;
      --bs-btn-line-height: 1.5;
      --bs-btn-border-width: var(--bs-border-width);
      --bs-btn-border-radius: var(--bs-border-radius);
      --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
      --bs-btn-disabled-opacity: 0.65;
      --bs-btn-focus-box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
      display: inline-block;
      padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
      font-family: var(--bs-btn-font-family);
      font-size: var(--bs-btn-font-size);
      font-weight: var(--bs-btn-font-weight);
      line-height: var(--bs-btn-line-height);
      color: var(--bs-btn-color);
      text-align: center;
      text-decoration: none;
      vertical-align: middle;
      cursor: pointer;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
      border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
      border-radius: var(--bs-btn-border-radius);
      background-color: var(--bs-btn-bg);
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;

      margin-top: 1em;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2 class="text-center fw-bold">
      <?php echo $training->getTrainingName(); ?>
    </h2>
    <div>
      <div class="section">
        <?php
        echo "
						<input type='hidden' value='{$trainingID}' name='training' id='training'>
						<input type='hidden' value='{$trainingDay}' name='days' id='days'>
						<input type='hidden' value='$inORout' name='inORout' id='inORout'>
					";
        ?>
        <p class="text-center">
          <?php
          $scanStatus = $inORout == "in" ? "Login" : "Logout";
          echo "Day $trainingDay - $scanStatus";
          ?>
        </p>
        <h2 id='status' class="text-center"></h2>
        <div id="my-qr-reader">
        </div><br>
        <table id="recorded" style="width: 100%; max-height: 2em;">
          <tr style="overflow: scroll;">

          </tr>
        </table>

        <div style="display: flex; gap: 1em; width: 100%; justify-content: center; align-items: center;">
          <button onclick="clearData()">Clear Data</button><button onclick="saveData()">Save to Server</button>
        </div>
        <!-- <div class="scanResult">
          <h3><label for="name-result" id="name-label"></label></h3>
          <input type="hidden" name="name-result" id="name-result"><br>
          <input type="submit" value="Save" id="saveButton" style="display: none;">
        </div> -->
      </div>
    </div>


  </div>
  <script src="../assets/js/html5-qrcode.min.js"></script>
  <script src="../assets/js/jquery-3.6.0.min.js"></script>

  <script>
    let qrCodeScanned = false;
    let previousData = "";
    setStatus("ready");

    const existingData = localStorage.getItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>");
    var recordedData = [];

    if (existingData) {
      try {
        recordedData = JSON.parse(existingData);

        recordedData.forEach(participant => {
          addRow(participant.numID, participant.name, participant.timestamp);
        });
      } catch (error) {
        alert('Failed to parse existing data:', e);
        recordedData = [];
      }
    } else {
      console.log("None");
    }

    var attendanceData = JSON.parse('<?php echo json_encode($attendanceData); ?>');

    function domReady(fn) {
      if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
      ) {
        setTimeout(fn, 1000);
      } else {
        document.addEventListener("DOMContentLoaded", fn);
      }
    }

    let htmlscanner = new Html5QrcodeScanner(
      "my-qr-reader",
      { fps: 50, qrbox: 250 }
    );

    function setStatus(status) {
      const statusElement = document.getElementById('status');

      switch (status) {
        case "ready":
          statusElement.innerHTML = 'Ready to Scan!';
          break;
        case "decrypting":
          statusElement.innerHTML = 'Decrypting data...';
          break;
        case "recording":
          statusElement.innerHTML = 'Recording data...';
          break;
        case "not found":
          statusElement.innerHTML = 'Participant not found...';
          break;
        default:
          statusElement.innerHTML = 'Error. Refresh the page.';
          break;
      }
    }

    domReady(function () {
      // If found you qr code

      async function onScanSuccess(decodeText, decodeResult) {
        // alert("Scanned data: "+decodeText);
        if (!qrCodeScanned) { // Check if QR code has already been scanned
          qrCodeScanned = true;

          // alert(previousData + " -- "+ decodeText);

          if (previousData === decodeText) {
            qrCodeScanned = false;
            return;
          }
          else {
            previousData = decodeText;
            setStatus("decrypting");

            console.log("Scanned data: " + decodeText);
            console.log("Call for addAttendance: " + decodeText.split(":")[1])

            addAttendance(decodeText.split(":")[1]);

            setStatus("ready");
            qrCodeScanned = false;
          }

        }
      }

      // if (counter === 0){
      // 	counter++;
      // 	htmlscanner.render(onScanSuccess);
      // }

      // counter++;
      htmlscanner.render(onScanSuccess);
    });

    function addAttendance(numID) {
      console.log(attendanceData);
      const participant = attendanceData.find(participant => participant.numID === parseInt(numID, 10));
      if (participant) {
        const now = new Date();

        // Get the current hour, minute, and second
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        participant.timestamp = `${hours}:${minutes}:${seconds}`;


        console.log(participant.numID + " " + participant.name + " " + participant.timestamp)

        saveRecordedData(participant.numID, participant.name, participant.timestamp);
      } else {
        setStatus("Not found");
      }
    }

    function deleteLastData() {
      document.getElementById("recorded").innerHTML = "<tr></tr>";
      recordedData.pop();
      localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
      recordedData.forEach(participant => {
        addRow(participant.numID, participant.name, participant.timestamp);
      });
    }

    function addRow(id, name, timestamp) {
      const tbody = document.querySelector('#recorded tbody');
      const newRow = document.createElement('tr');

      const idCell = document.createElement('td');
      idCell.textContent = id;
      newRow.appendChild(idCell);

      const nameCell = document.createElement('td');
      nameCell.textContent = name;
      newRow.appendChild(nameCell);

      const timestampCell = document.createElement('td');
      timestampCell.textContent = timestamp;
      newRow.appendChild(timestampCell);

      const firstRow = tbody.firstChild; // Get the first row
      tbody.insertBefore(newRow, firstRow);
    }

    function saveRecordedData(id, name, timestamp) {
      const newData = { numID: id, name: name, timestamp: timestamp };

      const dataExists = recordedData.find(participant => participant.numID === parseInt(id, 10));

      if (dataExists) {
        const updatedDataArray = recordedData.filter(item => item.numID !== parseInt(id, 10));
        recordedData = updatedDataArray;
      }

      document.getElementById("recorded").innerHTML = "<tr></tr>";
      recordedData.push(newData);
      recordedData.forEach(participant => {
        addRow(participant.numID, participant.name, participant.timestamp);
      });
      localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));

    }

    function saveDatabase() {
      const data = JSON.stringify(recordedData);
      const blob = new Blob([data], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'attendance.json';
      a.click();
      URL.revokeObjectURL(url);
    }

    function saveData() {
      const trainingID = "<?php echo $trainingID; ?>";
      const trainingDay = "<?php echo $trainingDay; ?>";
      const inorout = "<?php echo $inORout; ?>";

      $.ajax({
        url: 'components/saveRecordedData.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          inorout: inorout,
          trainingID: trainingID,
          day: trainingDay,
          participants: recordedData
        }),
        success: function (response) {
          if (response) {
            document.getElementById("recorded").innerHTML = "<tr></tr>";
            recordedData = [];
            localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
          }
        },
        error: function (xhr, status, error) {
          alert('Error:', error);
        }
      });
    }

    function clearData() {

      const confirmDelete = confirm("Are you sure you want to DELETE ALL DATA?");

      if (confirmDelete) {
        document.getElementById("recorded").innerHTML = "<tr></tr>";
        recordedData = [];
        localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
      }

    }
  </script>
</body>

</html>