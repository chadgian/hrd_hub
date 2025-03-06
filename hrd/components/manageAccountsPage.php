<div class='container-fluid body-content'>
  <div class='row body-content-row'>
    <div class='col-md-2 left-side'>
      <?php include 'components/navbar.php'; ?>
    </div>
    <div class='col-md-7 middle-content'>
      <div class="ma-body" id="ma-body">

      </div>
    </div>
    <div class='col-md-3 right-side' id="right-side">
      <?php

      // if ($_GET['p'] == "4" && isset($_GET['agency'])) {
      //   include 'components/ma-sideContent.php';
      // } else {
      //   include 'components/recents.php';
      // }
      
      // include 'components/recents.php';
      
      ?>
    </div>
  </div>
</div>

<!-- Add Agency Modal -->
<div class="modal fade" id="addAgencyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addAgencyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addAgencyModalLabel">Add Agency</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <di class="modal-body addAgency-content">
        <div class="row mb-3">
          <div class="d-flex flex-column">
            <label for="addAgency-name">Agency Name</label>
            <input type="text" id="addAgency-name" name="addAgency-name">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6 d-flex flex-column">
            <label for="addAgency-sector">Sector</label>
            <select name="addAgency-sector" id="addAgency-sector">
              <option value="">Select Sector...</option>
              <option value="lgu">LGU (Local Government Unit)</option>
              <option value="suc">SUC/LUC (State University and College/Local University and College)</option>
              <option value="gocc">GOCC (Government-Owned and Controlled Corporation)</option>
              <option value="nga">NGA (National Government Agency)</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="col-md-6 d-flex flex-column">
            <label for="addAgency-province">Province</label>
            <select name="addAgency-province" id="addAgency-province">
              <option value="aklan">Aklan</option>
              <option value="antique">Antique</option>
              <option value="capiz">Capiz</option>
              <option value="guimaras">Guimaras</option>
              <option value="iloilo">Iloilo</option>
              <option value="negros">Negros Occidental</option>
              <option value="other">Other</option>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="d-flex flex-column">
            <label for="addAgency-address">Address</label>
            <input type="text" name="addAgency-address" id="addAgency-address">
          </div>
        </div>
      </di>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="addAgencyProcess()">Add</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Agency Details Modal -->
<div class="modal fade" id="editAgencyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="editAgencyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editAgencyModalLabel">Edit Agency</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <di class="modal-body editAgency-content">
        <div class="row mb-3">
          <div class="d-flex flex-column">
            <label for="editAgency-name">Agency Name</label>
            <input type="text" id="editAgency-name" name="editAgency-name">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6 d-flex flex-column">
            <label for="editAgency-sector">Sector</label>
            <select name="editAgency-sector" id="editAgency-sector">
              <option value="">Select Sector...</option>
              <option value="lgu">LGU (Local Government Unit)</option>
              <option value="suc">SUC/LUC (State University and College/Local University and College)</option>
              <option value="gocc">GOCC (Government-Owned and Controlled Corporation)</option>
              <option value="nga">NGA (National Government Agency)</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="col-md-6 d-flex flex-column">
            <label for="editAgency-province">Province</label>
            <select name="editAgency-province" id="editAgency-province">
              <option value="aklan">Aklan</option>
              <option value="antique">Antique</option>
              <option value="capiz">Capiz</option>
              <option value="guimaras">Guimaras</option>
              <option value="iloilo">Iloilo</option>
              <option value="negros">Negros Occidental</option>
              <option value="other">Other</option>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="d-flex flex-column">
            <label for="editAgency-address">Address</label>
            <input type="text" name="editAgency-address" id="editAgency-address">
          </div>
        </div>
      </di>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveAgencyInfo()">Save</button>
      </div>
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
        // setTimeout(() => {
        //   fillAgencyDetails(0, agencyID);
        // }, 300);
        fillAgencyDetails(0, agencyID);
      }
    });
  }

  async function fillAgencyDetails(key, agencyID) {
    const page = ["ma-sideContent.php", "recents.php"];

    await new Promise((resolve, reject) => {
      $("#right-side").load('components/' + page[key], function (response, status, xhr) {
        if (status == "error") {
          reject(`Error loading component: ${xhr.status} ${xhr.statusText}`);
        } else {
          resolve();
        }
      });
    });

    if (key == "0") {
      const sectors = {
        lgu: "Local Government Unit",
        gocc: "Government-Owned and Controlled Corporation",
        suc: "State University/College / Local University/College",
        nga: "National Government Agency",
        others: "Other"
      };

      try {
        const data = await $.ajax({
          type: "POST",
          url: "components/ma-getAgencyDetails.php",
          data: { agencyID: agencyID }
        });

        const parsedData = JSON.parse(data);
        $("#agencyDetails-sector").html(sectors[parsedData.sector.toLowerCase()]);
        $("#agencyDetails-province").html(capitalizeFirstLetter(parsedData.province));
        $("#agencyDetails-address").html(parsedData.address === "" ? "N/A" : parsedData.address);
        $("#agency-details-agencyID").val(parsedData.agencyID);
      } catch (error) {
        console.error("Error fetching agency details:", error);
      }
    }
  }

  function capitalizeFirstLetter(string) {
    if (string.length === 0) return string; // Handle empty string
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function loadContent() {
    var urlParams = new URLSearchParams(window.location.search);
    var p = urlParams.get('p');
    var agency = urlParams.get('agency');
    var employee = urlParams.get('e');

    if (p == "4" && agency > 0) {
      if (employee > 0) {
        viewEmployeeProfile(employee);
      } else {
        var agencyID = agency;
        visitAgencyPage(agencyID);
      }
    } else if (p == "4") {
      $("#ma-body").html("<div class='d-flex justify-content-center align-items-center' style='height: 100%;'><img src='assets/images/ma-loading.svg' alt='' width='20%'></div>");
      $.ajax({
        type: "GET",
        url: "components/manageAccountsHome.php",
        success: function (data) {
          $("#ma-body").html(data);
          fillAgencyDetails(1, 0);
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
        fillAgencyDetails(1, 0);
      }
    })
  }

  function editEmployeeProfile(employeeID) {

  }

  function employeeProfileMenu(employeeID) {

  }

  function addAgencyProcess() {
    toggleLoadingOverlay();
    $("#addAgencyModal").modal("toggle");

    const agencyName = $("#addAgency-name").val();
    const sector = $("#addAgency-sector").val();
    const province = $("#addAgency-province").val();
    const address = $("#addAgency-address").val();

    $.ajax({
      type: "POST",
      url: "components/manageAccountsAddAgency.php",
      data: {
        agencyName: agencyName,
        sector: sector,
        province: province,
        address: address
      },
      success: function (data) {
        console.log(data);
        if (data == "ok") {
          toggleLoadingOverlay();
          loadContent();
          setTimeout(() => {
            alert("Agency added successfully!");
          }, 300);
        } else if (data == "existing") {
          toggleLoadingOverlay();
          setTimeout(() => {
            alert("Agency already exist!");
          }, 300);
        } else {
          toggleLoadingOverlay();
          setTimeout(() => {
            alert("Agency was not added due to error!");
          }, 300);
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      }
    });

  }

  function editAgencyModal() {
    var urlParams = new URLSearchParams(window.location.search);
    var agency = urlParams.get('agency');
    toggleLoadingOverlay();

    $.ajax({
      type: "GET",
      url: "components/manageAccountsEditAgency.php",
      data: {
        agencyID: agency,
      },
      success: function (data) {
        const parsedData = JSON.parse(data);
        // console.log(data);
        $("#editAgency-name").val(parsedData.agencyName);
        $("#editAgency-sector").val(parsedData.sector.toLowerCase());
        $("#editAgency-province").val(parsedData.province.toLowerCase());
        $("#editAgency-address").val(parsedData.address);
        $("#editAgencyModal").modal("toggle");
        toggleLoadingOverlay()
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      }
    });
  }

  function saveAgencyInfo() {
    var urlParams = new URLSearchParams(window.location.search);
    var agency = urlParams.get('agency');
    $("#editAgencyModal").modal("toggle");
    toggleLoadingOverlay();

    $.ajax({
      type: "POST",
      url: "components/manageAccountsEditAgency.php",
      data: {
        agencyID: agency,
        agencyName: $("#editAgency-name").val(),
        sector: $("#editAgency-sector").val(),
        province: $("#editAgency-province").val(),
        address: $("#editAgency-address").val(),
      },
      success: function (data) {
        if (data == "ok") {
          toggleLoadingOverlay();

          setTimeout(() => {
            alert("Agency information updated successfully");
            window.location.reload();
          }, 100);
        } else {
          console.log(data);
        }
      }
    });
  }

  function adminAccount() {
    // window.location.href = "manageAdminAccounts.php";
    $("#ma-body").html("<div class='d-flex justify-content-center align-items-center' style='height: 100%;'><img src='assets/images/ma-loading.svg' alt='' width='20%'></div>");
    $.ajax({
      type: "GET",
      url: "components/manageAccountsAdminAccounts.php",
      success: function (data) {
        $("#ma-body").html(data);
        fillAgencyDetails(1, 0);
      }
    });
  }
</script>