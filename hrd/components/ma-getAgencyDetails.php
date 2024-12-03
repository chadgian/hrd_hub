<?php

include '../../components/processes/db_connection.php';

$agencyID = $_POST['agencyID'];

$stmt = $conn->prepare("SELECT * FROM agency WHERE agencyID = ?");
$stmt->bind_param("i", $agencyID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);