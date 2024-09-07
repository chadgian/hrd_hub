<?php

include "../classes/userSession.php";
$userSession = new UserSession();

$newUserData = [
  "prefix" => $_POST['prefix'],
  "firstName" => $_POST['firstName'],
  "middleInitial" => $_POST['middleInitial'],
  "lastName" => $_POST['lastName'],
  "suffix" => $_POST['suffix'],
  "nickname" => $_POST['nickname'],
  "age" => $_POST['age'],
  "sex" => $_POST['sex'],
  "civilStatus" => $_POST['civilStatus'],
  "phoneNumber" => $_POST['phoneNumber'],
  "email" => $_POST['email'],
  "altEmail" => $_POST['altEmail'],
  "sector" => $_POST['sector'],
  "agencyName" => $_POST['agencyName'],
  "position" => $_POST['position'],
  "fo" => $_POST['fo'],
  "foodRestriction" => $_POST['foodRestriction']
];

$response = $userSession->updateDetails($newUserData);

if ($response) {
  echo "ok";
} else {
  echo "error";
}