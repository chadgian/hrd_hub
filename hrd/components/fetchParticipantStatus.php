<?php
include '../../components/processes/db_connection.php';

$participantID = $_POST['id'];

$fetchStatusStmt = $conn->prepare("SELECT * FROM training_participants WHERE participantID = ?");
$fetchStatusStmt->bind_param("i", $participantID);

if ($fetchStatusStmt->execute()) {
  $fetchStatusResult = $fetchStatusStmt->get_result();

  if ($fetchStatusResult->num_rows > 0) {
    $fetchStatusData = $fetchStatusResult->fetch_assoc();

    echo json_encode(
      [
        "participantID" => $fetchStatusData['participantID'],
        "remarks" => $fetchStatusData['remarks'],
        "outputs" => $fetchStatusData['outputs'],
        "attendance" => $fetchStatusData['attendance'],
        "payment" => $fetchStatusData['payment'],
        "receiptNumber" => $fetchStatusData['receiptNumber'],
        "co" => $fetchStatusData['co'],
        "fo" => $fetchStatusData['fo'],
        "paymentDate" => date('Y-m-d', strtotime($fetchStatusData['paymentDate'])),
        "amount" => $fetchStatusData['amount'],
        "discount" => $fetchStatusData['discount'],
      ]
    );
  } else {
    echo json_encode(['error' => "No participant found."]);
  }
} else {
  echo json_encode(["error" => $fetchStatusStmt->error]);
}