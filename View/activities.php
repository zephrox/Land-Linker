<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2>Activities</h2>
  <div class="card" style="padding:16px; margin-top:12px;">
    <p>Recent activities will appear here.</p>
  </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
