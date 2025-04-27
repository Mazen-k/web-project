<?php
//connect to the db and start session to acces user info
header('Content-Type: application/json');
session_start();                
require_once 'db.php';          

//get user id
$uid = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;

//if the user is not logged in
if (!$uid) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

//get the ramianing user info
$stmt = $conn->prepare(
    "SELECT firstName, lastName, email, q0, q1, q2
       FROM users
      WHERE id = ?"
);
$stmt->bind_param('i', $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($user) {
    echo json_encode($user);
} else {
    //if user not found
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
}
