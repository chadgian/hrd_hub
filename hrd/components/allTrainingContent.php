<div class='row body-content-row'>
  <div class='col-md-2 left-side'>
    <?php include 'components/navbar.php'; ?>
  </div>
  <div class='col-md-7 middle-content'>
    <h3 class="text-center">ALL TRAININGS</h3>
    <div class="allTrainingHeader">
      <input type="search" id="searchTraining" name="searchTraining">
      <select name="trainingYear" id="trainingYear">
        <option value="0">All</option>
        <?php
        $getYearStmt = $conn->prepare("SELECT DISTINCT YEAR(startDate) AS year FROM training_details ORDER BY year;");
        $getYearStmt->execute();
        $getYearResult = $getYearStmt->get_result();
        while ($row = $getYearResult->fetch_assoc()) {
          echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
        }
        ?>
      </select>
    </div>
    <div class="allTrainingContent">
    </div>
    <div class="all-training-loader" style="width: 100%; display: flex; align-items: center; justify-content: center;">
      <img src="assets/images/loading.svg" width="15%">
    </div>
  </div>
  <div class='col-md-3 right-side'>
    <?php include 'components/recents.php'; ?>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#searchTraining').on('input', function () {
      var searchValue = $(this).val().toLowerCase();
      $('.training').each(function () {
        if ($(this).text().toLowerCase().indexOf(searchValue) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });
    updateTrainingContent();
    $("#trainingYear").on('change', updateTrainingContent);
  });

  function updateTrainingContent() {
    $(".all-training-loader").show();
    $(".allTrainingContent").html("");
    var selectedYear = $("#trainingYear").val();
    $.ajax({
      type: "POST",
      url: "components/fetchAllTrainingsContent.php",
      data: { year: selectedYear },
      success: function (data) {
        $(".allTrainingContent").html(data);
        $(".all-training-loader").hide();
      }
    })
  }
</script>