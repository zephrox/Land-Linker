<?php
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
    'first_name' => $user['first_name'],
    'surname' => $user['surname'],
    'email' => $user['email'],
  ];
}

function logout_user(): void {
  auth_start_session();
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
  session_destroy();
}

function require_login(string $redirect_to = 'View/login.php'): void {
  if (!is_logged_in()) {
    header('Location: ' . $redirect_to);
    exit;
  }
}

function require_role(array $role_ids, string $redirect_to = 'index.php'): void {
  require_login(BASE_URL . 'View/login.php');
  $u = current_user();
  if (!$u || !in_array((int)$u['role_id'], $role_ids, true)) {
    header('Location: ' . $redirect_to);
    exit;
  }
}

function is_admin(): bool { $u = current_user(); return $u && (int)$u['role_id'] === 4; }
function is_manager(): bool { $u = current_user(); return $u && (int)$u['role_id'] === 3; }
function is_employee(): bool { $u = current_user(); return $u && (int)$u['role_id'] === 2; }

function db_fetch_user_by_email(mysqli $conn, string $email): ?array {
  $email = mysqli_real_escape_string($conn, $email);

  $sql = "SELECT id, role_id, first_name, surname, email, phone, password_hash, status
          FROM users
          WHERE email='{$email}'
          LIMIT 1";

  $res = mysqli_query($conn, $sql);
  if (!$res) return null;

  $row = mysqli_fetch_assoc($res);
  return $row ?: null;
}

function db_fetch_user_by_id(mysqli $conn, int $id): ?array {
  $id = (int)$id;

  $sql = "SELECT id, role_id, first_name, surname, email, phone, status, created_at, updated_at
          FROM users
          WHERE id={$id}
          LIMIT 1";

  $res = mysqli_query($conn, $sql);
  if (!$res) return null;

  $row = mysqli_fetch_assoc($res);
  return $row ?: null;
}
