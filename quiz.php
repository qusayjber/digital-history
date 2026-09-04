<?php
// quiz.php
// Digital History - Interactive Quiz Page

require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
$quiz = null;

if (!empty($slug)) {
    $quiz = getQuiz($slug);
}

if (!$quiz) {
    http_response_code(404);
    include '404.php';
    exit;
}

$pageTitle = $quiz['title'];
$pageDescription = truncate($quiz['description'], 160);

$questions = getQuizQuestions($quiz['id']);

trackPageView('quiz_' . $quiz['id']);

ob_start();
?>

<!-- Quiz Header -->
<section class="section" style="padding-top: 2rem; padding-bottom: 1rem;">
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <a href="games.php" style="color: #8892b0; text-decoration: none; font-size: 0.85rem;">← Back to Games</a>
        </div>
        
        <div>
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <span style="font-size: 3rem;">📝</span>
                <div>
                    <h1 style="font-size: clamp(1.8rem, 3vw, 2.5rem); color: #fff; margin-bottom: 0.3rem;">
                        <?php echo escape($quiz['title']); ?>
                    </h1>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; color: #8892b0; font-size: 0.9rem;">
                        <span>Category: <?php echo escape($quiz['category'] ?: 'General'); ?></span>
                        <span>Difficulty: 
                            <span style="color: <?php 
                                echo $quiz['difficulty'] === 'easy' ? '#00ff88' : 
                                    ($quiz['difficulty'] === 'medium' ? '#ffa500' : '#ff6b6b'); 
                            ?>;">
                                <?php echo ucfirst($quiz['difficulty']); ?>
                            </span>
                        </span>
                        <span>Questions: <?php echo count($questions); ?></span>
                        <span>Time Limit: <?php echo floor($quiz['time_limit'] / 60); ?> min <?php echo $quiz['time_limit'] % 60; ?> sec</span>
                        <span>Passing Score: <?php echo $quiz['passing_score']; ?>%</span>
                    </div>
                </div>
            </div>
            
            <?php if ($quiz['description']): ?>
            <p style="color: #8892b0; margin-top: 1rem; font-size: 1.05rem;">
                <?php echo escape($quiz['description']); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Quiz Content -->
<section class="section" style="padding-top: 1rem; padding-bottom: 3rem;">
    <div style="max-width: 800px; margin: 0 auto;">
        <?php if (empty($questions)): ?>
        <div style="text-align: center; padding: 3rem 0; background: rgba(255,255,255,0.02); border-radius: 12px;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">❌</div>
            <p style="color: #8892b0;">This quiz has no questions yet.</p>
        </div>
        <?php else: ?>
        
        <!-- Quiz Container -->
        <div id="quizContainer">
            <!-- Start Screen -->
            <div id="quizStart" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🎯</div>
                <h3 style="color: #00d4ff; margin-bottom: 0.5rem;">Ready to Test Your Knowledge?</h3>
                <p style="color: #8892b0; margin-bottom: 1.5rem;">
                    You'll have <?php echo count($questions); ?> questions to answer.
                    <?php if ($quiz['time_limit']): ?>
                    You have <?php echo floor($quiz['time_limit'] / 60); ?> minutes to complete the quiz.
                    <?php endif; ?>
                </p>
                <button onclick="startQuiz()" style="padding: 0.8rem 2.5rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-play"></i> Start Quiz
                </button>
            </div>
            
            <!-- Question Screen (hidden initially) -->
            <div id="quizQuestions" style="display: none;">
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
                    <!-- Progress -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <span style="color: #8892b0;">Question <span id="currentQuestionNum">1</span> of <span id="totalQuestions"><?php echo count($questions); ?></span></span>
                        </div>
                        <div>
                            <span style="color: #8892b0;">Score: <span id="quizScore" style="color: #00d4ff; font-weight: 600;">0</span></span>
                            <span style="color: #8892b0; margin-left: 1rem;">Time: <span id="quizTimer" style="color: #ffa500; font-weight: 600;"><?php echo $quiz['time_limit']; ?></span>s</span>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; height: 4px; background: rgba(255,255,255,0.05); border-radius: 2px; margin-bottom: 1.5rem; overflow: hidden;">
                        <div id="quizProgress" style="height: 100%; width: 0%; background: linear-gradient(90deg, #00d4ff, #7b2ffc); transition: width 0.3s ease;"></div>
                    </div>
                    
                    <!-- Question -->
                    <div id="questionContainer">
                        <!-- Dynamically loaded -->
                    </div>
                </div>
            </div>
            
            <!-- Results Screen (hidden initially) -->
            <div id="quizResults" style="display: none; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; text-align: center;">
                <!-- Dynamically loaded -->
            </div>
        </div>
        
        <?php endif; ?>
    </div>
</section>

<?php
$pageContent = ob_get_clean();

// Prepare questions data for JavaScript
$questionsData = [];
foreach ($questions as $q) {
    $answers = getQuizAnswers($q['id']);
    $questionsData[] = [
        'id' => $q['id'],
        'question' => $q['question'],
        'type' => $q['type'],
        'answers' => $answers,
        'points' => $q['points']
    ];
}

$pageJS = '
const quizData = ' . json_encode([
    'id' => $quiz['id'],
    'title' => $quiz['title'],
    'questions' => $questionsData,
    'timeLimit' => $quiz['time_limit'],
    'passingScore' => $quiz['passing_score']
]) . ';

let currentQuestion = 0;
let score = 0;
let timer = ' . $quiz['time_limit'] . ';
let timerInterval = null;
let quizStarted = false;
let answers = [];

function startQuiz() {
    document.getElementById("quizStart").style.display = "none";
    document.getElementById("quizQuestions").style.display = "block";
    quizStarted = true;
    currentQuestion = 0;
    score = 0;
    answers = [];
    timer = ' . $quiz['time_limit'] . ';
    
    document.getElementById("quizScore").textContent = "0";
    document.getElementById("quizTimer").textContent = timer;
    
    loadQuestion();
    startTimer();
}

function loadQuestion() {
    const data = quizData.questions[currentQuestion];
    const container = document.getElementById("questionContainer");
    const total = quizData.questions.length;
    
    document.getElementById("currentQuestionNum").textContent = currentQuestion + 1;
    document.getElementById("quizProgress").style.width = ((currentQuestion + 1) / total * 100) + "%";
    
    let html = `
        <div style="margin-bottom: 1.5rem;">
            <h4 style="color: #fff; font-size: 1.2rem;">${data.question}</h4>
            <div style="color: #8892b0; font-size: 0.85rem; margin-top: 0.3rem;">Points: ${data.points}</div>
        </div>
        <div style="display: grid; gap: 0.8rem;">
    `;
    
    data.answers.forEach((answer, index) => {
        const letter = String.fromCharCode(65 + index);
        html += `
            <button onclick="selectAnswer(${index})" class="answer-btn" data-index="${index}" style="display: flex; align-items: center; gap: 1rem; padding: 0.8rem 1.2rem; background: rgba(255,255,255,0.03); border: 2px solid rgba(255,255,255,0.06); border-radius: 8px; color: #ccd6f6; cursor: pointer; transition: all 0.3s; width: 100%; text-align: left; font-size: 1rem;">
                <span style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); border-radius: 50%; font-weight: 600; color: #8892b0; flex-shrink: 0;">${letter}</span>
                <span>${answer.answer}</span>
            </button>
        `;
    });
    
    html += `</div>`;
    container.innerHTML = html;
}

function selectAnswer(index) {
    const data = quizData.questions[currentQuestion];
    const selectedAnswer = data.answers[index];
    const allButtons = document.querySelectorAll(".answer-btn");
    
    // Disable all buttons
    allButtons.forEach(btn => btn.style.pointerEvents = "none");
    
    // Highlight correct/incorrect
    allButtons.forEach((btn, i) => {
        const answer = data.answers[i];
        if (answer.is_correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (i === index && !answer.is_correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    // Update score
    if (selectedAnswer.is_correct) {
        score += data.points;
        document.getElementById("quizScore").textContent = score;
    }
    
    // Store answer
    answers.push({
        questionId: data.id,
        answerId: selectedAnswer.id,
        isCorrect: selectedAnswer.is_correct
    });
    
    // Load next question or finish
    setTimeout(() => {
        currentQuestion++;
        if (currentQuestion < quizData.questions.length) {
            loadQuestion();
        } else {
            finishQuiz();
        }
    }, 1000);
}

function startTimer() {
    timerInterval = setInterval(() => {
        timer--;
        document.getElementById("quizTimer").textContent = timer;
        
        if (timer <= 0) {
            clearInterval(timerInterval);
            finishQuiz();
        }
    }, 1000);
}

function finishQuiz() {
    clearInterval(timerInterval);
    quizStarted = false;
    
    const total = quizData.questions.length;
    const totalPoints = quizData.questions.reduce((sum, q) => sum + q.points, 0);
    const percentage = Math.round((score / totalPoints) * 100);
    const passed = percentage >= quizData.passingScore;
    
    document.getElementById("quizQuestions").style.display = "none";
    document.getElementById("quizResults").style.display = "block";
    
    let html = `
        <div style="font-size: 4rem; margin-bottom: 1rem;">${passed ? "🎉" : "😅"}</div>
        <h3 style="color: ${passed ? "#00ff88" : "#ff6b6b"}; margin-bottom: 0.5rem;">
            ${passed ? "Congratulations! You Passed!" : "Try Again!"}
        </h3>
        <div style="font-size: 3rem; font-weight: 700; color: #00d4ff; margin: 1rem 0;">
            ${percentage}%
        </div>
        <div style="color: #8892b0; margin-bottom: 0.5rem;">
            Score: ${score} / ${totalPoints} points
        </div>
        <div style="color: #8892b0; margin-bottom: 1.5rem;">
            ${passed ? "Great job! You know your stuff!" : "Keep learning and try again!"}
        </div>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <button onclick="restartQuiz()" style="padding: 0.8rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer;">
                <i class="fas fa-redo"></i> Retry
            </button>
            <a href="games.php" style="padding: 0.8rem 2rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Back to Games
            </a>
        </div>
    `;
    
    document.getElementById("quizResults").innerHTML = html;
    
    // Save attempt if logged in
    <?php if (isLoggedIn()): ?>
    fetch("api/save-quiz-attempt.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "quiz_id=" + quizData.id + "&score=" + score + "&total=" + totalPoints + "&answers=" + encodeURIComponent(JSON.stringify(answers))
    });
    <?php endif; ?>
}

function restartQuiz() {
    document.getElementById("quizResults").style.display = "none";
    document.getElementById("quizQuestions").style.display = "block";
    currentQuestion = 0;
    score = 0;
    answers = [];
    timer = ' . $quiz['time_limit'] . ';
    
    document.getElementById("quizScore").textContent = "0";
    document.getElementById("quizTimer").textContent = timer;
    
    loadQuestion();
    startTimer();
}

// Keyboard shortcuts for answer selection
document.addEventListener("keydown", function(e) {
    if (!quizStarted) return;
    const key = parseInt(e.key);
    if (key >= 1 && key <= 4) {
        const buttons = document.querySelectorAll(".answer-btn");
        if (buttons[key - 1]) {
            buttons[key - 1].click();
        }
    }
});
';

$pageCSS = '
.answer-btn:hover {
    border-color: #00d4ff !important;
    background: rgba(0, 212, 255, 0.05) !important;
}

@media (max-width: 480px) {
    .answer-btn {
        font-size: 0.9rem !important;
        padding: 0.6rem 1rem !important;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>