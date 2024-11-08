<?php
include '../../components/processes/db_connection.php';

$participantID = $_POST['id'];

$fetchStatusStmt = $conn->prepare("SELECT * FROM training_participants as tp INNER JOIN employee as e ON tp.employeeID = e.employeeID INNER JOIN agency as a ON e.agency = a.agencyID  WHERE participantID = ?");
$fetchStatusStmt->bind_param("i", $participantID);

if ($fetchStatusStmt->execute()) {
  $fetchStatusResult = $fetchStatusStmt->get_result();

  if ($fetchStatusResult->num_rows > 0) {
    $fetchStatusData = $fetchStatusResult->fetch_assoc();

    $prefix = trim($fetchStatusData['prefix']);
    $firstName = trim($fetchStatusData['firstName']);
    $middleInitial = trim($fetchStatusData['middleInitial']);
    $lastName = trim($fetchStatusData['lastName']);
    $suffix = trim($fetchStatusData['suffix']);

    if ($prefix !== "") {
      if ($suffix !== "") {
        $name = "$prefix $firstName $middleInitial $lastName, $suffix";
      } else {
        $name = "$prefix $firstName $middleInitial $lastName";
      }
    } else {
      if ($suffix !== "") {
        $name = "$firstName $middleInitial $lastName, $suffix";
      } else {
        $name = "$firstName $middleInitial $lastName";
      }
    }

    echo json_encode(
      [
        "participantID" => $fetchStatusData['participantID'],
        "attendanceRemark" => $fetchStatusData['attendanceRemarks'],
        "remarks" => $fetchStatusData['remarks'],
        "outputs" => $fetchStatusData['outputs'],
        "attendance" => $fetchStatusData['attendance'],
        "payment" => $fetchStatusData['payment'],
        "receiptNumber" => $fetchStatusData['receiptNumber'],
        "co" => $fetchStatusData['co'],
        "fo" => $fetchStatusData['fo'],
        "paymentDate" => isset($fetchStatusData['paymentDate']) ? (new DateTime($fetchStatusData['paymentDate']))->format('Y-m-d') : null,
        "amount" => $fetchStatusData['amount'],
        "discount" => $fetchStatusData['discount'],
        "name" => $name,
        "agency" => $fetchStatusData['agencyName']
      ]
    );
  } else {
    echo json_encode(['error' => "No participant found."]);
  }
} else {
  echo json_encode(["error" => $fetchStatusStmt->error]);
}