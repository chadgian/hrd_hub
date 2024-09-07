<?php
function checkLogin()
{
  // Start session if not already started
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  include_once __DIR__ . '/../processes/db_connection.php';
  include_once __DIR__ . '/../classes/userSession.php';

  // Base URL of your application
  $baseURL = '/hrd_hub'; // Change this to your base URL

  $currentPage = str_replace($baseURL, '', $_SERVER['PHP_SELF']);

  // $redirectPath = getRedirectPath('default', $baseURL);

  switch ($currentPage) {
    case 'index.php':
      if (isset($_SESSION['userID'])) {
        switch ($_SESSION['role']) {
          case 'admin':
            header('Location: ' . $baseURL . '/hrd');
            break;

          case 'general':
            header('Location: ' . $baseURL . '/employeeHome.php');
            break;
        }
        exit();
      }
      // Check if user is logged in via remember me cookie
      elseif (isset($_COOKIE['rememberme'])) {
        $cookie = $_COOKIE['rememberme'];
        $userSession = new UserSession();
        if ($userSession->validateCookie($cookie)) {
          switch ($_SESSION['role']) {
            case 'admin':
              header('Location: ' . $baseURL . '/hrd');
              break;

            case 'general':
              header('Location: ' . $baseURL . '/employeeHome.php');
              break;
          }
          exit();
        }
      }
      break;

    case 'employeeHome.php':
      if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] !== "general") {
          switch ($_SESSION['role']) {
            case 'admin':
              header('Location: ' . $baseURL . '/hrd');
              break;

            default:
              header("Location: $baseURL");
              break;
          }
          exit();
        }
      }
      // Check if user is logged in via remember me cookie
      elseif (isset($_COOKIE['rememberme'])) {
        $cookie = $_COOKIE['rememberme'];
        $userSession = new UserSession();
        if ($userSession->validateCookie($cookie)) {
          if ($_SESSION['general'] !== "general") {
            switch ($_SESSION['role']) {
              case 'admin':
                header('Location: ' . $baseURL . '/hrd');
                break;

              default:
                header('Location: ' . $baseURL);
                break;
            }
            exit();
          }
        }
      }
      break;

    case 'profile.php':
      if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] !== "general") {
          switch ($_SESSION['role']) {
            case 'admin':
              header('Location: ' . $baseURL . '/hrd');
              break;

            default:
              header("Location: $baseURL");
              break;
          }
          exit();
        }
      }
      // Check if user is logged in via remember me cookie
      elseif (isset($_COOKIE['rememberme'])) {
        $cookie = $_COOKIE['rememberme'];
        $userSession = new UserSession();
        if ($userSession->validateCookie($cookie)) {
          if ($_SESSION['general'] !== "general") {
            switch ($_SESSION['role']) {
              case 'admin':
                header('Location: ' . $baseURL . '/hrd');
                break;

              default:
                header('Location: ' . $baseURL);
                break;
            }
            exit();
          }
        }
      }
      break;

    case 'hrd/index.php':
      if (isset($_SESSION['userID'])) {
        if ($_SESSION['role'] !== "admin") {
          switch ($_SESSION['role']) {
            case 'general':
              header('Location: ' . $baseURL . '/employeeHome.php');
              break;

            default:
              header("Location: $baseURL");
              break;
          }
          exit();
        }
      }
      // Check if user is logged in via remember me cookie
      elseif (isset($_COOKIE['rememberme'])) {
        $cookie = $_COOKIE['rememberme'];
        $userSession = new UserSession();
        if ($userSession->validateCookie($cookie)) {
          if ($_SESSION['role'] !== "admin") {
            switch ($_SESSION['role']) {
              case 'general':
                header('Location: ' . $baseURL . '/employeeHome.php');
                break;

              default:
                header('Location: ' . $baseURL);
                break;
            }
            exit();
          }
        }
      }
      break;

    case 'login.php':
      if (isset($_SESSION['userID'])) {
        switch ($_SESSION['role']) {
          case 'general':
            header('Location: ' . $baseURL . '/employeeHome.php');
            break;

          case 'admin':
            header('Location: ' . $baseURL . '/hrd');
            break;

          default:
            header("Location: $baseURL");
            break;
        }
        exit();
      }
      // Check if user is logged in via remember me cookie
      elseif (isset($_COOKIE['rememberme'])) {
        $cookie = $_COOKIE['rememberme'];
        $userSession = new UserSession();
        if ($userSession->validateCookie($cookie)) {
          switch ($_SESSION['role']) {
            case 'general':
              header('Location: ' . $baseURL . '/employeeHome.php');
              break;

            case 'admin':
              header('Location: ' . $baseURL . '/hrd');
              break;

            default:
              header("Location: $baseURL");
              break;
          }
          exit();
        }
      }
      break;
  }
}