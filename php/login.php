<?php
/* login.php  – process the form at /php/login.php */

session_start();
require_once 'db.php';          // provides  $conn  (MySQLi)

/* ─── 1. Basic validation ─────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../loginpage.php');
    exit();
}

$email    = trim($_POST['email']    ?? '');
$password =       $_POST['password']?? '';
$redirect =       $_POST['redirect']?? '../index.html';

if ($email === '' || $password === '') {
    // front-end can handle this token and show “missing fields”
    header("Location: ../loginpage.php?error=missing_fields");
    exit();
}

/* ─── 2. Look up the user by email ────────────────────────────────────── */
$stmt = $conn->prepare(
    "SELECT Id, FirstName, Is_admin, Password FROM users WHERE Email = ? LIMIT 1"
);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../loginpage.php?error=no account with that email");        // “No account with that email”
    exit();
}

$user = $result->fetch_assoc();

/* ─── 3. Check the password hash ──────────────────────────────────────── */
if (!password_verify($password, $user['Password'])) {
    header("Location: ../loginpage.php?error=wrong password");         // “Wrong password”
    exit();
}

/* ─── 4. Success → set session & redirect ─────────────────────────────── */
$_SESSION['user_id']    = $user['Id'];
$_SESSION['first_name'] = $user['FirstName'];
$_SESSION['is_admin']   = (bool)$user['Is_admin'];

// regenerate the session ID to prevent fixation attacks
session_regenerate_id(true);

header("Location: $redirect");
exit();
?>
