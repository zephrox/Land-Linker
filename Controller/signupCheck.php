<?php
session_start();
require_once('../Model/userModel.php');

if(isset($_POST['submit'])){
    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    if($username == "" || $email == "" || $password == ""){
        echo "null value!";
    }else{
        $user = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role_id' => 1
        ];

        $status = addUser($user);
        if($status){
            header('location: ../View/auth/login.php');
        }else{
            header('location: ../View/auth/signup.php');
        }
    }
}else{
    header('location: ../View/auth/signup.php');
}
?>
