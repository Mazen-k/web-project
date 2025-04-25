// elements
const startBtn  = document.getElementById("start-btn");
const timerEl   = document.getElementById("timer");
const timeLeft  = document.getElementById("time-left");
const form      = document.getElementById("quiz-form");
const startScr  = document.getElementById("start-screen");
const resultScr = document.getElementById("result-screen");
const scoreTxt  = document.getElementById("score-text");
const gradeMsg  = document.getElementById("grade-msg");

let countdown;   // interval reference
let remaining = 600;   // 10 * 60 sec

const container = document.getElementById("quiz-container");
const quizNo    = container.dataset.quiz;

/* ───────────── START QUIZ ───────────── */
startBtn?.addEventListener("click", async () => {
  startScr.style.display = "none";
  timerEl.style.display  = "block";

  // fetch 10 random questions
  const res  = await fetch(`php/get_quiz.php?quiz=${quizNo}`);
  const data = await res.json();      // [{id,question,op1..op4}]
  buildQuiz(data);

  form.style.display = "block";
  startTimer();
});

/* ───────────── BUILD QUIZ HTML ───────────── */
function buildQuiz(questions){
  questions.forEach((q, idx) => {
    const qDiv = document.createElement("div");
    qDiv.className = "question-block";
    qDiv.innerHTML = `
      <h3>${idx+1}. ${q.question}</h3>
      ${[q.op1,q.op2,q.op3,q.op4].map((opt,i)=>`
        <label style="display:block;margin:5px 0;">
          <input type="radio" name="q${idx}" value="${i+1}" required>
          ${opt}
        </label>
      `).join("")}
      <input type="hidden" name="qid${idx}" value="${q.id}">
    `;
    form.appendChild(qDiv);
  });

  // submit btn
  const submit = document.createElement("button");
  submit.type  = "submit";
  submit.textContent = "Submit Quiz";
  submit.style = "margin-top:15px; background:#FFD43B; padding:10px 15px; border:none; border-radius:5px; cursor:pointer;";
  form.appendChild(submit);
}

/* ───────────── TIMER ───────────── */
function startTimer(){
  updateTimerDisplay();
  countdown = setInterval(()=>{
    remaining--;
    updateTimerDisplay();
    if (remaining <= 0){
      clearInterval(countdown);
      form.requestSubmit();   // auto-submit
    }
  },1000);
}
function updateTimerDisplay(){
  const m = String(Math.floor(remaining/60)).padStart(2,"0");
  const s = String(remaining%60).padStart(2,"0");
  timeLeft.textContent = `${m}:${s}`;
}

/* ───────────── HANDLE SUBMIT ───────────── */
form.addEventListener("submit", async (e)=>{
  e.preventDefault();
  clearInterval(countdown);

  const fd = new FormData(form);
  // pass the quiz number as query-string too
  const res = await fetch(`php/grade_quiz.php?quiz=${quizNo}`, {
    method: "POST",
    body: fd,
  });
  const { score, message } = await res.json();

  // UI
  form.style.display    = "none";
  timerEl.style.display = "none";
  scoreTxt.textContent  = score;
  gradeMsg.textContent  = message;
  resultScr.style.display = "block";
});
