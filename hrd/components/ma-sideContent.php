<div class='recent-acts'>
  <div class='recent-header'>
    <span class="recent-header-title">Agency Details</span>
    <span class="recent-refresh" id="recent-refresh" style="cursor: pointer;" onclick="editAgencyModal()">Edit</span>
  </div>
  <div class='agency-details-content'>
    <div class="d-flex flex-column">
      <input type="hidden" id="agency-detials-agencyID">
      <div class="mb-2 d-flex flex-column">
        <div class="agencyDetails-title">
          Sector
        </div>
        <div id="agencyDetails-sector" class="agencyDetails-item">

        </div>
      </div>
      <div class="mb-2 d-flex flex-column">
        <div class="agencyDetails-title">
          Province
        </div>
        <div class="agencyDetails-item" id="agencyDetails-province">

        </div>
      </div>

      <div class="mb-2 d-flex flex-column">
        <div class="agencyDetails-title">
          Address
        </div>
        <div class="agencyDetails-item" id="agencyDetails-address">

        </div>
      </div>
    </div>
  </div>
  <div class='recent-footer'>
    <div class="recent-duration"></div>
  </div>
</div>

<!-- <script>
    $("#agencyDetails-sector").html("Chad Gian");
  </script> -->