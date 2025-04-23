<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$first  = trim($_POST['first']  ?? '');
$last   = trim($_POST['last']   ?? '');
$email  = trim($_POST['email']  ?? '');
$pass   =        $_POST['password'] ?? '';

/* ------------- server‑side sanity checks ------------- */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);  echo 'Invalid e‑mail.';  exit;
}
if (strlen($pass) < 8 || !preg_match('/\d/', $pass)) {
    http_response_code(400);  echo 'Password too weak.';  exit;
}

/* ------------- unique e‑mail? ------------- */
$stmt = $conn->prepare('SELECT Id FROM users WHERE Email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();  $stmt->store_result();
if ($stmt->num_rows) {                      // already registered
    http_response_code(409);  echo 'E‑mail already taken.';  exit;
}
$stmt->close();

/* ------------- insert new user ------------- */
$hash = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
  'INSERT INTO users (FirstName, LastName, Email, Is_admin, Password)
   VALUES (?,?,?,?,?)');
$is_admin = 0;
$stmt->bind_param('sssds', $first, $last, $email, $is_admin, $hash);

if ($stmt->execute()) {
    $_SESSION['uid']   = $stmt->insert_id;
    $_SESSION['fname'] = $first;
    header('Location: ../index.html');
    exit;
    // AJAX success flag
} else {
    http_response_code(500);  echo 'DB error.';
}
