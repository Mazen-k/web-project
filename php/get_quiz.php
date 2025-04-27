<?php

//connect to the db
header('Content-Type: application/json');
require_once 'db.php';

//get the quiz nb 0,1 or 2
$quiz = isset($_GET['quiz']) ? intval($_GET['quiz']) : 0;   

//get 10 random question from the specified quiz
$table = "quiz{$quiz}";
$sql= "SELECT id, question, op1, op2, op3, op4 
          FROM $table
          ORDER BY RAND()
          LIMIT 10";

//fetch the questiosns and return them
$res= $conn->query($sql);
$rows =[];
while ($row = $res->fetch_assoc()) {
  $rows[] = $row;
}
echo json_encode($rows);
