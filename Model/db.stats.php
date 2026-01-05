<?php

function db_scalar_int(mysqli $conn, string $sql): int {
  try {
    $res = mysqli_query($conn, $sql);
    if (!$res) return 0;
    $row = mysqli_fetch_row($res);
    return (int)($row[0] ?? 0);
  } catch (mysqli_sql_exception $e) {
    return 0;
  }
}

function db_scalar_float(mysqli $conn, string $sql): float {
  try {
    $res = mysqli_query($conn, $sql);
    if (!$res) return 0.0;
    $row = mysqli_fetch_row($res);
    return (float)($row[0] ?? 0);
  } catch (mysqli_sql_exception $e) {
    return 0.0;
  }
}

function db_table_exists(mysqli $conn, string $table): bool {
  try {
    $table = db_es($conn, $table);
    $res = mysqli_query($conn, "SHOW TABLES LIKE '{$table}'");
    return $res && mysqli_num_rows($res) > 0;
  } catch (mysqli_sql_exception $e) {
    return false;
  }
}

function db_column_exists(mysqli $conn, string $table, string $column): bool {
  try {
    if (!db_table_exists($conn, $table)) return false;
    $table = db_es($conn, $table);
    $column = db_es($conn, $column);
    $res = mysqli_query($conn, "SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    return $res && mysqli_num_rows($res) > 0;
  } catch (mysqli_sql_exception $e) {
    return false;
  }
}

function db_stats_global(mysqli $conn): array {
  return [
    'users_total'        => db_scalar_int($conn, "SELECT COUNT(*) FROM users"),
    'users_active'       => db_scalar_int($conn, "SELECT COUNT(*) FROM users WHERE status='active'"),
    'properties_total'   => db_scalar_int($conn, "SELECT COUNT(*) FROM properties"),
    'properties_pub'     => db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE status='published'"),
    'properties_draft'   => db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE status='draft'"),
    'properties_sold'    => db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE status='sold'"),
    'inquiries_total'    => db_scalar_int($conn, "SELECT COUNT(*) FROM inquiries"),
    'deals_total'        => db_scalar_int($conn, "SELECT COUNT(*) FROM deals"),
    'payments_total'     => db_scalar_int($conn, "SELECT COUNT(*) FROM payments"),
    'revenue_total_bdt'  => db_scalar_float($conn, "SELECT COALESCE(SUM(amount_bdt),0) FROM payments WHERE status='paid'"),
    'subs_total'         => db_scalar_int($conn, "SELECT COUNT(*) FROM subscriptions"),
    'subs_active'        => db_scalar_int($conn, "SELECT COUNT(*) FROM subscriptions WHERE status='active'")
  ];
}

function db_stats_user(mysqli $conn, int $user_id): array {
  $user_id = (int)$user_id;

  $my_properties_total = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id}");
  $my_properties_pub   = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id} AND status='published'");
  $my_properties_draft = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id} AND status='draft'");
  $my_properties_sold  = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id} AND status='sold'");

  $my_payments_total = 0;
  $my_spend_bdt = 0.0;

  if (db_table_exists($conn, 'payments')) {
    $paymentUserCols = ['user_id', 'payer_user_id', 'customer_user_id', 'owner_user_id'];
    $paymentCol = null;
    foreach ($paymentUserCols as $c) {
      if (db_column_exists($conn, 'payments', $c)) { $paymentCol = $c; break; }
    }
    if ($paymentCol) {
      $my_payments_total = db_scalar_int($conn, "SELECT COUNT(*) FROM payments WHERE {$paymentCol}={$user_id}");
      $my_spend_bdt = db_scalar_float($conn, "SELECT COALESCE(SUM(amount_bdt),0) FROM payments WHERE {$paymentCol}={$user_id} AND status='paid'");
    }
  }

  $my_inquiries_total = 0;

  if (db_table_exists($conn, 'inquiries')) {
    $inqUserCols = ['user_id', 'buyer_user_id', 'requester_user_id', 'sender_user_id', 'created_by', 'customer_id'];
    $inqCol = null;
    foreach ($inqUserCols as $c) {
      if (db_column_exists($conn, 'inquiries', $c)) { $inqCol = $c; break; }
    }
    if ($inqCol) {
      $my_inquiries_total = db_scalar_int($conn, "SELECT COUNT(*) FROM inquiries WHERE {$inqCol}={$user_id}");
    }
  }

  return [
    'my_properties_total' => $my_properties_total,
    'my_properties_pub'   => $my_properties_pub,
    'my_properties_draft' => $my_properties_draft,
    'my_properties_sold'  => $my_properties_sold,
    'my_payments_total'   => $my_payments_total,
    'my_spend_bdt'        => $my_spend_bdt,
    'my_inquiries_total'  => $my_inquiries_total,
  ];
}

function db_stats_public(mysqli $conn): array {
  return [
    'properties_pub'    => db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE status='published'"),
    'properties_total'  => db_scalar_int($conn, "SELECT COUNT(*) FROM properties"),
  ];
}
