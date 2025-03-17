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
          <li><span class='dropdown-item' style='cursor: pointer;' onclick='editProfile()'>Edit Profile</span></li>
          <li><span class='dropdown-item' style='cursor: pointer;' onclick='resetAccountPassword($userID)'>Reset Password</span></li>
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

  echo "<a class='ma-viewAgency-employeeProfile-trainingDetails' href='index.php?p=6&t={$training['trainingID']}'>Visit training</a>";

  echo "</div>";
}

echo "</div>";

$allAgency = json_encode(getAllAgency());

function getAllAgency()
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM agency");
  $stmt->execute();
  $result = $stmt->get_result();

  $agencies = [];

  while ($data = $result->fetch_assoc()) {
    $agencies[] = $data;
  }

  return $agencies;
}

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

?>

<!-- Edit employee profile modal-->
<div class="modal fade" id="editEmployeeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editEmployeeModalLabel">Edit Employee Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="regForm" enctype="multipart/form-data">
          <input type="hidden" id="trainingID" name="trainingID">
          <!-- Personal Information -->
          <h6 class="mb-3 form-title">PERSONAL INFORMATION</h6>
          <div class="form-row">
            <div class="form-group col-md-2">
              <label for="prefix">Prefix <small><i>(optional)</i></small></label>
              <input type="text" class="form-control" id="prefix" name="prefix">
            </div>
            <div class="form-group col-md-3">
              <label for="firstName">First Name <small>*</small></label>
              <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="form-group col-md-2">
              <label for="middleInitial">Middle Initial <small>*</small></label>
              <input type="text" class="form-control" id="middleInitial" name="middleInitial">
            </div>
            <div class="form-group col-md-3">
              <label for="lastName">Last Name <small>*</small></label>
              <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="form-group col-md-2">
              <label for="suffix">Suffix <small>*</small></label>
              <select class="form-control" id="suffix" name="suffix" required>
                <option value="">None</option>
                <option value=" Jr. ">Jr.</option>
                <option value=" Sr. ">Sr.</option>
                <option value=" I ">I</option>
                <option value=" II ">II</option>
                <option value=" III ">III</option>
                <option value=" IV ">IV</option>
                <option value=" V ">V</option>
                <option value=" VI ">VI</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="age">Nickname <small><u>(to be shown in your training ID)</u></small>
                <small>*</small></label>
              <input type="text" class="form-control" id="nickname" name="nickname" required>
            </div>
            <div class="form-group col-md-2">
              <label for="gender">Sex <small>*</small></label>
              <select class="form-control" id="sex" name="sex" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="age">Age <small>*</small></label>
              <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="form-group col-md-4">
              <label for="civilStatus">Civil Status <small>*</small></label>
              <select class="form-control" name="civilStatus" id="civilStatus" name="civilStatus" required>
                <option value="single">Single</option>
                <option value="married">Married</option>
                <option value="widow">Widow</option>
                <option value="annulled">Annulled</option>
                <option value="secret">Rather not say</option>
              </select>
            </div>
          </div>

          <!-- Contact Information -->
          <h6 class="mb-3 form-title">CONTACT INFORMATION</h6>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="phoneNumber">Phone Number <small>*</small></label>
              <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
            </div>
            <div class="form-group col-md-4">
              <label for="personalEmail">Email Address <small>*</small><small id='emailNotice'></small></label>
              <input type="email" class="form-control" id="personalEmail" name="personalEmail" required>
            </div>
            <div class="form-group col-md-4">
              <label for="altEmail">Alternative Email Address <small><i>(optional)</i></small></label>
              <input type="email" class="form-control" id="altEmail" name="altEmail">
            </div>
          </div>

          <!-- Agency Information -->
          <h6 class="mb-3 form-title">AGENCY INFORMATION</h6>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="sector">Sector <small>*</small></label>
              <select class="form-control" id="sector" name="sector">
                <option value="lgu">LGU (Local Government Unit)</option>
                <option value="suc">SUC/LUC (State University and College/Local University and College)</option>
                <option value="gocc">GOCC (Government-Owned and Controlled Corporation)</option>
                <option value="nga">NGA (National Government Agency)</option>
                <option value="others">Other</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="location">Province <small>*</small></label>
              <select name="fo" id="fo" class="form-control">
                <option value="iloilo">Iloilo</option>
                <option value="guimaras">Guimaras</option>
                <option value="antique">Antique</option>
                <option value="capiz">Capiz</option>
                <option value="negros">Negros Occidental</option>
                <option value="aklan">Aklan</option>
                <option value="other">Others</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="agencyName">Name of Agency / Organization </label>
              <select class="form-control" id="agencyName" name="agencyName">
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="position">Position <small>*</small></label>
              <input type="text" class="form-control" id="position" name="position" required>
            </div>
          </div>

          <!-- Food Restrictions -->
          <h6 class="mb-3 form-title">FOOD RESTRICTIONS <small>(leave blank if none)</small></h6>
          <div class="form-group">
            <input type="text" class="form-control" id="foodRestrictions" name="foodRestrictions">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick='updateProfile()'>Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="loadingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h3 id="updateProfileStatus">Saving...</h3>
        <img src="assets/images/loading.gif" alt="" width="20%" id="updateProfileStatusGif">
      </div>
    </div>
  </div>
</div>

<script>
  function editProfile() {

    //update the fields of edit modal
    $("#prefix").val("<?php echo $employee['prefix']; ?>");
    $("#firstName").val("<?php echo $employee['firstName']; ?>");
    $("#lastName").val("<?php echo $employee['lastName']; ?>");
    $("#middleInitial").val("<?php echo $employee['middleInitial']; ?>");
    $("#suffix").val("<?php echo $employee['suffix']; ?>");
    $("#nickname").val("<?php echo $employee['nickname']; ?>");
    $("#position").val("<?php echo $employee['position']; ?>");
    $("#fo").val("<?php echo strtolower($employee['province']); ?>");
    $("#sex").val("<?php echo $employee['sex']; ?>");
    $("#age").val("<?php echo $employee['age']; ?>");
    $("#phoneNumber").val("<?php echo $employee['phoneNumber']; ?>");
    $("#personalEmail").val("<?php echo $employee['email']; ?>");
    $("#altEmail").val("<?php echo $employee['altEmail']; ?>");
    $("#sector").val("<?php echo strtolower($employee['sector']); ?>");
    $("#agencyName").val("<?php echo $employee['agencyName']; ?>");
    $("#civilStatus").val("<?php echo $employee['civilStatus']; ?>");
    $("#foodRestrictions").val("<?php echo $employee['foodRestriction']; ?>");


    updateAgencyNameSelect();
    $("#editEmployeeModal").modal("show");
  }

  function updateProfile() {
    var prefix = $("#prefix").val();
    var firstName = $("#firstName").val();
    var middleInitial = $("#middleInitial").val();
    var lastName = $("#lastName").val();
    var suffix = $("#suffix").val();
    var nickname = $("#nickname").val();
    var position = $("#position").val();
    var province = $("#fo").val();
    var sex = $("#sex").val();
    var age = $("#age").val();
    var phoneNumber = $("#phoneNumber").val();
    var personalEmail = $("#personalEmail").val();
    var altEmail = $("#altEmail").val();
    var sector = $("#sector").val();
    var agencyID = $("#agencyName").val();
    var civilStatus = $("#civilStatus").val();
    var foodRestrictions = $("#foodRestrictions").val();

    var employeeID = <?php echo $employeeID; ?>;
    var userID = <?php echo $userID; ?>;

    $("#editEmployeeModal").modal("hide");

    $("#loadingModal").modal("show");

    // setTimeout(() => {
    //   $("#loadingModal").modal("hide");
    // }, 3000);

    $.ajax({
      type: "POST",
      url: "components/manageAccountsUpdateEmployeeProfile.php",
      data: {
        prefix: prefix,
        firstName: firstName,
        middleInitial: middleInitial,
        lastName: lastName,
        suffix: suffix,
        nickname: nickname,
        position: position,
        province: province,
        sex: sex,
        age: age,
        phoneNumber: phoneNumber,
        personalEmail: personalEmail,
        altEmail: altEmail,
        sector: sector,
        agencyID: agencyID,
        civilStatus: civilStatus,
        foodRestrictions: foodRestrictions,
        employeeID: employeeID,
        userID: userID
      },
      success: function (data) {
        $("#updateProfileStatus").text("Profile updated!");
        $("#updateProfileStatusGif").hide();
        setTimeout(() => {
          $("#loadingModal").modal("hide");
          location.reload();
        }, 1000);
      }
    })
  }

  $("#sector").change(updateAgencyNameSelect);
  $("#fo").change(updateAgencyNameSelect);

  function updateAgencyNameSelect() {
    var sector = $("#sector").val().toLowerCase();
    var province = $("#fo").val().toLowerCase();

    const agencies = <?php echo $allAgency; ?>;

    console.log(agencies);

    let agencyNameSelect = $("#agencyName");
    agencyNameSelect.empty();
    agencyNameSelect.append("<option value=''>Select Agency</option>");
    for (let i = 0; i < agencies.length; i++) {
      if (agencies[i].sector.toLowerCase() == sector && agencies[i].province.toLowerCase() == province) {
        if (agencies[i].agencyID == <?php echo $employee['agencyID']; ?>) {
          agencyNameSelect.append(`<option value='${agencies[i].agencyID}' selected>${agencies[i].agencyName}</option>`);
        } else {
          agencyNameSelect.append(`<option value='${agencies[i].agencyID}'>${agencies[i].agencyName}</option>`);
        }
      }
    }
  }
</script>