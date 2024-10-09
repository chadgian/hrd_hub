<?php
date_default_timezone_set('Asia/Manila');

// Get current date and time
$currentDate = new DateTime();
$currentDateFormatted = $currentDate->format("m-d-Y");
$currentTime = $currentDate->format("H:i:s");

// Define time intervals for login and logout
$timeIntervals = [
  'day1' => [
    'date' => '10-05-2024',
    'login' => ['start' => '07:00:00', 'end' => '10:00:00'],
    'logout' => ['start' => '14:00:00', 'end' => '17:00:00'],
  ],
  'day2' => [
    'date' => '10-06-2024',
    'login' => ['start' => '07:00:00', 'end' => '10:00:00'],
    'logout' => ['start' => '10:00:00', 'end' => '12:00:00'],
  ],
];

// Function to check if current time is within a given interval
function isTimeInInterval($currentTime, $start, $end)
{
  return $currentTime >= $start && $currentTime <= $end;
}

// Determine the username value and status based on current date and time
function determineUsernameAndStatus($currentDateFormatted, $currentTime, $timeIntervals)
{
  foreach ($timeIntervals as $key => $intervals) {
    if ($currentDateFormatted === $intervals['date']) {
      if (isTimeInInterval($currentTime, $intervals['login']['start'], $intervals['login']['end'])) {
        return ['usernameVal' => "{$key}login", 'status' => 'LOGIN'];
      } elseif (isTimeInInterval($currentTime, $intervals['logout']['start'], $intervals['logout']['end'])) {
        return ['usernameVal' => "{$key}logout", 'status' => 'LOGOUT'];
      } else {
        return ['usernameVal' => 'default', 'status' => 'none'];
      }
    }
  }
  return ['usernameVal' => 'default', 'status' => 'none'];
}

$result = determineUsernameAndStatus($currentDateFormatted, $currentTime, $timeIntervals);

$usernameVal = $result['usernameVal'];
$status = $result['status'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    :root {
      --bs-body-bg: #bad5e9;
    }

    .login-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .login-form {
      background-color: #739ebd;
      border-radius: 5px;
      color: black;
    }
  </style>
</head>

<body>
  <img src="img/header.png" alt="" class="img-fluid">
  <div class="login-container container-sm mt-5" id="login-container">
    <span class="fs-4 fw-bolder" style="color: #013378;">ATTENDANCE - <?php echo $status; ?></span>
    <span class="fs-5 fw-bolder" style="color: #013378;">2024 REGIONAL HR LEADERS SUMMIT</span>
    <form action="login-process.php" method="GET"
      class="login-form mt-3 py-3 px-5 d-flex flex-column justify-content-center align-items-center">
      <input type="hidden" name="username" id="username" value="<?php echo $usernameVal; ?>">
      <div class="d-flex flex-column justify-content-center align-items-center">
        <label for="password" class="fw-bold fs-4">PASSWORD</label>
        <?php
        if (isset($_GET['err'])) {
          echo "<small style='color: red; font-style: italic;'>error password</small>";
        }

        ?>
        <input type="text" name="password" id="password" class="form-control my-2 bg-white"
          placeholder="Type the password here...">
        <button type="submit" class="btn btn-primary fw-bolder">ENTER</button>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>

  <script>
    const username = "<?php echo $usernameVal; ?>";
    const passwordInput = document.getElementById('password');

    if (username == "default") {
      const container = document.getElementById("login-container");
      container.innerHTML = "<span class='fs-2 fw-semibold'>Please come back another time...</span>"
    }

  </script>

</body>

</html>