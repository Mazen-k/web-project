<?php
/* logout.php  – kills the PHP session
   -----------------------------------
   • Works whether called by a normal link
     (location.href = "php/logout.php")
     or by fetch()/XHR.
   • No includes needed.
*/
session_start();
session_unset();     // remove all session variables
session_destroy();   // destroy the session cookie

// If the request came via fetch/ajax, return JSON
$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
      && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($ajax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

/* Otherwise perform a normal redirect */
header('Location: ../login.html');
exit;
