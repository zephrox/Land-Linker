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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  db_property_delete($conn, $id);
  flash_set('success', 'Property deleted.');
  redirect(BASE_URL . 'View/my-properties.php');
}

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2>Delete Property</h2>

  <div class="card" style="padding:16px; margin-top:12px;">
    <p>Are you sure you want to delete:</p>
    <p><strong><?= e($p['title']) ?></strong></p>

    <form method="post" style="margin-top:12px;">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <button class="btn btn-danger" type="submit">Yes, Delete</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">Cancel</a>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
