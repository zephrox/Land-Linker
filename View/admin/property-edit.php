<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$id = get_int('id');
$prop = $id ? db_property_by_id($conn, $id) : null;
if (!$prop) { http_response_code(404); die('Property not found.'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $data = [
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
    'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
  ];

  if ($data['title']==='' || $data['price_bdt']<=0) $errors[]="Title and price required.php";

  if (!$errors) {
    db_update_property($conn, $id, (int)$prop['owner_user_id'], $data, true);
    flash_set('success', 'Property updated.');
    redirect(BASE_URL . 'View/admin/properties.php');
  }
}

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Edit Property #<?= (int)$id ?></h2>

  <?php if ($errors): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px;">
      <ul style="margin:0; padding-left:18px;"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <form class="card" method="post" style="padding:16px; margin-top:12px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <label>Title*</label>
    <input class="input" name="title" value="<?= e($prop['title']) ?>" required>

    <label>Description</label>
    <textarea class="input" name="description" rows="4"><?= e($prop['description'] ?? '') ?></textarea>

    <label>Price*</label>
    <input class="input" type="number" name="price_bdt" value="<?= (int)$prop['price_bdt'] ?>" min="1" required>

    <label>Type</label>
    <input class="input" name="land_type" value="<?= e($prop['land_type']) ?>">

    <label>Status</label>
    <input class="input" name="status" value="<?= e($prop['status']) ?>">

    <label style="display:flex; gap:8px; align-items:center; margin-top:10px;">
      <input type="checkbox" name="is_featured" value="1" <?= ((int)$prop['is_featured']===1)?'checked':''; ?>> Featured
    </label>

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit">Save</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/properties.php">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
