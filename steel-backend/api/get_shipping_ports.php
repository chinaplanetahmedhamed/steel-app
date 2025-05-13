<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT id, name AS label FROM shipping_ports ORDER BY name ASC");
$ports = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
  "status" => "success",
  "data" => $ports
]);
