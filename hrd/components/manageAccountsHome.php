<?php
include "../../components/processes/db_connection.php";

echo "<div class='ma-registeredEmployees'>Registered Employees <div style='cursor: pointer;' onclick='adminAccount()'><small>Admin Accounts</small></div></div>
<div class='ma-search'>
  <div>
    <input type='search' id='ma-searchInput' name='ma-searchInput' placeholder='Search'>
    <select id='ma-searchFilter' name='ma-searchFilter' style='padding: 0.3em;'>
      <option value='agency'>Agency</option>
      <option value='name'>Name</option>
    </select>
  </div>
  <button class='addAgency-btn d-flex justify-content-center align-items-center gap-2' data-bs-toggle='modal' data-bs-target='#addAgencyModal'>
    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-plus-circle' viewBox='0 0 16 16'>
      <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/>
      <path d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4'/>
    </svg>
    Add agency
  </button>
</div>
<div class='ma-searchResult mt-3' style='display: none;'>

</div>
<div class='ma-agencyList'>";

$getAllAgencyForMA = $conn->prepare("SELECT * FROM agency ORDER BY agencyName ASC");
$getAllAgencyForMA->execute();
$resultAgencyMA = $getAllAgencyForMA->get_result();

$lastLetter = "";
$firstAgency = TRUE;
while ($dataAgencyMA = $resultAgencyMA->fetch_assoc()) {
  $ma_agencyID = $dataAgencyMA['agencyID'];
  $ma_agencyName = $dataAgencyMA['agencyName'];
  $ma_agencySector = $dataAgencyMA['sector'];
  $ma_agencyProvince = $dataAgencyMA['province'];
  $ma_agencyAddress = $dataAgencyMA['address'];

  $ma_agencyNameInitial = strtoupper((str_split($ma_agencyName))[0]);

  if ($lastLetter !== $ma_agencyNameInitial) {
    $lastLetter = $ma_agencyNameInitial;
    if ($firstAgency == false) {
      echo "</div>";
    }
    echo "<div class='ma-agencyListHeader'>$ma_agencyNameInitial</div>";

    echo "<div class='ma-agencyListBody'>";
    $firstAgency = false;
  }

  echo "<div class='ma-agencyListItem'><span class='ma-agenctName'>$ma_agencyName</span><span class='ma-viewAgency' onclick='visitAgencyPage($ma_agencyID)'>
                View
              </span></div>";
}
echo "</div>";

echo "</div>";

?>
<script>

  document.getElementById("ma-searchInput").addEventListener("input", updateSearchResult);
  document.getElementById("ma-searchFilter").addEventListener("change", updateSearchResult);

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
        searchType: searchType,
        userType: "employee"
      },
      success: function (data) {
        // const parsedData = JSON.parse(data);
        console.log(data);
        $(".ma-searchResult").html(data);
        $(".ma-searchResult").css("display", "block");
        $(".ma-agencyList").css("display", "none");
      }
    });
  }
</script>