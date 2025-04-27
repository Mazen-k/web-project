<?php
session_start();
require 'db.php';

header('Content-Type: application/json; charset=utf-8');   

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'POST required']);
    exit;
}

// collect the data entered by the user from the form
$first = trim($_POST['first']  ?? '');
$last = trim($_POST['last']   ?? '');
$email = trim($_POST['email']  ?? '');
$pass = $_POST['password'] ?? '';

// validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid e-mail address'
    ]); ;  exit;
}

//validate password
if (strlen($pass) < 8 || !preg_match('/\d/', $pass)) {
   echo json_encode([
        'status' => 'error',
        'message' => 'password must be at least 8 characters long and contain at least one number'
    ]);  exit;
}

//check if the email enetred is already used for another account
$stmt = $conn->prepare('SELECT Id FROM users WHERE Email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();  $stmt->store_result();
if ($stmt->num_rows) {                      
    echo json_encode([
        'status'  => 'error',
        'message' => 'Email already in use'
    ]); exit;
}
$stmt->close();

//hash the password for security
$hash = password_hash($pass, PASSWORD_DEFAULT);
$is_admin  = 0;

//add the user into the db
$stmt = $conn->prepare(
  'INSERT INTO users (FirstName, LastName, Email, Is_admin, Password)
   VALUES (?,?,?,?,?)'
);
$is_admin = 0;
$stmt->bind_param('sssis', $first, $last, $email, $is_admin, $hash);

if ($stmt->execute()) {
    $_SESSION['uid'] = $stmt->insert_id;
    $_SESSION['fname'] = $first;

    
    echo json_encode([
        'status'=> 'success',
        'redirect' => 'index.html'
    ]);
    exit;
} 
//if it reached here then something went wrong 
http_response_code(500);
echo json_encode(['status' => 'error', 'message' => 'DB error']);
