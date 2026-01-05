<?php

function db_admin_deals(mysqli $conn): array {
  $sql = "
    SELECT d.*, p.title AS property_title,
      bu.email AS buyer_email, su.email AS seller_email
    FROM deals d
    JOIN properties p ON p.id=d.property_id
    JOIN users bu ON bu.id=d.buyer_user_id
    JOIN users su ON su.id=d.seller_user_id
    ORDER BY d.id DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_create_deal(mysqli $conn, int $property_id, int $buyer_id, int $seller_id, int $deal_value_bdt, string $status='pending', string $notes=''): int {
  $property_id = (int)$property_id;
  $buyer_id = (int)$buyer_id;
  $seller_id = (int)$seller_id;
  $deal_value_bdt = (int)$deal_value_bdt;

  $status = db_es($conn, $status);
  $notes = db_es($conn, $notes);

  $sql = "
    INSERT INTO deals (property_id,buyer_user_id,seller_user_id,deal_value_bdt,status,notes)
    VALUES ({$property_id}, {$buyer_id}, {$seller_id}, {$deal_value_bdt}, '{$status}', '{$notes}')
  ";
  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}
