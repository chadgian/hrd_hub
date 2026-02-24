<?php
if (!function_exists('loadEnvFile')) {
  function loadEnvFile(string $path): void
  {
    if (!is_file($path) || !is_readable($path)) {
      return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
      return;
    }
    foreach ($lines as $line) {
      $trimmed = trim($line);
      if ($trimmed === '' || str_starts_with($trimmed, '#')) {
        continue;
      }
      $parts = explode('=', $trimmed, 2);
      if (count($parts) !== 2) {
        continue;
      }
      $key = trim($parts[0]);
      $value = trim($parts[1]);
      $value = trim($value, "\"'");
      if ($key !== '' && getenv($key) === false) {
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
      }
    }
  }
}
$projectRoot = dirname(__DIR__, 2);
loadEnvFile($projectRoot . '/.env');
if (!function_exists('appConfig')) {
  function appConfig(): array
  {
    return [
      'app' => [
        'base_url' => getenv('APP_BASE_URL') ?: '/hrd_hub',
        'timezone' => getenv('APP_TIMEZONE') ?: 'Asia/Manila',
      ],
      'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
        'name' => getenv('DB_NAME') ?: 'lad_hub',
      ],
      'upload' => [
        'max_bytes' => (int) (getenv('UPLOAD_MAX_BYTES') ?: 5000000),
      ],
    ];
  }
}
