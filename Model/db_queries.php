<?php
declare(strict_types=1);

function db_fetch_user_by_email(mysqli $conn, string $email): ?array {
  $sql = "SELECT id, role_id, first_name, surname, email, phone, password_hash, status
          FROM users WHERE email = ? LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $row = $res ? $res->fetch_assoc() : null;
  $stmt->close();
  return $row ?: null;
}
