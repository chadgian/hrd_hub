<?php
date_default_timezone_set('Asia/Manila');
include '../../components/processes/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
} else {
  if ($_SESSION['username'] == "admin") {
    header("Location: /hrd_hub/test/self-qr/admin");
    exit();
  }
}

$currentDate = (new DateTime())->format("m-d-Y");
$currentTime = (new DateTime())->format("H:i:s");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Self QR Code</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="qr-styles.css">
</head>

<body>
  <img src="img/header.png" alt="" class="img-fluid">
  <h3></h3>
  <?php include 'scan.php'; ?>

  <a href="logout.php" class="btn btn-primary">Logout</a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
</body>

</html>