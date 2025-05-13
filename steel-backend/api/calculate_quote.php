<?php
header('Content-Type: application/json');

require_once '../includes/functions.php';
require_once '../database/db.php'; // adjust path if needed

// Read POST data
$input = json_decode(file_get_contents('php://input'), true);

// Check if required fields are present
$required = ['material_id', 'thickness_id', 'zinc', 'coating_id', 'processing_ids', 'packing_id', 'shipping_type', 'shipping_port_id', 'profit_type', 'profit_value', 'width'];
foreach ($required as $field) {
    if (!isset($input[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
        exit;
    }
}

try {
    $result = calculate_price($pdo, $input);
    echo json_encode([
        'status' => 'success',
        'data' => $result
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error calculating quote: ' . $e->getMessage()
    ]);
}
