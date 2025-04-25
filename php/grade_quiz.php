<?php
// grade_quiz.php — grade any quiz
header('Content-Type: application/json');
session_start();
require_once 'db.php';

$quiz = isset($_GET['quiz']) ? intval($_GET['quiz']) : 0;
if ($quiz < 0 || $quiz > 9) $quiz = 0;       // simple whitelist

$table   = "quiz{$quiz}";                    // quiz0, quiz1 …
$userCol = "q{$quiz}";                       // column that stores the score

/* 1) collect answers sent by JS */
$answers = [];                                // [id => chosen]
foreach ($_POST as $k => $v) {
  if (preg_match('/^qid(\d+)$/', $k, $m)) {
    $idx      = $m[1];
    $qid      = intval($v);
    $chosen   = intval($_POST["q$idx"] ?? 0);
    $answers[$qid] = $chosen;
  }
}

if (!$answers) {
  echo json_encode(['score'=>0,'message'=>'No answers received.']);
  exit;
}

/* 2) look up correct answers in the right table */
$ids  = implode(',', array_keys($answers));
$sql  = "SELECT id, answer FROM $table WHERE id IN ($ids)";
$res  = $conn->query($sql);

$score = 0;
while ($row = $res->fetch_assoc()) {
  $id = $row['id'];
  if ($answers[$id] == $row['answer']) $score++;
}

/* 3) save score in users.{q0|q1|q2…} if logged in */
if (isset($_SESSION['id'])) {
  $uid = $_SESSION['id'];
  // column name already validated; bind only VALUES
  $conn->query("UPDATE users SET $userCol = $score WHERE id = $uid");
}

/* 4) respond */
echo json_encode([
  'score'   => $score,
  'message' => $score >= 7 ? 'Great job! 🥳'
            : ($score >= 5 ? 'Nice effort – keep practicing.'
                            : 'Keep studying and try again.'),
]);
