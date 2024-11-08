<?php

include "../../components/processes/db_connection.php";

$currentEmployee = $_GET['employeeID'];
$search = "%" . $_GET['search'] . "%";
$trainingID = $_GET['trainingID'];

$getAllEmployees = $conn->prepare("SELECT * FROM employee as e INNER JOIN agency as a ON e.agency = a.agencyID WHERE employeeID != ? AND (firstName LIKE ? OR lastName LIKE ? OR agencyName LIKE ?) ORDER BY a.agencyName ASC");
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

  $trainingPaxID = getTrainingPax($trainingID);

  // Filter employeeList to exclude those that exist in employeeID
  $filteredEmployeeList = array_filter($employeeList, function ($employee) use ($trainingPaxID) {
    return !in_array($employee['employeeID'], $trainingPaxID);
  });

  // foreach ($trainingPaxID as $id) {
  //   foreach ($employeeList as $key => $employee) {
  //     if ($employee['employeeID'] == $id) {
  //       unset($employeeList[$key]);
  //     }
  //   }
  // }

  // $employeeList = array_values($employeeList);

  echo json_encode($filteredEmployeeList);
}

function getTrainingPax($trainingID)
{
  global $conn;

  $getCurrentPax = $conn->prepare("SELECT * FROM training_participants WHERE trainingID = ?");
  $getCurrentPax->bind_param("s", $trainingID);

  $getCurrentPax->execute();

  $getCurrentPaxResult = $getCurrentPax->get_result();

  $trainingPaxID = [];

  while ($getCurrentPaxData = $getCurrentPaxResult->fetch_assoc()) {
    $trainingPaxID[] = $getCurrentPaxData['employeeID'];
  }

  return $trainingPaxID;
}