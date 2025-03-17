<?php

include "../../components/processes/db_connection.php";

$searchQuery = "%" . $_POST['searchQuery'] . "%";
$searchType = $_POST['searchType'] ?? null;
$userType = $_POST['userType'];

if ($userType == "employee") {
  switch ($searchType) {
    case "name":
      getEmployeeByName();
      break;
    case "agency":
      getEmployeeByAgency();
      break;
  }
} else if ($userType == "admin") {
  getAdminByName();
}

function getEmployeeByName()
{
  global $conn, $searchQuery;

  $getEmployeeByName = $conn->prepare("SELECT * FROM employee WHERE CONCAT(firstName, ' ', middleInitial, ' ', lastName) LIKE ? ORDER BY firstName ASC");
  $getEmployeeByName->bind_param("s", $searchQuery);
  $getEmployeeByName->execute();
  $resultEmployeeByName = $getEmployeeByName->get_result();
  // $searchResult = $resultEmployeeByName->fetch_all(MYSQLI_ASSOC);

  if ($resultEmployeeByName->num_rows == 0) {
    echo "<div class='ma-agencyListBody mb-2'>";
    echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>No results found</span></div>";
    echo "</div>";
    return;
  }

  while ($searchResultData = $resultEmployeeByName->fetch_assoc()) {
    $employeeId = $searchResultData['employeeID'];
    $employeeFName = $searchResultData['firstName'];
    $employeeMName = $searchResultData['middleInitial'];
    $employeeLName = $searchResultData['lastName'];

    echo "<div class='ma-agencyListBody mb-2'>";

    echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>$employeeFName $employeeMName $employeeLName</span><span class='ma-viewAgency' onclick='viewEmployeeProfile($employeeId)'>
                View
              </span></div>";

    echo "</div>";
  }

  // echo json_encode($searchResult);
}

function getEmployeeByAgency()
{
  global $conn, $searchQuery;

  $getEmployeeByAgency = $conn->prepare("SELECT * FROM agency WHERE agencyName LIKE ? ORDER BY agencyName ASC");
  $getEmployeeByAgency->bind_param("s", $searchQuery);
  $getEmployeeByAgency->execute();
  $resultEmployeeByAgency = $getEmployeeByAgency->get_result();
  // $searchResult = $resultEmployeeByAgency->fetch_all(MYSQLI_ASSOC);

  if ($resultEmployeeByAgency->num_rows == 0) {
    echo "<div class='ma-agencyListBody mb-2'>";
    echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>No results found</span></div>";
    echo "</div>";
    return;
  }
  while ($searchResultData = $resultEmployeeByAgency->fetch_assoc()) {
    $agencyId = $searchResultData['agencyID'];
    $agencyName = $searchResultData['agencyName'];

    echo "<div class='ma-agencyListBody mb-2'>";

    echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>$agencyName</span><span class='ma-viewAgency' onclick='visitAgencyPage($agencyId)'>
                View
              </span></div>";

    echo "</div>";
  }

  // echo json_encode($searchResult);
}

function getAdminByName()
{
  global $conn, $searchQuery;

  $getAdminByName = $conn->prepare("SELECT * FROM user WHERE CONCAT(firstName, ' ', middleInitial, ' ', lastName, ' ', username, ' ', initials) LIKE ? ORDER BY username ASC");
  $getAdminByName->bind_param("s", $searchQuery);
  $getAdminByName->execute();
  $resultAdminByName = $getAdminByName->get_result();
  // $searchResult = $resultAdminByName->fetch_all(MYSQLI_ASSOC);

  if ($resultAdminByName->num_rows == 0) {
    echo "<div class='ma-agencyListBody mb-2'>";
    echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>No results found</span></div>";
    echo "</div>";
    return;
  }

  while ($searchResultData = $resultAdminByName->fetch_assoc()) {
    $adminId = $searchResultData['userID'];
    $adminFName = $searchResultData['firstName'];
    $adminMName = $searchResultData['middleInitial'];
    $adminLName = $searchResultData['lastName'];
    $adminUsername = $searchResultData['username'];

    echo "<div class='ma-agencyListBody mb-2'>";

    echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>$adminUsername</span><span class='ma-viewAgency' onclick='visitAdminPage($adminId)'>
                View
              </span></div>";

    echo "</div>";
  }

  // echo json_encode($searchResult);
}