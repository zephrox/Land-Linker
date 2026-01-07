<?php
require_once __DIR__ . '/../Model/init.php';

logout_user();
setcookie('status', 'true', time()-10, '/');

flash_set('success', 'Logged out.');
redirect(BASE_URL . 'View/login.php');
exit;