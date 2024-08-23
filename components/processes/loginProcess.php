<?php

include 'db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

$loginStmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
$loginStmt->bind_param("ss", $username, $password);

if ($loginStmt->execute()) {
  $loginResult = $loginStmt->get_result();
  if ($loginResult->num_rows > 0) {
    $loginData = $loginResult->fetch_assoc();

    session_start();
    $_SESSION['username'] = $loginData['username'];
    $_SESSION['userID'] = $loginData['userID'];
    $_SESSION['prefix'] = $loginData['prefix'];
    $_SESSION['firstName'] = $loginData['firstName'];
    $_SESSION['lastName'] = $loginData['lastName'];
    $_SESSION['middleInitial'] = $loginData['middleInitial'];
    $_SESSION['suffix'] = $loginData['suffix'];
    $_SESSION['position'] = $loginData['position'];
    $_SESSION['initials'] = $loginData['initials'];
    $_SESSION['role'] = $loginData['role'];
    $_SESSION['agency'] = $loginData['agency'];

    if ($loginData['role'] == "admin") {
      header('Location: /hrd_hub/hrd');
    } else if ($loginData['role'] == "general") {
      header('Location: /hrd_hub/employeeHome.php');
    } else {
      header('Location: /hrd_hub');
    }
  } else {
    echo "No user found";
  }
}

