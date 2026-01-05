<?php

function db_active_plans(mysqli $conn): array {
  $sql = "SELECT * FROM plans WHERE is_active=1 ORDER BY price_bdt ASC";
  $res = mysqli_query($conn, $sql);
  return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
}

function db_user_active_subscription(mysqli $conn, int $user_id): ?array {
  $user_id = (int)$user_id;

  $sql = "SELECT s.*, p.name AS plan_name, p.price_bdt, p.period
          FROM subscriptions s JOIN plans p ON p.id=s.plan_id
          WHERE s.user_id={$user_id} AND s.status IN ('active','trial','past_due')
          ORDER BY s.id DESC LIMIT 1";

  $res = mysqli_query($conn, $sql);
  if (!$res) return null;

  $row = mysqli_fetch_assoc($res);
  return $row ?: null;
}

function db_create_subscription(mysqli $conn, int $user_id, int $plan_id): int {
  $user_id = (int)$user_id;
  $plan_id = (int)$plan_id;

  $sql = "INSERT INTO subscriptions (user_id, plan_id, status)
          VALUES ({$user_id}, {$plan_id}, 'active')";

  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}

function db_create_payment(
  mysqli $conn,
  int $user_id,
  ?int $subscription_id,
  int $amount_bdt,
  string $method,
  string $provider_txn_id,
  string $status='paid'
): int {
  $user_id = (int)$user_id;
  $amount_bdt = (int)$amount_bdt;

  $sid = ($subscription_id === null) ? "NULL" : (string)((int)$subscription_id);

  $method = mysqli_real_escape_string($conn, $method);
  $provider_txn_id = mysqli_real_escape_string($conn, $provider_txn_id);
  $status = mysqli_real_escape_string($conn, $status);

  $sql = "INSERT INTO payments (user_id, subscription_id, amount_bdt, method, provider_txn_id, status, paid_at)
          VALUES ({$user_id}, {$sid}, {$amount_bdt}, '{$method}', '{$provider_txn_id}', '{$status}', NOW())";

  mysqli_query($conn, $sql);
  return (int)mysqli_insert_id($conn);
}
