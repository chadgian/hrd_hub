<div class='container-fluid body-content'>
  <div class='row body-content-row'>
    <div class='col-md-2 left-side'>
      <?php include 'components/navbar.php'; ?>
    </div>
    <div class='col-md-7 middle-content'>
      <div class="ma-body" id="ma-body">

      </div>
    </div>
    <div class='col-md-3 right-side'>
      <?php include 'components/recents.php'; ?>
    </div>
  </div>
</div>

<script>
  function visitAgencyPage(agencyID) {
    $("#ma-body").html("<div class='d-flex justify-content-center align-items-center' style='height: 100%;'><img src='assets/images/ma-loading.svg' alt='' width='20%'></div>");
    var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?p=4&agency=' + agencyID;
    history.pushState({ path: newUrl }, '', newUrl);

    $.ajax({
      type: "POST",
      url: "components/ma-viewAgency.php",
      data: { agencyID: agencyID },
      success: function (data) {
        $("#ma-body").html(data);
      }
    });
  }

  function loadContent() {
    var urlParams = new URLSearchParams(window.location.search);
    var p = urlParams.get('p');
    var agency = urlParams.get('agency');
    var employee = urlParams.get('e');

    if (p === "4" && agency > 0) {
      if (employee > 0) {
        viewEmployeeProfile(employee);
      } else {
        var agencyID = agency;
        visitAgencyPage(agencyID);
      }
    } else if (p === "4") {
      $("#ma-body").html("<div class='d-flex justify-content-center align-items-center' style='height: 100%;'><img src='assets/images/ma-loading.svg' alt='' width='20%'></div>");
      $.ajax({
        type: "GET",
        url: "components/manageAccountsHome.php",
        success: function (data) {
          $("#ma-body").html(data);
        }
      });
    }
  }

  // Run loadContent on page load
  document.addEventListener('DOMContentLoaded', function () {
    loadContent();
  });

  // Handle back/forward navigation
  window.onpopstate = function (event) {
    loadContent();
  };

  function goBackMAHome() {
    var urlParams = new URLSearchParams(window.location.search);
    var p = urlParams.get('p');
    var agency = urlParams.get('agency');
    var employee = urlParams.get('e');
    if (employee > 0) {
      var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?p=4&agency=' + agency;
    } else if (agency > 0) {
      var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?p=4';
    }

    history.pushState({ path: newUrl }, '', newUrl);
    loadContent();
  }

  function viewEmployeeProfile(employeeID) {
    $("#ma-body").html("<div class='d-flex justify-content-center align-items-center' style='height: 100%;'><img src='assets/images/ma-loading.svg' alt='' width='20%'></div>");

    var urlParams = new URLSearchParams(window.location.search);
    var agency = urlParams.get('agency');

    var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?p=4&agency=' + agency + '&e=' + employeeID;
    history.pushState({ path: newUrl }, '', newUrl);
    $.ajax({
      type: "POST",
      url: "components/manageAccountsEmployeeProfile.php",
      data: {
        employeeID: employeeID
      },
      success: function (data) {
        $("#ma-body").html(data);

      }
    })
  }
</script>