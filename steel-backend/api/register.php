<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins (or set to your domain)
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

file_put_contents("debug_post.txt", print_r($_POST, true));
header('Content-Type: application/json');
require_once '../database/db.php';

// Collect POST data
$name     = $_POST['name']     ?? '';
$email    = $_POST['email']    ?? '';
$company  = $_POST['company']  ?? '';
$country  = $_POST['country']  ?? '';
$phone    = $_POST['phone']    ?? '';
$notes    = $_POST['notes']    ?? '';
$created  = date('Y-m-d H:i:s');
$status   = 'pending';

// Basic validation
if (!$name || !$email || !$company) {
  echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
  exit;
}

// Prevent duplicates (email)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pending_customers WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
  echo json_encode(['success' => false, 'message' => 'Email already submitted.']);
  exit;
}

// Insert into DB
$stmt = $pdo->prepare("INSERT INTO pending_customers (name, email, company, country, phone, notes, created_at, status)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$success = $stmt->execute([$name, $email, $company, $country, $phone, $notes, $created, $status]);

if ($success) {
  echo json_encode(['success' => true, 'message' => 'Registration submitted.']);
} else {
  echo json_encode(['success' => false, 'message' => 'Database error.']);
}
