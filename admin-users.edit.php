<?php
// admin-users-edit.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $newPassword = $_POST['newPassword'];
    
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $id]);
    header("Location: admin-users.php");
    exit;
} else {
    die("Invalid request.");
}
?>
