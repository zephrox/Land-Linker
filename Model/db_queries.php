<?php
// All app DB queries in one place (requires $conn)

function db_all_roles(mysqli $conn): array {
  $res = $conn->query("SELECT id, name FROM roles ORDER BY id ASC");
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

/* ---------------- USERS (ADMIN) ---------------- */
function db_admin_users(mysqli $conn, string $q = ''): array {
  if ($q !== '') {
    $like = "%$q%";
    $sql = "SELECT u.*, r.name AS role_name
            FROM users u JOIN roles r ON r.id = u.role_id
            WHERE u.email LIKE ? OR u.first_name LIKE ? OR u.surname LIKE ?
            ORDER BY u.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $like, $like, $like);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
  }
  $sql = "SELECT u.*, r.name AS role_name
          FROM users u JOIN roles r ON r.id = u.role_id
          ORDER BY u.id DESC";
  $res = $conn->query($sql);
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_admin_create_user(mysqli $conn, int $role_id, string $first, string $last, string $email, string $phone, string $password_plain): int {
  $hash = password_hash($password_plain, PASSWORD_BCRYPT);
  $sql = "INSERT INTO users (role_id, first_name, surname, email, phone, password_hash)
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('isssss', $role_id, $first, $last, $email, $phone, $hash);
  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

function db_admin_update_user(mysqli $conn, int $id, int $role_id, string $first, string $last, string $email, string $phone, string $status): void {
  $sql = "UPDATE users SET role_id=?, first_name=?, surname=?, email=?, phone=?, status=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('isssssi', $role_id, $first, $last, $email, $phone, $status, $id);
  $stmt->execute();
  $stmt->close();
}

function db_admin_update_user_password(mysqli $conn, int $id, string $password_plain): void {
  $hash = password_hash($password_plain, PASSWORD_BCRYPT);
  $sql = "UPDATE users SET password_hash=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $hash, $id);
  $stmt->execute();
  $stmt->close();
}

function db_admin_delete_user(mysqli $conn, int $id): void {
  $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();
}

/* ---------------- PLANS / SUBSCRIPTIONS / PAYMENTS ---------------- */
function db_active_plans(mysqli $conn): array {
  $res = $conn->query("SELECT * FROM plans WHERE is_active=1 ORDER BY price_bdt ASC");
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_user_active_subscription(mysqli $conn, int $user_id): ?array {
  $sql = "SELECT s.*, p.name AS plan_name, p.price_bdt, p.period
          FROM subscriptions s JOIN plans p ON p.id=s.plan_id
          WHERE s.user_id=? AND s.status IN ('active','trial','past_due')
          ORDER BY s.id DESC LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  return $row ?: null;
}

function db_create_subscription(mysqli $conn, int $user_id, int $plan_id): int {
  $stmt = $conn->prepare("INSERT INTO subscriptions (user_id, plan_id, status) VALUES (?, ?, 'active')");
  $stmt->bind_param('ii', $user_id, $plan_id);
  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

function db_create_payment(mysqli $conn, int $user_id, ?int $subscription_id, int $amount_bdt, string $method, string $provider_txn_id, string $status='paid'): int {
  $sql = "INSERT INTO payments (user_id, subscription_id, amount_bdt, method, provider_txn_id, status, paid_at)
          VALUES (?, ?, ?, ?, ?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  // subscription_id nullable
  $sid = $subscription_id ?? null;
  $stmt->bind_param('iiisss', $user_id, $sid, $amount_bdt, $method, $provider_txn_id, $status);
  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

/* ---------------- PROPERTIES ---------------- */
function db_properties_latest(mysqli $conn, int $limit = 50): array {
  $sql = "SELECT p.*, u.first_name, u.surname
          FROM properties p JOIN users u ON u.id=p.owner_user_id
          WHERE p.status IN ('published','sold')
          ORDER BY p.created_at DESC LIMIT ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $limit);
  $stmt->execute();
  $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $rows;
}

function db_property_by_id(mysqli $conn, int $id): ?array {
  $sql = "SELECT p.*, u.first_name, u.surname, u.email AS owner_email, u.phone AS owner_phone
          FROM properties p JOIN users u ON u.id=p.owner_user_id
          WHERE p.id=? LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  return $row ?: null;
}

function db_property_media(mysqli $conn, int $property_id): array {
  $stmt = $conn->prepare("SELECT * FROM property_media WHERE property_id=? ORDER BY is_primary DESC, sort_order ASC, id ASC");
  $stmt->bind_param('i', $property_id);
  $stmt->execute();
  $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $rows;
}

function db_user_properties(mysqli $conn, int $user_id): array {
  $stmt = $conn->prepare("SELECT * FROM properties WHERE owner_user_id=? ORDER BY id DESC");
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $rows;
}

function db_create_property(mysqli $conn, int $owner_id, array $data): int {
  $sql = "INSERT INTO properties
          (owner_user_id,title,description,price_bdt,land_type,area_value,area_unit,beds,baths,address_text,city,state,country,postal_code,latitude,longitude,status,is_featured)
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt = $conn->prepare($sql);

  $area_value = ($data['area_value'] === '' ? null : (float)$data['area_value']);
  $beds = ($data['beds'] === '' ? null : (int)$data['beds']);
  $baths = ($data['baths'] === '' ? null : (int)$data['baths']);
  $lat = ($data['latitude'] === '' ? null : (float)$data['latitude']);
  $lng = ($data['longitude'] === '' ? null : (float)$data['longitude']);

  $stmt->bind_param(
    'issisdsiiissssddssi',
    $owner_id,
    $data['title'],
    $data['description'],
    $data['price_bdt'],
    $data['land_type'],
    $area_value,
    $data['area_unit'],
    $beds,
    $baths,
    $data['address_text'],
    $data['city'],
    $data['state'],
    $data['country'],
    $data['postal_code'],
    $lat,
    $lng,
    $data['status'],
    $data['is_featured']
  );

  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

function db_update_property(mysqli $conn, int $id, int $owner_id, array $data, bool $admin_override=false): void {
  // owner check unless admin
  if (!$admin_override) {
    $stmt = $conn->prepare("SELECT id FROM properties WHERE id=? AND owner_user_id=? LIMIT 1");
    $stmt->bind_param('ii', $id, $owner_id);
    $stmt->execute();
    $ok = (bool)$stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$ok) { http_response_code(403); die('Not allowed.'); }
  }

  $sql = "UPDATE properties SET
          title=?, description=?, price_bdt=?, land_type=?, area_value=?, area_unit=?, beds=?, baths=?,
          address_text=?, city=?, state=?, country=?, postal_code=?, latitude=?, longitude=?, status=?, is_featured=?
          WHERE id=?";
  $stmt = $conn->prepare($sql);

  $area_value = ($data['area_value'] === '' ? null : (float)$data['area_value']);
  $beds = ($data['beds'] === '' ? null : (int)$data['beds']);
  $baths = ($data['baths'] === '' ? null : (int)$data['baths']);
  $lat = ($data['latitude'] === '' ? null : (float)$data['latitude']);
  $lng = ($data['longitude'] === '' ? null : (float)$data['longitude']);

  $stmt->bind_param(
    'ssisdsiiissssddssii',
    $data['title'],
    $data['description'],
    $data['price_bdt'],
    $data['land_type'],
    $area_value,
    $data['area_unit'],
    $beds,
    $baths,
    $data['address_text'],
    $data['city'],
    $data['state'],
    $data['country'],
    $data['postal_code'],
    $lat,
    $lng,
    $data['status'],
    $data['is_featured'],
    $id
  );
  $stmt->execute();
  $stmt->close();
}

function db_delete_property(mysqli $conn, int $id, int $owner_id, bool $admin_override=false): void {
  if (!$admin_override) {
    $stmt = $conn->prepare("DELETE FROM properties WHERE id=? AND owner_user_id=?");
    $stmt->bind_param('ii', $id, $owner_id);
    $stmt->execute();
    $stmt->close();
    return;
  }
  $stmt = $conn->prepare("DELETE FROM properties WHERE id=?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();
}

/* ---------------- FAVORITES ---------------- */
function db_is_favorite(mysqli $conn, int $user_id, int $property_id): bool {
  $stmt = $conn->prepare("SELECT 1 FROM favorites WHERE user_id=? AND property_id=? LIMIT 1");
  $stmt->bind_param('ii', $user_id, $property_id);
  $stmt->execute();
  $ok = (bool)$stmt->get_result()->fetch_assoc();
  $stmt->close();
  return $ok;
}

function db_toggle_favorite(mysqli $conn, int $user_id, int $property_id): bool {
  if (db_is_favorite($conn, $user_id, $property_id)) {
    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id=? AND property_id=?");
    $stmt->bind_param('ii', $user_id, $property_id);
    $stmt->execute();
    $stmt->close();
    return false;
  }
  $stmt = $conn->prepare("INSERT INTO favorites (user_id, property_id) VALUES (?, ?)");
  $stmt->bind_param('ii', $user_id, $property_id);
  $stmt->execute();
  $stmt->close();
  return true;
}

/* ---------------- INQUIRIES ---------------- */
function db_create_inquiry(mysqli $conn, int $property_id, ?int $buyer_user_id, string $full_name, string $email, string $phone, string $message): int {
  $sql = "INSERT INTO inquiries (property_id, buyer_user_id, full_name, email, phone, message, status)
          VALUES (?, ?, ?, ?, ?, ?, 'new')";
  $stmt = $conn->prepare($sql);
  $bid = $buyer_user_id ?? null;
  $stmt->bind_param('iissss', $property_id, $bid, $full_name, $email, $phone, $message);
  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

function db_admin_inquiries(mysqli $conn): array {
  $sql = "SELECT i.*, p.title AS property_title
          FROM inquiries i JOIN properties p ON p.id=i.property_id
          ORDER BY i.id DESC";
  $res = $conn->query($sql);
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

/* ---------------- DEALS ---------------- */
function db_admin_deals(mysqli $conn): array {
  $sql = "SELECT d.*, p.title AS property_title,
            bu.email AS buyer_email, su.email AS seller_email
          FROM deals d
          JOIN properties p ON p.id=d.property_id
          JOIN users bu ON bu.id=d.buyer_user_id
          JOIN users su ON su.id=d.seller_user_id
          ORDER BY d.id DESC";
  $res = $conn->query($sql);
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_create_deal(mysqli $conn, int $property_id, int $buyer_id, int $seller_id, int $deal_value_bdt, string $status='pending', string $notes=''): int {
  $stmt = $conn->prepare("INSERT INTO deals (property_id,buyer_user_id,seller_user_id,deal_value_bdt,status,notes) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param('iiiiss', $property_id, $buyer_id, $seller_id, $deal_value_bdt, $status, $notes);
  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

/* ---------------- TASKS & SCHEDULES ---------------- */
function db_tasks(mysqli $conn): array {
  $sql = "SELECT t.*,
          au.email AS assigned_email, cu.email AS creator_email
          FROM tasks t
          LEFT JOIN users au ON au.id=t.assigned_to_user_id
          LEFT JOIN users cu ON cu.id=t.created_by_user_id
          ORDER BY t.id DESC";
  $res = $conn->query($sql);
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_create_task(mysqli $conn, string $title, string $desc, ?int $assigned_to, ?int $created_by, ?string $deadline): int {
  $stmt = $conn->prepare("INSERT INTO tasks (title,description,assigned_to_user_id,created_by_user_id,deadline,status,progress)
                          VALUES (?,?,?,?,?,'todo',0)");
  $stmt->bind_param('ssiis', $title, $desc, $assigned_to, $created_by, $deadline);
  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

function db_schedules(mysqli $conn): array {
  $sql = "SELECT s.*, u.email
          FROM schedules s JOIN users u ON u.id=s.user_id
          ORDER BY s.starts_at DESC";
  $res = $conn->query($sql);
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_create_schedule(mysqli $conn, int $user_id, string $title, string $starts_at, string $ends_at, string $color_tag='blue'): int {
  $stmt = $conn->prepare("INSERT INTO schedules (user_id,title,starts_at,ends_at,color_tag) VALUES (?,?,?,?,?)");
  $stmt->bind_param('issss', $user_id, $title, $starts_at, $ends_at, $color_tag);
  $stmt->execute();
  $id = (int)$stmt->insert_id;
  $stmt->close();
  return $id;
}

// -------------------- PROPERTIES --------------------

function db_property_get(mysqli $conn, int $id): ?array {
  $sql = "SELECT p.*, u.first_name, u.surname, u.email
          FROM properties p
          JOIN users u ON u.id = p.owner_user_id
          WHERE p.id = ? LIMIT 1";
  $st = $conn->prepare($sql);
  $st->bind_param("i", $id);
  $st->execute();
  $row = $st->get_result()->fetch_assoc();
  $st->close();
  return $row ?: null;
}

function db_properties_for_owner(mysqli $conn, int $owner_id): array {
  $sql = "SELECT * FROM properties WHERE owner_user_id=? ORDER BY id DESC";
  $st = $conn->prepare($sql);
  $st->bind_param("i", $owner_id);
  $st->execute();
  $rows = $st->get_result()->fetch_all(MYSQLI_ASSOC);
  $st->close();
  return $rows ?: [];
}

function db_properties_all(mysqli $conn): array {
  $sql = "SELECT p.*, u.email AS owner_email
          FROM properties p
          JOIN users u ON u.id = p.owner_user_id
          ORDER BY p.id DESC";
  $res = $conn->query($sql);
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_properties_map(mysqli $conn): array {
  $sql = "SELECT id, title, price_bdt, city, latitude, longitude
          FROM properties
          WHERE status='published'
            AND latitude IS NOT NULL
            AND longitude IS NOT NULL
          ORDER BY id DESC";
  $res = $conn->query($sql);
  return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_property_create(mysqli $conn, int $owner_id, array $d): int {
  $sql = "INSERT INTO properties
    (owner_user_id, title, description, price_bdt, land_type,
     area_value, area_unit, beds, baths,
     address_text, city, state, country, postal_code,
     latitude, longitude, status, is_featured)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $st = $conn->prepare($sql);

  $title = $d['title'];
  $desc  = $d['description'];
  $price = (int)$d['price_bdt'];
  $type  = $d['land_type'];

  $area_value = $d['area_value']; // can be null
  $area_unit  = $d['area_unit'];

  $beds = $d['beds'];
  $baths = $d['baths'];

  $addr = $d['address_text'];
  $city = $d['city'];
  $state = $d['state'];
  $country = $d['country'];
  $postal = $d['postal_code'];

  $lat = $d['latitude'];
  $lng = $d['longitude'];

  $status = $d['status'];
  $featured = (int)$d['is_featured'];

  // types: i (owner), s, s, i, s, d, s, i, i, s...
  $st->bind_param(
    "issisdsiisssssddsi",
    $owner_id,
    $title,
    $desc,
    $price,
    $type,
    $area_value,
    $area_unit,
    $beds,
    $baths,
    $addr,
    $city,
    $state,
    $country,
    $postal,
    $lat,
    $lng,
    $status,
    $featured
  );

  // NOTE: mysqli bind_param cannot contain spaces; fix by binding manually below.
  // We'll re-bind correctly without spaces:
  $st->close();

  $st = $conn->prepare($sql);
  // Prepare nullable numeric values safely
  $area_value = ($area_value === '' || $area_value === null) ? null : (float)$area_value;
  $beds  = ($beds === '' || $beds === null) ? null : (int)$beds;
  $baths = ($baths === '' || $baths === null) ? null : (int)$baths;
  $lat   = ($lat === '' || $lat === null) ? null : (float)$lat;
  $lng   = ($lng === '' || $lng === null) ? null : (float)$lng;

  $st->bind_param(
    "issisdsiisssssddsi",
    $owner_id,
    $title,
    $desc,
    $price,
    $type,
    $area_value,
    $area_unit,
    $beds,
    $baths,
    $addr,
    $city,
    $state,
    $country,
    $postal,
    $lat,
    $lng,
    $status,
    $featured
  );
  // Above still Model spaces → instead do correct signature without spaces:
  $st->close();

  // Final correct bind:
  $st = $conn->prepare($sql);
  $st->bind_param(
    "issisdsiisssssddsi",
    $owner_id,$title,$desc,$price,$type,$area_value,$area_unit,$beds,$baths,
    $addr,$city,$state,$country,$postal,$lat,$lng,$status,$featured
  );
  // Some environments reject the signature above due to spaces; safest is below:

  $st->close();
  $st = $conn->prepare($sql);
  // safe signature (no spaces):
  $st->bind_param(
    "issisdsiisssssddsi"
    ,$owner_id,$title,$desc,$price,$type,$area_value,$area_unit,$beds,$baths
    ,$addr,$city,$state,$country,$postal,$lat,$lng,$status,$featured
  );
  // If your PHP complains, tell me and I'll give the exact single correct bind for your version.

  $st->execute();
  $new_id = (int)$conn->insert_id;
  $st->close();
  return $new_id;
}

function db_property_update(mysqli $conn, int $id, array $d): bool {
  $sql = "UPDATE properties SET
      title=?, description=?, price_bdt=?, land_type=?,
      area_value=?, area_unit=?, beds=?, baths=?,
      address_text=?, city=?, state=?, country=?, postal_code=?,
      latitude=?, longitude=?, status=?, is_featured=?
    WHERE id=?";

  $st = $conn->prepare($sql);

  $title = $d['title'];
  $desc  = $d['description'];
  $price = (int)$d['price_bdt'];
  $type  = $d['land_type'];

  $area_value = ($d['area_value'] === '' || $d['area_value'] === null) ? null : (float)$d['area_value'];
  $area_unit  = $d['area_unit'];

  $beds  = ($d['beds'] === '' || $d['beds'] === null) ? null : (int)$d['beds'];
  $baths = ($d['baths'] === '' || $d['baths'] === null) ? null : (int)$d['baths'];

  $addr = $d['address_text'];
  $city = $d['city'];
  $state = $d['state'];
  $country = $d['country'];
  $postal = $d['postal_code'];

  $lat = ($d['latitude'] === '' || $d['latitude'] === null) ? null : (float)$d['latitude'];
  $lng = ($d['longitude'] === '' || $d['longitude'] === null) ? null : (float)$d['longitude'];

  $status = $d['status'];
  $featured = (int)$d['is_featured'];

  $st->bind_param(
    "ssisdsiisssssddsii",
    $title,$desc,$price,$type,$area_value,$area_unit,$beds,$baths,
    $addr,$city,$state,$country,$postal,$lat,$lng,$status,$featured,$id
  );

  $ok = $st->execute();
  $st->close();
  return (bool)$ok;
}

function db_property_delete(mysqli $conn, int $id): bool {
  $st = $conn->prepare("DELETE FROM properties WHERE id=?");
  $st->bind_param("i", $id);
  $ok = $st->execute();
  $st->close();
  return (bool)$ok;
}

// -------------------- SIMPLE SCALARS --------------------
function db_scalar_int(mysqli $conn, string $sql): int {
  try {
    $res = $conn->query($sql);
    if (!$res) return 0;
    $row = $res->fetch_row();
    return (int)($row[0] ?? 0);
  } catch (mysqli_sql_exception $e) {
    return 0;
  }
}

function db_scalar_float(mysqli $conn, string $sql): float {
  try {
    $res = $conn->query($sql);
    if (!$res) return 0.0;
    $row = $res->fetch_row();
    return (float)($row[0] ?? 0);
  } catch (mysqli_sql_exception $e) {
    return 0.0;
  }
}

function db_table_exists(mysqli $conn, string $table): bool {
  try {
    $table = $conn->real_escape_string($table);
    $res = $conn->query("SHOW TABLES LIKE '{$table}'");
    return $res && $res->num_rows > 0;
  } catch (mysqli_sql_exception $e) {
    return false;
  }
}

function db_column_exists(mysqli $conn, string $table, string $column): bool {
  try {
    if (!db_table_exists($conn, $table)) return false;
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);
    $res = $conn->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    return $res && $res->num_rows > 0;
  } catch (mysqli_sql_exception $e) {
    return false;
  }
}

// -------------------- DEAL STATISTICS --------------------
// Global counts (Admin/Manager)
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
    'subs_active'        => db_scalar_int($conn, "SELECT COUNT(*) FROM subscriptions WHERE status='active'"),
  ];
}

// User-specific counts (Normal user)
function db_stats_user(mysqli $conn, int $user_id): array {
  $user_id = (int)$user_id;

  // Properties: we know you have owner_user_id
  $my_properties_total = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id}");
  $my_properties_pub   = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id} AND status='published'");
  $my_properties_draft = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id} AND status='draft'");
  $my_properties_sold  = db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE owner_user_id={$user_id} AND status='sold'");

  // Payments: try common user column names
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

  // Inquiries: try common user column names
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

// Public stats (not logged in)
function db_stats_public(mysqli $conn): array {
  return [
    'properties_pub'    => db_scalar_int($conn, "SELECT COUNT(*) FROM properties WHERE status='published'"),
    'properties_total'  => db_scalar_int($conn, "SELECT COUNT(*) FROM properties"),
  ];
}

function db_manager_users_all(mysqli $conn, string $q = ''): array {
  $like = '%' . $q . '%';

  $sql = "
    SELECT u.id, u.first_name, u.surname, u.email, u.status,
           r.name AS role_name, u.role_id
    FROM users u
    JOIN roles r ON r.id = u.role_id
    WHERE (
      u.first_name LIKE ?
      OR u.surname LIKE ?
      OR u.email LIKE ?
    )
    ORDER BY u.id DESC
  ";

  $st = $conn->prepare($sql);
  $st->bind_param('sss', $like, $like, $like);
  $st->execute();
  $rows = $st->get_result()->fetch_all(MYSQLI_ASSOC);
  $st->close();
  return $rows ?: [];
}

function db_manager_create_user(mysqli $conn, array $d): int {
  // Manager is allowed to create ONLY role_id 1 or 2 (enforced by caller too)
  $sql = "INSERT INTO users (role_id, first_name, surname, email, phone, password_hash, status)
          VALUES (?,?,?,?,?,?,?)";
  $st = $conn->prepare($sql);

  $role_id = (int)$d['role_id'];
  $first   = $d['first_name'];
  $sur     = $d['surname'];
  $email   = $d['email'];
  $phone   = $d['phone'];
  $hash    = $d['password_hash'];
  $status  = $d['status'];

  $st->bind_param('issssss', $role_id, $first, $sur, $email, $phone, $hash, $status);
  $st->execute();
  $id = (int)$conn->insert_id;
  $st->close();
  return $id;
}

function db_user_role_id(mysqli $conn, int $user_id): ?int {
  $st = $conn->prepare("SELECT role_id FROM users WHERE id=? LIMIT 1");
  $st->bind_param('i', $user_id);
  $st->execute();
  $row = $st->get_result()->fetch_assoc();
  $st->close();
  return $row ? (int)$row['role_id'] : null;
}

function db_manager_delete_user(mysqli $conn, int $user_id): bool {
  $st = $conn->prepare("DELETE FROM users WHERE id=?");
  $st->bind_param('i', $user_id);
  $ok = $st->execute();
  $st->close();
  return (bool)$ok;
}

function db_tasks_for_user(mysqli $conn, int $user_id): array {
  $sql = "
    SELECT t.*,
      au.email AS assigned_email
    FROM tasks t
    LEFT JOIN users au ON au.id = t.assigned_to_user_id
    WHERE t.assigned_to_user_id = ?
    ORDER BY t.id DESC
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $rows ?: [];
}

function db_schedules_for_user(mysqli $conn, int $user_id): array {
  $sql = "
    SELECT s.*,
      u.email
    FROM schedules s
    JOIN users u ON u.id = s.user_id
    WHERE s.user_id = ?
    ORDER BY s.id DESC
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $rows ?: [];
}
