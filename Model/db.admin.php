<?php

function db_admin_users(mysqli $conn, string $q = ''): array {
  if ($q !== '') {
    $like = '%' . db_es($conn, $q) . '%';
    $sql = "
      SELECT u.*, r.name AS role_name
      FROM users u
      JOIN roles r ON r.id = u.role_id
      WHERE u.email LIKE '{$like}'
         OR u.first_name LIKE '{$like}'
         OR u.surname LIKE '{$like}'
      ORDER BY u.id DESC
    ";
    $res = mysqli_query($conn, $sql);
    return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
  }

  $sql = "
    SELECT u.*, r.name AS role_name
    FROM users u
    JOIN roles r ON r.id = u.role_id
    ORDER BY u.id DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_admin_create_user(mysqli $conn, int $role_id, string $first, string $last, string $email, string $phone, string $password_plain): int {
  $hash = password_hash($password_plain, PASSWORD_BCRYPT);

  $role_id = (int)$role_id;
  $first = db_es($conn, $first);
  $last = db_es($conn, $last);
  $email = db_es($conn, $email);
  $phone = db_es($conn, $phone);
  $hash = db_es($conn, $hash);

  $sql = "
    INSERT INTO users (role_id, first_name, surname, email, phone, password_hash)
    VALUES ({$role_id}, '{$first}', '{$last}', '{$email}', '{$phone}', '{$hash}')
  ";
  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}

function db_admin_update_user(mysqli $conn, int $id, int $role_id, string $first, string $last, string $email, string $phone, string $status): void {
  $id = (int)$id;
  $role_id = (int)$role_id;

  $first = db_es($conn, $first);
  $last = db_es($conn, $last);
  $email = db_es($conn, $email);
  $phone = db_es($conn, $phone);
  $status = db_es($conn, $status);

  $sql = "
    UPDATE users SET
      role_id={$role_id},
      first_name='{$first}',
      surname='{$last}',
      email='{$email}',
      phone='{$phone}',
      status='{$status}'
    WHERE id={$id}
  ";
  mysqli_query($conn, $sql);
}

function db_admin_update_user_password(mysqli $conn, int $id, string $password_plain): void {
  $id = (int)$id;
  $hash = password_hash($password_plain, PASSWORD_BCRYPT);
  $hash = db_es($conn, $hash);

  $sql = "UPDATE users SET password_hash='{$hash}' WHERE id={$id}";
  mysqli_query($conn, $sql);
}

function db_admin_delete_user(mysqli $conn, int $id): void {
  $id = (int)$id;
  mysqli_query($conn, "DELETE FROM users WHERE id={$id}");
}

function db_manager_users_all(mysqli $conn, string $q = ''): array {
  $like = '%' . db_es($conn, $q) . '%';

  $sql = "
    SELECT u.id, u.first_name, u.surname, u.email, u.status,
           r.name AS role_name, u.role_id
    FROM users u
    JOIN roles r ON r.id = u.role_id
    WHERE (
      u.first_name LIKE '{$like}'
      OR u.surname LIKE '{$like}'
      OR u.email LIKE '{$like}'
    )
    ORDER BY u.id DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_manager_create_user(mysqli $conn, array $d): int {
  $role_id = (int)$d['role_id'];
  $first   = db_es($conn, (string)$d['first_name']);
  $sur     = db_es($conn, (string)$d['surname']);
  $email   = db_es($conn, (string)$d['email']);
  $phone   = db_es($conn, (string)$d['phone']);
  $hash    = db_es($conn, (string)$d['password_hash']);
  $status  = db_es($conn, (string)$d['status']);

  $sql = "
    INSERT INTO users (role_id, first_name, surname, email, phone, password_hash, status)
    VALUES ({$role_id}, '{$first}', '{$sur}', '{$email}', '{$phone}', '{$hash}', '{$status}')
  ";
  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}

function db_user_role_id(mysqli $conn, int $user_id): ?int {
  $user_id = (int)$user_id;
  $res = mysqli_query($conn, "SELECT role_id FROM users WHERE id={$user_id} LIMIT 1");
  if (!$res) return null;
  $row = mysqli_fetch_assoc($res);
  return $row ? (int)$row['role_id'] : null;
}

function db_manager_delete_user(mysqli $conn, int $user_id): bool {
  $user_id = (int)$user_id;
  $ok = mysqli_query($conn, "DELETE FROM users WHERE id={$user_id}");
  return (bool)$ok;
}
