<?php

include "../../components/processes/db_connection.php";

$agencyID = $_POST['agencyID'];

$getAgency = $conn->prepare("SELECT * FROM agency WHERE agencyID = ?");
$getAgency->bind_param("i", $agencyID);

if ($getAgency->execute()) {
  $result = $getAgency->get_result();
  $agency = $result->fetch_assoc();

  echo "<div class='ma-viewAgency-header'>{$agency['agencyName']}</div>";

  echo "<div class='ma-search mb-3'>
          <input type='search' id='ma-searchInput' name='ma-searchInput' placeholder='Search'>
          <button onclick='goBackMAHome()' class='btn ma-viewAgency-backbtn'>Back</button>
        </div>";
}

echo "<div class='ma-viewAgency-employeeList'>";

$getAllEmployee = $conn->prepare("SELECT * FROM employee WHERE agency = ? ORDER BY firstName ASC");
$getAllEmployee->bind_param("i", $agencyID);

$getAllEmployee->execute();
$employeeResult = $getAllEmployee->get_result();

while ($employeeData = $employeeResult->fetch_assoc()) {
  $fullname = getFullName($employeeData);

  echo "<div class='ma-viewAgency-employeeItem row'>";

  echo "<div class='col-md-6 ma-viewAgency-employeeName'>$fullname</div>";

  echo "<div class='col-md-4 ma-viewAgency-employeePosition'>{$employeeData['position']}</div>";

  echo "<div class='col-md-2 ma-viewAgency-viewBtn'><button class='ma-manageAccounts-viewProfileBtn' onclick='viewEmployeeProfile({$employeeData['employeeID']})'>View</button></div>";

  echo "</div>";
}

echo "</div>";

function getFullName($employeeData)
{
  $prefix = trim($employeeData['prefix']);
  $firstName = trim($employeeData['firstName']);
  $middleInitial = trim($employeeData['middleInitial']);
  $lastName = trim($employeeData['lastName']);
  $suffix = trim($employeeData['suffix']);

  $fullname = "";

  if ($prefix !== "") {
    $fullname .= $prefix . " ";
  }

  $fullname .= $firstName . " ";

  if ($middleInitial !== '') {
    $fullname .= $middleInitial . " ";
  }

  $fullname .= $lastName;

  if ($suffix !== "") {
    if ($suffix == "Jr." || $suffix == "Sr.") {
      $fullname .= ", " . $suffix;
    } else {
      $fullname .= " " . $suffix;
    }
  }

  return $fullname;

}