<?php

include "../../components/processes/db_connection.php";

$currentEmployee = $_GET['employeeID'];
$search = "%" . $_GET['search'] . "%";

$getAllEmployees = $conn->prepare("SELECT * FROM employee WHERE employeeID != ? AND (firstName LIKE ? OR lastName LIKE ? OR agencyName LIKE ? )");
$getAllEmployees->bind_param("isss", $currentEmployee, $search, $search, $search);

if ($getAllEmployees->execute()) {
  $result = $getAllEmployees->get_result();
  $employeeList = [];
  while ($row = $result->fetch_assoc()) {
    $fullName = "";

    if ($row['prefix'] != "") {
      $fullName .= $row['prefix'] . " ";
    }

    $fullName .= $row['firstName'] . " ";

    if ($row['middleInitial'] != "") {
      $fullName .= $row['middleInitial'] . " ";
    }

    $fullName .= $row['lastName'] . " ";

    if ($row['suffix'] != "") {
      $fullName .= $row['suffix'];
    }

    $employeeList[] = [
      "employeeID" => $row['employeeID'],
      "fullName" => $fullName,
      "agency" => $row['agencyName']
    ];

  }

  echo json_encode($employeeList);
}