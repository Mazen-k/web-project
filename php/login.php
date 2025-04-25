<?php
/* --------------------------------------------------------------------------
   LOGIN END-POINT  (expects: POST email, POST password)
   -------------------------------------------------------------------------- */
session_start();
header('Content-Type: application/json');          // always answer JSON

require_once __DIR__ . '/db.php';           //  ➜ $conn  (mysqli)

/* ─── 1. Basic request sanity ──────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);                       // Method Not Allowed
    echo json_encode([
        'status'  => 'error',
        'message' => 'POST request required'
    ]);
    exit;
}

/* ─── 2. Grab and validate input ───────────────────────────────────────── */
$email    = trim($_POST['email']    ?? '');
$password =       $_POST['password']?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid e-mail or password'
    ]);
    exit;
}

/* ─── 3. Look up user ──────────────────────────────────────────────────── */
$stmt = $conn->prepare(
    'SELECT id, email, password FROM users WHERE email = ? LIMIT 1'
);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid e-mail or password'
    ]);
    exit;
}

$user = $result->fetch_assoc();

/* ─── 4. Verify password hash ──────────────────────────────────────────── */
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid e-mail or password'
    ]);
    exit;
}

/* ─── 5. Success → prime session & answer JSON ─────────────────────────── */
$_SESSION['loggedin'] = true;
$_SESSION['id']       = $user['id'];
$_SESSION['email']    = $user['email'];

$redirect = $_POST['redirect'] ?? '/web-project/index.html'; // Corrected path
echo json_encode([
    'status'   => 'success',
    'redirect' => $redirect
]);
?>
