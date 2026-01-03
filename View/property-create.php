<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

$u = current_user();
$user_id = (int)($u['id'] ?? 0);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();

  $d = [
    'title'        => post_str('title', 180),
    'description'  => trim((string)($_POST['description'] ?? '')),
    'price_bdt'    => post_int('price_bdt'),
    'land_type'    => post_str('land_type', 20),
    'area_value'   => trim((string)($_POST['area_value'] ?? '')),
    'area_unit'    => post_str('area_unit', 10),
    'beds'         => trim((string)($_POST['beds'] ?? '')),
    'baths'        => trim((string)($_POST['baths'] ?? '')),
    'address_text' => post_str('address_text', 255),
    'city'         => post_str('city', 80),
    'state'        => post_str('state', 80),
    'country'      => post_str('country', 80),
    'postal_code'  => post_str('postal_code', 20),
    'latitude'     => trim((string)($_POST['latitude'] ?? '')),
    'longitude'    => trim((string)($_POST['longitude'] ?? '')),
    'status'       => post_str('status', 20),
    'is_featured'  => (int)($_POST['is_featured'] ?? 0),
  ];

  if ($d['title'] === '') $errors[] = 'Title is required.';
  if ((int)$d['price_bdt'] <= 0) $errors[] = 'Price must be greater than 0.';

  if (!$errors) {
    db_property_create($conn, $user_id, $d);
    flash_set('success', 'Property created.');
    redirect(BASE_URL . 'View/my-properties.php');
    exit;
  }
}

require_once __DIR__ . '/layout/header.php';

$types    = ['land'=>'Land','house'=>'House','apartment'=>'Apartment','commercial'=>'Commercial','farm'=>'Farm','other'=>'Other'];
$statuses = ['published','draft','sold','archived'];
$units    = ['sqft','katha','bigha','acre','sqm','hectare'];

$selType   = $_POST['land_type'] ?? 'land';
$selStatus = $_POST['status'] ?? 'published';
$selUnit   = $_POST['area_unit'] ?? 'sqft';
?>

<div class="container">
  <h2>Add Property</h2>

  <?php if ($errors): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px; margin-top:12px;">
      <ul style="margin:0; padding-left:18px;">
        <?php foreach ($errors as $er): ?>
          <li><?= e($er) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form class="card" method="post" style="padding:18px; margin-top:12px; max-width:900px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div style="display:grid; grid-template-columns:1fr; gap:12px;">

      <div>
        <label>Title</label>
        <input class="input" name="title" value="<?= e($_POST['title'] ?? '') ?>" required style="width:100%;">
      </div>

      <div>
        <label>Description</label>
        <textarea class="input" name="description" rows="4" style="width:100%;"><?= e($_POST['description'] ?? '') ?></textarea>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
        <div>
          <label>Price (BDT)</label>
          <input class="input" type="number" name="price_bdt" min="1" value="<?= e($_POST['price_bdt'] ?? '') ?>" required style="width:100%;">
        </div>

        <div>
          <label>Type</label>
          <select class="input" name="land_type" style="width:100%;">
            <?php foreach ($types as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($selType === $k ? 'selected' : '') ?>><?= e($v) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label>Status</label>
          <select class="input" name="status" style="width:100%;">
            <?php foreach ($statuses as $s): ?>
              <option value="<?= e($s) ?>" <?= ($selStatus === $s ? 'selected' : '') ?>><?= e(ucfirst($s)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label>Area Value</label>
          <input class="input" type="number" step="0.01" name="area_value" value="<?= e($_POST['area_value'] ?? '') ?>" style="width:100%;">
        </div>
        <div>
          <label>Area Unit</label>
          <select class="input" name="area_unit" style="width:100%;">
            <?php foreach ($units as $u1): ?>
              <option value="<?= e($u1) ?>" <?= ($selUnit === $u1 ? 'selected' : '') ?>><?= e($u1) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label>Latitude</label>
          <input class="input" type="number" step="0.0000001" name="latitude" value="<?= e($_POST['latitude'] ?? '') ?>" style="width:100%;">
        </div>
        <div>
          <label>Longitude</label>
          <input class="input" type="number" step="0.0000001" name="longitude" value="<?= e($_POST['longitude'] ?? '') ?>" style="width:100%;">
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
        <div>
          <label>City</label>
          <input class="input" name="city" value="<?= e($_POST['city'] ?? '') ?>" style="width:100%;">
        </div>
        <div>
          <label>State</label>
          <input class="input" name="state" value="<?= e($_POST['state'] ?? '') ?>" style="width:100%;">
        </div>
        <div>
          <label>Country</label>
          <input class="input" name="country" value="<?= e($_POST['country'] ?? '') ?>" style="width:100%;">
        </div>
      </div>

      <div style="display:grid; grid-template-columns:2fr 1fr; gap:12px;">
        <div>
          <label>Address</label>
          <input class="input" name="address_text" value="<?= e($_POST['address_text'] ?? '') ?>" style="width:100%;">
        </div>
        <div>
          <label>Postal Code</label>
          <input class="input" name="postal_code" value="<?= e($_POST['postal_code'] ?? '') ?>" style="width:100%;">
        </div>
      </div>

      <div style="display:flex; align-items:center; gap:10px;">
        <input type="checkbox" id="feat" name="is_featured" value="1" <?= !empty($_POST['is_featured']) ? 'checked' : '' ?>>
        <label for="feat" style="margin:0;">Featured</label>
      </div>

      <div style="display:flex; gap:10px; margin-top:8px;">
        <button class="btn btn-primary" type="submit">Create</button>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">Back</a>
      </div>

    </div>
  </form>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
