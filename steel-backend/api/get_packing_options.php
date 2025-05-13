<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT id, name AS label FROM packing_options ORDER BY id ASC");
$packing = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
  "status" => "success",
  "data" => $packing
]);
