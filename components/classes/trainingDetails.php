<?php
class getTraining
{
  private $conn;
  private $trainingID;
  private $name;
  private $admin;
  private $startDate;
  private $endDate;
  private $venue;
  private $mode;
  private $fee;
  private $hours;
  private $registeredPax;
  private $targetPax;
  private $details;
  private $currArea;
  private $requiredDocs;
  private $trainingType;
  private $regStatus;
  private $lastUpdateBy;


  public function __construct($trainingID = null)
  {
    date_default_timezone_set('Asia/Manila');
    include __DIR__ . '/../processes/db_connection.php';

    $this->trainingID = $trainingID;
    $this->conn = $conn;

    $getTrainingDetailsStmt = $this->conn->prepare("SELECT * FROM training_details WHERE trainingID = ?");
    $getTrainingDetailsStmt->bind_param("i", $this->trainingID);

    if ($getTrainingDetailsStmt->execute()) {
      $result = $getTrainingDetailsStmt->get_result();
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $this->name = $row['trainingName'];
        $this->admin = $row['training_admin'];
        $this->startDate = $row['startDate'];
        $this->endDate = $row['endDate'];
        $this->venue = $row['venue'];
        $this->mode = $row['mode'];
        $this->fee = $row['fee'];
        $this->hours = $row['trainingHours'];
        $this->registeredPax = $row['registeredPax'];
        $this->targetPax = $row['targetPax'];
        $this->details = $row['details'];
        $this->currArea = $row['currArea'];
        $this->requiredDocs = $row['requiredDocs'];
        $this->trainingType = $row['trainingType'];
        $this->regStatus = $row['openReg'];
        $this->lastUpdatedBy = $row['lastUpdateBy'];
      }
    }
  }

  public function toArray()
  {
    return [
      'trainingID' => $this->trainingID,
      'name' => $this->name,
      'admin' => $this->admin,
      'startDate' => $this->startDate,
      'endDate' => $this->endDate,
      'venue' => $this->venue,
      'mode' => $this->mode,
      'fee' => $this->fee,
      'hours' => $this->hours,
      'registeredPax' => $this->registeredPax,
      'targetPax' => $this->targetPax,
      'details' => $this->details,
      'currArea' => $this->currArea,
      'requiredDocs' => $this->requiredDocs,
      'trainingType' => $this->trainingType,
      'regStatus' => $this->regStatus
    ];
  }

  public function allTraining()
  {
    $getTrainingStmt = $this->conn->prepare("SELECT * FROM training_details");
    $getTrainingStmt->execute();
    $result = $getTrainingStmt->get_result();
    $trainingList = [];
    while ($row = $result->fetch_assoc()) {
      $training = new getTraining($row['trainingID']);
      $trainingList[] = $training->toArray();
    }
    return $trainingList;
  }

  public function getTrainingName()
  {
    return $this->name;
  }

  public function getTrainingID()
  {
    return $this->trainingID;
  }

  public function getParticipants()
  {
    $getPaxStmt = $this->conn->prepare("SELECT * FROM training_participants as tp INNER JOIN employee as e ON tp.employeeID = e.employeeID  WHERE tp.trainingID = ?");
    $getPaxStmt->bind_param("i", $this->trainingID);
    $getPaxStmt->execute();
    $result = $getPaxStmt->get_result();
    $paxList = [];
    while ($row = $result->fetch_assoc()) {
      $paxList[] = [
        'numID' => $row['idNumber'],
        'name' => $this->getFullName($row),
        'timestamp' => "00:00:00"
      ];
    }
    return $paxList;
  }

  private function getFullName($paxData)
  {
    $fullName = '';

    // Add prefix if available
    if (!empty($paxData['prefix'])) {
      $fullName .= $paxData['prefix'] . ' ';
    }

    // Add first name
    $fullName .= $paxData['firstName'] . ' ';

    // Add middle initial if available
    if (!empty($paxData['middleInitial'])) {
      $fullName .= $paxData['middleInitial'] . ' ';
    }

    // Add last name
    $fullName .= $paxData['lastName'];

    // Add suffix if available
    if (!empty($paxData['suffix'])) {
      $fullName .= ' ' . $paxData['suffix'];
    }

    return trim($fullName);
  }

}