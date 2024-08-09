<?php

$registrationID = $_GET['registrationID'];
$trainingID = $_GET['trainingID'];

include "../../components/processes/db_connection.php";

$fetchRegDetailStmt = $conn->prepare("SELECT rd.*, e.* FROM registration_details AS rd INNER JOIN employee AS e ON rd.employeeID = e.employeeID WHERE rd.registrationID = ?");
$fetchRegDetailStmt->bind_param("s", $registrationID);

if ($fetchRegDetailStmt->execute()) {
  $fetchRegDetailResult = $fetchRegDetailStmt->get_result();

  if ($fetchRegDetailResult->num_rows > 0) {
    while ($fetchRegDetailData = $fetchRegDetailResult->fetch_assoc()) {

      $fileName = getFileName($trainingID, $registrationID);
      $date = new DateTime($fetchRegDetailData['timeDate']);

      $data = [
        'registrationID' => $fetchRegDetailData['registrationID'],
        'trainingID' => $fetchRegDetailData['trainingID'],
        "name" => "{$fetchRegDetailData['prefix']}{$fetchRegDetailData['firstName']}{$fetchRegDetailData['middleInitial']} {$fetchRegDetailData['lastName']}{$fetchRegDetailData['suffix']}",
        "age" => $fetchRegDetailData['age'],
        "sex" => $fetchRegDetailData['sex'],
        "civilStatus" => $fetchRegDetailData['civilStatus'],
        "phoneNumber" => $fetchRegDetailData['phoneNumber'],
        "email" => $fetchRegDetailData['email'],
        "position" => $fetchRegDetailData['position'],
        "agency" => $fetchRegDetailData['agencyName'],
        "sector" => strtoupper($fetchRegDetailData['sector']),
        "fo" => "FO-" . ucwords($fetchRegDetailData['fo']),
        "foodRestriction" => $fetchRegDetailData['foodRestriction'],
        "timeDate" => $date->format("h:iA | F j, Y"),
        "confirmationSlip" => "../assets/conf_slips/$trainingID/$fileName"
      ];
    }
  } else {
    $data = [
      "error" => "No record found."
    ];
  }
} else {
  $data = [
    "error" => "Error fetching data."
  ];
}

echo json_encode($data);

function getFileName($trainID, $regID)
{
  try {
    $directory = "../../assets/conf_slips/$trainID";
    $files = scandir($directory);
    $found = false;

    foreach ($files as $file) {
      if (pathinfo($file, PATHINFO_FILENAME) == $regID) {
        $fileInfo = pathinfo($file);
        return "$regID.{$fileInfo['extension']}";
      }
    }

    if (!$found) {
      return false;
    }
  } catch (\Throwable $th) {
    // throw $th;
    return false;
  }
}