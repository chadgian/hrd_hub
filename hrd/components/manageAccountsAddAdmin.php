<?php
include '../../components/processes/db_connection.php';

$role = $_POST['role'];
$prefix = $_POST['prefix'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$lname = $_POST['lname'];
$suffix = $_POST['suffix'];
$position = $_POST['position'];
$initials = $_POST['initials'];
$username = $_POST['username'];
$password = "@LingkodBayani";
$agencyID = "19";

if ($role == "admin") {
  $addAdmin = $conn->prepare("INSERT INTO user (role, prefix, firstName, middleInitial, lastName, suffix, agency, position, initials, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $addAdmin->bind_param("sssssssssss", $role, $prefix, $fname, $mname, $lname, $suffix, $agencyID, $position, $initials, $username, $password);
  if ($addAdmin->execute()) {
    echo "ok";
  } else {
    echo "failed";
  }
  $addAdmin->close();
} else if ($role == "payment") {
  $addPayment = $conn->prepare("INSERT INTO user (role, initials, username, password, agency) VALUES (?, ?, ?, ?, ?)");
  $addPayment->bind_param("sssss", $role, $initials, $username, $password, $agencyID);
  if ($addPayment->execute()) {
    echo "ok";
  } else {
    echo "failed";
  }
  $addPayment->close();
}