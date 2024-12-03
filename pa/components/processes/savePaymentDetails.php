<?php

include "../../../components/processes/db_connection.php";

$participantID = $_POST['participantID'];
$payment = $_POST['payment'];
$receiptNumber = $_POST['receiptNo'];
$paymentDate = $_POST['paymentDate'];
$fieldOffice = $_POST['fieldOffice'];
$collectingOfficer = $_POST['collectingOfficer'];
$amount = $_POST['amount'];
$discount = $_POST['discount'];
$lastEditedBy = $_POST['lastEditedBy'];

$stmt = $conn->prepare("UPDATE training_participants SET payment = ?, receiptNumber = ?, co = ?, fo = ?, paymentDate = ?, amount = ?, discount = ?, lastEditedBy = ? WHERE participantID = ? ");
$stmt->bind_param("sssssssss", $payment, $receiptNumber, $collectingOfficer, $fieldOffice, $paymentDate, $amount, $discount, $lastEditedBy, $participantID);

if ($stmt->execute()) {
  echo "ok";
}