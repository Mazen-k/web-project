<?php

//connect to the db and check is the user is an admin
header('Content-Type: application/json');
require_once 'check_admin.php';    
require_once 'db.php';

//get the quiz nb, question, and options entered by the user
$quiz = isset($_POST['quiz']) ? (int)$_POST['quiz'] : -1;
$q = trim($_POST['question'] ?? '');
$op = [ $_POST['op1'] ?? '', $_POST['op2'] ?? '',
              $_POST['op3'] ?? '', $_POST['op4'] ?? '' ];
$answer = isset($_POST['answer']) ? (int)$_POST['answer'] : 0;

//validations that data entered are  valid
if ($quiz < 0 || $quiz > 9) die(json_encode(['error'=>'quiz#']));
if (!$q || in_array('', $op, true))die(json_encode(['error'=>'Missing text']));
if ($answer < 1 || $answer > 4) die(json_encode(['error'=>'answer 1-4']));


//add the question to the correct quiz in the db
$table = "quiz$quiz";
$sql = "INSERT INTO `$table` (question, op1, op2, op3, op4, answer)
          VALUES (?,?,?,?,?,?)";
$stmt = $conn->prepare($sql) or die(json_encode(['error'=>$conn->error]));
$stmt->bind_param('sssssi', $q, $op[0], $op[1], $op[2], $op[3], $answer);
$stmt->execute() or die(json_encode(['error'=>$stmt->error]));

//return succes msg and id of the new quiestion
echo json_encode(['ok'=>true, 'id'=>$stmt->insert_id]);
