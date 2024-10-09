<?php
function includeFiles()
{
  include_once __DIR__ . '/../processes/db_connection.php';
  include_once __DIR__ . '/../classes/userSession.php';
}

function redirectBasedOnRole($baseURL)
{
  switch ($_SESSION['role']) {
    case 'admin':
      header('Location: ' . $baseURL . '/hrd');
      break;
    case 'general':
      header('Location: ' . $baseURL . '/employeeHome.php');
      break;
    default:
      header('Location: ' . $baseURL);
      break;
  }
  exit();
}

function checkRememberMe($baseURL)
{
  if (isset($_COOKIE['rememberme'])) {
    $cookie = $_COOKIE['rememberme'];
    $userSession = new UserSession();
    if ($userSession->validateCookie($cookie)) {
      redirectBasedOnRole($baseURL);
    }
  }
}

function checkLogin()
{
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  includeFiles();

  $baseURL = '/hrd_hub'; // Change this to your base URL
  $currentPage = str_replace($baseURL, '', $_SERVER['PHP_SELF']);

  switch ($currentPage) {
    case '/index.php':
    case '/login.php':
      if (isset($_SESSION['userID'])) {
        redirectBasedOnRole($baseURL);
      } else {
        checkRememberMe($baseURL);
      }
      break;

    case '/employeeHome.php':
    case '/profile.php':
      if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] !== "general") {
          redirectBasedOnRole($baseURL);
        }
      } else {
        checkRememberMe($baseURL);
      }
      break;

    case '/hrd/index.php':
      if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] !== "admin") {
          redirectBasedOnRole($baseURL);
        }
      } else {
        checkRememberMe($baseURL);
      }
      break;
  }
}