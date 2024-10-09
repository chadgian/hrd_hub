<?php

include_once 'components/functions/checkLogin.php';
checkLogin();

if (isset($_SESSION['userID'])) {
  $username = $_SESSION['username'];
  $prefix = $_SESSION['prefix'];
  $firstName = $_SESSION['firstName'];
  $middleInitial = $_SESSION['middleInitial'];
  $lastName = $_SESSION['lastName'];
  $suffix = $_SESSION['suffix'];
  $agency = $_SESSION['agency'];
  $position = $_SESSION['position'];
  $userID = $_SESSION['userID'];

  include "components/processes/db_connection.php";

  $getEmployeeDetailStmt = $conn->prepare("SELECT * FROM employee WHERE userID = ?");
  $getEmployeeDetailStmt->bind_param("i", $userID);

  if ($getEmployeeDetailStmt->execute()) {
    $getEmployeeDetailResult = $getEmployeeDetailStmt->get_result();

    if ($getEmployeeDetailResult->num_rows > 0) {
      while ($getEmployeeDetailData = $getEmployeeDetailResult->fetch_assoc()) {

        $employeeID = $getEmployeeDetailData['employeeID'];

        // personal information
        $prefixRaw = $getEmployeeDetailData['prefix'];
        $prefixDetail = trim($getEmployeeDetailData['prefix']) == "" ? "N/A" : $getEmployeeDetailData['prefix'];
        $firstNameDetail = $getEmployeeDetailData['firstName'];
        $middleInitialRaw = $getEmployeeDetailData['middleInitial'];
        $middleInitialDetail = $getEmployeeDetailData['middleInitial'] == "" ? "N/A" : $getEmployeeDetailData['middleInitial'];
        $lastNameDetail = $getEmployeeDetailData['lastName'];
        $suffixRaw = $getEmployeeDetailData['suffix'];
        $suffixDetail = $getEmployeeDetailData['suffix'] == "" ? "N/A" : $getEmployeeDetailData['suffix'];
        $nicknameDetail = $getEmployeeDetailData['nickname'];
        $ageDetail = $getEmployeeDetailData['age'];
        $sexDetail = $getEmployeeDetailData['sex'];
        $civilStatusDetail = $getEmployeeDetailData['civilStatus'];

        // contact information
        $phoneNumberDetail = $getEmployeeDetailData['phoneNumber'];
        $emailDetail = $getEmployeeDetailData['email'];
        $altEmailDetail = $getEmployeeDetailData['altEmail'] == "" ? "N/A" : $getEmployeeDetailData['altEmail'];

        //agency information
        $sectorRaw = $getEmployeeDetailData['sector'];
        $sectorDetail = match ($getEmployeeDetailData['sector']) {
          "nga" => "National Government Agency",
          "suc" => "State/Local University and College",
          "gocc" => "Government Owned and Controlled Corporation",
          "lgu" => "Local Government Unit",
          default => "Other",
        };

        $agencyDetail = $getEmployeeDetailData['agencyName'];
        $positionDetail = $getEmployeeDetailData['position'];

        $foRaw = $getEmployeeDetailData['fo'];
        $foDetail = match ($getEmployeeDetailData['fo']) {
          "iloilo" => "FO - Iloilo",
          "antique" => "FO - Antique",
          "capiz" => "FO - Capiz",
          "aklan" => "FO - Aklan",
          "negros" => "FO - Negros Occidental",
          "guimaras" => "FO - Guimaras"
        };

        //food restriction
        $foodRestrictionDetail = $getEmployeeDetailData['foodRestriction'] == "" ? "N/A" : $getEmployeeDetailData['foodRestriction'];
      }
    } else {
      echo "No employee record found";
    }
  } else {
    echo $getEmployeeDetailStmt->error;
  }
} else {
  Header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CSC-RO6 L&D Hub | Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/icon/favicon.ico" rel="icon">
  <link href="assets/img/icon/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <!-- <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Selecao
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body style="background-color: #D9D9D9;">

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center sticky-top">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <!-- <h1><a href="/hrd_hub"><img src="assets/img/logo.png" alt="" width="100%" height="100%"></a></h1> -->
        <!-- Uncomment below if you prefer to use an image logo -->
        <a href="/hrd_hub"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto" href="employeeHome.php#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="employeeHome.php#guidelines">Guidelines</a></li>
          <li><a class="nav-link scrollto" href="employeeHome.php#trainings">Trainings</a></li>
          <li><a class="nav-link scrollto" href="employeeHome.php#policies">Training Policies</a></li>
          <li><a class="nav-link scrollto" href="employeeHome.php#faq">FAQs</a></li>
          <li><a class="nav-link scrollto" href="employeeHome.php#team">About</a></li>
          <li><a class="nav-link scrollto " href="employeeHome.php#contact">Contact Us</a></li>
          <li><a class="nav-link scrollto " href="components/processes/logoutProcess.php">Logout</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <div class="userProfile">
    <div class="profileTitle">User Profile</div>
    <div class="profile-content">
      <div class="row d-flex gap-2">
        <div class="col-md-6">
          <div class="profile-name">
            <div class="profile-fullname">
              <?php
              if ($prefix !== "" & $suffix !== "") {
                $fullName = "$prefix $firstName $middleInitial $lastName, $suffix";
              } else if ($prefix !== "") {
                $fullName = "$prefix $firstName $middleInitial $lastName";
              } else if ($suffix !== "") {
                $fullName = "$firstName $middleInitial $lastName, $suffix";
              } else {
                $fullName = "$firstName $middleInitial $lastName";
              }

              echo $fullName;
              ?>
            </div>
            <div class="profile-position">
              <?php echo $position; ?>
            </div>
            <div class="profile-agency">
              <?php echo $agency; ?>
            </div>
          </div>
          <div class="profile-details">
            <div class="profile-details-header">
              <span style="font-weight: 500; font-size: large;">Registration Details</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-pencil-square" viewBox="0 0 16 16" style="cursor: pointer;" data-bs-toggle='modal'
                data-bs-target='#editRegistrationDetails'>
                <path
                  d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                  d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
              </svg>
            </div>
            <div class="profile-details-content">
              <div class="detail-title">PERSONAL INFORMATION</div>
              <div class="row">
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Prefix:</div>
                  <div class="detail-content"><?php echo $prefixDetail; ?></div>
                </div>
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">First Name:</div>
                  <div class="detail-content"><?php echo $firstNameDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Middle Initial:</div>
                  <div class="detail-content"><?php echo $middleInitialDetail; ?></div>
                </div>
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Last Name:</div>
                  <div class="detail-content"><?php echo $lastNameDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Suffix:</div>
                  <div class="detail-content"><?php echo $suffixDetail; ?></div>
                </div>
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Nickname:</div>
                  <div class="detail-content"><?php echo $nicknameDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Age:</div>
                  <div class="detail-content"><?php echo $ageDetail; ?></div>
                </div>
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Sex:</div>
                  <div class="detail-content"><?php echo $sexDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Civil Status:</div>
                  <div class="detail-content"><?php echo $civilStatusDetail; ?></div>
                </div>
              </div>
              <div class="detail-title">CONTACT INFORMATION</div>
              <div class="row">
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Number:</div>
                  <div class="detail-content"><?php echo $phoneNumberDetail; ?></div>
                </div>
                <div class="col-md-6 d-flex detail-data">
                  <div class="detail-name">Alternative Email:</div>
                  <div class="detail-content"><?php echo $altEmailDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 d-flex detail-data">
                  <div class="detail-name">Email:</div>
                  <div class="detail-content"><?php echo $emailDetail; ?></div>
                </div>
              </div>
              <div class="detail-title">AGENCY INFORMATION</div>
              <div class="row">
                <div class="col-md-12 d-flex detail-data flex-row">
                  <div class="detail-name" style="flex: 1;">Sector:</div>
                  <div class="detail-content" style="flex: 2;"><?php echo $sectorDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 d-flex detail-data flex-row">
                  <div class="detail-name" style="flex: 1;">Name of Agency:</div>
                  <div class="detail-content" style="flex: 2;"><?php echo $agencyDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 d-flex detail-data flex-row">
                  <div class="detail-name" style="flex: 1;">Position:</div>
                  <div class="detail-content" style="flex: 2;"><?php echo $positionDetail; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 d-flex detail-data flex-row">
                  <div class="detail-name" style="flex: 1;">CSC Field Office:</div>
                  <div class="detail-content" style="flex: 2;"><?php echo $foDetail; ?></div>
                </div>
              </div>
              <div class="detail-title">FOOD RESTRICTIONS:</div>
              <div class="col-md-12 d-flex detail-data">
                <div class="detail-content"><?php echo $foodRestrictionDetail; ?></div>
              </div>
            </div>
          </div>
          <button class="changePassword-btn" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change
            Password</button>
        </div>
        <div class="col-md-6">
          <div class="profile-notification">
            <marquee behavior="scroll" direction="right" scrollamount="10" style="color: red;">
              This text will scroll from right to left.
            </marquee>
          </div>
          <div class="profile-trainings">
            <div class="profile-trainings-header">
              <div class="training-header-nav" id="nav-0"
                onclick="populateProfileTraining(0, <?php echo $employeeID; ?>)" style="font-weight: bold;">
                Recent Training</div>
              <div class="training-header-nav" id="nav-1"
                onclick="populateProfileTraining(1, <?php echo $employeeID; ?>)" style="font-weight: 500;">
                Training History</div>
            </div>
            <div class="profile-trainings-content">

            </div>
          </div>
        </div>
      </div>
    </div>
    <?php

    // echo $firstName;
    
    ?>
  </div>

  <!-- Edit profile modal -->
  <div class="modal fade" id="editRegistrationDetails" tabindex="-1" aria-labelledby="editRegistrationDetailsLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="font-weight-bold">Edit Registration Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body">
          <form class="regForm" enctype="multipart/form-data">
            <input type="hidden" id="trainingID" name="trainingID">
            <!-- Personal Information -->
            <h6 class="mb-3 form-title">PERSONAL INFORMATION</h6>
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="prefix">Prefix <small><i>(optional)</i></small></label>
                <input type="text" class="form-control" id="prefix" name="prefix" value="<?php echo $prefixRaw; ?>">
              </div>
              <div class="form-group col-md-3">
                <label for="firstName">First Name <small>*</small></label>
                <input type="text" class="form-control" id="firstName" name="firstName"
                  value="<?php echo $firstNameDetail; ?>" required>
              </div>
              <div class="form-group col-md-2">
                <label for="middleInitial">Middle Initial <small>*</small></label>
                <input type="text" class="form-control" id="middleInitial" name="middleInitial"
                  value="<?php echo $middleInitialRaw; ?>">
              </div>
              <div class="form-group col-md-3">
                <label for="lastName">Last Name <small>*</small></label>
                <input type="text" class="form-control" id="lastName" name="lastName"
                  value="<?php echo $lastNameDetail; ?>" required>
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
                <script>
                  document.getElementById("suffix").value = "<?php echo $suffixRaw; ?>";
                </script>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="age">Nickname <small><u>(to be shown in your training ID)</u></small>
                  <small>*</small></label>
                <input type="text" class="form-control" id="nickname" name="nickname"
                  value="<?php echo $nicknameDetail; ?>" required>
              </div>
              <div class="form-group col-md-2">
                <label for="gender">Sex <small>*</small></label>
                <select class="form-control" id="sex" name="sex" required>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
                <script>
                  document.getElementById("sex").value = "<?php echo $sexDetail; ?>";
                </script>
              </div>
              <div class="form-group col-md-2">
                <label for="age">Age <small>*</small></label>
                <input type="number" class="form-control" id="age" name="age" value="<?php echo $ageDetail; ?>"
                  required>
              </div>
              <div class="form-group col-md-4">
                <label for="civilStatus">Civil Status <small>*</small></label>
                <select class="form-control" name="civilStatus" id="civilStatus" name="civilStatus" required>
                  <option value="single">Single</option>
                  <option value="married">Married</option>
                  <option value="widow">Widow</option>
                </select>
                <script>
                  document.getElementById("civilStatus").value = "<?php echo $civilStatusDetail; ?>";
                </script>
              </div>
            </div>

            <!-- Contact Information -->
            <h6 class="mb-3 form-title">CONTACT INFORMATION</h6>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="phoneNumber">Phone Number <small>*</small></label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                  value="<?php echo $phoneNumberDetail; ?>" required>
              </div>
              <div class="form-group col-md-4">
                <label for="personalEmail">Email Address <small>*</small><small id='emailNotice'></small></label>
                <input type="email" class="form-control" id="personalEmail" name="personalEmail"
                  value="<?php echo $emailDetail; ?>" required>
              </div>
              <div class="form-group col-md-4">
                <label for="altEmail">Alternative Email Address <small><i>(optional)</i></small></label>
                <input type="email" class="form-control" id="altEmail" name="altEmail"
                  value="<?php echo $altEmailDetail; ?>">
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
                <script>
                  document.getElementById("sector").value = "<?php echo $sectorRaw; ?>";
                </script>
              </div>
              <div class="form-group col-md-6">
                <label for="agencyName">Name of Agency / Organization <small><u>(please don't
                      abbreviate)</u></small> <small>*</small></label>
                <input type="text" class="form-control" id="agencyName" name="agencyName"
                  value="<?php echo $agencyDetail; ?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="position">Position <small>*</small></label>
                <input type="text" class="form-control" id="position" name="position"
                  value="<?php echo $positionDetail; ?>" required>
              </div>
              <div class="form-group col-md-6">
                <label for="location">CSC Field Office that has jurisdiction in your area <small>*</small></label>
                <select name="fo" id="fo" class="form-control">
                  <option value="iloilo">FO Iloilo</option>
                  <option value="guimaras">FO Guimaras</option>
                  <option value="antique">FO Antique</option>
                  <option value="capiz">FO Capiz</option>
                  <option value="negros">FO Negros Occidental</option>
                  <option value="aklan">FO Aklan</option>
                </select>
                <script>
                  document.getElementById("fo").value = "<?php echo $foRaw; ?>";
                </script>
              </div>
            </div>

            <!-- Food Restrictions -->
            <h6 class="mb-3 form-title">FOOD RESTRICTIONS <small>(leave blank if none)</small></h6>
            <div class="form-group">
              <input type="text" class="form-control" id="foodRestrictions" name="foodRestrictions"
                value="<?php echo $foodRestrictionDetail; ?>">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary"
            onclick="saveEditProfile('<?php echo $userID; ?>')">Save</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal training history -->
  <div class="modal fade" id="trainingHistory" tabindex="-1" aria-labelledby="trainingHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content training-detail">
        <div class="modal-header training-detail-header">
          <div id="modalTrainingName">Modal title</div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body">
          <div class='training-detail-content'>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Date of Training:
              </div>
              <div class='training-detail-group-content' style='flex: 2;' id="modalTrainingDate">

              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Venue:
              </div>
              <div class='training-detail-group-content' style='flex: 2;' id="modalTrainingVenue">

              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Mode of Training:
              </div>
              <div class='training-detail-group-content' style='flex: 2;' id="modalTrainingMode">

              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Registration Fee:
              </div>
              <div class='training-detail-group-content' style='flex: 2;' id="modalTrainingFee">

              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Training Hours:
              </div>
              <div class='training-detail-group-content' style='flex: 2;' id="modalTrainingHours">

              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Curriculum Area:
              </div>
              <div class='training-detail-group-content' style='flex: 2;' id="modalTrainingArea">

              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Description:
              </div>
              <div class='training-detail-group-content' style='flex: 2;' id="modalTrainingDescription">

              </div>
            </div>
            <div class='row mt-4'>
              <div class='col-md-6 payment-section'>
                <div class='payment-indicator my-3'>
                  Payment:
                  <div class='payment-toggle' style='font-weight: bold;' id="modalPaymentContent"> </div>
                </div>
                <div class='vertical-line'>
                  <div style='color: #24305E; font-weight: bold; font-size: medium;'>PAYMENT DETAILS:</div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>OR Number:</div>
                      <div class='payment-details-line-content'
                        style='font-weight: bold; color: #CE2F2F; font-size: medium;' id="modalOrNumber"> </div>
                    </div>
                  </div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>Amount Paid:</div>
                      <div class='payment-details-line-content' style='font-weight: bold;' id="modalAmountPaid"></div>
                    </div>
                  </div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>Discount:</div>
                      <div class='payment-details-line-content' style='font-weight: bold;' id="modalDiscount"> </div>
                    </div>
                  </div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>Date of Payment:</div>
                      <div class='payment-details-line-content' style='font-weight: bold;' id="modalPaymentDate"> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class='col-md-6 attendance-section mt-3'>
                <div class='attendance-indicator'>
                  Attendance:
                  <div class='attendance-toggle' style='font-weight: bold;' id="modalAttendanceContent"></div>
                </div>
                <div class='attendance-remark'>
                  <div class='attendance-remark-title'>Attendance Remark:</div>
                  <div class='attendance-remark-content' id="modalAttendanceRemarks"> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Change Password Modal -->
  <div class="modal fade" id="changePasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="changePasswordModalLabel">Change Password</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>

            <div class="mb-4">
              <label for="oldPassword" class="form-label d-flex align-items-center">Current Password:<span
                  id="wrongPassword" style="color: red; font-size: small; padding-left: 1em; display: none;">Wrong
                  password!</span>
              </label>
              <input class="form-control" type="password" id="oldPassword" name="oldPassword">
            </div>
            <div class="mb-2">
              <label for="newPassword" class="form-label">New Password:<span id="passwordMatch"
                  style="color: red; font-size: small; padding-left: 1em; display: none;">Passwords don't
                  match</span></label>
              <input class="form-control" type="password" id="newPassword" name="newPassword" disabled>
            </div>
            <div class="mb-2">
              <label for="newPassword2" class="form-label">Confirm New Password:<span id="passwordMatch2"
                  style="color: red; font-size: small; padding-left: 1em; display: none;">Passwords don't
                  match</span></label>
              <input class="form-control" type="password" id="newPassword2" name="newPassword2" disabled>
              <div class="passwordLength"
                style="color: red; font-size: small; padding-left: 0.5em; padding-top: 1em; display: none;">
                Password
                length must be at least 8 characters.
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="changePassword-btn" onclick="changePassword()"
            disabled>Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="toast-container position-static">
    <div id="changePasswordToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
      style="position: fixed; bottom: 0; right: 0;">
      <div class="toast-header d-flex justify-content-between">
        <strong class="me-auto">Change Password</strong>
        <small class="text-body-secondary">just now</small>
      </div>
      <div class="toast-body">
        Password changed successfully!
      </div>
    </div>

    <!-- <div id="updateProfileToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
      style="position: absolute; bottom: 0; right: 0;">
      <div class=" toast-header">
        <strong class="me-auto">Edit Profile</strong>
        <small class="text-body-secondary">just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        Profile updated successfully!
      </div>
    </div> -->
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

  <script>

    document.getElementById("oldPassword").addEventListener("input", function () {
      const oldPassword = document.getElementById("oldPassword").value;

      const userID = "<?php echo $userID; ?>";

      $.ajax({
        type: "POST",
        url: "components/processes/checkPassword.php",
        data: {
          oldPassword: oldPassword,
          userID: userID
        },
        success: function (response) {
          if (response == "ok") {
            $("#wrongPassword").hide();
            document.getElementById("newPassword").disabled = false;
            document.getElementById("newPassword2").disabled = false;
          } else {
            $("#wrongPassword").show();
            document.getElementById("newPassword").disabled = true;
            document.getElementById("newPassword2").disabled = true;
          }
        }
      })
    });

    document.getElementById("newPassword2").addEventListener("input", function () {
      const newPassword = document.getElementById("newPassword").value;
      const newPassword2 = document.getElementById("newPassword2").value;

      if (newPassword !== "" && newPassword2 !== "") {
        if (newPassword.length > 7 && newPassword2.length > 7) {
          $(".passwordLength").hide();
          if (newPassword === newPassword2) {
            document.getElementById("changePassword-btn").disabled = false;
            $("#passwordMatch").hide();
            $("#passwordMatch2").hide();
          } else {
            document.getElementById("changePassword-btn").disabled = true;
            $("#passwordMatch").show();
            $("#passwordMatch2").show();
          }
        } else {
          document.getElementById("changePassword-btn").disabled = true;
          $(".passwordLength").show();
        }

      } else {
        $("#passwordMatch").hide();
        $("#passwordMatch2").hide();
        document.getElementById("changePassword-btn").disabled = true;
      }
    });

    document.getElementById("newPassword").addEventListener("input", function () {
      const newPassword = document.getElementById("newPassword").value;
      const newPassword2 = document.getElementById("newPassword2").value;

      if (newPassword !== "" && newPassword2 !== "") {
        if (newPassword.length > 7 && newPassword2.length > 7) {
          $(".passwordLength").hide();
          if (newPassword === newPassword2) {
            document.getElementById("changePassword-btn").disabled = false;
            $("#passwordMatch").hide();
            $("#passwordMatch2").hide();
          } else {
            document.getElementById("changePassword-btn").disabled = true;
            $("#passwordMatch").show();
            $("#passwordMatch2").show();
          }
        } else {
          document.getElementById("changePassword-btn").disabled = true;
          $(".passwordLength").show();
        }

      } else {
        $("#passwordMatch").hide();
        $("#passwordMatch2").hide();
        document.getElementById("changePassword-btn").disabled = true;
      }
    });

    populateProfileTraining(0, <?php echo $employeeID; ?>);

    function populateProfileTraining(type, employeeID) {
      if (type == 0) {
        $("#nav-0").css("font-weight", "bold");
        $("#nav-1").css("font-weight", "500");
      } else {
        $("#nav-1").css("font-weight", "bold");
        $("#nav-0").css("font-weight", "500");
      }
      $.ajax({
        url: 'components/processes/fetchProfileTrainings.php',
        type: 'POST',
        data: { type: type, id: employeeID },
        success: function (response) {
          // const json_response = JSON.parse(response)
          $(".profile-trainings-content").html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log("failed");
          console.error('Error in registration data:', textStatus, errorThrown);
        }
      });
    }

    function updateViewTrainingHistoryModal(participantID) {
      $.ajax({
        url: 'components/processes/fetchForModalProfileTrainings.php',
        type: 'POST',
        data: { id: participantID },
        success: function (response) {
          const data = JSON.parse(response);

          const attendanceRemark = data.attendanceRemark;

          switch (attendanceRemark.split("::")[0]) {
            case "0":
              $("#modalAttendanceRemarks").html("Replaced");
              break;

            case "1":
              $("#modalAttendanceRemarks").html("Valid Cancellation");
              break;

            case "2":
              $("#modalAttendanceRemarks").html("Invalid Cancellation");
              break;

            case "3":
              $("#modalAttendanceRemarks").html("Lack of training hours");
              break;

            case "4":
              $("#modalAttendanceRemarks").html("Absent/No show");
              break;

            case "5":
              $("#modalAttendanceRemarks").html("Other: " + attendanceRemark.split("::")[1]);
              break;

            default:
              $("#modalAttendanceRemarks").html(attendanceRemark);
              break;
          }

          $("#modalTrainingName").html(data.trainingName);
          $("#modalTrainingDate").html(data.trainingDate);
          $("#modalTrainingVenue").html(data.trainingVenue);
          $("#modalTrainingMode").html(data.trainingMode);
          $("#modalTrainingFee").html("₱ " + data.trainingFee + ".00");
          $("#modalTrainingHours").html(data.trainingHours);
          $("#modalTrainingArea").html(data.trainingArea);
          $("#modalTrainingDescription").html(data.trainingDescription);
          $("#modalPaymentContent").html(data.paymentContent);
          $("#modalAttendanceContent").html(data.attendanceContent);
          $("#modalAttendanceContent").css("color", data.attendanceColor);
          $("#modalPaymentContent").css("color", data.paymentColor);

          if (data.paymentContent == "Unpaid") {
            $("#modalOrNumber").html("N/A");
            $("#modalAmountPaid").html("N/A");
            $("#modalDiscount").html("N/A");
            $("#modalPaymentDate").html("N/A");
          } else {
            $("#modalOrNumber").html(data.orNumber);
            $("#modalAmountPaid").html(data.amount);
            $("#modalDiscount").html(data.discount);
            $("#modalPaymentDate").html(data.paymentDate);
          }

          console.log(data.trainingDate);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log("failed");
          console.error('Error in registration data:', textStatus, errorThrown);
        }
      });
    }

    function saveEditProfile(userID) {
      $.ajax({
        url: 'components/processes/updateProfile.php',
        type: 'POST',
        data: {
          prefix: document.getElementById("prefix").value,
          firstName: document.getElementById("firstName").value,
          middleInitial: document.getElementById("middleInitial").value,
          lastName: document.getElementById("lastName").value,
          suffix: document.getElementById("suffix").value,
          nickname: document.getElementById("nickname").value,
          sex: document.getElementById("sex").value,
          age: document.getElementById("age").value,
          civilStatus: document.getElementById("civilStatus").value,
          phoneNumber: document.getElementById("phoneNumber").value,
          email: document.getElementById("personalEmail").value,
          altEmail: document.getElementById("altEmail").value,
          sector: document.getElementById("sector").value,
          agencyName: document.getElementById("agencyName").value,
          position: document.getElementById("position").value,
          fo: document.getElementById("fo").value,
          foodRestriction: document.getElementById("foodRestrictions").value
        },
        success: function (response) {
          console.log(response);
          if (response == "ok") {
            location.reload();
            $("#editRegistrationDetails").modal("toggle");
            $("#updateProfileToast").show();
          } else {
            console.error(response);
          }
        }
      });
    }

    function changePassword() {
      const userID = "<?php echo $userID; ?>";

      const newPassword = document.getElementById("newPassword").value;
      const newPassword2 = document.getElementById("newPassword2").value;

      if (newPassword === newPassword2 && (newPassword !== "" && newPassword2 !== "")) {
        $.ajax({
          url: 'components/processes/changePassword.php',
          type: 'POST',
          data: {
            oldPassword: document.getElementById("oldPassword").value,
            newPassword: newPassword,
            confirmPassword: newPassword2,
            userID: userID
          },
          success: function (response) {
            if (response == "ok") {
              $("#changePasswordModal").modal("toggle");
              $("#newPassword").val("");
              $("#newPassword2").val("");
              $("#oldPassword").val("");

              const toastBootstrap = bootstrap.Toast.getOrCreateInstance(document.getElementById("changePasswordToast"));

              toastBootstrap.show();
            }
          }
        })
      } else {
        alert("Passwords don't match!");
      }
    }

    document.addEventListener('keydown', function (event) {
      // Check if the Ctrl key is pressed along with 'S'
      if (event.ctrlKey && (event.key === 'g' || event.key === 'G')) {
        event.preventDefault();  // Prevent the default browser behavior

        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(document.getElementById("changePasswordToast"));

        toastBootstrap.show();
      }
    });
  </script>

  <script src="assets/js/main.js"></script>

</body>

</html>