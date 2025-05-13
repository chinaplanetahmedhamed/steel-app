<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT id, thickness_mm AS label FROM thickness_costs ORDER BY thickness_mm ASC");
$thicknesses = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
  "status" => "success",
  "data" => $thicknesses
]);
