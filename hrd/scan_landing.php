<?php
include '../components/processes/db_connection.php';
session_start();
// $_SESSION['scanLogged_in'] = false; // Simulate login for testing

$getTrainingNowStmt = $conn->prepare("SELECT * FROM training_details WHERE CURDATE() BETWEEN startDate AND endDate");
$getTrainingNowStmt->execute();
$trainingNowResult = $getTrainingNowStmt->get_result();

$trainingNow = null;
$currentDay = 0;
$totalDays = 0;

if ($trainingNowResult->num_rows > 0) {
  $trainingNow = $trainingNowResult->fetch_assoc();

  // Calculate training days
  $startDate = new DateTime($trainingNow['startDate']);
  $endDate = new DateTime($trainingNow['endDate']);
  $today = new DateTime();

  $currentDay = $today->diff($startDate)->days + 1;
  $totalDays = $endDate->diff($startDate)->days + 1;
}

if (!isset($_SESSION['scanLogged_in']) || $_SESSION['scanLogged_in'] !== true) {
  header("Location: scan_login.php");
  exit();
}

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: scan_login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Scanning | TrackMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    .scanning-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .action-btn {
      transition: transform 0.2s ease;
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .action-btn:hover {
      transform: translateY(-5px);
    }

    .training-header {
      background: linear-gradient(135deg, #2c3e50, #3498db);
      color: white;
      border-radius: 15px;
    }

    .day-badge {
      font-size: 1.1rem;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(5px);
    }
  </style>
</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="bi bi-qr-code-scan"></i> QR Scanning Attendance
      </a>
      <div class="ms-auto navbar-nav">
        <a href="?logout=true" class="nav-link btn btn-light">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
    </div>
  </nav>

  <main class="container my-5">
    <?php if ($trainingNow): ?>
      <div class="scanning-container">
        <div class="card shadow-lg">
          <div class="card-header training-header text-center py-4">
            <h1 class="h2 mb-3"><?= htmlspecialchars($trainingNow['trainingName']) ?></h1>
            <span class="day-badge badge rounded-pill">
              Day <?= $currentDay ?> of <?= $totalDays ?>
            </span>
          </div>

          <div class="card-body">
            <div class="row g-4 mt-3">
              <div class="col-md-6">
                <a href="scan.php?action=in&t=<?php echo $trainingNow['trainingID']; ?>&d=<?php echo $currentDay; ?>"
                  class="btn btn-success action-btn">
                  <div class="text-center">
                    <i class="bi bi-box-arrow-in-right fs-1"></i>
                    <h3 class="h5 mt-2">Log In</h3>
                    <small class="d-block">Start your training day</small>
                  </div>
                </a>
              </div>
              <div class="col-md-6">
                <a href="scan.php?action=out&t=<?php echo $trainingNow['trainingID']; ?>&d=<?php echo $currentDay; ?>"
                  class="btn btn-danger action-btn">
                  <div class="text-center">
                    <i class="bi bi-box-arrow-right fs-1"></i>
                    <h3 class="h5 mt-2">Log Out</h3>
                    <small class="d-block">End your training day</small>
                  </div>
                </a>
              </div>
            </div>

            <div class="text-center mt-5">
              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Please select either Log In or Log Out to start scanning of IDs
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card shadow-lg">
            <div class="card-body text-center py-5">
              <div class="mb-4">
                <i class="bi bi-calendar-x-fill text-danger" style="font-size: 4rem;"></i>
              </div>
              <h2 class="h4 mb-3">No Active Training Session</h2>
              <p class="text-muted mb-4">
                There is currently no ongoing training. Please check the schedule or contact your administrator.
              </p>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </main>

  <footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
      <span class="text-muted">
        © 2025 CSC ROVI LEARN. All rights reserved.
      </span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>