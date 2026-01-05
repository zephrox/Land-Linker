<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([3,4], url('index.php'));

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2>Employee Directory</h2>
  <div class="card" style="padding:16px; margin-top:12px;">
    <p>This page will manage employees (Manager/Admin only).</p>
  </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
