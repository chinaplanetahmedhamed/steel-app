<?php
require_once '../database/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');
$invite_code = trim($data['invite_code'] ?? '');

if (!$email || !$invite_code) {
    echo json_encode(['status' => 'error', 'message' => 'Email and Invite Code are required']);
    exit;
}

// Check active user with matching email + invite_code
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND invite_code = ? AND status = 'active'");
$stmt->execute([$email, $invite_code]);
$user = $stmt->fetch();

if ($user) {
    echo json_encode(['status' => 'success', 'user_id' => $user['id']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or invite code, or account not active']);
}

?>
