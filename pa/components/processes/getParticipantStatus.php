<?php

include '../../../components/processes/db_connection.php';

$participantID = $_GET['participantID'];

$stmt = $conn->prepare("SELECT * FROM training_participants WHERE participantID = ?");
$stmt->bind_param("i", $participantID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);