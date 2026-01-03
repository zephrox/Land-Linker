<?php
session_start();
require_once __DIR__ . '/../Model/init.php';

if(!isset($_COOKIE['status']) || !is_logged_in()){
  redirect(BASE_URL . 'View/login.php');
}
