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

  $noticeID = $_GET['noticeID'];

  $stmt = $conn->prepare("SELECT * FROM notices WHERE noticeID = ?");
  $stmt->bind_param("i", $noticeID);
  $stmt->execute();
  $result = $stmt->get_result();
  $notice = $result->fetch_assoc();

  echo json_encode($notice);
}
