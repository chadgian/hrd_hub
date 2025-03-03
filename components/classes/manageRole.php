<?php

class ManageRole
{
  private $conn;
  private $currentPage;
  private $currentRole;

  public function __construct($currentPage)
  {
    include __DIR__ . '/../processes/db_connection.php';
    $this->conn = $conn;
    $this->currentPage = $currentPage;
  }

  public function checkUserRole()
  {
    if (!isset($_SESSION['role'])) {
      header('Location: /hrd_hub/');
      exit();
    }

    $role = $_SESSION['role'];
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch ($role) {
      case "admin":
        if (strpos($currentPath, '/hrd_hub/hrd/') === false) {
          header('Location: /hrd_hub/hrd/');
          exit();
        }
        break;
      case "general":
        if ($currentPath !== '/hrd_hub/employeeHome.php') {
          header('Location: /hrd_hub/employeeHome.php');
          exit();
        }
        break;
      case "payment":
        if (strpos($currentPath, '/hrd_hub/pa/') === false) {
          header('Location: /hrd_hub/pa/');
          exit();
        }
        break;
      default:
        header('Location: /hrd_hub/');
        exit();
    }
  }
}
