<?php
// get_quiz.php — fetch 10 random questions for any quiz
header('Content-Type: application/json');
require_once 'db.php';

$quiz = isset($_GET['quiz']) ? intval($_GET['quiz']) : 0;   


$table = "quiz{$quiz}";
$sql   = "SELECT id, question, op1, op2, op3, op4
          FROM $table
          ORDER BY RAND()
          LIMIT 10";

$res  = $conn->query($sql);
$rows = [];
while ($row = $res->fetch_assoc()) {
  $rows[] = $row;
}
echo json_encode($rows);
