<?php
session_start();
if (isset($_SESSION['scanLogged_in'])) {
  if ($_SESSION['scanLogged_in'] === true) {
    header('Location: scan_landing.php');
    exit();
  }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $enteredPin = $_POST['pin'] ?? '';
  // Hardcoded PIN - change this to your desired PIN
  $correctPin = '1234';

  if ($enteredPin === $correctPin) {
    $_SESSION['scanLogged_in'] = true;
    header('Location: scan_landing.php');
    exit();
  } else {
    $error = 'Incorrect PIN. Please try again.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | TrackMate Scanning</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .login-container {
      max-width: 400px;
      margin: 5rem auto;
    }
  </style>
</head>

<body class="bg-light">
  <div class="login-container">
    <div class="card shadow-lg">
      <div class="card-body p-5">
        <h2 class="text-center mb-4">
          <i class="bi bi-lock"></i> Scanning Portal
        </h2>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label for="pin" class="form-label">Enter PIN</label>
            <input type="password" class="form-control form-control-lg" id="pin" name="pin" required autofocus>
          </div>
          <button type="submit" class="btn btn-primary btn-lg w-100">
            Unlock Scanner
          </button>
        </form>

        <div class="text-center mt-4">
          <a href="../" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Homepage
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>