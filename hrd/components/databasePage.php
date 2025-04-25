<div class='container-fluid body-content'>
  <div class='row body-content-row'>
    <div class='col-md-2 left-side'>
      <?php include 'components/navbar.php'; ?>
    </div>
    <div class='col-md-7 middle-content'>
      <?php
      if (isset($_GET['t']) && $page == 7) {
        $trainingID = $_GET['t'];
        include 'components/databaseTraining.php';
      } else {
        include 'components/databaseHomepage.php';
      }
      ?>
    </div>
    <div class='col-md-3 right-side'>
      <?php include 'components/recents.php'; ?>
    </div>
  </div>
</div>