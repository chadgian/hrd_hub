<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="assets/pa-main.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <header class="sticky-top">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" onclick="setAddress('p', 0)" style="cursor: pointer;">
          <img src="../hrd/assets/images/logo.png" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
          aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active" onclick="setAddress('p', 0)">Home</a>
            <a class="nav-link" onclick="setAddress('p', 1)">All Trainings</a>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <div id="loadingOverlay" class="loading-overlay">
    <!-- <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div> -->
    <img src="assets/images/loadingv2.svg" alt="" width="15%">
  </div>

  <main id="main-content">
    <!-- Content will be loaded here -->
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    handleActiveNav();
    getAddress();

    function navigatePage(pageNumber) {
      const mainContent = document.getElementById('main-content');
      mainContent.innerHTML = "<div style='display: flex; width: 100%; justify-content: center;'><img src='assets/images/loading.svg' width='20%'></div>";

      const pages = {
        0: 'components/pages/homepage.php',
        1: 'components/pages/allTrainings.php',
      };

      // Check if the pageNumber exists in the pages object
      const pageUrl = pages[pageNumber] || 'components/pages/page404.php';

      const xhr = new XMLHttpRequest();
      xhr.open('GET', pageUrl, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            mainContent.innerHTML = xhr.responseText;
            // evaluateScripts(mainContent);
            // if (pages == 0) { forHomepage() };
            forHomepage();
          }
        }
      };
      xhr.send();
    }

    function getAddress() {
      const urlParams = new URLSearchParams(window.location.search);
      const pageNumber = urlParams.get('p') || 0; // Default to 0 if 'p' is not found
      const trainingID = urlParams.get('t') || 0;

      if (trainingID > 0) {
        viewTraining(trainingID);
      } else {
        navigatePage(pageNumber);
      }
    }

    function viewTraining(trainingID) {
      const mainContent = document.getElementById('main-content');
      mainContent.innerHTML = "<div style='display: flex; width: 100%; justify-content: center;'><img src='assets/images/loading.svg' width='20%'></div>";

      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'components/pages/viewTraining.php?trainingID=' + trainingID, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            mainContent.innerHTML = xhr.responseText;
            // evaluateScripts(mainContent);
            // forHomepage();
            forViewTraining(trainingID);
          }
        }
      };
      xhr.send();

    }

    function setAddress(parameter, pageNumber) {
      const baseUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}`;

      const newUrl = pageNumber === 0 ? baseUrl : `${baseUrl}?${parameter}=${pageNumber}`;

      history.pushState({ path: newUrl }, '', newUrl);
      getAddress();
    }

    function handleActiveNav() {
      const navLinks = document.querySelectorAll('.nav-link');

      function handleClick(event) {
        navLinks.forEach(link => link.classList.remove('active'));
        event.target.classList.add('active');
      }

      navLinks.forEach(link => link.addEventListener('click', handleClick));
    }

    // function evaluateScripts(container) {
    //   const scripts = container.querySelectorAll('script');
    //   scripts.forEach((script) => {
    //     const newScript = document.createElement('script');
    //     newScript.text = script.textContent;
    //     document.body.appendChild(newScript);
    //   });
    // }

    // function evaluateScripts(container) {
    //   const scripts = container.querySelectorAll('script');

    //   scripts.forEach((script) => {
    //     // Create a hash of the script content to check if it exists
    //     const scriptContent = script.textContent.trim();

    //     // Check if a script with the same content already exists in the document
    //     const existingScripts = document.querySelectorAll('script');

    //     existingScripts.forEach(existingScript => {
    //       if (existingScript.textContent.trim() === scriptContent) {
    //         existingScript.remove();
    //       }
    //     });

    //     const newScript = document.createElement('script');
    //     newScript.textContent = scriptContent;
    //     document.body.appendChild(newScript);
    //   });
    // }


    function viewTrainingDetails(trainingID) {
      setAddress('t', trainingID);
    }

    window.onpopstate = function (event) {
      getAddress();
    };

    function forHomepage() {

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
    }

    function forViewTraining(trainingID) {
      document.getElementById("participant-search-input").addEventListener("input", populateParticipantTable);

      populateParticipantTable();
      togglePaymentStatus();

      function populateParticipantTable() {
        $(".participant-content ").html("<div style='display: flex; width: 100%; justify-content: center;'><img src='assets/images/loading.svg' width='20%'></div>");
        const searchQuery = document.getElementById("participant-search-input").value.trim();

        $.ajax({
          type: "GET",
          url: "components/processes/searchParticipant.php",
          data: {
            searchQuery: searchQuery,
            trainingID: trainingID
          },
          success: function (data) {
            $(".participant-content").html(data);
          }
        });
      }

      document.getElementById("payment-checkbox").addEventListener("click", togglePaymentStatus);

      function togglePaymentStatus() {
        const paymentButton = document.getElementById("payment-checkbox");
        const paymentField = document.getElementById("payment-field");

        if (paymentButton.checked) {
          paymentField.style.display = "flex";
        } else {
          paymentField.style.display = "none";
        }
      }
    }

    function viewParticipantStatus(participantID) {
      toggleLoadingOverlay();

      // // Simulate an asynchronous task
      // setTimeout(function () {
      //   toggleLoadingOverlay();
      // }, 3000); // Simulate a 3-second task

      $.ajax({
        type: "GET",
        url: "components/processes/getParticipantStatus.php",
        data: { participantID: participantID },
        success: function (data) {
          toggleLoadingOverlay();
          $("#viewParticipantStatusModal").modal("toggle");
        }
      });
    }

    function viewParticipantDetails(participantID) {
      toggleLoadingOverlay();

      $.ajax({
        type: "GET",
        url: "components/processes/getParticipantDetails.php",
        data: { participantID: participantID },
        success: function (data) {
          toggleLoadingOverlay();
          // const parsedData = data;
          // console.log(parsedData);
          // console.log(Object.values(JSON.parse(data)));
          console.log(data);


          var fullname = `${data.prefix} ${data.firstName} ${data.middleInitial} ${data.lastName} ${data.suffix}`;

          console.log(fullname);

          fullname = fullname.replace(/\s+/g, ' ').trim();

          // const confSlip = searchFile("../assets/conf_slips/" + data.trainingID, data.registrationID);

          $("#participantName").val(fullname);
          $("#participantSex").val(data.sex);
          $("#participantAge").val(data.age);
          $("#participantCivilStatus").val(data.civilStatus);
          $("#participantNumber").val(data.phoneNumber);
          $("#participantEmail").val(data.email);
          $("#participantPosition").val(data.position);
          $("#participantSector").val(data.sector);
          $("#participantAgency").val(data.agencyName);
          $("#participantFO").val("CSC FO-" + data.province);
          $("#participantFoodRestrictions").val(data.foodRestriction);
          $("#participantDate").val(formatDateTime(data.timeDate));
          $("#participantComfirmationSlip").prop("href", data.file)

          $("#viewParticipantDetailsModal").modal("toggle");
        },
        error: function (xhr, status, error) {
          console.log(xhr.responseText);
          toggleLoadingOverlay();
        }
      });

      function formatDateTime(dateTimeStr) {
        const months = [
          "January", "February", "March", "April", "May", "June",
          "July", "August", "September", "October", "November", "December"
        ];

        // Parse the date string into a Date object
        const dateObj = new Date(dateTimeStr.replace(' ', 'T'));

        // Extract date components
        const year = dateObj.getFullYear();
        const month = months[dateObj.getMonth()];
        const day = dateObj.getDate();

        // Extract time components and format to 12-hour format
        let hours = dateObj.getHours();
        const minutes = dateObj.getMinutes().toString().padStart(2, '0');
        const period = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12 || 12; // Convert to 12-hour format and handle midnight case

        // Construct the formatted date string
        const formattedDate = `${month} ${day.toString().padStart(2, '0')}, ${year} | ${hours}:${minutes} ${period}`;

        return formattedDate;
      }
    }

    function toggleLoadingOverlay() {
      const loadingElement = document.getElementById('loadingOverlay');

      // Check if the loading element is currently hidden
      const isHidden = loadingElement.style.display === "none" || loadingElement.style.display === "";

      // Set the display property based on the current state
      loadingElement.style.display = isHidden ? 'flex' : 'none';
    }
  </script>
</body>

</html>