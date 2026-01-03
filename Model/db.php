<?php
$host = "localhost";
$user = "root";
$pass = "";          // default in XAMPP is empty
$db   = "land_linker";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("DB Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
