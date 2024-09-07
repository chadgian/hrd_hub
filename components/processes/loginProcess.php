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
      default:
        echo $result;
    }
  } else {
    echo "not okayyyy - " . $result;
  }
}