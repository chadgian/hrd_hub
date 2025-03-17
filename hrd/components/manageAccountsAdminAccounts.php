<?php
include "../../components/processes/db_connection.php";

echo "<div class='ma-registeredEmployees'><div style='cursor: pointer;' onclick='registeredAccount()'><small>Registered Employees </small></div>Admin Accounts</div>
<div class='ma-search'>
  <input type='search' id='ma-searchInput' name='ma-searchInput' placeholder='Search'>
  <button class='addAgency-btn d-flex justify-content-center align-items-center gap-2' data-bs-toggle='modal' data-bs-target='#addAdminModal'>
    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-plus-circle' viewBox='0 0 16 16'>
      <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/>
      <path d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4'/>
    </svg>
    Add Account
  </button>
</div>
<div class='ma-searchResult' style='display: none;'>

</div>
<div class='ma-agencyList'>";

$getAdminAccounts = $conn->prepare("SELECT * FROM user WHERE role = 'admin' ORDER BY firstName ASC");
$getAdminAccounts->execute();
$resultAdminAccounts = $getAdminAccounts->get_result();

echo "<div class='ma-agencyListHeader' style='width: auto; '>TRAINING ADMINS</div>";

while ($dataAdminAccounts = $resultAdminAccounts->fetch_assoc()) {
  $adminFName = $dataAdminAccounts['firstName'];
  $adminLName = $dataAdminAccounts['lastName'];
  $adminMName = $dataAdminAccounts['middleInitial'];
  $adminID = $dataAdminAccounts['userID'];
  echo "<div class='ma-agencyListBody'>";
  echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>$adminFName $adminMName $adminLName</span><span class='ma-viewAgency' onclick='visitAdminPage($adminID)'>
          Edit
        </span></div>";
}

echo "</div>";

echo "</div>";

echo "<div class='ma-agencyListHeader' style='width: auto; '>PAYMENT ACCOUNT</div>";

$getPaymentAccounts = $conn->prepare("SELECT * FROM user WHERE role = 'payment' ORDER BY username ASC");
$getPaymentAccounts->execute();
$resultPaymentAccounts = $getPaymentAccounts->get_result();

while ($dataPaymentAccounts = $resultPaymentAccounts->fetch_assoc()) {
  $paymentFName = $dataPaymentAccounts['firstName'];
  $paymentLName = $dataPaymentAccounts['lastName'];
  $paymentMName = $dataPaymentAccounts['middleInitial'];
  $paymentID = $dataPaymentAccounts['userID'];
  $paymentUsername = $dataPaymentAccounts['username'];
  $paymentID = $dataPaymentAccounts['userID'];
  echo "<div class='ma-agencyListBody'>";
  echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>$paymentUsername</span><span class='ma-viewAgency' onclick='visitAdminPage($paymentID)'>
          Edit
        </span></div>";
}

echo "</div>";

echo "</div>";

?>

<script>

  document.getElementById("ma-searchInput").addEventListener("input", updateSearchResult);

  function updateSearchResult() {
    $(".ma-searchResult").html("");
    var searchQuery = $("#ma-searchInput").val().toLowerCase().trim();
    var searchType = $("#ma-searchFilter").val();
    console.log(searchQuery);

    if (searchQuery == "") {
      $(".ma-searchResult").css("display", "none");
      $(".ma-agencyList").css("display", "block");
      return;
    }

    $.ajax({
      type: "POST",
      url: "components/manageAccountsSearch.php",
      data: {
        searchQuery: searchQuery,
        userType: "admin"
      },
      success: function (data) {
        // const parsedData = JSON.parse(data);
        $(".ma-searchResult").html(data);
        $(".ma-searchResult").css("display", "block");
        $(".ma-agencyList").css("display", "none");
      }
    });
  }
</script>