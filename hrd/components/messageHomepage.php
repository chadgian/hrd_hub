<?php
include "../components/processes/db_connection.php";

// Fetch messages from the database
$sql = "SELECT msgID, name, agency, subject, status, timeDate FROM messages ORDER BY timeDate DESC";
$result = $conn->query($sql);
?>

<div class="messages-list">
  <h2 class="text-center my-4">Admin Messages</h2>
  <div class="list-group">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <a href="index.php?p=3&m=<?php echo $row['msgID']; ?>" class="list-group-item list-group-item-action">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"><?php echo htmlspecialchars($row['subject']); ?></h5>
            <small
              class="text-muted"><?php echo $row['status'] === 'unread' ? '<span class="badge bg-danger">Unread</span>' : '<span class="badge bg-success">Read</span>'; ?></small>
          </div>
          <p class="mb-1"><?php echo htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['agency']); ?></p>
          <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($row['timeDate'])); ?></small>
        </a>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">No messages found.</p>
    <?php endif; ?>
  </div>
</div>
<?php $conn->close(); ?>