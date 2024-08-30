<?php

include "../../components/processes/db_connection.php";

$id = $_POST['id'];
$day = $_POST['day'];
$type = $_POST['type'];
$searchQuery = "%" . $_POST['search'] . "%";
// for type: 0 = all, 1 = search, 2 = number, 3 = firstname, 4 = login, 5 = logout

if ($type == "0") {
  $attendanceTableStmt = $conn->prepare("SELECT * FROM attendance as a INNER JOIN employee as e ON a.employeeID = e.employeeID INNER JOIN training_participants as tp ON a.participantID = tp.participantID WHERE a.trainingID = ? AND day = ? ORDER BY tp.idNumber ASC");
  $attendanceTableStmt->bind_param("ii", $id, $day);

  executeQuery($attendanceTableStmt, $day);
} else if ($type == "1") {
  $attendanceTableStmt = $conn->prepare("SELECT * FROM attendance as a INNER JOIN employee as e ON a.employeeID = e.employeeID INNER JOIN training_participants as tp ON a.participantID = tp.participantID WHERE (a.trainingID = ? AND day = ?) AND (e.firstName LIKE ? OR e.lastName LIKE ? )");
  $attendanceTableStmt->bind_param("iiss", $id, $day, $searchQuery, $searchQuery);

  executeQuery($attendanceTableStmt, $day);
} else {
  $sort = match ($type) {
    '2' => 'tp.idNumber ASC',
    '3' => 'e.firstName ASC',
    '4' => 'a.login DESC',
    '5' => 'a.logout DESC',
  };

  $attendanceTableStmt = $conn->prepare("SELECT * FROM attendance as a INNER JOIN employee as e ON a.employeeID = e.employeeID INNER JOIN training_participants as tp ON a.participantID = tp.participantID WHERE (a.trainingID = ? AND day = ?) AND (e.firstName LIKE ? OR e.lastName LIKE ? ) ORDER BY $sort");
  $attendanceTableStmt->bind_param("iiss", $id, $day, $searchQuery, $searchQuery);

  executeQuery($attendanceTableStmt, $day);
}

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
        $login = $attendanceTableData['login'] == NULL ? "-----------" : $loginRaw->format("H:i");
        $logoutRaw = new DateTime($attendanceTableData['logout']);
        $logout = $attendanceTableData['logout'] == NULL ? "-----------" : $logoutRaw->format("H:i");
        $attendanceID = $attendanceTableData['attendanceID'];

        echo "
            <tr style='padding: 5px;'>
              <td class='tableNumber'>$idNum</td>
              <td>$fullname</td>
              <td id='login-$attendanceID' class='attendanceTime'>$login</td>
              <td id='logout-$attendanceID' class='attendanceTime'>$logout</td>
              <td class='d-flex justify-content-center align-items-center gap-1'><div onclick=\"editAttendance($attendanceID, '$login', '$logout')\" class='attendance-action-button px-4' id='attendance-edit-$attendanceID'>Edit</div><div onclick='updateAttendance($attendanceID, $day)' class='attendance-action-button' id='attendance-save-$attendanceID' style='display: none;width: max-content;'>Save</div><div class='attendance-close-edit' id='attendance-close-$attendanceID' style='display: none;' onclick=\"cancelUpdateAttendance($attendanceID)\">⨉</div></td>
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