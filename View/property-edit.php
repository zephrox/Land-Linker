<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

$u = current_user();
$user_id = (int)$u['id'];

$id = get_int('id');
$p = $id > 0 ? db_property_get($conn, $id) : null;

if (!$p) { flash_set('error', 'Property not found.'); redirect(BASE_URL . 'View/my-properties.php'); }

$owner_id = (int)$p['owner_user_id'];
if (!is_admin() && $owner_id !== $user_id) {
  flash_set('error', 'Access denied.');
  redirect(BASE_URL . 'View/my-properties.php');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();

  $d = [
    'title' => post_str('title', 180),
    'description' => trim((string)($_POST['description'] ?? '')),
    'price_bdt' => post_int('price_bdt'),
    'land_type' => post_str('land_type', 20),
    'area_value' => trim((string)($_POST['area_value'] ?? '')),
    'area_unit' => post_str('area_unit', 10),
    'beds' => trim((string)($_POST['beds'] ?? '')),
    'baths' => trim((string)($_POST['baths'] ?? '')),
    'address_text' => post_str('address_text', 255),
    'city' => post_str('city', 80),
    'state' => post_str('state', 80),
    'country' => post_str('country', 80),
    'postal_code' => post_str('postal_code', 20),
    'latitude' => trim((string)($_POST['latitude'] ?? '')),
    'longitude' => trim((string)($_POST['longitude'] ?? '')),
    'status' => post_str('status', 20),
    'is_featured' => (int)($_POST['is_featured'] ?? 0),
  ];

  if ($d['title'] === '') $errors[] = 'Title is required.';
  if ((int)$d['price_bdt'] <= 0) $errors[] = 'Price must be greater than 0.';

  if (!$errors) {
    db_property_update($conn, $id, $d);
    flash_set('success', 'Property updated.');
    redirect(BASE_URL . 'View/my-properties.php');
  }
}

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2>Edit Property #<?= (int)$p['id'] ?></h2>

  <?php if ($errors): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px;">
      <ul style="margin:0; padding-left:18px;">
        <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form class="card" method="post" style="padding:16px; margin-top:12px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <label>Title</label>
    <input class="input" name="title" value="<?= e($p['title']) ?>" required>

    <label>Description</label>
    <textarea class="input" name="description" rows="4"><?= e($p['description'] ?? '') ?></textarea>

    <label>Price (BDT)</label>
    <input class="input" type="number" name="price_bdt" min="1" value="<?= (int)$p['price_bdt'] ?>" required>

    <label>Status</label>
    <select class="input" name="status">
      <?php foreach (['published','draft','sold','archived'] as $s): ?>
        <option value="<?= e($s) ?>" <?= ($p['status'] === $s ? 'selected' : '') ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
      <div>
        <label>Latitude</label>
        <input class="input" type="number" step="0.0000001" name="latitude" value="<?= e((string)($p['latitude'] ?? '')) ?>">
      </div>
      <div>
        <label>Longitude</label>
        <input class="input" type="number" step="0.0000001" name="longitude" value="<?= e((string)($p['longitude'] ?? '')) ?>">
      </div>
    </div>

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit">Save</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">Back</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
