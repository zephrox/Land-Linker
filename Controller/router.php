<?php
session_start();

// If cookie missing -> not logged in
if(!isset($_COOKIE['status'])){
    header('location: ../View/auth/login.php');
    exit;
}

// If session missing -> not logged in
if(!isset($_SESSION['user'])){
    header('location: ../View/auth/login.php');
    exit;
}

$role = 1;
if(isset($_SESSION['user']['role_id'])){
    $role = (int)$_SESSION['user']['role_id'];
}

if($role === 4){
    header('location: ../View/admin/dashboard.php');
    exit;
}
if($role === 3){
    header('location: ../View/manager/dashboard.php');
    exit;
}
if($role === 2){
    header('location: ../View/employee/dashboard.php');
    exit;
}

header('location: ../View/user/dashboard.php');
exit;
?>
