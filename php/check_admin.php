<?php
//connect to the db and start session
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'db.php';                     

//get user id from session
$uid = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;   
if (!$uid) {
    header('Location: ../login.html?redirect=admin.html');
    exit;
}
//check if the user is an admin
$stmt = $conn->prepare("SELECT is_Admin FROM users WHERE id=?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$isAdmin =(bool)$stmt->get_result()->fetch_column();
$stmt->close();

//if the user is not an admin 
if (!$isAdmin) {
    http_response_code(403);      
    echo 'Access denied';
    exit;
}
