<?php
session_start();
require_once __DIR__ . '/../Model/init.php';

if(!isset($_POST['submit'])){
  redirect(BASE_URL . 'View/login.php');
}

csrf_check();

$email = strtolower(post_str('email', 191));
$pass  = (string)($_POST['password'] ?? '');

if($email === '' || $pass === ''){
  flash_set('error', 'Email and password are required.');
  redirect(BASE_URL . 'View/login.php');
}

$u = db_fetch_user_by_email($conn, $email);
if(!$u || ($u['status'] ?? '') !== 'active'){
  flash_set('error', 'Invalid email or account inactive.');
  redirect(BASE_URL . 'View/login.php');
}

$hash = (string)($u['password_hash'] ?? '');
if($hash === '' || !password_verify($pass, $hash)){
  flash_set('error', 'Invalid password.');
  redirect(BASE_URL . 'View/login.php');
}

login_user($u);
setcookie('status', 'true', time()+3000, '/'); 

flash_set('success', 'Login successful.');
if (is_logged_in()) { redirect(BASE_URL . 'View/profile.php'); exit; }
