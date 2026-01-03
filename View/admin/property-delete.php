<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$id = get_int('id');
$prop = $id ? db_property_by_id($conn, $id) : null;
if (!$prop) { http_response_code(404); die('Property not found.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  db_delete_property($conn, $id, (int)$prop['owner_user_id'], true);
  flash_set('success', 'Property deleted.');
  redirect(BASE_URL . 'View/admin/properties.php');
}

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Delete Property #<?= (int)$id ?></h2>
  <div class="card" style="padding:16px;">
    <p>Delete <b><?= e($prop['title']) ?></b>?</p>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <button class="btn btn-primary" type="submit">Yes, Delete</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/properties.php">Cancel</a>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
