<?php
/*  admin_auth.php  – include FIRST in every admin page / endpoint
    Requires:  users table has column  is_Admin  (TINYINT or BOOLEAN)
------------------------------------------------------------------------*/
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'db.php';                     // $conn  (mysqli)

$uid = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;   // pick your key
if (!$uid) {
    header('Location: ../login.html?redirect=admin.html');
    exit;
}

$stmt = $conn->prepare("SELECT is_Admin FROM users WHERE id=?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$isAdmin = (bool) $stmt->get_result()->fetch_column();
$stmt->close();

if (!$isAdmin) {
    http_response_code(403);      // Forbidden
    echo 'Access denied';
    exit;
}
