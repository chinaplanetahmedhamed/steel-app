<?php
require_once '../database/db.php';
header('Content-Type: application/json');

try {

  $stmt = $pdo->query("SELECT id, thickness_mm AS label FROM thickness_costs ORDER BY thickness_mm ASC");
  $thicknesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode([
  "status" => "success",
  "data" => $thicknesses
  ]);
}
catch (Exception $e){
  echo json_encode([
  "status" => "error",
  "message" => "Failed to load thicknesses"
  ]);
}

