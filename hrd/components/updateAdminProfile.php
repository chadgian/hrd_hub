<?php

include "../../components/classes/userSession.php";
$adminProfile = new UserSession();

$prefix = $_POST['prefix'];
$firstName = $_POST['firstName'];

$rawMI = str_split(trim($_POST['middleInitial']), 1);
if ($rawMI[count($rawMI) - 1] == ".") {
  $middleInitial = trim($_POST['middleInitial']);
} else {
  $middleInitial = trim($_POST['middleInitial']) . ".";
}


$lastName = $_POST['lastName'];
$suffix = $_POST['suffix'];
$position = $_POST['position'];
$initials = strtoupper($_POST['initials']);
$username = $_POST['username'];

$newAdminProfile = [
  "prefix" => $prefix,
  "firstName" => $firstName,
  "middleInitial" => $middleInitial,
  "lastName" => $lastName,
  "suffix" => $suffix,
  "position" => $position,
  "initials" => $initials,
  "username" => $username
];

if ($adminProfile->updateAdminProfile($newAdminProfile) == true) {
  echo "ok";
}