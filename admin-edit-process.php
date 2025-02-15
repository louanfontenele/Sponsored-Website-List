<?php
// admin-edit-process.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $domain = trim($_POST['domain']);
    $da = (int) $_POST['da'];
    $gambling = trim($_POST['gambling']); // "unchecked", "check", or "x"
    $country = trim($_POST['country']);
    $links = (int) $_POST['links'];
    $gambling_price = (float) $_POST['gambling_price'];
    $general_price = (float) $_POST['general_price'];
    $tab = trim($_POST['tab']);

    $stmt = $pdo->prepare("UPDATE links SET domain = ?, da = ?, gambling = ?, country = ?, links = ?, gambling_price = ?, general_price = ?, tab = ? WHERE id = ?");
    $stmt->execute([$domain, $da, $gambling, $country, $links, $gambling_price, $general_price, $tab, $id]);
    header("Location: admin.php");
    exit;
} else {
    die("Invalid request.");
}
?>
