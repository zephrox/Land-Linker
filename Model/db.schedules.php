<?php

function db_schedules(mysqli $conn): array {
  $sql = "
    SELECT s.*, u.email
    FROM schedules s JOIN users u ON u.id=s.user_id
    ORDER BY s.starts_at DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_create_schedule(mysqli $conn, int $user_id, string $title, string $starts_at, string $ends_at, string $color_tag='blue'): int {
  $user_id = (int)$user_id;

  $title = db_es($conn, $title);
  $starts_at = db_es($conn, $starts_at);
  $ends_at = db_es($conn, $ends_at);
  $color_tag = db_es($conn, $color_tag);

  $sql = "
    INSERT INTO schedules (user_id,title,starts_at,ends_at,color_tag)
    VALUES ({$user_id}, '{$title}', '{$starts_at}', '{$ends_at}', '{$color_tag}')
  ";
  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}

function db_schedules_for_user(mysqli $conn, int $user_id): array {
  $user_id = (int)$user_id;
  $sql = "
    SELECT s.*,
      u.email
    FROM schedules s
    JOIN users u ON u.id=s.user_id
    WHERE s.user_id = {$user_id}
    ORDER BY s.id DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}
