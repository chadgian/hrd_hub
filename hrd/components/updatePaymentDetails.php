<?php

include "../../components/processes/db_connection.php";

$participantID = $_POST['id'];
$orNumber = $_POST['orNumber'];
$co = $_POST['co'];
$fo = $_POST['fo'];
$paymentDate = $_POST['paymentDate'];
$amount = $_POST['amount'];
$discount = $_POST['discount'];

$updatePaymentStmt = $conn->prepare("UPDATE training_participants SET receiptNumber = ?, co = ?, fo = ?, paymentDate = ?, amount = ?, discount = ? WHERE participantID = ?");
$updatePaymentStmt->bind_param("sssssss", $orNumber, $co, $fo, $paymentDate, $amount, $discount, $participantID);

if ($updatePaymentStmt->execute()) {
  echo "ok";
} else {
  echo "error: {$updatePaymentStmt->error}";
}