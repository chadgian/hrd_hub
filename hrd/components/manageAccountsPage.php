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

<!-- Edit Agency Details Modal -->
<div class="modal fade" id="editAdminModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="editAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editAdminModalLabel">Edit Admin Details - <span id="adminNameHeader"></span>
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <di class="modal-body editAgency-content row">
        <input type="hidden" id="adminID" name="adminID" value="">
        <div class="col-md-6">
          <div class="d-flex justify-content-between mb-3">
            <label for="adminPrefix">Prefix:</label>
            <input type="text" id="adminPrefix" name="adminPrefix">
          </div>
          <div class="d-flex justify-content-between mb-3">
            <label for="adminFname">First Name:</label>
            <input type="text" id="adminFname" name="adminFname">
          </div>
          <div class="d-flex justify-content-between mb-3">
            <label for="adminMname">Middle Name:</label>
            <input type="text" id="adminMname" name="adminMname">
          </div>
          <div class="d-flex justify-content-between mb-3">
            <label for="adminLname">Last Name:</label>
            <input type="text" id="adminLname" name="adminLname">
          </div>
          <div class="d-flex justify-content-between mb-3">
            <label for="adminSuffix">Suffix:</label>
            <input type="text" id="adminSuffix" name="adminSuffix">
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex justify-content-between mb-3">
            <label for="adminRole">Role:</label>
            <select name="adminRole" id="adminRole" style="width: 50%;">
              <option value="admin">Admin</option>
              <option value="payment">Payment</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <label for="adminPosition">Position:</label>
            <input type="text" id="adminPosition">
          </div>
          <div class="d-flex justify-content-between mb-3">
            <label for="adminInitials">Initials:</label>
            <input type="text" id="adminInitials">
          </div>
          <div class="d-flex justify-content-between mb-3">
            <label for="adminUsername">Username:</label>
            <input type="text" id="adminUsername">
          </div>
          <div class="d-flex justify-content-end mb-3">
            <button onclick="resetAdminPassword()" class="btn btn-primary">Reset Password</button>
          </div>
        </div>
        </d>
      </di>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveAdminInfo()">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addAdminModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addAdminModalLabel">Add Admin Account</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <label for="createAdminRole">Role:</label>
          <Select id="createAdminRole" name="createAdminRole">
            <option value="admin">Admin</option>
            <option value="payment">Payment</option>
          </Select>
        </div>
        <div class="row">
          <div class="forAdmin col-md-6">
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-prefix">Prefix:</label>
              <input type="text" id="ca-prefix" name="ca-prefix" placeholder="Atty./Dr.">
            </div>
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-fname">First Name:</label>
              <input type="text" id="ca-fname" name="ca-fname" placeholder="Juan">
            </div>
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-mname">Middle Initial:</label>
              <input type="text" id="ca-mname" name="ca-mname" placeholder="M.">
            </div>
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-lname">Last Name</label>
              <input type="text" id="ca-lname" name="ca-lname" placeholder="Dela Cruz">
            </div>
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-suffix">Suffix:</label>
              <input type="text" id="ca-suffix" name="ca-suffix" placeholder="Jr./Sr./III">
            </div>
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-position">Position:</label>
              <input type="text" id="ca-position" name="ca-position" placeholder="Human Resource Specialist I">
            </div>
          </div>
          <div class="forPayment col-md-6">
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-initials">Initials:</label>
              <input type="text" id="ca-initials" name="ca-initials" placeholder="JDC/FO-AKLAN/MSD">
            </div>
            <div class="d-flex justify-content-between mb-3">
              <label for="ca-username">Username:</label>
              <input type="text" id="ca-username" name="ca-username" placeholder="jdcruz">
            </div>
            <div class="mt-3">
              <small><i>Default password is <b>@LingkodBayani</b></i></small>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="addAdminAccount()">Add Account</button>
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

  function registeredAccount() {
    // window.location.href = "manageAdminAccounts.php";
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

  function visitAdminPage(adminID) {
    $.ajax({
      type: "POST",
      url: "components/manageAccountsGetAdmin.php",
      data: { type: "getData", adminID: adminID },
      success: function (data) {
        const parsedData = JSON.parse(data);
        console.log(parsedData);
        $("#adminID").val(parsedData.userID);

        let adminName = parsedData.prefix !== "" ? parsedData.prefix + " " : "";
        adminName = adminName + parsedData.firstName + " ";
        adminName = parsedData.middleInitial !== "" ? adminName + parsedData.middleInitial + " " : adminName + "";
        adminName = adminName + parsedData.lastName;
        adminName = parsedData.suffix !== "" ? adminName + ", " + parsedData.suffix : adminName + "";

        $("#adminPrefix").val(parsedData.prefix);
        $("#adminFname").val(parsedData.firstName);
        $("#adminMname").val(parsedData.middleInitial);
        $("#adminLname").val(parsedData.lastName);
        $("#adminSuffix").val(parsedData.suffix);
        $("#adminRole").val(parsedData.role);
        $("#adminPosition").val(parsedData.position);
        $("#adminInitials").val(parsedData.initials);
        $("#adminUsername").val(parsedData.username);
        $("#adminNameHeader").text(adminName);

        $("#editAdminModal").modal("toggle");
      }
    });
  }

  function saveAdminInfo() {

    const adminID = $("#adminID").val();
    const adminPrefix = $("#adminPrefix").val();
    const adminFname = $("#adminFname").val();
    const adminMname = $("#adminMname").val();
    const adminLname = $("#adminLname").val();
    const adminSuffix = $("#adminSuffix").val();
    const adminRole = $("#adminRole").val();
    const adminPosition = $("#adminPosition").val();
    const adminInitials = $("#adminInitials").val();
    const adminUsername = $("#adminUsername").val();

    $.ajax({
      type: "POST",
      url: "components/manageAccountsGetAdmin.php",
      data: {
        type: "saveData",
        adminID: adminID,
        adminPrefix: adminPrefix,
        adminFname: adminFname,
        adminMname: adminMname,
        adminLname: adminLname,
        adminSuffix: adminSuffix,
        adminRole: adminRole,
        adminPosition: adminPosition,
        adminInitials: adminInitials,
        adminUsername: adminUsername
      },
      success: function (data) {
        console.log(data);
        if (data == "ok") {
          $("#editAdminModal").modal("toggle");
        } else {
          alert("Something went wrong. Please contact admin.")
          console.log(data);
        }
      }
    })
  }

  function resetAdminPassword() {
    const adminID = $("#adminID").val();
    const adminUsername = $("#adminUsername").val();

    if (confirm(`Are you sure you want to reset the password of ${adminUsername}?`)) {
      $.ajax({
        type: "POST",
        url: "components/manageAccountsResetPassword.php",
        data: {
          userID: adminID
        },
        success: function (data) {
          if (data == "ok") {
            alert("Password reset successfully!");
          } else {
            alert("Something went wrong. Please contact admin.")
            console.log(data);
          }
        }
      });
    }
  }

  function resetAccountPassword(userID) {
    if (confirm("Are you sure you want to reset the password of this account?")) {
      $.ajax({
        type: "POST",
        url: "components/manageAccountsResetPassword.php",
        data: {
          userID: userID
        },
        success: function (data) {
          if (data == "ok") {
            alert("Password reset successfully!");
          } else {
            alert("Something went wrong. Please contact admin.")
            console.log(data);
          }
        }
      });
    }
  }

  $("#createAdminRole").change(function () {
    if ($("#createAdminRole").val() == "admin") {
      $(".forAdmin").show();
    } else {
      $(".forAdmin").hide();
    }
  });

  function addAdminAccount() {
    const role = $("#createAdminRole").val();
    const prefix = $("#ca-prefix").val();
    const fname = $("#ca-fname").val();
    const mname = $("#ca-mname").val();
    const lname = $("#ca-lname").val();
    const suffix = $("#ca-suffix").val();
    const position = $("#ca-position").val();
    const initials = $("#ca-initials").val();
    const username = $("#ca-username").val();

    if (username == "") {
      alert("Please provide a username.");
      return;
    }

    $.ajax({
      type: "POST",
      url: "components/manageAccountsAddAdmin.php",
      data: {
        role: role,
        prefix: prefix,
        fname: fname,
        mname: mname,
        lname: lname,
        suffix: suffix,
        position: position,
        initials: initials,
        username: username
      },
      success: function (data) {
        if (data == "ok") {
          alert("Admin account added successfully!");
          $("#addAdminModal").modal("toggle");
          location.reload();
        } else {
          alert("Something went wrong. Please contact admin.")
          console.log(data);
        }
      }
    });
  }
</script>