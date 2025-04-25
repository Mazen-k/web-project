<?php
/*  add_question.php  – Admin inserts a question into quizN
    Expects: quiz (0/1/2 …), question, op1-4, answer (1-4)
    Returns: { ok:true }  or  { error:"…" }
-------------------------------------------------------------*/
header('Content-Type: application/json');
require_once 'check_admin.php';    // blocks non-admins
require_once 'db.php';

$quiz     = isset($_POST['quiz']) ? (int)$_POST['quiz'] : -1;
$q        = trim($_POST['question'] ?? '');
$op       = [ $_POST['op1'] ?? '', $_POST['op2'] ?? '',
              $_POST['op3'] ?? '', $_POST['op4'] ?? '' ];
$answer   = isset($_POST['answer']) ? (int)$_POST['answer'] : 0;

/* basic validation */
if ($quiz < 0 || $quiz > 9)           die(json_encode(['error'=>'quiz#']));
if (!$q || in_array('', $op, true))   die(json_encode(['error'=>'Missing text']));
if ($answer < 1 || $answer > 4)       die(json_encode(['error'=>'answer 1-4']));

$table = "quiz$quiz";
$sql   = "INSERT INTO `$table` (question, op1, op2, op3, op4, answer)
          VALUES (?,?,?,?,?,?)";
$stmt  = $conn->prepare($sql) or die(json_encode(['error'=>$conn->error]));
$stmt->bind_param('sssssi', $q, $op[0], $op[1], $op[2], $op[3], $answer);
$stmt->execute() or die(json_encode(['error'=>$stmt->error]));

echo json_encode(['ok'=>true, 'id'=>$stmt->insert_id]);
