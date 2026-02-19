<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
function jsonResponse(int $statusCode, array $payload): void
{
  http_response_code($statusCode);
  header('Content-Type: application/json');
  echo json_encode($payload);
  exit();
}
function requirePostMethod(): void
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(405, ['ok' => false, 'message' => 'Method not allowed']);
  }
}
function csrfToken(): string
{
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}
function verifyCsrfToken(?string $token): bool
{
  if (empty($_SESSION['csrf_token']) || empty($token)) {
    return false;
  }
  return hash_equals($_SESSION['csrf_token'], $token);
}
function requireCsrf(): void
{
  $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
  if (!verifyCsrfToken($token)) {
    jsonResponse(403, ['ok' => false, 'message' => 'Invalid CSRF token']);
  }
}
function requireAuthenticatedSession(): void
{
  if (empty($_SESSION['userID'])) {
    jsonResponse(401, ['ok' => false, 'message' => 'Unauthorized']);
  }
}
function requireRole(string $requiredRole): void
{
  requireAuthenticatedSession();
  if (($_SESSION['role'] ?? null) !== $requiredRole) {
    jsonResponse(403, ['ok' => false, 'message' => 'Forbidden']);
  }
}
