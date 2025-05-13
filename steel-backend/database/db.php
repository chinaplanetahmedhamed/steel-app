<?php

$host = 'localhost';         // or your database host
$db   = 'steel_db';          // your database name
$user = 'admin';  // replace with your MySQL username
$pass = 'admin';  // replace with your MySQL password
define('BASE_URL', '/app/steel-backend/'); // base URL for your application


try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
?>
