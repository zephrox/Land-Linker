<?php

function db_tasks(mysqli $conn): array {
  $sql = "
    SELECT t.*,
      au.email AS assigned_email, cu.email AS creator_email
    FROM tasks t
    LEFT JOIN users au ON au.id=t.assigned_to_user_id
    LEFT JOIN users cu ON cu.id=t.created_by_user_id
    ORDER BY t.id DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_create_task(mysqli $conn, string $title, string $desc, ?int $assigned_to, ?int $created_by, ?string $deadline): int {
  $title = db_es($conn, $title);
  $desc = db_es($conn, $desc);

  $assigned = ($assigned_to === null) ? "NULL" : (string)((int)$assigned_to);
  $creator = ($created_by === null) ? "NULL" : (string)((int)$created_by);
  $deadline = ($deadline === null || $deadline === '') ? "NULL" : "'" . db_es($conn, $deadline) . "'";

  $sql = "
    INSERT INTO tasks (title,description,assigned_to_user_id,created_by_user_id,deadline,status,progress)
    VALUES ('{$title}', '{$desc}', {$assigned}, {$creator}, {$deadline}, 'todo', 0)
  ";
  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}

function db_tasks_for_user(mysqli $conn, int $user_id): array {
  $user_id = (int)$user_id;
  $sql = "
    SELECT t.*,
      au.email AS assigned_email
    FROM tasks t
    LEFT JOIN users au ON au.id=t.assigned_to_user_id
    WHERE t.assigned_to_user_id = {$user_id}
    ORDER BY t.id DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}
