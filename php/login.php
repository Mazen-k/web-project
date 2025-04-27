<?php

session_start();
header('Content-Type: application/json');          

//conect to the db
require_once __DIR__ . '/db.php';           


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);                       
    echo json_encode([
        'status' => 'error',
        'message'=> 'POST request required'
    ]);
    exit;
}

//get the email and password entered by the user from the form
$email= trim($_POST['email']    ?? '');
$password = $_POST['password']?? '';


//checks for invalid emails 
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid e-mail or password'
    ]);
    exit;
}

//checks the db if the user account exists 
$stmt = $conn->prepare(
    'SELECT id, email, password FROM users WHERE email = ? LIMIT 1'
);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

//if the user not found
if ($result->num_rows !== 1) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid e-mail or password'
    ]);
    exit;
}
$user = $result->fetch_assoc();

//verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        'status'=> 'error',
        'message' =>'Invalid e-mailor password'
    ]);
    exit;
}

//if it reached here then accoutn exixts so we store the info in the session
$_SESSION['loggedin'] = true;
$_SESSION['id'] = $user['id'];
$_SESSION['email'] = $user['email'];

//succes response and redirect to the homepage
$redirect = $_POST['redirect'] ?? '/web-project/index.html'; 
echo json_encode([
    'status'=> 'success',
    'redirect' => $redirect
]);
?>
