
console.log("Homepage.php: okay.");
const searchInput = document.getElementById('searchHomepageInput');

searchInput.addEventListener('keydown', function (event) {
  if (event.key === 'Enter') {
    showSearchResults();
    // console.log("ok");
  }
});

searchInput.addEventListener('input', function (event) {
  const searchValue = ($("#searchHomepageInput").val()).trim();
  if (searchValue == "") {
    $(".homepage-trainings").show();
    $(".homepage-searchResult").hide();
    $(".homepage-loading").hide();
    $(".homepage-noSearchResult").hide();
  }
})

function showSearchResults() {
  const searchInput = (document.getElementById("searchHomepageInput").value).trim();
  const searchValue = searchInput.toLowerCase();

  if (searchValue == "") {
    return false;
  }

  $(".homepage-loading").show();
  $(".homepage-trainings").hide();
  $(".homepage-searchResult").hide();

  $.ajax({
    type: "GET",
    url: "components/processes/searchTrainings.php",
    data: { searchInput: searchValue },
    success: function (data) {
      $(".homepage-trainings").hide();
      $(".homepage-searchResult").show();
      $(".homepage-loading").hide();
      $(".homepage-searchResult-content").html("");
      if (data == "0") {
        $(".homepage-searchResult-content").html("<i style='display: flex; justify-content: center;'>No training found...</i>");
      } else {
        const parsedData = Object.values(JSON.parse(data));

        // console.log(parsedData);

        parsedData.forEach(training => {
          const displaySearchResult = document.querySelector(".homepage-searchResult-content");

          const trainingDiv = document.createElement("div");
          trainingDiv.classList.add("homepage-recentTrainings-training");

          const trainingDateDiv = document.createElement("div");
          trainingDateDiv.classList.add("homepage-recentTrainings-trainingDate");
          trainingDateDiv.textContent = training['trainingDate']
          trainingDiv.appendChild(trainingDateDiv);

          const trainingNameDiv = document.createElement("div");
          trainingNameDiv.classList.add("homepage-recentTrainings-trainingName");
          trainingNameDiv.textContent = training['trainingName'];
          trainingDiv.appendChild(trainingNameDiv);

          const trainingActionDiv = document.createElement("div");
          trainingActionDiv.classList.add("homepage-recentTrainings-action");
          trainingActionDiv.setAttribute("onclick", "viewTrainingDetails(" + training['trainingID'] + ")");
          trainingActionDiv.textContent = "View";
          trainingDiv.appendChild(trainingActionDiv);

          displaySearchResult.appendChild(trainingDiv);
        });
      }
    }
  })


}