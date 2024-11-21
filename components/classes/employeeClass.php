<?php

class EmployeeClass
{
  private $conn;
  private $employeeID;

  // Constructor to initialize the database connection and userID
  public function __construct($employeeID)
  {
    date_default_timezone_set('Asia/Manila');

    include __DIR__ . '/../processes/db_connection.php';
    $this->conn = $conn;

    $this->employeeID = $employeeID;
  }

  public function getEmployeeDetails()
  {
    $stmt = $this->conn->prepare("SELECT * FROM employee as e INNER JOIN agency as a ON e.agency = a.agencyID WHERE e.employeeID = ?");
    $stmt->bind_param("i", $this->employeeID);

    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
}