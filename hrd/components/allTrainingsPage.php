<?php
if (isset($_GET['t']) && $page == 6) {
  $trainingID = $_GET['t'];
  include 'components/viewTraining.php';
} else {
  echo "<div class='container-fluid body-content'>";
  include 'components/allTrainingContent.php';
  echo "</div>";
}