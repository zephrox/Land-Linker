<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  flash_set('error', 'Invalid request.');
  redirect(BASE_URL . 'View/my-properties.php');
}

csrf_check();

$property_id = (int)($_POST['property_id'] ?? 0);

if ($property_id <= 0) {
  flash_set('error', 'Invalid property ID.');
  redirect(BASE_URL . 'View/my-properties.php');
}

$chk = mysqli_query($conn, "SELECT id, owner_user_id FROM properties WHERE id={$property_id} LIMIT 1");
$prop = $chk ? mysqli_fetch_assoc($chk) : null;

if (!$prop) {
  flash_set('error', 'Property not found.');
  redirect(BASE_URL . 'View/my-properties.php');
}

$u = current_user();
$user_id = (int)($u['id'] ?? 0);
$owner_id = (int)$prop['owner_user_id'];

if (!is_admin() && $owner_id !== $user_id) {
  flash_set('error', 'Access denied.');
  redirect(BASE_URL . 'View/my-properties.php');
}

if (empty($_FILES['images']['name'][0])) {
  flash_set('error', 'No images selected.');
  redirect(BASE_URL . 'View/property-details.php?id=' . $property_id);
}

$upload_dir = __DIR__ . '/../upload/property/';
if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0755, true);
}

$allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
$uploaded_count = 0;

$res_primary = mysqli_query($conn, "SELECT id FROM property_media WHERE property_id={$property_id} AND is_primary=1 LIMIT 1");
$has_primary = $res_primary ? (bool)mysqli_fetch_assoc($res_primary) : false;

$res_max = mysqli_query($conn, "SELECT MAX(sort_order) as max_order FROM property_media WHERE property_id={$property_id}");
$row_max = $res_max ? mysqli_fetch_assoc($res_max) : null;
$next_sort = ($row_max && $row_max['max_order'] !== null) ? ((int)$row_max['max_order'] + 1) : 0;

foreach ($_FILES['images']['name'] as $idx => $name) {
  if ($_FILES['images']['error'][$idx] !== UPLOAD_ERR_OK) continue;
  
  $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
  if (!in_array($ext, $allowed_ext)) continue;
  
  $filename = time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
  $target = $upload_dir . $filename;
  
  if (move_uploaded_file($_FILES['images']['tmp_name'][$idx], $target)) {
    $file_path = mysqli_real_escape_string($conn, 'upload/property/' . $filename);
    $is_primary = !$has_primary ? 1 : 0;
    $sort_order = $next_sort + $idx;
    
    $sql = "INSERT INTO property_media (property_id, file_path, is_primary, sort_order)
            VALUES ({$property_id}, '{$file_path}', {$is_primary}, {$sort_order})";
    
    mysqli_query($conn, $sql);
    
    if ($is_primary) $has_primary = true;
    $uploaded_count++;
  }
}

if ($uploaded_count > 0) {
  flash_set('success', $uploaded_count . ' image(s) uploaded successfully.');
} else {
  flash_set('error', 'No valid images uploaded.');
}

redirect(BASE_URL . 'View/property-details.php?id=' . $property_id);
