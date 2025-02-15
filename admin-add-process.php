<?php
// admin-add-process.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain']);
    $da = (int) $_POST['da'];
    $gambling = trim($_POST['gambling']);
    $country = trim($_POST['country']);
    $links = (int) $_POST['links'];
    $gambling_price = (float) $_POST['gambling_price'];
    $general_price = (float) $_POST['general_price'];
    $tab = trim($_POST['tab']);

    $stmt = $pdo->prepare("INSERT INTO links (domain, da, gambling, country, links, gambling_price, general_price, tab) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$domain, $da, $gambling, $country, $links, $gambling_price, $general_price, $tab]);
    header("Location: admin.php");
    exit;
} else {
    die("Invalid request.");
}
?>
