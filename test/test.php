<?php
include '../components/processes/db_connection.php';
$trainingID = "22";
$trainingDay = "1";

$getParticipantStmt = $conn->prepare("SELECT * FROM employee as e INNER JOIN attendance as a ON e.employeeID = a.employeeID INNER JOIN training_participants as tp ON a.participantID = tp.participantID WHERE a.trainingID = ? AND a.day = ? ");
$getParticipantStmt->bind_param("ii", $trainingID, $trainingDay);

if ($getParticipantStmt->execute()) {
  $getParticipantResult = $getParticipantStmt->get_result();
  if ($getParticipantResult->num_rows > 0) {

    $attendanceData = [];

    while ($getParticipantData = $getParticipantResult->fetch_assoc()) {
      $numID = $getParticipantData['idNumber'];
      $attendanceID = $getParticipantData['attendanceID'];

      $prefix = trim($getParticipantData['prefix']);
      $firstName = trim($getParticipantData['firstName']);
      $middleInitial = trim($getParticipantData['middleInitial']);
      $lastName = trim($getParticipantData['lastName']);
      $suffix = trim($getParticipantData['suffix']);

      if ($prefix !== "") {
        $name = ($suffix !== "") ? "$prefix $firstName $middleInitial $lastName, $suffix" : "$prefix $firstName $middleInitial $lastName";
      } else {
        $name = ($suffix !== "") ? "$firstName $middleInitial $lastName, $suffix" : "$firstName $middleInitial $lastName";
      }

      $timestamp = date("H:i:s");

      $attendanceData[] = [
        'numID' => $numID,
        'attendanceID' => $attendanceID,
        'name' => $name,
        'timestamp' => "00:00:00"
      ];

    }

  } else {
    echo "No participants";
  }
} else {
  echo $getParticipantStmt->error;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test</title>
  <style>
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

  <table id="recorded" style="width: 100%; max-height: 2em;">
    <tr style="overflow: scroll;">

    </tr>
  </table>

  <input type="text" id="displayNum"
    style="width: 100%; border: 1px solid black; padding: 1em; font-size: large; font-weight: bold;" disabled>

  <table style="width: 100%; text-align: center;">
    <tbody>
      <tr>
        <td onclick="numberPad(7)">7</td>
        <td onclick="numberPad(8)">8</td>
        <td onclick="numberPad(9)">9</td>
        <td onclick="numberPad(-1)">undo</td>
      </tr>
      <tr>
        <td onclick="numberPad(4)">4</td>
        <td onclick="numberPad(5)">5</td>
        <td onclick="numberPad(6)">6</td>
        <td onclick="numberPad(-2)">delete</td>
      </tr>
      <tr>
        <td onclick="numberPad(1)">1</td>
        <td onclick="numberPad(2)">2</td>
        <td onclick="numberPad(3)">3</td>
        <td onclick="numberPad(-3)">enter</td>
      </tr>
    </tbody>
  </table>
  <button onclick="saveDatabase()">Save Data</button>
  <script>

    const existingData = localStorage.getItem("attendance");
    var recordedData = [];

    if (existingData) {
      try {
        recordedData = JSON.parse(existingData);

        recordedData.forEach(participant => {
          addRow(participant.numID, participant.name, participant.timestamp);
        });
      } catch (error) {
        console.error('Failed to parse existing data:', e);
        recordedData = [];
      }
    } else {
      console.log("None");
    }

    var attendanceData = JSON.parse('<?php echo json_encode($attendanceData); ?>');

    function numberPad(num) {
      const displayNum = document.getElementById("displayNum");
      let displayNumVal = displayNum.value;

      switch (num) {
        case -1:
          deleteLastData();
          break;

        case -2:
          displayNumVal = displayNumVal.slice(0, -1);
          break;

        case -3:
          addAttendance(displayNumVal);
          displayNumVal = "";
          break;

        default:
          displayNumVal = displayNumVal + "" + num;
          break;
      }

      displayNum.value = displayNumVal;

    }

    function addAttendance(numID) {
      console.log(numID);
      const participant = attendanceData.find(participant => participant.numID === parseInt(numID, 10));
      if (participant) {
        const now = new Date();

        // Get the current hour, minute, and second
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        participant.timestamp = `${hours}:${minutes}:${seconds}`;

        saveRecordedData(participant.numID, participant.attendanceID, participant.name, participant.timestamp);
      } else {
        alert("Participants not found!");
      }
    }

    function deleteLastData() {
      document.getElementById("recorded").innerHTML = "<tr></tr>";
      recordedData.pop();
      localStorage.setItem("attendance", JSON.stringify(recordedData));
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

    function saveRecordedData(id, attendanceID, name, timestamp) {
      const newData = { numID: id, attendanceID: attendanceID, name: name, timestamp: timestamp };

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
      localStorage.setItem("attendance", JSON.stringify(recordedData));

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
  </script>

</body>

</html>