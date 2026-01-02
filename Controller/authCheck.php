<?php
session_start();

if(!isset($_COOKIE['status'])){
    header('location: ../View/auth/login.php');
    exit;
}

if(!isset($_SESSION['user'])){
    header('location: ../View/auth/login.php');
    exit;
}
?>
