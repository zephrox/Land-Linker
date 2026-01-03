<?php
function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
function redirect(string $to): void {
  header("Location: $to");
  exit;
}

function flash_set(string $key, string $msg): void {
  $_SESSION['flash'][$key] = $msg;
}
function flash_get(string $key): ?string {
  if (!isset($_SESSION['flash'][$key])) return null;
  $m = $_SESSION['flash'][$key];
  unset($_SESSION['flash'][$key]);
  return $m;
}

function csrf_token(): string {
  if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf_token'];
}
function csrf_check(): void {
  $token = $_POST['csrf_token'] ?? '';
  if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    die('CSRF validation failed.');
  }
}

function post_str(string $key, int $max = 255): string {
  $v = trim((string)($_POST[$key] ?? ''));
  if (mb_strlen($v) > $max) $v = mb_substr($v, 0, $max);
  return $v;
}
function post_int(string $key): int {
  return (int)($_POST[$key] ?? 0);
}
function get_int(string $key): int {
  return (int)($_GET[$key] ?? 0);
}

function url(string $path): string {
  return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

