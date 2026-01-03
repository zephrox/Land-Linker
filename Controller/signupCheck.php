<?php
session_start();
require_once __DIR__ . '/../Model/init.php';

if(!isset($_POST['submit'])){
  redirect(BASE_URL . 'View/signup.php');
}

csrf_check();

$first = post_str('first_name', 60);
$last  = post_str('surname', 60);
$email = strtolower(post_str('email', 191));
$phone = post_str('phone', 30);
$pass  = (string)($_POST['password'] ?? '');

if($first === '' || $last === '' || $email === '' || $pass === ''){
  flash_set('error', 'All required fields must be filled.');
  redirect(BASE_URL . 'View/signup.php');
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  flash_set('error', 'Invalid email format.');
  redirect(BASE_URL . 'View/signup.php');
}

if(strlen($pass) < 6){
  flash_set('error', 'Password must be at least 6 characters.');
  redirect(BASE_URL . 'View/signup.php');
}

$exists = db_fetch_user_by_email($conn, $email);
if($exists){
  flash_set('error', 'Email already exists.');
  redirect(BASE_URL . 'View/signup.php');
}

$hash = password_hash($pass, PASSWORD_BCRYPT);
$role_id = 1;

$stmt = $conn->prepare("INSERT INTO users (role_id, first_name, surname, email, phone, password_hash) VALUES (?,?,?,?,?,?)");
$stmt->bind_param('isssss', $role_id, $first, $last, $email, $phone, $hash);
$stmt->execute();
$stmt->close();

flash_set('success', 'Account created. Please login.');
redirect(BASE_URL . 'View/login.php');
