<?php

include 'db_connection.php';

$sector = $_POST['sector'];

$getAgencies = $conn->prepare("SELECT * FROM agency WHERE sector = ?");
$getAgencies->bind_param("s", $sector);
$getAgencies->execute();
$getAgenciesResult = $getAgencies->get_result();

$agencies = [];

while ($data = $getAgenciesResult->fetch_assoc()) {
  $agencyID = $data['agencyID'];
  $agencyName = $data['agencyName'];

  $agencies[] = [
    "id" => $agencyID,
    "name" => $agencyName
  ];
}

echo json_encode($agencies);