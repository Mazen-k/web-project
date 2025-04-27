<?php


header('Content-Type: application/json');
require_once 'db.php';  


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST method required']);
    exit;
}

//get the email from enetred by user from the form
$email = trim($_POST['email'] ?? '');

//validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

//check if the email belongs to an existing account on the db
$stmt = $conn->prepare("SELECT 1 FROM users WHERE Email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

echo json_encode(['in_use' => $stmt->num_rows > 0]);
