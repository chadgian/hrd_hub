<?php
session_start();

if (isset($_SESSION['scanLogged_in']) || isset($_SESSION['role'])) {
  if (isset($_GET['t']) && isset($_GET['d']) && isset($_GET['action'])) {
    include '../components/classes/trainingDetails.php';
    $trainingID = $_GET['t'];
    $training = new getTraining($trainingID);

    $attendanceData = $training->getParticipants();
    $trainingDetails = $training->toArray();
    $trainingDays = getTrainingDays($trainingDetails['startDate'], $trainingDetails['endDate']);

    $trainingDay = ($trainingDays < $_GET['d']) ? $trainingDays : $_GET['d'];

    $inORout = $_GET['action'];

  } else {
    if (isset($_SESSION['role'])) {
      header('Location: index.php?p=5');
    } else {
      header("Location: scan_login.php");
    }
  }
} else {
  header("Location: scan_login.php");
}

function getTrainingDays($startDate, $endDate)
{
  $start = new DateTime($startDate);
  $end = new DateTime($endDate);
  $numberOfDays = ($start->diff($end))->days + 1;
  return $numberOfDays;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Training Attendance</title>
  <!-- Bootstrap CSS and Javascript-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <style>
    :root {
      --primary-color: #4e73df;
      --success-color: #1cc88a;
      --danger-color: #e74a3b;
      --warning-color: #f6c23e;
      --light-bg: #f8f9fc;
    }

    * {
      -webkit-tap-highlight-color: transparent;
    }

    body {
      background-color: var(--light-bg);
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      line-height: 1.5;
      -webkit-text-size-adjust: 100%;
    }

    .app-container {
      max-width: 100%;
      margin: 0 auto;
      padding: 15px;
    }

    .header-section {
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #e3e6f0;
    }

    .training-title {
      color: var(--primary-color);
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      word-break: break-word;
    }

    .training-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      color: #5a5c69;
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }

    .meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .meta-icon {
      color: var(--primary-color);
      font-size: 1.1rem;
    }

    .action-buttons {
      display: flex;
      gap: 0.75rem;
      margin-top: 0.5rem;
    }

    /* Latest Participant Card */
    .latest-participant-card {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 0.1rem 0.5rem rgba(58, 59, 69, 0.1);
      padding: 1rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .latest-participant-title {
      font-size: 0.9rem;
      color: #5a5c69;
      margin-bottom: 0.5rem;
    }

    .participant-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.25rem;
    }

    .participant-id {
      font-size: 1rem;
      color: #5a5c69;
      margin-bottom: 0.5rem;
    }

    .scan-time {
      font-size: 0.9rem;
      color: #5a5c69;
      background-color: #f8f9fc;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      display: inline-block;
    }

    .scanner-card,
    .status-card {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 0.1rem 0.5rem rgba(58, 59, 69, 0.1);
      padding: 1.25rem;
      margin-bottom: 1rem;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--primary-color);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    #status {
      font-size: 1rem;
      font-weight: 600;
      padding: 0.75rem;
      border-radius: 0.5rem;
      text-align: center;
      margin-bottom: 1rem;
    }

    .status-ready {
      background-color: rgba(78, 115, 223, 0.1);
      color: var(--primary-color);
      border-left: 4px solid var(--primary-color);
    }

    .status-processing {
      background-color: rgba(246, 194, 62, 0.1);
      color: var(--warning-color);
      border-left: 4px solid var(--warning-color);
    }

    .status-error {
      background-color: rgba(231, 74, 59, 0.1);
      color: var(--danger-color);
      border-left: 4px solid var(--danger-color);
    }

    #my-qr-reader {
      margin: 0 auto;
      max-width: 100%;
    }

    #my-qr-reader img {
      max-width: 100% !important;
    }

    #html5-qrcode-button-camera-start,
    #html5-qrcode-button-camera-stop {
      width: 100%;
      margin: 0.5rem 0;
      padding: 0.5rem;
      font-size: 0.9rem;
    }

    .attendance-section {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 0.1rem 0.5rem rgba(58, 59, 69, 0.1);
      overflow: hidden;
      margin-bottom: 1rem;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 1rem;
      background-color: #f8f9fc;
      border-bottom: 1px solid #e3e6f0;
      font-size: 0.9rem;
    }

    .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .attendance-table {
      width: 100%;
      margin-bottom: 0;
      font-size: 0.85rem;
    }

    .attendance-table thead {
      background-color: var(--primary-color);
      color: white;
    }

    .attendance-table th {
      padding: 0.75rem;
      font-weight: 600;
      text-align: left;
      font-size: 0.8rem;
    }

    .attendance-table td {
      padding: 0.5rem;
      vertical-align: middle;
      border-top: 1px solid #e3e6f0;
    }

    .empty-state {
      text-align: center;
      padding: 2rem 1rem;
      color: #5a5c69;
    }

    .empty-icon {
      font-size: 2.5rem;
      color: #dddfeb;
      margin-bottom: 0.75rem;
    }

    .btn {
      padding: 0.5rem 0.75rem;
      font-weight: 600;
      border-radius: 0.35rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.85rem;
      white-space: nowrap;
    }

    .btn-sm {
      padding: 0.35rem 0.5rem;
      font-size: 0.8rem;
    }

    .time-badge {
      background-color: #eaecf4;
      color: #4e4e4e;
      padding: 0.2rem 0.4rem;
      border-radius: 0.2rem;
      font-family: monospace;
      font-size: 0.8rem;
    }

    .badge {
      font-size: 0.75rem;
      font-weight: 600;
      padding: 0.35rem 0.5rem;
    }

    /* Mobile-specific adjustments */
    @media (max-width: 576px) {
      .app-container {
        padding: 12px;
      }

      .training-title {
        font-size: 1.3rem;
      }

      .training-meta {
        font-size: 0.8rem;
        gap: 0.75rem;
      }

      .latest-participant-card {
        padding: 0.75rem;
      }

      .participant-name {
        font-size: 1.3rem;
      }

      .scanner-card,
      .status-card {
        padding: 1rem;
      }

      .card-title {
        font-size: 1rem;
      }

      .btn {
        padding: 0.45rem 0.65rem;
        font-size: 0.8rem;
      }

      .attendance-table th,
      .attendance-table td {
        padding: 0.5rem 0.3rem;
        font-size: 0.8rem;
      }
    }

    /* Accessibility improvements */
    button:focus,
    input:focus {
      outline: 2px solid var(--primary-color);
      outline-offset: 2px;
    }

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border-width: 0;
    }

    /* Landscape orientation adjustments */
    @media screen and (orientation: landscape) and (max-width: 992px) {
      .scanner-card {
        max-height: 70vh;
        overflow-y: auto;
      }

      #my-qr-reader {
        max-height: 50vh;
      }
    }
  </style>
</head>

<body>
  <div class="app-container">
    <!-- Header Section -->
    <div class="header-section">
      <div class="training-info">
        <h1 class="training-title"><?php echo $training->getTrainingName(); ?></h1>
        <div class="training-meta">
          <div class="meta-item">
            <i class="bi bi-calendar-event meta-icon" aria-hidden="true"></i>
            <span>Day <?php echo $trainingDay; ?></span>
          </div>
          <div class="meta-item">
            <i class="bi bi-arrow-left-right meta-icon" aria-hidden="true"></i>
            <span><?php echo $inORout == "in" ? "Login" : "Logout"; ?> Session</span>
          </div>
        </div>
      </div>
      <div class="action-buttons">
        <button class="btn btn-danger btn-sm" onclick="clearData()" aria-label="Clear all scanned data">
          <i class="bi bi-trash" aria-hidden="true"></i> <span class="d-none d-sm-inline">Clear</span>
        </button>
        <button class="btn btn-success btn-sm" onclick="saveData()" aria-label="Save data to server">
          <i class="bi bi-cloud-upload" aria-hidden="true"></i> <span class="d-none d-sm-inline">Save</span>
        </button>
      </div>
    </div>

    <?php
    echo "
      <input type='hidden' value='{$trainingID}' name='training' id='training'>
      <input type='hidden' value='{$trainingDay}' name='days' id='days'>
      <input type='hidden' value='$inORout' name='inORout' id='inORout'>
    ";
    ?>

    <!-- Latest Participant Card -->
    <div class="latest-participant-card" id="latest-participant">
      <div class="latest-participant-title">LAST SCANNED PARTICIPANT</div>
      <!-- <div style="display: flex; justify-content: space-around; align-items: center;  flex-direction: row; gap: 0.5rem;"> -->
      <div class="participant-name" id="latest-name">-</div>
      <div class="participant-id" id="latest-id">ID: -</div>
      <div class="scan-time" id="latest-time">Time: --:--:--</div>
      <!-- </div> -->
    </div>

    <!-- Scanner Section -->
    <div class="scanner-card">
      <h2 class="card-title">
        <i class="bi bi-upc-scan" aria-hidden="true"></i> QR Scanner
      </h2>
      <div id='status' class="status-ready">Ready to scan QR codes</div>
      <div id="my-qr-reader"></div>
    </div>

    <!-- Quick Actions -->
    <div class="status-card">
      <h2 class="card-title">
        <i class="bi bi-lightning" aria-hidden="true"></i> Quick Actions
      </h2>
      <div class="d-grid gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="deleteLastData()">
          <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Undo Last
        </button>
        <button class="btn btn-outline-secondary btn-sm" onclick="saveDatabase()">
          <i class="bi bi-download" aria-hidden="true"></i> Export
        </button>
      </div>
    </div>

    <!-- Attendance Records -->
    <div class="attendance-section">
      <div class="section-header">
        <h2 class="mb-0 h6">
          <i class="bi bi-list-check" aria-hidden="true"></i> Attendance Records
        </h2>
        <div class="text-muted small">
          <span id="record-count">0</span> records
        </div>
      </div>

      <div class="table-responsive">
        <table class="attendance-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody id="recorded">
            <!-- Rows will be added dynamically -->
          </tbody>
        </table>
        <?php if (empty($attendanceData)): ?>
          <div class="empty-state">
            <i class="bi bi-people empty-icon" aria-hidden="true"></i>
            <h3 class="h5">No records yet</h3>
            <p class="small">Scan QR codes to begin</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="../assets/js/html5-qrcode.min.js"></script>
  <script src="../assets/js/jquery-3.6.0.min.js"></script>

  <script>
    // Initialize variables
    let qrCodeScanned = false;
    let previousData = "";
    setStatus("ready");

    // Load existing data from localStorage
    const existingData = localStorage.getItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>");
    var recordedData = [];

    if (existingData) {
      try {
        recordedData = JSON.parse(existingData);
        updateLatestParticipant();
        updateRecordCount();
        recordedData.forEach(participant => {
          addRow(participant.numID, participant.name, participant.timestamp);
        });
      } catch (error) {
        showAlert('Failed to load existing data', 'danger');
        recordedData = [];
      }
    }

    var attendanceData = JSON.parse('<?php echo json_encode($attendanceData); ?>');

    // Update latest participant display
    function updateLatestParticipant() {
      if (recordedData.length > 0) {
        const latest = recordedData[0];
        document.getElementById('latest-name').textContent = latest.name;
        document.getElementById('latest-id').textContent = 'ID: ' + latest.numID;
        document.getElementById('latest-time').textContent = 'Time: ' + latest.timestamp;
      } else {
        document.getElementById('latest-name').textContent = '-';
        document.getElementById('latest-id').textContent = 'ID: -';
        document.getElementById('latest-time').textContent = 'Time: --:--:--';
      }
    }

    function updateRecordCount() {
      document.getElementById('record-count').textContent = recordedData.length;
    }

    function showAlert(message, type) {
      // Create alert element
      const alert = document.createElement('div');
      alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
      alert.style.bottom = '20px';
      alert.style.left = '50%';
      alert.style.transform = 'translateX(-50%)';
      alert.style.zIndex = '1000';
      alert.style.maxWidth = '90%';
      alert.style.width = 'auto';
      alert.style.padding = '0.75rem 1.25rem';
      alert.role = 'alert';
      alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;

      // Add alert to the body
      document.body.appendChild(alert);

      // Auto-dismiss after 3 seconds
      setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 150);
      }, 3000);
    }

    function domReady(fn) {
      if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(fn, 1000);
      } else {
        document.addEventListener("DOMContentLoaded", fn);
      }
    }

    function configureScanner() {
      // Check if mobile device
      const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

      let config = {
        fps: isMobile ? 10 : 30, // Lower FPS on mobile for better performance
        qrbox: isMobile ? 200 : 250,
        aspectRatio: isMobile ? 1.0 : 1.777778,
        disableFlip: isMobile // Disable flip on mobile to reduce CPU usage
      };

      return new Html5QrcodeScanner("my-qr-reader", config);
    }

    let htmlscanner = configureScanner();

    function setStatus(status) {
      const statusElement = document.getElementById('status');
      statusElement.className = 'status-' + (status === "ready" ? "ready" : status === "not found" ? "error" : "processing");

      switch (status) {
        case "ready":
          statusElement.innerHTML = 'Ready to scan QR codes';
          statusElement.setAttribute('aria-live', 'polite');
          break;
        case "decrypting":
          statusElement.innerHTML = 'Processing...';
          statusElement.setAttribute('aria-live', 'assertive');
          break;
        case "not found":
          statusElement.innerHTML = 'Participant not found';
          statusElement.setAttribute('aria-live', 'assertive');
          break;
        default:
          statusElement.innerHTML = 'Ready';
          statusElement.setAttribute('aria-live', 'polite');
          break;
      }
    }

    domReady(function () {
      async function onScanSuccess(decodeText, decodeResult) {
        if (!qrCodeScanned) {
          qrCodeScanned = true;

          if (previousData === decodeText) {
            qrCodeScanned = false;
            return;
          }

          previousData = decodeText;
          setStatus("decrypting");

          try {
            console.log("Processing scanned data: " + decodeText);
            addAttendance(decodeText.split(":")[1]);

            // Vibrate on successful scan (mobile only)
            if (navigator.vibrate) {
              navigator.vibrate(100);
            }
          } catch (error) {
            console.error("Error processing QR code:", error);
            showAlert('Error processing QR code', 'danger');
          }

          setStatus("ready");
          qrCodeScanned = false;
        }
      }

      htmlscanner.render(onScanSuccess, onScanError);
    });

    function onScanError(error) {
      // Handle scan errors quietly in production
      console.log('Scan error:', error);
    }

    function addAttendance(numID) {
      const participant = attendanceData.find(p => p.numID === parseInt(numID, 10));
      if (participant) {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');

        participant.timestamp = `${hours}:${minutes}:${seconds}`;
        saveRecordedData(participant.numID, participant.name, participant.timestamp);
        showAlert(`Recorded: ${participant.name}`, 'success');
      } else {
        setStatus("not found");
        showAlert('Participant not found', 'warning');
      }
    }

    function deleteLastData() {
      if (recordedData.length === 0) {
        showAlert('No records to undo', 'info');
        return;
      }

      const lastParticipant = recordedData[0];
      recordedData.shift();
      localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
      document.getElementById("recorded").innerHTML = "";
      recordedData.forEach(p => addRow(p.numID, p.name, p.timestamp));
      updateLatestParticipant();
      updateRecordCount();
      showAlert(`Removed: ${lastParticipant.name}`, 'info');
    }

    function addRow(id, name, timestamp) {
      const tbody = document.querySelector('#recorded');
      const newRow = document.createElement('tr');

      const cells = [
        document.createElement('td'),
        document.createElement('td'),
        document.createElement('td')
      ];

      cells[0].textContent = id;
      cells[1].textContent = name;
      cells[2].innerHTML = `<span class="time-badge">${timestamp}</span>`;

      cells.forEach(cell => newRow.appendChild(cell));
      tbody.insertBefore(newRow, tbody.firstChild);
    }

    function saveRecordedData(id, name, timestamp) {
      const newData = { numID: id, name: name, timestamp: timestamp };

      // Remove existing record if this participant was already scanned
      recordedData = recordedData.filter(item => item.numID !== parseInt(id, 10));

      document.getElementById("recorded").innerHTML = "";
      recordedData.unshift(newData);

      localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
      recordedData.forEach(p => addRow(p.numID, p.name, p.timestamp));
      updateLatestParticipant();
      updateRecordCount();
    }

    function saveDatabase() {
      const data = JSON.stringify(recordedData);
      const blob = new Blob([data], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `attendance-day-<?php echo $trainingDay; ?>-<?php echo $inORout; ?>.json`;
      a.click();
      URL.revokeObjectURL(url);
      showAlert('Data exported', 'success');
    }

    function saveData() {
      const trainingID = "<?php echo $trainingID; ?>";
      const trainingDay = "<?php echo $trainingDay; ?>";
      const inorout = "<?php echo $inORout; ?>";

      if (recordedData.length === 0) {
        showAlert('No data to save', 'warning');
        return;
      }

      $.ajax({
        url: 'components/saveRecordedData.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          inorout: inorout,
          trainingID: trainingID,
          day: trainingDay,
          participants: recordedData
        }),
        success: function (response) {
          if (response) {
            document.getElementById("recorded").innerHTML = "";
            recordedData = [];
            localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
            updateLatestParticipant();
            updateRecordCount();
            showAlert('Data saved successfully', 'success');
          }
        },
        error: function (xhr, status, error) {
          showAlert('Error saving data', 'danger');
        }
      });
    }

    function clearData() {
      if (recordedData.length === 0) {
        showAlert('No data to clear', 'info');
        return;
      }

      if (confirm("DELETE ALL SCANNED DATA?\nThis cannot be undone.")) {
        document.getElementById("recorded").innerHTML = "";
        recordedData = [];
        localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $inORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
        updateLatestParticipant();
        updateRecordCount();
        showAlert('All data cleared', 'info');
      }
    }

    // Handle mobile back button to prevent accidental navigation
    window.addEventListener('popstate', function (event) {
      if (recordedData.length > 0) {
        if (!confirm('You have unsaved data. Leave anyway?')) {
          history.pushState(null, null, window.location.pathname);
        }
      }
    });

    // Add initial state to history
    history.pushState(null, null, window.location.pathname);
  </script>
</body>

</html>