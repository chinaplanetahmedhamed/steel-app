<?php
require_once '../database/db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, name AS label FROM materials");
    $steelTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'status' => 'success',
        'data' => $steelTypes
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to load steel types'
    ]);
}
