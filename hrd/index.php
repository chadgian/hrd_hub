<?php
include_once '../components/functions/checkLogin.php';
checkLogin();
include "../components/processes/db_connection.php";
include "../components/classes/trainingDetails.php";

// $adminProfile = new UserSession();
// $adminProfile->saveSessionData();

if
(session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $userID = $_SESSION['userID'];
  $checkLoginStmt = $conn->prepare("SELECT * FROM user WHERE
  username = ? and userID = ?");
  $checkLoginStmt->bind_param("ss", $username, $userID);

  if ($checkLoginStmt->execute()) {
    $checkLoginResult = $checkLoginStmt->get_result();
    if ($checkLoginResult->num_rows < 1) {
      header("Location: ../");
      exit();
    } else {
      // $adminProfile->logout();
    }
  }
} else {
  header("Location: ../");
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learner's Management System - HRD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles/main.css">

  <!-- Favicons -->
  <link href="assets/images/icon/favicon.ico" rel="icon">
  <link href="assets/images/icon/apple-touch-icon.png" rel="apple-touch-icon">

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <header>
    <nav class="navbar sticky-top header">
      <a class="navbar-brand" href="/index.php">
        <img src="assets/images/logo.png" alt="">
      </a>
      <div class="profile-icon btn-group">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
          <img id="profile-pic" src="assets/images/default-profile.png" alt="">
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <div class="dropdown-item" onclick="editProfile()">Edit Profile</div>
          </li>
          <li><a class="dropdown-item" href="#">Change Password</a></li>
          <li><a class="dropdown-item" href="../components/processes/logoutProcess.php"
              style="border-top: 1px solid #dddddd;">Logout</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <div id="loadingOverlay" class="loading-overlay">
    <!-- <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div> -->
    <img src="assets/images/loadingv2.svg" alt="" width="15%">
  </div>

  <!-- Edit Profile Modal -->
  <?php

  $adminProfile = new UserSession();
  $adminProfile->saveSessionData();

  if (true) {
    ?>
    <div class="modal fade" id="editProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="1"
      aria-labelledby="editProfileLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="editProfileLabel">Edit Profile</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="">
              <div class="row mb-1">
                <div class="col-md-3">
                  <label for="profilePrefix">Prefix:</label>
                  <input type="text" id="profilePrefix" name="profilePrefix" value="<?php echo $_SESSION['prefix']; ?>"
                    placeholder="<?php echo $_SESSION['prefix'] == "" ? "N/A" : ""; ?>">
                </div>
                <div class="col-md-3">
                  <label for="profileFirstName">First Name:</label>
                  <input type="text" id="profileFirstName" name="profileFirstName"
                    value="<?php echo $_SESSION['firstName']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="profileMiddleInitial">Middle Initial:</label>
                  <input type="text" id="profileMiddleInitial" name="profileMiddleInitial"
                    value="<?php echo $_SESSION['middleInitial']; ?>"
                    placeholder="<?php echo $_SESSION['middleInitial'] == "" ? "N/A" : ""; ?>">
                </div>
              </div>
              <div class="row mb-1">
                <div class="col-md-3">
                  <label for="profileLastName">Last Name:</label>
                  <input type="text" id="profileLastName" name="profileLastName"
                    value="<?php echo $_SESSION['lastName']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="profileSuffix">Suffix:</label>
                  <input type="text" id="profileSuffix" name="profileSuffix" value="<?php echo $_SESSION['suffix']; ?>"
                    placeholder="<?php echo $_SESSION['suffix'] == "" ? "N/A" : ""; ?>">
                </div>
                <div class="col-md-3">
                  <label for="profilePosition">Position:</label>
                  <input type="text" id="profilePosition" name="profilePosition"
                    value="<?php echo $_SESSION['position']; ?>">
                </div>
              </div>
              <div class="row mb-1">
                <div class="col-md-3">
                  <label for="profileInitials">Initials:</label>
                  <input type="text" id="profileInitials" name="profileInitials"
                    value="<?php echo $_SESSION['initials']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="profileUsername">Username:</label>
                  <input type="text" id="profileUsername" name="profileUsername"
                    value="<?php echo $_SESSION['username']; ?>">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="updateAdminProfile()">Save</button>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <div class="main-body">
    <?php
    $pageName = ['homePage', 'addTrainingPage', 'noticesPage', 'messagesPage', 'manageAccountsPage', 'scanAttendancePage', 'allTrainingsPage', 'databasePage'];

    $page = $_GET['p'] ?? 0;

    if ($page > 8) {
      header("Location: index.php");
      exit();
    }

    if (isset($_GET['t']) && $page == 0) {
      $trainingID = $_GET['t'];
      include 'components/viewTraining.php';
    } else {
      include 'components/' . $pageName[$page] . '.php';
    }

    ?>
  </div>

  <script>
    var page = <?php echo $page; ?>;

    const pageArray = ["home", "addTraining", "notices", "messages", "manageAccounts", "scanAttendance", "allTrainings", "database"];

    const pageName = document.querySelectorAll('#' + pageArray[page]);
    // const pageName = document.getElementById(pageArray[page]);

    pageName.forEach(function (element) {
      element.style.backgroundColor = "#24305E";
      element.style.color = "#fff";
    })

    function editProfile() {
      $("#editProfile").modal("show");
    }

    function changePassword() {

    }

    function updateAdminProfile() {
      const prefix = $("#profilePrefix").val();
      const firstName = $("#profileFirstName").val();
      const middleInitial = $("#profileMiddleInitial").val();
      const lastName = $("#profileLastName").val();
      const suffix = $("#profileSuffix").val();
      const position = $("#profilePosition").val();
      const initials = $("#profileInitials").val();
      const username = $("#profileUsername").val();

      $.ajax({
        type: "POST",
        url: "components/updateAdminProfile.php",
        data: {
          prefix: prefix,
          firstName: firstName,
          middleInitial: middleInitial,
          lastName: lastName,
          suffix: suffix,
          position: position,
          initials: initials,
          username: username
        },
        success: function (response) {
          if (response == "ok") {
            $("#editProfile").modal("hide");
            setTimeout(() => {
              alert("Profile updated successfully");
            }, 500);
          } else {
            console.log(response);
          }
        }
      });
    }

    function toggleLoadingOverlay() {
      const loadingElement = document.getElementById('loadingOverlay');

      // Check if the loading element is currently hidden
      const isHidden = loadingElement.style.display === "none" || loadingElement.style.display === "";

      // Set the display property based on the current state
      loadingElement.style.display = isHidden ? 'flex' : 'none';
    }

  </script>
</body>

</html>