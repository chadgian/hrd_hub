<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Handle form submission for status update and remarks
  $status = $_POST['status'];
  $remarks = $_POST['remarks'];
  $msgID = $_POST['msgID'];

  // Update message status and remarks in the database
  $updateQuery = "UPDATE messages SET status = ?, remarks = ?, lastUpdate = NOW(), lastUpdateBy = ? WHERE msgID = ?";
  $stmt = $conn->prepare($updateQuery);
  $stmt->bind_param("sssi", $status, $remarks, $_SESSION['userID'], $msgID);
  if ($stmt->execute()) {
    echo '<div class="alert alert-success">Message updated successfully</div>';
  } else {
    echo '<div class="alert alert-danger">Error updating message</div>';
  }
}

// Get message ID from URL
$msgId = isset($_GET['m']) ? intval($_GET['m']) : 0;

// Fetch message details
$query = "SELECT * FROM messages WHERE msgID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $msgId);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();

if (!$message) {
  echo '<div class="alert alert-danger">Message not found</div>';
  exit;
}

if (session_status() == PHP_SESSION_NONE) {
  // Start session if not already started
  session_start();
}
?>

<div class="message-detail-container">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="message-title mb-0"><?= htmlspecialchars($message['subject']) ?></h2>
    <a href="messages.php" class="btn btn-back">
      <i class="fas fa-arrow-left me-2"></i>Back to Messages
    </a>
  </div>

  <!-- Sender Info -->
  <div class="sender-card card mb-4">
    <div class="card-body">
      <div class="d-flex align-items-center">
        <div class="sender-avatar">
          <?= strtoupper(substr($message['name'], 0, 1)) ?>
        </div>
        <div class="ms-3">
          <h5 class="sender-name mb-1"><?= htmlspecialchars($message['name']) ?></h5>
          <div class="sender-meta text-muted">
            <span><?= htmlspecialchars($message['agency']) ?></span>
            <span class="mx-2">•</span>
            <span><?= htmlspecialchars($message['email']) ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Message Content -->
  <div class="message-content-card card mb-4">
    <div class="card-body">
      <div class="message-body">
        <?= nl2br(htmlspecialchars($message['message'])) ?>
      </div>
      <div class="message-timestamps mt-4">
        <small class="text-muted">Received: <?= date('M d, Y \a\t h:i A', strtotime($message['timeDate'])) ?></small>
      </div>
    </div>
  </div>

  <!-- Status Update Section -->
  <div class="status-card card">
    <div class="card-body">
      <form action="" method="POST">
        <input type="hidden" name="msgID" value="<?= $message['msgID'] ?>">

        <div class="row g-3">
          <!-- Status Selector -->
          <div class="col-md-6">
            <div class="status-control">
              <label class="form-label">Current Status</label>
              <div class="input-group">
                <select name="status" class="form-select status-select">
                  <option value="Pending" <?php echo $message['status'] == 'Pending' ? 'selected' : ''; ?>>Pending
                  </option>
                  <option value="In Progress" <?php echo $message['status'] == 'In Progress' ? 'selected' : ''; ?>>In
                    Progress</option>
                  <option value="Resolved" <?php echo $message['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved
                  </option>
                </select>
                <span class="status-indicator"></span>
              </div>
            </div>
          </div>

          <!-- Last Updated -->
          <div class="col-md-6">
            <div class="update-info">
              <label class="form-label">Last Updated</label>
              <div class="form-control-static">
                <?php
                switch ($message['lastUpdate']) {
                  case "":
                    echo "Not yet updated";
                    break;
                  default:
                    echo date('M d, Y h:i A', strtotime($message['lastUpdate']));
                    break;
                }
                ?>
                <div class="text-muted small">by<b>
                    <?php
                    if (isset($message['lastUpdateBy'])) {
                      $query = "SELECT initials FROM user WHERE userID = ? AND role = 'admin'";
                      $stmt = $conn->prepare($query);
                      $stmt->bind_param("i", $message['lastUpdateBy']);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $user = $result->fetch_assoc();
                      echo htmlspecialchars($user['initials']);
                    } else {
                      // Fallback if lastUpdateBy is not set
                      echo "N/A";
                    }
                    ?></b>
                </div>
              </div>
            </div>
          </div>

          <!-- Remarks -->
          <div class="col-12">
            <div class="remarks-control">
              <label class="form-label">Admin Remarks</label>
              <textarea name="remarks" class="form-control remarks-input" rows="3"
                placeholder="Add internal notes..."><?= htmlspecialchars($message['remarks']) ?></textarea>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="col-12">
            <button type="submit" class="btn btn-save">
              <i class="fas fa-save me-2"></i>Save Changes
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .message-detail-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 1rem;
  }

  .message-title {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.8rem;
  }

  .btn-back {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
  }

  .btn-back:hover {
    background-color: #e9ecef;
    color: #2c3e50;
  }

  .sender-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .sender-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background-color: #4e73df;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
  }

  .sender-name {
    color: #2c3e50;
    font-weight: 500;
  }

  .message-content-card {
    border: none;
    background-color: #f8fafc;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .message-body {
    color: #4a5568;
    line-height: 1.7;
    font-size: 1rem;
  }

  .status-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
  }

  .status-control .input-group {
    position: relative;
  }

  .status-indicator {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 12px;
    height: 12px;
    border-radius: 50%;
  }

  .status-select[data-status="Pending"]+.status-indicator {
    background: #f6ad55;
  }

  .status-select[data-status="In Progress"]+.status-indicator {
    background: #4299e1;
  }

  .status-select[data-status="Resolved"]+.status-indicator {
    background: #48bb78;
  }

  .remarks-input {
    resize: vertical;
    min-height: 100px;
  }

  .btn-save {
    background-color: #4e73df;
    color: white;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
  }

  .btn-save:hover {
    background-color: #3b5ab5;
    color: white;
  }

  .form-control-static {
    padding: 0.375rem 0;
    color: #4a5568;
  }
</style>

<script>
  // Update status indicator when selection changes
  document.querySelector('.status-select').addEventListener('change', function () {
    const indicator = this.parentElement.querySelector('.status-indicator');
    indicator.style.backgroundColor = {
      'Pending': '#f6ad55',
      'In Progress': '#4299e1',
      'Resolved': '#48bb78'
    }[this.value];
  });

  // Initialize status indicator color
  const initialStatus = document.querySelector('.status-select').dataset.status;
  document.querySelector('.status-indicator').style.backgroundColor = {
    'Pending': '#f6ad55',
    'In Progress': '#4299e1',
    'Resolved': '#48bb78'
  }[initialStatus];
</script>