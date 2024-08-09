<div class='recent-acts'>
  <div class='recent-header'>
    <span class="recent-header-title">Admin Activities</span>
    <!-- <span class="recent-refresh" id="recent-refresh" style="cursor: pointer;">Refresh</span> -->
  </div>
  <div class='recent-content'>
    <div id="recentLoader" style="display: flex; justify-content: center; width: 100%;">
      <img src="assets/images/loading.svg" alt="" width="25%">
    </div>
  </div>
  <div class='recent-footer'>
    <div class="recent-duration">See all</div>
  </div>
</div>
<script>
  $(document).ready(function () {
    $.ajax({
      url: 'components/recentsReload.php',
      method: 'GET',
      success: function (response) {
        // Insert the HTML content into the div
        $('#recentLoader').hide();
        $('.recent-content').html(response);
      },
      error: function () {
        console.error('Error fetching content');
      }
    });
    setInterval(() => {
      $.ajax({
        url: 'components/recentsReload.php',
        method: 'GET',
        success: function (response) {
          // Insert the HTML content into the div
          $('#recentLoader').hide();
          $('.recent-content').html(response);
        },
        error: function () {
          console.error('Error fetching content');
        }
      });
    }, 10000);
  });
</script>