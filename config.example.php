<?php
// config.php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'your_db_name');     // Change to your MySQL database name
define('DB_USER', 'your_db_user');     // Change to your MySQL username
define('DB_PASS', 'your_db_password'); // Change to your MySQL password

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
