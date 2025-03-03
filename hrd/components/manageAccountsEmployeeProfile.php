<?php

include '../../components/processes/db_connection.php';
include '../../components/classes/userSession.php';
$userClass = new UserSession();

$employeeID = $_POST['employeeID'];
$userID = getUserID($employeeID);

$employee = $userClass->fetchUserDetails($userID);

$fullname = getFullName($employee);

$prefix = trim($employee['prefix']) == null ? "N/A" : trim($employee['prefix']);
$suffix = trim($employee['suffix']) == null ? "N/A" : trim($employee['suffix']);
$foodRestriction = trim($employee['foodRestriction']) == null ? "N/A" : trim($employee['foodRestriction']);
$altEmail = trim($employee['altEmail']) == null ? "N/A" : trim($employee['altEmail']);

echo "<div class='ma-viewAgency-employeeProfile-header'><span style='font-weight: normal; font-size: small; cursor: pointer;' onclick='goBackMAHome()'>Back</span><span>$fullname</span>
      <div class='dropdown'>
        <div data-bs-toggle='dropdown' aria-expanded='false' style='font-size: small; font-weight: normal; cursor: pointer;'>
          Menu
        </div>
        <ul class='dropdown-menu'>
          <li><span class='dropdown-item' style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#editEmployeeModal'>Edit Profile</span></li>
          <li><span class='dropdown-item' style='cursor: pointer;' onclick='editEmployeeProfile($employeeID)'>Update Credentials</span></li>
        </ul>
      </div>
    </div>";

echo "
<div class='ma-viewAgency-employeeProfile-body'>
<div class='profile-details'>
<div class='profile-details-header'>
  <span style='font-weight: 500; font-size: large;'>Registration Details</span>
  <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor'
    class='bi bi-pencil-square' viewBox='0 0 16 16' style='cursor: pointer;' data-bs-toggle='modal'
    data-bs-target='#editRegistrationDetails'>
    <path
      d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
    <path fill-rule='evenodd'
      d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z' />
  </svg>
</div>
<div class='profile-details-content'>
  <div class='detail-title'>PERSONAL INFORMATION</div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Prefix:</div>
      <div class='detail-content'>{$prefix}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>First Name:</div>
      <div class='detail-content'>{$employee['firstName']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Middle Initial:</div>
      <div class='detail-content'>{$employee['middleInitial']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Last Name:</div>
      <div class='detail-content'>{$employee['lastName']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Suffix:</div>
      <div class='detail-content'>{$suffix}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Nickname:</div>
      <div class='detail-content'>{$employee['nickname']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Age:</div>
      <div class='detail-content'>{$employee['age']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Sex:</div>
      <div class='detail-content'>{$employee['sex']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Civil Status:</div>
      <div class='detail-content'>{$employee['civilStatus']}</div>
    </div>
  <div class='detail-title'>CONTACT INFORMATION</div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Number:</div>
      <div class='detail-content'>{$employee['phoneNumber']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Alternative Email:</div>
      <div class='detail-content'>{$altEmail}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name'>Email:</div>
      <div class='detail-content'>{$employee['email']}</div>
    </div>
  <div class='detail-title'>AGENCY INFORMATION</div>
    <div class='d-flex detail-data'>
      <div class='detail-name' style='flex: 1;'>Sector:</div>
      <div class='detail-content' style='flex: 2;'>{$employee['sector']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name' style='flex: 1;'>Name of Agency:</div>
      <div class='detail-content' style='flex: 2;'>{$employee['agencyName']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name' style='flex: 1;'>Position:</div>
      <div class='detail-content' style='flex: 2;'>{$employee['position']}</div>
    </div>
    <div class='d-flex detail-data'>
      <div class='detail-name' style='flex: 1;'>CSC Field Office:</div>
      <div class='detail-content' style='flex: 2;'>FO-{$employee['province']}</div>
    </div>
  <div class='detail-title'>FOOD RESTRICTIONS:</div>
  <div class='d-flex detail-data'>
    <div class='detail-content'>{$foodRestriction}</div>
  </div>
</div>
</div>
";

echo "
<div class='ma-viewAgency-employeeProfile-trainings'>
  <div class='ma-viewAgency-employeeProfile-trainingsHeader'>Trainings Attended</div>";

$trainings = getTrainings($employeeID);

foreach ($trainings as $training) {

  $trainingDate = (new DateTime($training['startDate']))->format("F Y");

  $paymentStatus = $training['payment'] == 0 ? "Unpaid" : "Paid";
  $paymentColor = $training['payment'] == 0 ? "#FF0707" : "#00A52E";
  $attendanceColor = $training['attendance'] == 0 ? "#FF0707" : "#00A52E";
  $AttendanceStatus = $training['attendance'] == 0 ? "Incomplete" : "Completed";

  echo "<div class='ma-viewAgency-employeeProfile-training'>";

  echo "<div class='ma-viewAgency-employeeProfile-trainingName'><b>{$training['trainingName']}</b> <div class='fst-italic'>({$trainingDate})</div></div>";

  echo "<div class='participantStatus'>
          <div class='d-flex flex-row'>Payment Status: <div style='color: $paymentColor; font-weight: bold;padding-left: 5px;'>{$paymentStatus}</div></div>
          <div class='d-flex flex-row'>Attendance Status: <div style='color: $attendanceColor; font-weight: bold;padding-left: 5px;'>{$AttendanceStatus}</div></div>
        </div>";

  echo "<a class='ma-viewAgency-employeeProfile-trainingDetails' href='index.php?p=7&t={$training['trainingID']}'>Visit training</a>";

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

function getUserID($employeeID)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM employee WHERE employeeID = ?");
  $stmt->bind_param("i", $employeeID);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();

  return $data['userID'];
}

function getTrainings($employeeID)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM training_participants as tp INNER JOIN employee as e ON tp.employeeID = e.employeeID INNER JOIN agency as a ON e.agency = a.agencyID INNER JOIN training_details as td ON tp.trainingID = td.trainingID WHERE tp.employeeID = ?");
  $stmt->bind_param("i", $employeeID);
  $stmt->execute();
  $result = $stmt->get_result();

  $trainings = [];

  while ($data = $result->fetch_assoc()) {
    $trainings[] = $data;
  }

  return $trainings;
}