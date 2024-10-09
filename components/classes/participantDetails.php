<?php

class participantDetails
{
  private $conn;
  private $participantID;
  private $employeeID;
  private $idNumber;
  private $trainingID;

  public function __construct($idType, $id, $trainingID)
  {

    date_default_timezone_set('Asia/Manila');
    include __DIR__ . '/../processes/db_connection.php';

    $this->conn = $conn;
    $this->trainingID = $trainingID;

    switch ($idType) {
      case 'participantID':
        $this->getDetails("tp.participantID", $id);
        break;
      case 'employeeID':
        $this->getDetails("e.employeeID", $id);
        break;
      case 'idNumber':
        $this->getDetails("tp.idNumber", $id);
        break;
    }

  }

  private function getDetails($column, $id)
  {
    $getDetailsStmt = $this->conn->prepare("SELECT * FROM training_participants as tp INNER JOIN employee as e ON tp.employeeID = e.employeeID INNER JOIN attendance as a ON e.employeeID = a.employeeID INNER JOIN user as u ON e.userID = u.userID WHERE $column = ? AND tp.trainingID = ? AND a.trainingID = ?");
    $getDetailsStmt->bind_param("iii", $id, $this->trainingID, $this->trainingID);
    $getDetailsStmt->execute();
    $getDetailsResult = $getDetailsStmt->get_result();

    if ($getDetailsResult->num_rows > 0) {
      $getDetailsData = $getDetailsResult->fetch_assoc();
      $this->participantID = $getDetailsData['participantID'];
      $this->employeeID = $getDetailsData['employeeID'];
      $this->idNumber = $getDetailsData['idNumber'];
    }
  }

  public function getID($idType)
  {
    return match ($idType) {
      'employeeID' => $this->employeeID,
      'participantID' => $this->participantID,
      'idNumber' => $this->idNumber,
    };
  }

  public function updateAttendance($day, $time, $timeColumn, $trainingID)
  {

    $stmt = $this->conn->prepare("UPDATE attendance SET $timeColumn = ? WHERE participantID = ? AND day = ? AND trainingID = ?");
    $stmt->bind_param("ssss", $time, $this->participantID, $day, $trainingID);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}
