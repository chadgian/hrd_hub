<?php
include '../classes/userSession.php';
$userSession = new UserSession();

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $result = $userSession->login($username, $password);
  if ($result == true) {
    switch ($_SESSION['role']) {
      case "admin":
        header('Location: /hrd_hub/hrd');
        break;
      case "general":
        header('Location: /hrd_hub/employeeHome.php');
        break;
      case "payment":
        header("Location: /hrd_hub/co");
      default:
        echo $result;
    }
  } else {
    header("Location: ../../login.php?e=1");
  }
}