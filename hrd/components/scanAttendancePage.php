<div class='container-fluid body-content'>
  <div class='row body-content-row'>
    <div class='col-md-2 left-side'>
      <?php include 'components/navbar.php'; ?>
    </div>
    <div class='col-md-7 middle-content'>
      <div class="scanCurrent">
        <h4 class="scanTitle">Current Training</h4>
        <div class="accordion" id="scanCurrentAccordion">
          <?php
          $trainingClass = new getTraining();
          $trainingArray = $trainingClass->allTraining();

          $now = new DateTime();
          $hasCurrentTraining = false;
          foreach ($trainingArray as $training) {

            $startDate = DateTime::createFromFormat('Y-m-d', $training['startDate']);
            $start = new DateTime($training['startDate']);
            $end = new DateTime($training['endDate']);
            $numberOfDays = ($start->diff($end))->days + 1;

            if ($startDate->format('Y-m-d') == $now->format('Y-m-d')) {
              $hasCurrentTraining = true;
              echo "
              <div class='accordion-item'>
                <h1 class='accordion-header'>
                  <button class='accordion-button collapsed scanTrainingTitle' type='button' data-bs-toggle='collapse' data-bs-target='#training-collapse-{$training['trainingID']}' aria-expanded='false' aria-controls='training-collapse-{$training['trainingID']}'>
                      {$training['name']}
                  </button>
                </h1>
                <div id='training-collapse-{$training['trainingID']}' class='accordion-collapse collapse' data-bs-parent='#scanCurrentAccordion'>
                  <div class='accordion-body'>
                    <div class='scanAttendanceContainer'>";

              for ($i = 1; $i <= $numberOfDays; $i++) {
                echo "
                  <div class='scanAttendanceBtns'>
                    <h4 class='scanAttendanceDay'><b>DAY $i</b></h4>
                    <div class='scanAttendanceSelect'>
                      <a href='scan.php?id={$training['trainingID']}&d=$i&log=in'>login</a>
                      <a href='scan.php?id={$training['trainingID']}&d=$i&log=out'>logout</a>
                    </div>
                  </div>
                ";
              }

              echo "
                    </div>
                  </div>
                </div>
              </div>
              ";
            }
          }

          if (!$hasCurrentTraining) {
            echo "<i>No current training .</i>";
          }
          ?>
        </div>
      </div>
      <div class="scanPrevious">
        <h4 class="scanTitle">Previous Trainings</h4>
        <div class="accordion" id="scanPreviousAccordion">
          <?php
          foreach ($trainingArray as $training) {
            $startDate = DateTime::createFromFormat('Y-m-d', $training['startDate']);
            $start = new DateTime($training['startDate']);
            $end = new DateTime($training['endDate']);
            $numberOfDays = ($start->diff($end))->days + 1;

            if ($startDate < $now) {
              echo "
              <div class='accordion-item'>
                <h1 class='accordion-header'>
                  <button class='accordion-button collapsed scanTrainingTitle' type='button' data-bs-toggle='collapse' data-bs-target='#training-collapse-{$training['trainingID']}' aria-expanded='false' aria-controls='training-collapse-{$training['trainingID']}'>
                      {$training['name']}
                  </button>
                </h1>
                <div id='training-collapse-{$training['trainingID']}' class='accordion-collapse collapse' data-bs-parent='#scanPreviousAccordion'>
                  <div class='accordion-body'>
                    <div class='scanAttendanceContainer'>";

              for ($i = 1; $i <= $numberOfDays; $i++) {
                echo "
                <div class='scanAttendanceBtns'>
                  <h4 class='scanAttendanceDay'><b>DAY $i</b></h4>
                  <div class='scanAttendanceSelect'>
                    <a href='scan.php?id={$training['trainingID']}&d=$i&log=in'>login</a>
                    <a href='scan.php?id={$training['trainingID']}&d=$i&log=out'>logout</a>
                  </div>
                </div>
                ";
              }

              echo "
                    </div>
                  </div>
                </div>
              </div>
              ";
            }
          }
          ?>
        </div>
      </div>
    </div>
    <div class='col-md-3 right-side'>
      <?php include 'components/recents.php'; ?>
    </div>
  </div>
</div>