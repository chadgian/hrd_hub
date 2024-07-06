<?php
include_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $agency = $_POST['agency'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  $msgStmt = $conn->prepare("INSERT INTO messages (name, email, agency, subject, message) VALUES (?, ?, ?, ?, ?)");
  $msgStmt->bind_param('sssss', $name, $email, $agency, $subject, $message);

  if ($msgStmt->execute()){
    echo "Message sent successfully!";
  }
}


?>