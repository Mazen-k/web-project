<?php
// check_email.php – AJAX endpoint to check if an email exists

header('Content-Type: application/json');
require_once 'db.php';  // assumes $conn (MySQLi) is defined here

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST method required']);
    exit;
}

$email = trim($_POST['email'] ?? '');

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

// Prepare and execute safe SQL query
$stmt = $conn->prepare("SELECT 1 FROM users WHERE Email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Email exists if row count > 0
echo json_encode(['in_use' => $stmt->num_rows > 0]);
