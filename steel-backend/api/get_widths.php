<?php
require_once '../database/db.php';
header('Content-Type: application/json');

try {
    // Fetch widths
    $stmt = $pdo->query("SELECT id, width_mm AS label FROM widths ORDER BY width_mm ASC");
    $widths = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Send response
    echo json_encode([
        'status' => 'success',
        'data' => $widths
    ]);
}
catch (Exception $e) {
    // Handle error
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to load widths'
    ]);
}
