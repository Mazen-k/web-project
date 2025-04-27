<?php

session_start();
header('Content-Type: application/json');
echo json_encode([
    'loggedIn' => !empty($_SESSION['loggedin'])
]);
