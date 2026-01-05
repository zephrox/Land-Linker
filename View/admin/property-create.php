<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([1, 2, 3, 4], BASE_URL . 'View/login.php'); // All users

$u = current_user();
$user_id = (int)$u['id'];

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
    db_property_create($conn, $user_id, $d);
    flash_set('success', 'Property created.');
    redirect(BASE_URL . 'View/my-properties.php');
  }
}

$types = ['land'=>'Land','house'=>'House','apartment'=>'Apartment','commercial'=>'Commercial','farm'=>'Farm','other'=>'Other'];
$units = ['sqft','katha','bigha','acre','sqm','hectare'];
$statuses = ['published','draft','sold','archived'];

require_once __DIR__ . '/layout/header.php';
?>

<div class="container">
  <div style="display:flex; justify-content:space-between; align-items:flex-end; gap:10px; flex-wrap:wrap;">
    <div>
      <h2 style="margin-bottom:4px;">Add Property</h2>
      <p style="margin:0; color:#667085;">Create a listing that appears in “My Properties” and Map View (when published + coordinates).</p>
    </div>
    <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">Back to My Properties</a>
  </div>

  <?php if ($errors): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px; margin-top:12px;">
      <ul style="margin:0; padding-left:18px;">
        <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form class="card form-card" method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="form-grid">
      <div>
        <div class="section-title">Basic Information</div>
        <div class="section-sub">Title, type, price, and public visibility.</div>

        <div class="form-row">
          <div class="form-group col-8">
            <label for="title">Title</label>
            <input id="title" class="input" name="title" value="<?= e($_POST['title'] ?? '') ?>" placeholder="e.g., 5 Katha Land in Bashundhara" required>
          </div>

          <div class="form-group col-4">
            <label for="status">Status</label>
            <select id="status" class="input" name="status">
              <?php $selS = $_POST['status'] ?? 'published'; ?>
              <?php foreach ($statuses as $s): ?>
                <option value="<?= e($s) ?>" <?= ($selS === $s ? 'selected' : '') ?>><?= e(ucfirst($s)) ?></option>
              <?php endforeach; ?>
            </select>
            <div class="form-hint">Only “published” shows on Map View.</div>
          </div>

          <div class="form-group col-4">
            <label for="land_type">Type</label>
            <select id="land_type" class="input" name="land_type">
              <?php $selT = $_POST['land_type'] ?? 'land'; ?>
              <?php foreach ($types as $k => $v): ?>
                <option value="<?= e($k) ?>" <?= ($selT === $k ? 'selected' : '') ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group col-4">
            <label for="price_bdt">Price (BDT)</label>
            <input id="price_bdt" class="input" type="number" min="1" name="price_bdt" value="<?= e($_POST['price_bdt'] ?? '') ?>" placeholder="e.g., 1500000" required>
          </div>

          <div class="form-group col-4">
            <label for="is_featured">Featured</label>
            <div class="checkbox-row">
              <input id="is_featured" type="checkbox" name="is_featured" value="1" <?= !empty($_POST['is_featured']) ? 'checked' : '' ?>>
              <span style="color:#344054;">Highlight this listing</span>
            </div>
          </div>

          <div class="form-group col-12">
            <label for="description">Description</label>
            <textarea id="description" class="input" name="description" rows="4" placeholder="Add key details: road access, utilities, nearby landmarks..."><?= e($_POST['description'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <div>
        <div class="section-title">Size & Location</div>
        <div class="section-sub">Area + coordinates help map placement.</div>

        <div class="form-row">
          <div class="form-group col-6">
            <label for="area_value">Area Value</label>
            <input id="area_value" class="input" type="number" step="0.01" name="area_value" value="<?= e($_POST['area_value'] ?? '') ?>" placeholder="e.g., 5">
          </div>

          <div class="form-group col-6">
            <label for="area_unit">Area Unit</label>
            <select id="area_unit" class="input" name="area_unit">
              <?php $selU = $_POST['area_unit'] ?? 'sqft'; ?>
              <?php foreach ($units as $u1): ?>
                <option value="<?= e($u1) ?>" <?= ($selU === $u1 ? 'selected' : '') ?>><?= e($u1) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group col-6">
            <label for="latitude">Latitude</label>
            <input id="latitude" class="input" type="number" step="0.0000001" name="latitude" value="<?= e($_POST['latitude'] ?? '') ?>" placeholder="e.g., 23.8103">
          </div>

          <div class="form-group col-6">
            <label for="longitude">Longitude</label>
            <input id="longitude" class="input" type="number" step="0.0000001" name="longitude" value="<?= e($_POST['longitude'] ?? '') ?>" placeholder="e.g., 90.4125">
            <div class="form-hint">To appear on map, provide both latitude & longitude.</div>
          </div>

          <div class="form-group col-4">
            <label for="city">City</label>
            <input id="city" class="input" name="city" value="<?= e($_POST['city'] ?? '') ?>" placeholder="e.g., Dhaka">
          </div>

          <div class="form-group col-4">
            <label for="state">State</label>
            <input id="state" class="input" name="state" value="<?= e($_POST['state'] ?? '') ?>" placeholder="e.g., Dhaka Division">
          </div>

          <div class="form-group col-4">
            <label for="country">Country</label>
            <input id="country" class="input" name="country" value="<?= e($_POST['country'] ?? '') ?>" placeholder="e.g., Bangladesh">
          </div>

          <div class="form-group col-8">
            <label for="address_text">Address</label>
            <input id="address_text" class="input" name="address_text" value="<?= e($_POST['address_text'] ?? '') ?>" placeholder="e.g., Road 12, Block C, Bashundhara">
          </div>

          <div class="form-group col-4">
            <label for="postal_code">Postal Code</label>
            <input id="postal_code" class="input" name="postal_code" value="<?= e($_POST['postal_code'] ?? '') ?>" placeholder="e.g., 1229">
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Create Property</button>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">Cancel</a>
      </div>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
