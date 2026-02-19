<?php
require_once __DIR__ . '/../config/app.php';

$config = appConfig();
date_default_timezone_set($config['app']['timezone']);

$conn = new mysqli(
  $config['db']['host'],
  $config['db']['user'],
  $config['db']['pass'],
  $config['db']['name']
);

if ($conn->connect_error) {
  http_response_code(500);
  die('Database connection failed.');
}
?>