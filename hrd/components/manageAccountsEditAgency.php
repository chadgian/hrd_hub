<?php
include '../../components/processes/db_connection.php';

if ($_SERVER['REQUEST_METHOD']) {
  $method = $_SERVER['REQUEST_METHOD'];

  switch ($method) {
    case 'GET':
      // Handle GET request
      handleGetRequest();
      break;

    case 'POST':
      // Handle POST request
      handlePostRequest();
      break;

    default:
      // Handle unsupported request methods
      http_response_code(405);
      echo json_encode(['error' => 'Method Not Allowed']);
      break;
  }
}

function handleGetRequest()
{
  global $conn;
  $agencyID = $_GET['agencyID'];

  $stmt = $conn->prepare("SELECT * FROM agency WHERE agencyID = ?");
  $stmt->bind_param("i", $agencyID);
  $stmt->execute();
  $result = $stmt->get_result();
  $agency = $result->fetch_assoc();
  echo json_encode($agency);
}

function handlePostRequest()
{
  global $conn;
  $agencyID = $_POST['agencyID'];
  $agencyName = $_POST['agencyName'];
  $sector = $_POST['sector'];
  $province = $_POST['province'];
  $address = $_POST['address'];

  $stmt = $conn->prepare("UPDATE agency SET agencyName = ?, sector = ?, province = ?, address = ? WHERE agencyID = ?");
  $stmt->bind_param("sssss", $agencyName, $sector, $province, $address, $agencyID);

  if ($stmt->execute()) {
    echo "ok";
  }
}