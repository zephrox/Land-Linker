<?php

function db_is_favorite(mysqli $conn, int $user_id, int $property_id): bool {
  $user_id = (int)$user_id;
  $property_id = (int)$property_id;
  $res = mysqli_query($conn, "SELECT 1 FROM favorites WHERE user_id={$user_id} AND property_id={$property_id} LIMIT 1");
  return $res ? (bool)mysqli_fetch_assoc($res) : false;
}

function db_toggle_favorite(mysqli $conn, int $user_id, int $property_id): bool {
  $user_id = (int)$user_id;
  $property_id = (int)$property_id;

  if (db_is_favorite($conn, $user_id, $property_id)) {
    mysqli_query($conn, "DELETE FROM favorites WHERE user_id={$user_id} AND property_id={$property_id}");
    return false;
  }
  mysqli_query($conn, "INSERT INTO favorites (user_id, property_id) VALUES ({$user_id}, {$property_id})");
  return true;
}
