<?php
include '../../components/processes/db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve and sanitize input data
  $agencyName = trim($_POST['agencyName']);
  $sector = trim($_POST['sector']);
  $province = trim($_POST['province']);
  $address = trim($_POST['address']);

  if (!checkExistingAgency($agencyName, $sector, $province)) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO agency (agencyName, sector, province, address) VALUES (?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
      die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssss", $agencyName, $sector, $province, $address);

    // Execute the statement
    if ($stmt->execute()) {
      echo "ok";
    } else {
      echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  } else {
    echo "existing";
  }
}

// Close the database connection
$conn->close();

function checkExistingAgency($agencyName, $sector, $province)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM agency WHERE agencyName = ? AND sector = ? AND province = ?");
  $stmt->bind_param("sss", $agencyName, $sector, $province);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}