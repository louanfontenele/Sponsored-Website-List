<?php
// api-links.php
require_once 'config.php';

$tab = isset($_GET['tab']) ? $_GET['tab'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM links";
$params = [];
$clauses = [];

if ($tab !== '') {
    $clauses[] = "tab = ?";
    $params[] = $tab;
}
if ($search !== '') {
    $clauses[] = "domain LIKE ?";
    $params[] = "%" . $search . "%";
}
if (!empty($clauses)) {
    $query .= " WHERE " . implode(" AND ", $clauses);
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$links = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($links);
?>
