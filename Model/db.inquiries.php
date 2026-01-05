<?php

function db_create_inquiry(mysqli $conn, int $property_id, ?int $buyer_user_id, string $full_name, string $email, string $phone, string $message): int {
  $property_id = (int)$property_id;
  $bid = ($buyer_user_id === null) ? "NULL" : (string)((int)$buyer_user_id);

  $full_name = db_es($conn, $full_name);
  $email = db_es($conn, $email);
  $phone = db_es($conn, $phone);
  $message = db_es($conn, $message);

  $sql = "
    INSERT INTO inquiries (property_id, buyer_user_id, full_name, email, phone, message, status)
    VALUES ({$property_id}, {$bid}, '{$full_name}', '{$email}', '{$phone}', '{$message}', 'new')
  ";
  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}

function db_admin_inquiries(mysqli $conn): array {
  $sql = "
    SELECT i.*, p.title AS property_title
    FROM inquiries i JOIN properties p ON p.id=i.property_id
    ORDER BY i.id DESC
  ";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}
