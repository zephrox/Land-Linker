<?php
declare(strict_types=1);

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

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

function url(string $path): string {
  return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function post_str(string $key, int $maxLen): string {
  $v = trim((string)($_POST[$key] ?? ''));
  if (strlen($v) > $maxLen) $v = substr($v, 0, $maxLen);
  return $v;
}

function csrf_token(): string {
  if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf_token'];
}

function csrf_check(): void {
  $sent = (string)($_POST['csrf_token'] ?? '');
  $real = (string)($_SESSION['csrf_token'] ?? '');
  if ($sent === '' || $real === '' || $sent !== $real) {
    flash_set('error', 'Invalid CSRF token.');
    redirect(url('View/pages/login.php'));
  }
}
