<?php
include '../../../components/processes/db_connection.php';

$searchInput = "%" . $_GET['searchInput'] . "%";

$trainingData = [];

$stmt = $conn->prepare("SELECT * FROM training_details WHERE trainingName LIKE ? ORDER BY endDate DESC");
$stmt->bind_param("s", $searchInput);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $trainingData[] = [
      "trainingID" => $row['trainingID'],
      "trainingName" => $row['trainingName'],
      "trainingDate" => (new DateTime($row['startDate']))->format("M d") . "-" . (new DateTime($row['endDate']))->format("d")
    ];
  }
  echo json_encode($trainingData);
} else {
  echo "0";
}

// echo "0";