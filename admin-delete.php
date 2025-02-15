<?php
// admin-delete.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM links WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit;
} else {
    die("Invalid request.");
}
?>
