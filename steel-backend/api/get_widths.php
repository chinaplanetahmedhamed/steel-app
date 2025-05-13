<?php
require_once '../database/db.php';

header('Content-Type: application/json');

// Fetch widths
$stmt = $pdo->query("SELECT id, width_mm AS label FROM widths ORDER BY width_mm");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Send response
echo json_encode([
    'status' => 'success',
    'data' => $data
]);
exit;
