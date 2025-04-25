<?php
/*  profile_api.php
    ──────────────────────────────────────────────────────────
    • NO auth.php include – so nothing gets redirected
    • Uses the existing session cookie (PHPSESSID) to spot
      the logged-in user and returns JSON:
      { firstName, lastName, email, q0, q1, q2 }
      or 401 / 404 with { error:"…" }
───────────────────────────────────────────────────────────*/
header('Content-Type: application/json');
session_start();                // only this script cares about the session
require_once 'db.php';          // $conn  (mysqli)

// the key your login script sets – change if needed
$uid = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;

if (!$uid) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

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
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
}
