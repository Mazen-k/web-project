//get the elements
const $startBtn = $("#start-btn");
const $timerEl  = $("#timer");
const $timeLeft= $("#time-left");
const $form = $("#quiz-form");
const $startScr = $("#start-screen");
const $resultScr= $("#result-screen");
const $scoreTxt = $("#score-text");
const $gradeMsg = $("#grade-msg");

//intielaize timers
let countdown;           
let remaining = 600;    

//get the quiz number
const quizNb = $("#quiz-container").data("quiz");  

//when the user starts the timer is shwown and start screen is hidden
$startBtn.on("click", () => {
  $startScr.hide();
  $timerEl.show();

  //get 10 random questions 
  $.getJSON("php/get_quiz.php", { quiz: quizNb }, buildQuiz);
});

//building the quiz
function buildQuiz(questions) {
  //loop over the questions
  questions.forEach((q, idx) => {
    //creaate a div for each question
    const $qDiv = $("<div>", { class: "question-block" });
    //display the the questions, options with radiobuttons for each question
    let html = `<h3>${idx + 1}. ${q.question}</h3>`;
    html += [q.op1, q.op2, q.op3, q.op4]
      .map((opt, i) => `
        <label style="display:block;margin:5px 0;">
          <input type="radio" name="q${idx}" value="${i + 1}" required>
          ${opt}
        </label>
      `).join("");
    html += `<input type="hidden" name="qid${idx}" value="${q.id}">`;
    //add them to the html opage
    $qDiv.html(html);
    $form.append($qDiv);
  });

  //submit button at the end of the  quiz
  $("<button>", {
    type: "submit",
    text: "Submit Quiz",
    style: "margin-top:15px;background:#FFD43B;padding:10px 15px;border:none;border-radius:5px;cursor:pointer;"
  }).appendTo($form);

  $form.show();
  startTimer();
}


//timer function
function startTimer() {
  //starts at 10:00
  updateTimerDisplay();
  countdown = setInterval(() => {
    //decrement and update
    remaining--;
    updateTimerDisplay();
    //if time is over auto submit
    if (remaining <= 0) {
      clearInterval(countdown);
      $form.trigger("submit");   
    }
  }, 1000);
}
//update the time on the page
function updateTimerDisplay() {
  const m = String(Math.floor(remaining / 60)).padStart(2, "0");
  const s = String(remaining % 60).padStart(2, "0");
  $timeLeft.text(`${m}:${s}`);
}

//handle submission
$form.on("submit", (e) => {
  e.preventDefault();
  //stop the timer
  clearInterval(countdown);

  const fd = new FormData(e.target);
  //send teh form data to the php file to grade it
  $.ajax({
    url: `php/grade_quiz.php?quiz=${quizNb}`,
    method: "POST",
    data: fd,
    processData: false,
    contentType: false,
    dataType: "json"
  })
  //recieve score and message
  .done(({ score, message }) => {
    //hide the form and timer
    $form.hide();
    $timerEl.hide();
    //show the score and msg
    $scoreTxt.text(score);
    $gradeMsg.text(message);
    $resultScr.show();
  });
});
