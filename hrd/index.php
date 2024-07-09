<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learner's Management System - HRD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/styles/main.css">
</head>

<body>
  <header>
    <nav class="navbar sticky-top header">
      <a class="navbar-brand" href="/index.php">
        <img src="assets/images/logo.png" alt="">
      </a>
      <div class="profile-icon">
        <a href="#">
          <img id="profile-pic" src="assets/images/default-profile.png" alt="">
        </a>
      </div>
    </nav>
  </header>

  <div class="main-body">
    <div class="container-fluid body-content">
      <div class="row body-content-row">
        <div class="col-md-2 left-side">
          <?php include 'components/navbar.php'; ?>
        </div>
        <div class="col-md-8 middle-content">
          <?php include 'components/main-content.php'; ?>
        </div>
        <div class="col-md-2 right-side">

        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>