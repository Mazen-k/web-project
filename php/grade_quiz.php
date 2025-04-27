<?php
// coinnnect to the db and start session
header('Content-Type: application/json');
session_start();
require_once 'db.php';

//get the quiz nb 
$quiz = isset($_GET['quiz']) ? intval($_GET['quiz']) : 0;
if ($quiz < 0 || $quiz > 9) $quiz = 0;      


$table = "quiz{$quiz}";                    
$userCol = "q{$quiz}";                       

//collect the answers entered by the user from the form
$answers = [];                               
foreach ($_POST as $k => $v) {
  if (preg_match('/^qid(\d+)$/', $k, $m)) {
    $idx = $m[1];
    $qid = intval($v);
    $chosen = intval($_POST["q$idx"] ?? 0);
    $answers[$qid] = $chosen;
  }
}
//if no answers are submitted
if (!$answers) {
  echo json_encode(['score'=>0,'message'=>'No answers received.']);
  exit;
}

//check the answers of the questions from the db
$ids = implode(',', array_keys($answers));
$sql ="SELECT id, answer FROM $table WHERE id IN ($ids)";
$res = $conn->query($sql);

//compare answers with the users answers and calculate score
$score = 0;
while ($row = $res->fetch_assoc()) {
  $id = $row['id'];
  if ($answers[$id] == $row['answer']) $score++;
}

//if the user is logged in update his score
if (isset($_SESSION['id'])) {
  $uid = $_SESSION['id'];
  
  $conn->query("UPDATE users SET $userCol = $score WHERE id = $uid");
}

//return score and feedback
echo json_encode([
  'score' => $score,
  'message'=> $score >= 7 ? 'Great job'
            : ($score >= 5 ? 'Nice effort , keep practicing.'
                            : 'Keep studying and try again.'),
]);
