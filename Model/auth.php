<?php
declare(strict_types=1);

function auth_start_session(): void {
  if (session_status() === PHP_SESSION_NONE) session_start();
}

function current_user(): ?array {
  return $_SESSION['auth_user'] ?? null;
}

function is_logged_in(): bool {
  return !empty($_SESSION['auth_user']);
}

function login_user(array $user): void {
  $_SESSION['auth_user'] = [
    'id' => (int)$user['id'],
    'role_id' => (int)$user['role_id'],
    'first_name' => (string)$user['first_name'],
    'surname' => (string)$user['surname'],
    'email' => (string)$user['email'],
  ];
}

function logout_user(): void {
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
  }
  session_destroy();
}

function is_admin(): bool    { $u = current_user(); return $u && (int)$u['role_id'] === 4; }
function is_manager(): bool  { $u = current_user(); return $u && (int)$u['role_id'] === 3; }
function is_employee(): bool { $u = current_user(); return $u && (int)$u['role_id'] === 2; }
