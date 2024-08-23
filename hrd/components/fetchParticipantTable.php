<?php

include "../../components/processes/db_connection.php";

$id = $_POST['id'];

if (isset($_POST['search'])) {

  $search = "%" . $_POST['search'] . "%";

  $participantTableStmt = $conn->prepare("SELECT * FROM training_participants as tp INNER JOIN employee as e ON tp.employeeID = e.employeeID INNER JOIN training_details as td ON tp.trainingID = td.trainingID WHERE tp.trainingID = ? AND (e.firstName LIKE ? OR e.lastName LIKE ? OR e.agencyName LIKE ?)");
  $participantTableStmt->bind_param("isss", $id, $search, $search, $search);

  executeQuery($participantTableStmt);

} else {
  $participantTableStmt = $conn->prepare("SELECT * FROM training_participants as tp INNER JOIN employee as e ON tp.employeeID = e.employeeID INNER JOIN training_details as td ON tp.trainingID = td.trainingID WHERE tp.trainingID = ?");
  $participantTableStmt->bind_param("i", $id);

  executeQuery($participantTableStmt);
}

function executeQuery($participantTableStmt)
{
  if ($participantTableStmt->execute()) {
    $participantTableResult = $participantTableStmt->get_result();

    if ($participantTableResult->num_rows > 0) {
      while ($participantTableData = $participantTableResult->fetch_assoc()) {
        $idNum = $participantTableData['idNumber'];
        $prefix = $participantTableData['prefix'];
        $fname = $participantTableData['firstName'];
        $middleInitial = $participantTableData['middleInitial'];
        $lastName = $participantTableData['lastName'];
        $suffix = $participantTableData['suffix'];
        $fullname = "$prefix$fname$middleInitial $lastName$suffix";
        $agency = $participantTableData['agencyName'];
        $remarks = $participantTableData['remarks'];
        $outputs = $participantTableData['outputs'];
        $payment = $participantTableData['payment'];
        $ORnumber = $participantTableData['receiptNumber'];
        $attendance = $participantTableData['attendance'];
        $requiredDocs = $participantTableData['requiredDocs'];
        $participantID = $participantTableData['participantID'];

        $attendanceIndicator = $attendance == 0 ? "#CE2F2F" : "#049B01";
        $attendanceComplete = $attendance == "1" ? "checked" : "";
        $attendanceIncomplete = $attendance == "0" ? "checked" : "";

        $outputIndicator = $outputs == 0 ? "#CE2F2F" : "#049B01";
        $paymentComplete = $payment == 1 ? "checked" : "";
        $paymentIncomplete = $payment == 0 ? "checked" : "";

        $paymentIndicator = $payment == 0 ? "#CE2F2F" : "#049B01";

        $showORinput = $payment == 0 ? "style='display: none'" : "";


        if ($requiredDocs == 0) {
          $statusContent = "
          <div class='status-icon' style='color: $attendanceIndicator;'>
            <svg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='currentColor' class='bi bi-calendar-check-fill' viewBox='0 0 16 16'>
              <path d='M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708'/>
            </svg>
          </div>
          <div class='status-icon' style='color: $paymentIndicator;'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-currency-exchange' viewBox='0 0 16 16'>
              <path d='M0 5a5 5 0 0 0 4.027 4.905 6.5 6.5 0 0 1 .544-2.073C3.695 7.536 3.132 6.864 3 5.91h-.5v-.426h.466V5.05q-.001-.07.004-.135H2.5v-.427h.511C3.236 3.24 4.213 2.5 5.681 2.5c.316 0 .59.031.819.085v.733a3.5 3.5 0 0 0-.815-.082c-.919 0-1.538.466-1.734 1.252h1.917v.427h-1.98q-.004.07-.003.147v.422h1.983v.427H3.93c.118.602.468 1.03 1.005 1.229a6.5 6.5 0 0 1 4.97-3.113A5.002 5.002 0 0 0 0 5m16 5.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0m-7.75 1.322c.069.835.746 1.485 1.964 1.562V14h.54v-.62c1.259-.086 1.996-.74 1.996-1.69 0-.865-.563-1.31-1.57-1.54l-.426-.1V8.374c.54.06.884.347.966.745h.948c-.07-.804-.779-1.433-1.914-1.502V7h-.54v.629c-1.076.103-1.808.732-1.808 1.622 0 .787.544 1.288 1.45 1.493l.358.085v1.78c-.554-.08-.92-.376-1.003-.787zm1.96-1.895c-.532-.12-.82-.364-.82-.732 0-.41.311-.719.824-.809v1.54h-.005zm.622 1.044c.645.145.943.38.943.796 0 .474-.37.8-1.02.86v-1.674z'/>
            </svg>
          </div>
          <div class='update-status btn-group' data-bs-toggle='modal' data-bs-target='#updateStatusModal' onclick='showStatusDetails($participantID)'>
            <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
              <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
              <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
            </svg>
          </div>
          ";
        }

        echo "
            <tr style='padding: 5px;'>
              <td class='tableNumber'>$idNum</td>
              <td>$fullname</td>
              <td>$agency</td>
              <td class='status-content'>$statusContent</td>
            </tr>
            ";

      }
    } else {
      echo "<tr style='padding: 5px;'>
              <td></td>
              <td><i>No participants yet.</i></td>
              <td></td>
              <td></td>
            </tr>";
    }
  } else {
    echo $participantTableStmt->error;
  }
}