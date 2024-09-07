<?php
include "../components/processes/db_connection.php";
session_start();

if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $userID = $_SESSION['userID'];

  $checkLoginStmt = $conn->prepare("SELECT * FROM user WHERE username = ? and userID = ?");
  $checkLoginStmt->bind_param("ss", $username, $userID);

  if ($checkLoginStmt->execute()) {
    $checkLoginResult = $checkLoginStmt->get_result();
    if ($checkLoginResult->num_rows < 1) {
      header("Location: ../");
      exit();
    }
  }
} else {
  header("Location: ../");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learner's Management System - HRD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/styles/main.css">

  <!-- Favicons -->
  <link href="assets/images/icon/favicon.ico" rel="icon">
  <link href="assets/images/icon/apple-touch-icon.png" rel="apple-touch-icon">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <header>
    <nav class="navbar sticky-top header">
      <a class="navbar-brand" href="/index.php">
        <img src="assets/images/logo.png" alt="">
      </a>
      <div class="profile-icon">
        <a href="#">
          <img id="profile-pic" src="assets/images/default-profile.png" alt="">
        </a>
      </div>
    </nav>
  </header>

  <div class="main-body">
    <?php
    $pageName = ['homePage', 'addTrainingPage', 'noticesPage', 'messagesPage', 'generateIDPage', 'manageAccountsPage', 'scanAttendancePage', 'allTrainingsPage', 'databasePage'];

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

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>

  <script>
    var page = <?php echo $page; ?>;

    const pageArray = ["home", "addTraining", "notices", "messages", "generateID", "manageAccounts", "scanAttendance", "allTrainings", "database"];

    const pageName = document.querySelectorAll('#' + pageArray[page]);
    // const pageName = document.getElementById(pageArray[page]);

    pageName.forEach(function (element) {
      element.style.backgroundColor = "#24305E";
      element.style.color = "#fff";
    })



  </script>
</body>

</html>