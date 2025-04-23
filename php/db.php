<?php
/* db.php  –  one place for DB credentials */
$host = 'localhost';
$user = 'root';        // change if you set a password
$pass = '';            //  ↑
$db   = 'learnpy';     // better than using the system “mysql” DB

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
