<?php


session_start();
session_unset();     
session_destroy();   


$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
      && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($ajax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}
header('Location: ../login.html');
exit;
