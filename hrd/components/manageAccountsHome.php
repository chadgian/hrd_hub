<?php
include "../../components/processes/db_connection.php";

echo "<div class='ma-registeredEmployees'>Registered Employees</div>
<div class='ma-search'>
  <input type='search' id='ma-searchInput' name='ma-searchInput' placeholder='Search'>
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