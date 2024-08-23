<?php

include "../../components/processes/db_connection.php";

$id = $_POST['id'];
$day = $_POST['day'];

$attendanceTableStmt = $conn->prepare("SELECT * FROM attendance as a INNER JOIN employee as e ON a.employeeID = e.employeeID INNER JOIN training_participants as tp ON a.participantID = tp.participantID WHERE a.trainingID = ? AND day = ?");
$attendanceTableStmt->bind_param("ii", $id, $day);

executeQuery($attendanceTableStmt, $day);

function executeQuery($attendanceTableStmt, $day)
{
  if ($attendanceTableStmt->execute()) {
    $attendanceTableResult = $attendanceTableStmt->get_result();

    if ($attendanceTableResult->num_rows > 0) {
      while ($attendanceTableData = $attendanceTableResult->fetch_assoc()) {
        $idNum = $attendanceTableData['idNumber'];
        $prefix = $attendanceTableData['prefix'];
        $fname = $attendanceTableData['firstName'];
        $middleInitial = $attendanceTableData['middleInitial'];
        $lastName = $attendanceTableData['lastName'];
        $suffix = $attendanceTableData['suffix'];
        $fullname = "$prefix$fname$middleInitial $lastName$suffix";
        $loginRaw = new DateTime($attendanceTableData['login']);
        $login = $attendanceTableData['login'] == NULL ? "-----------" : $loginRaw->format("h:i A");
        $logoutRaw = new DateTime($attendanceTableData['logout']);
        $logout = $attendanceTableData['logout'] == NULL ? "-----------" : $logoutRaw->format("h:i A");
        $attendanceID = $attendanceTableData['attendanceID'];

        echo "
            <tr style='padding: 5px;'>
              <td class='tableNumber'>$idNum</td>
              <td>$fullname</td>
              <td id='login-$attendanceID' class='attendanceTime'>$login</td>
              <td id='logout-$attendanceID' class='attendanceTime'>$logout</td>
              <td onclick='editAttendance($attendanceID, $day)'>Edit</td>
            </tr>
            ";

      }
    } else {
      echo "<tr style='padding: 5px;'>
              <td></td>
              <td><i>No attendances yet.</i></td>
              <td></td>
              <td></td>
            </tr>";
    }
  } else {
    echo $attendanceTableStmt->error;
  }
}