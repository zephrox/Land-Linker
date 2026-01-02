<?php
session_start();
$_SESSION = [];
session_destroy();

setcookie('status', 'true', time()-10, '/');
header('location: ../View/auth/login.php');
?>
