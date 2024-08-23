<?php

include 'db_connection.php';

$participantID = $_POST['id'];

$fetchModalDetailStmt = $conn->prepare("
  SELECT 
    rd.*,
    tp.*,
    td.* 
  FROM 
    registration_details rd
  INNER JOIN 
    training_participants tp 
  ON 
    rd.registrationID = tp.registrationID
  INNER JOIN 
    training_details td 
  ON 
    tp.trainingID = td.trainingID
  WHERE tp.participantID = ?");

$fetchModalDetailStmt->bind_param("i", $participantID);

if ($fetchModalDetailStmt->execute()) {
  $result = $fetchModalDetailStmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $startDate = new DateTime($row['startDate']);
      $endDate = new DateTime($row['endDate']);
      $trainingDate = $startDate->format("F j") . "-" . $endDate->format("j, Y");

      $attendanceColor = $row['attendance'] == 0 ? "#FF0707" : "#00A52E";
      $paymentColor = $row['payment'] == 0 ? "#FF0707" : "#00A52E";

      $orNumber = $row['receiptNumber'] == "" ? "N/A" : $row['receiptNumber'];
      $amount = $row['amount'] == "" ? "N/A" : "₱" . $row['amount'];
      $discount = $row['discount'] == "" ? "N/A" : $row['discount'] . "%";
      $rawPaymentDate = new DateTime($row['paymentDate']);
      $paymentDate = $rawPaymentDate == "" ? "N/A" : $rawPaymentDate->format("F d, Y");

      $attendanceContent = $row['attendance'] == 0 ? "Incomplete" : "Complete";
      $paymentContent = $row['payment'] == 0 ? "Unpaid" : "Paid";

      $attendanceRemark = $row['attendanceRemarks'] == "" ? "N/A" : $row['attendanceRemarks'];

      $data = [
        "trainingName" => $row['trainingName'],
        "trainingVenue" => $row['venue'],
        "trainingMode" => $row['mode'],
        "trainingFee" => $row['fee'],
        "trainingHours" => $row['trainingHours'],
        "trainingArea" => $row['currArea'],
        "trainingDescription" => $row['details'],
        "trainingDate" => $trainingDate,
        "attendanceColor" => $attendanceColor,
        "paymentColor" => $paymentColor,
        "orNumber" => $orNumber,
        "amount" => $amount,
        "discount" => $discount,
        "paymentDate" => $paymentDate,
        "attendanceContent" => $attendanceContent,
        "paymentContent" => $paymentContent,
        "attendanceRemark" => $attendanceRemark
      ];

      echo json_encode($data);
    }
  }
}