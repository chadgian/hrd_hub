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
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <style>
    #html5-qrcode-button-camera-start {
      background-color: #EF4B4C;
      color: #E6FFFF;
    }

    #html5-qrcode-anchor-scan-type-change {
      color: #E6FFFF;
    }

    .container {
      width: 100%;
    }

    .container h1 {
      color: #2e2c2d;
      text-align: center;
    }

    .section {
      background-color: #ffffff;
      padding: 10px 30px;
      border: 1.5px solid #b2b2b2;
      border-radius: 0.25em;
      box-shadow: 0 20px 25px rgba(0, 0, 0, 0.25);
    }

    #my-qr-reader {
      padding: 20px !important;
      border: 1.5px solid #b2b2b2 !important;
      border-radius: 8px;
      background-color: #3D619B;
    }

    #my-qr-reader img[alt="Info icon"] {
      display: none;
    }

    #my-qr-reader img[alt="Camera based scan"] {
      width: 100px !important;
      height: 100px !important;
    }

    #my-qr-reader video {
      width: 50% !important;
      /* Set the width to 50% of the parent container */
      /* height: 50% !important; */
      /* Set the height to 50% of the viewport height */
      max-width: 400px !important;
      /* Set the maximum width to 400px */
      max-height: 400px !important;
      /* Set the maximum height to 300px */
      margin: 0 auto !important;
      /* Center the video horizontally */
      aspect-ratio: 1/1 !important;
      object-fit: cover !important;
    }

    button {
      padding: 10px 20px;
      border: 1px solid #b2b2b2;
      outline: none;
      border-radius: 0.25em;
      color: white;
      font-size: 15px;
      cursor: pointer;
      margin-top: 15px;
      margin-bottom: 10px;
      background-color: #008000ad;
      transition: 0.3s background-color;
    }

    button:hover {
      background-color: #008000;
    }

    #html5-qrcode-anchor-scan-type-change {
      text-decoration: none !important;
      color: #1d9bf0;
    }

    video {
      width: 100% !important;
      border: 1px solid #b2b2b2 !important;
      border-radius: 0.25em;
    }

    td {
      border: 1px solid black;
      padding: 10px;
      cursor: pointer;
    }

    td:hover {
      background-color: #f0f0f0;
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