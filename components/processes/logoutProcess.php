<?php

include '../classes/userSession.php';

$userSession = new UserSession();
$result = $userSession->logout();
if ($result) {
  header("Location: ../../");
} else {
  echo $result;
}
