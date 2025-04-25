<?php
// Minimal – just answers whether the user is authenticated.
session_start();
header('Content-Type: application/json');

// Your login code should set $_SESSION['loggedin'] = true
echo json_encode([
    'loggedIn' => !empty($_SESSION['loggedin'])
]);
