<?php

function db_all_roles(mysqli $conn): array {
  $res = mysqli_query($conn, "SELECT id, name FROM roles ORDER BY id ASC");
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}
