<?php
// games.php
// Digital History - Educational Games (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'Digital Games';
$pageDescription = 'Learn about technology history through interactive quizzes and games.';

$quizzes = getQuizzes();

// Get user game statistics if logged in
$gameStats = null;
if (isLoggedIn()) {
    $gameStats = db()->fetch(
        "SELECT COUNT(*) as total_games, SUM(score) as total_score 
         FROM user_quiz_attempts 
         WHERE user_id = ?",
        [$_SESSION['user_id']]
    );
}

trackPageView('games');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            <?php echo t('games.title'); ?>
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;"><?php echo t('games.subtitle'); ?></p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;">6</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Interactive Games</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;">50+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Questions</div>
        </div>
        <?php if ($gameStats && $gameStats['total_games'] > 0): ?>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;"><?php echo $gameStats['total_games']; ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Games Played</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;"><?php echo round($gameStats['total_score'] / max(1, $gameStats['total_games']), 1); ?>%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Average Score</div>
        </div>
        <?php else: ?>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">0</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Games Played</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">0%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Average Score</div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Games Grid -->
<section class="section" style="padding-top: 1rem;">
    <div class="card-grid">
        <!-- Guess the Year -->
        <div class="card animate-on-scroll game-card" style="text-align: center; cursor: pointer;" onclick="startGame('guess-year')">
            <div style="position: relative;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">📅</div>
                <span style="position: absolute; top: -10px; right: -10px; font-size: 0.6rem; background: rgba(0,212,255,0.1); color: #00d4ff; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(0,212,255,0.2);">
                    Easy
                </span>
            </div>
            <div class="card-title">Guess the Year</div>
            <div class="card-description">When was this technology invented? Test your knowledge!</div>
            <div style="color: #8892b0; font-size: 0.7rem; margin: 0.3rem 0;">8 Questions</div>
            <button style="margin-top: 0.5rem; padding: 0.4rem 1.2rem; background: #00d4ff; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Play Now
            </button>
        </div>
        
        <!-- Guess the Technology -->
        <div class="card animate-on-scroll game-card" style="text-align: center; cursor: pointer;" onclick="startGame('guess-tech')">
            <div style="position: relative;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">🔧</div>
                <span style="position: absolute; top: -10px; right: -10px; font-size: 0.6rem; background: rgba(123,47,252,0.1); color: #7b2ffc; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(123,47,252,0.2);">
                    Medium
                </span>
            </div>
            <div class="card-title">Guess the Technology</div>
            <div class="card-description">Can you identify the technology from its description?</div>
            <div style="color: #8892b0; font-size: 0.7rem; margin: 0.3rem 0;">6 Questions</div>
            <button style="margin-top: 0.5rem; padding: 0.4rem 1.2rem; background: #7b2ffc; border: none; border-radius: 6px; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Play Now
            </button>
        </div>
        
        <!-- Guess the Programmer -->
        <div class="card animate-on-scroll game-card" style="text-align: center; cursor: pointer;" onclick="startGame('guess-programmer')">
            <div style="position: relative;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">👨‍💻</div>
                <span style="position: absolute; top: -10px; right: -10px; font-size: 0.6rem; background: rgba(0,255,136,0.1); color: #00ff88; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(0,255,136,0.2);">
                    Medium
                </span>
            </div>
            <div class="card-title">Guess the Programmer</div>
            <div class="card-description">Match the programmer to their creation.</div>
            <div style="color: #8892b0; font-size: 0.7rem; margin: 0.3rem 0;">4 Questions</div>
            <button style="margin-top: 0.5rem; padding: 0.4rem 1.2rem; background: #00ff88; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Play Now
            </button>
        </div>
        
        <!-- Binary Challenge -->
        <div class="card animate-on-scroll game-card" style="text-align: center; cursor: pointer;" onclick="startGame('binary-challenge')">
            <div style="position: relative;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">💻</div>
                <span style="position: absolute; top: -10px; right: -10px; font-size: 0.6rem; background: rgba(255,107,107,0.1); color: #ff6b6b; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(255,107,107,0.2);">
                    Hard
                </span>
            </div>
            <div class="card-title">Binary Challenge</div>
            <div class="card-description">Convert binary to decimal and back.</div>
            <div style="color: #8892b0; font-size: 0.7rem; margin: 0.3rem 0;">Random Questions</div>
            <button style="margin-top: 0.5rem; padding: 0.4rem 1.2rem; background: #ff6b6b; border: none; border-radius: 6px; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Play Now
            </button>
        </div>
        
        <!-- Networking Challenge -->
        <div class="card animate-on-scroll game-card" style="text-align: center; cursor: pointer;" onclick="startGame('networking')">
            <div style="position: relative;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">🌐</div>
                <span style="position: absolute; top: -10px; right: -10px; font-size: 0.6rem; background: rgba(255,165,0,0.1); color: #ffa500; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(255,165,0,0.2);">
                    Hard
                </span>
            </div>
            <div class="card-title">Networking Challenge</div>
            <div class="card-description">Test your knowledge of network protocols and concepts.</div>
            <div style="color: #8892b0; font-size: 0.7rem; margin: 0.3rem 0;">4 Questions</div>
            <button style="margin-top: 0.5rem; padding: 0.4rem 1.2rem; background: #ffa500; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Play Now
            </button>
        </div>
        
        <!-- Internet Quiz -->
        <div class="card animate-on-scroll game-card" style="text-align: center; cursor: pointer;" onclick="startGame('internet-quiz')">
            <div style="position: relative;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">📡</div>
                <span style="position: absolute; top: -10px; right: -10px; font-size: 0.6rem; background: rgba(255,0,100,0.1); color: #ff0064; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(255,0,100,0.2);">
                    Medium
                </span>
            </div>
            <div class="card-title">Internet Quiz</div>
            <div class="card-description">How well do you know the history of the internet?</div>
            <div style="color: #8892b0; font-size: 0.7rem; margin: 0.3rem 0;">4 Questions</div>
            <button style="margin-top: 0.5rem; padding: 0.4rem 1.2rem; background: #ff0064; border: none; border-radius: 6px; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Play Now
            </button>
        </div>
    </div>
</section>

<!-- Game Stats Info -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 3rem 2rem;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h3 style="color: #00d4ff; margin-bottom: 1rem; font-size: 1.5rem;">🎯 How to Play</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem;">1️⃣</div>
                <div style="color: #fff; font-weight: 500;">Select</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Choose a game</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem;">2️⃣</div>
                <div style="color: #fff; font-weight: 500;">Answer</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Answer questions</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem;">3️⃣</div>
                <div style="color: #fff; font-weight: 500;">Score</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Earn points</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem;">4️⃣</div>
                <div style="color: #fff; font-weight: 500;">Learn</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Discover facts</div>
            </div>
        </div>
    </div>
</section>

<!-- Game Modal -->
<div id="gameModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 5000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 700px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative; min-height: 400px; max-height: 90vh; overflow-y: auto;">
        <button onclick="closeGame()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer; z-index: 10;">
            <i class="fas fa-times"></i>
        </button>
        <div id="gameContent">
            <div style="text-align: center; padding: 2rem 0;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🎮</div>
                <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 1rem;">Digital Games</h2>
                <p style="color: #8892b0;">Select a game from the cards above to start playing.</p>
            </div>
        </div>
    </div>
</div>

<!-- Score Notification -->
<div id="scoreNotification" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.9); padding: 2rem 3rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); z-index: 6000; text-align: center;">
    <div id="scoreNotificationContent">
        <!-- Dynamically loaded -->
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
let gameScore = 0;
let gameQuestions = [];
let currentQuestion = 0;
let gameType = "";
let totalQuestions = 0;

function startGame(type) {
    gameType = type;
    gameScore = 0;
    currentQuestion = 0;
    
    const modal = document.getElementById("gameModal");
    const content = document.getElementById("gameContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    // Load game content based on type
    let html = "";
    
    switch(type) {
        case "guess-year":
            html = getGuessYearGame();
            break;
        case "guess-tech":
            html = getGuessTechGame();
            break;
        case "guess-programmer":
            html = getGuessProgrammerGame();
            break;
        case "binary-challenge":
            html = getBinaryChallengeGame();
            break;
        case "networking":
            html = getNetworkingGame();
            break;
        case "internet-quiz":
            html = getInternetQuizGame();
            break;
        default:
            html = `<h2 style="font-family: 'Orbitron', monospace; color: #00d4ff;">Game not found</h2>
                    <p style="color: #8892b0;">Please select a valid game.</p>`;
    }
    
    content.innerHTML = html;
}

function closeGame() {
    document.getElementById("gameModal").style.display = "none";
    document.body.style.overflow = "";
}

document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeGame();
});

document.getElementById("gameModal").addEventListener("click", function(e) {
    if (e.target === this) closeGame();
});

function showScoreNotification(percentage, message) {
    const notification = document.getElementById("scoreNotification");
    const content = document.getElementById("scoreNotificationContent");
    
    const icon = percentage >= 70 ? "🎉" : percentage >= 50 ? "😊" : "📚";
    const color = percentage >= 70 ? "#00ff88" : percentage >= 50 ? "#ffa500" : "#ff6b6b";
    
    content.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">${icon}</div>
        <div style="font-size: 2rem; font-weight: 700; color: ${color}; margin-bottom: 0.5rem;">${percentage}%</div>
        <p style="color: #8892b0; font-size: 1.1rem;">${message}</p>
        <button onclick="document.getElementById('scoreNotification').style.display='none'" style="margin-top: 1rem; padding: 0.5rem 1.5rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
            Continue
        </button>
    `;
    
    notification.style.display = "block";
    setTimeout(() => {
        notification.style.display = "none";
    }, 3000);
}

// ==================== GUESS THE YEAR ====================
function getGuessYearGame() {
    const questions = [
        { year: 1945, event: "ENIAC, the first electronic computer, was completed." },
        { year: 1969, event: "ARPANET, the precursor to the internet, was created." },
        { year: 1981, event: "IBM released the first personal computer." },
        { year: 1989, event: "The World Wide Web was invented by Tim Berners-Lee." },
        { year: 1998, event: "Google was founded by Larry Page and Sergey Brin." },
        { year: 2004, event: "Facebook was launched by Mark Zuckerberg." },
        { year: 2007, event: "The first iPhone was released by Apple." },
        { year: 2016, event: "AlphaGo defeated world champion Lee Sedol in Go." }
    ];
    
    gameQuestions = questions;
    currentQuestion = 0;
    gameScore = 0;
    totalQuestions = questions.length;
    
    return renderGuessYearQuestion();
}

function renderGuessYearQuestion() {
    if (currentQuestion >= gameQuestions.length) {
        const percentage = Math.round((gameScore / gameQuestions.length) * 100);
        const message = percentage >= 70 ? "Great job! You know your tech history!" : 
                       percentage >= 50 ? "Good effort! Keep learning!" : 
                       "Keep practicing! You'll get better!";
        showScoreNotification(percentage, message);
        
        return `
            <h2 style="font-family: 'Orbitron', monospace; color: #00ff88; margin-bottom: 1rem;">🎉 Game Complete!</h2>
            <p style="font-size: 1.5rem; color: #fff; margin: 1rem 0;">Score: ${gameScore}/${gameQuestions.length}</p>
            <button onclick="startGame('guess-year')" style="padding: 0.8rem 2rem; background: #00d4ff; border: none; border-radius: 8px; color: #000; font-weight: 600; cursor: pointer;">
                Play Again
            </button>
        `;
    }
    
    const q = gameQuestions[currentQuestion];
    const progress = `${currentQuestion + 1}/${gameQuestions.length}`;
    
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 0.5rem;">📅 Guess the Year</h2>
        <div style="color: #8892b0; margin-bottom: 1.5rem;">Progress: ${progress} | Score: ${gameScore}</div>
        <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="font-size: 1.1rem; color: #fff;">${q.event}</p>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem;">
            ${[0, 1, 2, 3].map(i => {
                const year = q.year + Math.floor((i - 1.5) * 5 + (Math.random() - 0.5) * 20);
                return `<button onclick="checkGuessYear(${year}, ${q.year})" style="padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; cursor: pointer; font-size: 1.1rem; transition: all 0.3s;">
                    ${year}
                </button>`;
            }).join('')}
        </div>
    `;
}

function checkGuessYear(answer, correct) {
    if (answer === correct) {
        gameScore++;
    }
    currentQuestion++;
    document.getElementById("gameContent").innerHTML = renderGuessYearQuestion();
}

// ==================== GUESS THE TECHNOLOGY ====================
function getGuessTechGame() {
    const questions = [
        { tech: "TCP/IP", hint: "The standard communication protocol suite for the internet." },
        { tech: "HTML", hint: "The standard markup language for creating web pages." },
        { tech: "JavaScript", hint: "The programming language of the web browser." },
        { tech: "DNS", hint: "The system that translates domain names to IP addresses." },
        { tech: "Linux", hint: "An open-source operating system kernel." },
        { tech: "Python", hint: "A versatile programming language known for its readability." }
    ];
    
    gameQuestions = questions;
    currentQuestion = 0;
    gameScore = 0;
    totalQuestions = questions.length;
    
    return renderGuessTechQuestion();
}

function renderGuessTechQuestion() {
    if (currentQuestion >= gameQuestions.length) {
        const percentage = Math.round((gameScore / gameQuestions.length) * 100);
        const message = percentage >= 70 ? "Excellent! You know your technologies!" : 
                       percentage >= 50 ? "Good try! Keep exploring!" : 
                       "Keep learning about technology!";
        showScoreNotification(percentage, message);
        
        return `
            <h2 style="font-family: 'Orbitron', monospace; color: #00ff88; margin-bottom: 1rem;">🎉 Game Complete!</h2>
            <p style="font-size: 1.5rem; color: #fff; margin: 1rem 0;">Score: ${gameScore}/${gameQuestions.length}</p>
            <button onclick="startGame('guess-tech')" style="padding: 0.8rem 2rem; background: #7b2ffc; border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer;">
                Play Again
            </button>
        `;
    }
    
    const q = gameQuestions[currentQuestion];
    const progress = `${currentQuestion + 1}/${gameQuestions.length}`;
    
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #7b2ffc; margin-bottom: 0.5rem;">🔧 Guess the Technology</h2>
        <div style="color: #8892b0; margin-bottom: 1.5rem;">Progress: ${progress} | Score: ${gameScore}</div>
        <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="font-size: 1.1rem; color: #8892b0;">"${q.hint}"</p>
        </div>
        <div style="display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
            <input type="text" id="techGuess" placeholder="Enter your answer..." style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="checkTechGuess()" style="padding: 0.8rem 1.5rem; background: #7b2ffc; border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Submit
            </button>
        </div>
        <div id="techFeedback" style="margin-top: 1rem; color: #8892b0;"></div>
    `;
}

function checkTechGuess() {
    const input = document.getElementById("techGuess");
    const feedback = document.getElementById("techFeedback");
    const q = gameQuestions[currentQuestion];
    
    if (input.value.toLowerCase().trim() === q.tech.toLowerCase()) {
        gameScore++;
        feedback.innerHTML = '<span style="color: #00ff88;">✅ Correct! Well done!</span>';
    } else {
        feedback.innerHTML = `<span style="color: #ff6b6b;">❌ The answer was: ${q.tech}</span>`;
    }
    
    setTimeout(() => {
        currentQuestion++;
        document.getElementById("gameContent").innerHTML = renderGuessTechQuestion();
    }, 1500);
}

// ==================== GUESS THE PROGRAMMER ====================
function getGuessProgrammerGame() {
    const questions = [
        { programmer: "Alan Turing", hint: "Father of theoretical computer science and artificial intelligence." },
        { programmer: "Tim Berners-Lee", hint: "Invented the World Wide Web." },
        { programmer: "Dennis Ritchie", hint: "Created the C programming language." },
        { programmer: "Linus Torvalds", hint: "Created the Linux kernel and Git." }
    ];
    
    gameQuestions = questions;
    currentQuestion = 0;
    gameScore = 0;
    totalQuestions = questions.length;
    
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #00ff88; margin-bottom: 0.5rem;">👨‍💻 Guess the Programmer</h2>
        <p style="color: #8892b0;">Match the hint to the programmer. Type your answer below.</p>
        <div style="margin-top: 1.5rem;" id="programmerGameContent">
            ${renderProgrammerQuestion()}
        </div>
    `;
}

function renderProgrammerQuestion() {
    if (currentQuestion >= gameQuestions.length) {
        const percentage = Math.round((gameScore / gameQuestions.length) * 100);
        const message = percentage >= 70 ? "Amazing! You know your programmers!" : 
                       percentage >= 50 ? "Good effort! Keep learning!" : 
                       "Keep reading about tech pioneers!";
        showScoreNotification(percentage, message);
        
        return `
            <h3 style="color: #00ff88;">🎉 Game Complete!</h3>
            <p style="font-size: 1.5rem; color: #fff; margin: 1rem 0;">Score: ${gameScore}/${gameQuestions.length}</p>
            <button onclick="startGame('guess-programmer')" style="padding: 0.8rem 2rem; background: #00ff88; border: none; border-radius: 8px; color: #000; font-weight: 600; cursor: pointer;">
                Play Again
            </button>
        `;
    }
    
    const q = gameQuestions[currentQuestion];
    const progress = `${currentQuestion + 1}/${gameQuestions.length}`;
    
    return `
        <div style="color: #8892b0; margin-bottom: 1.5rem;">Progress: ${progress} | Score: ${gameScore}</div>
        <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="font-size: 1.1rem; color: #8892b0;">"${q.hint}"</p>
        </div>
        <div style="display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
            <input type="text" id="programmerGuess" placeholder="Enter programmer name..." style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="checkProgrammerGuess()" style="padding: 0.8rem 1.5rem; background: #00ff88; border: none; border-radius: 8px; color: #000; cursor: pointer; font-weight: 600;">
                Submit
            </button>
        </div>
        <div id="programmerFeedback" style="margin-top: 1rem; color: #8892b0;"></div>
    `;
}

function checkProgrammerGuess() {
    const input = document.getElementById("programmerGuess");
    const feedback = document.getElementById("programmerFeedback");
    const q = gameQuestions[currentQuestion];
    
    if (input.value.toLowerCase().trim() === q.programmer.toLowerCase()) {
        gameScore++;
        feedback.innerHTML = '<span style="color: #00ff88;">✅ Correct! Well done!</span>';
    } else {
        feedback.innerHTML = `<span style="color: #ff6b6b;">❌ The answer was: ${q.programmer}</span>`;
    }
    
    setTimeout(() => {
        currentQuestion++;
        document.getElementById("programmerGameContent").innerHTML = renderProgrammerQuestion();
    }, 1500);
}

// ==================== BINARY CHALLENGE ====================
function getBinaryChallengeGame() {
    gameQuestions = [];
    currentQuestion = 0;
    gameScore = 0;
    totalQuestions = 5;
    
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #ff6b6b; margin-bottom: 0.5rem;">💻 Binary Challenge</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Convert binary to decimal. Answer 5 questions correctly to complete!</p>
        <div id="binaryChallengeContent">
            ${renderBinaryChallenge()}
        </div>
    `;
}

function renderBinaryChallenge() {
    if (currentQuestion >= totalQuestions) {
        const percentage = Math.round((gameScore / totalQuestions) * 100);
        const message = percentage >= 70 ? "Excellent binary skills!" : 
                       percentage >= 50 ? "Good effort! Keep practicing!" : 
                       "Keep learning binary!";
        showScoreNotification(percentage, message);
        
        return `
            <h3 style="color: #00ff88;">🎉 Challenge Complete!</h3>
            <p style="font-size: 1.5rem; color: #fff; margin: 1rem 0;">Score: ${gameScore}/${totalQuestions}</p>
            <button onclick="startGame('binary-challenge')" style="padding: 0.8rem 2rem; background: #ff6b6b; border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer;">
                Try Again
            </button>
        `;
    }
    
    const binary = Math.floor(Math.random() * 255).toString(2).padStart(8, '0');
    const decimal = parseInt(binary, 2);
    
    return `
        <div style="color: #8892b0; margin-bottom: 1.5rem;">Question ${currentQuestion + 1}/${totalQuestions} | Score: ${gameScore}</div>
        <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="font-size: 2rem; font-family: 'Orbitron', monospace; color: #00d4ff; text-align: center;">${binary}</p>
        </div>
        <div style="display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
            <input type="number" id="binaryAnswer" placeholder="Enter decimal value..." style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="checkBinaryAnswer(${decimal})" style="padding: 0.8rem 1.5rem; background: #ff6b6b; border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Check
            </button>
        </div>
        <div id="binaryFeedback" style="margin-top: 1rem; color: #8892b0;"></div>
    `;
}

function checkBinaryAnswer(correct) {
    const input = document.getElementById("binaryAnswer");
    const feedback = document.getElementById("binaryFeedback");
    
    if (parseInt(input.value) === correct) {
        gameScore++;
        feedback.innerHTML = '<span style="color: #00ff88;">✅ Correct! Great job!</span>';
        setTimeout(() => {
            currentQuestion++;
            document.getElementById("binaryChallengeContent").innerHTML = renderBinaryChallenge();
        }, 1500);
    } else {
        feedback.innerHTML = `<span style="color: #ff6b6b;">❌ The correct answer is: ${correct}</span>`;
        setTimeout(() => {
            currentQuestion++;
            document.getElementById("binaryChallengeContent").innerHTML = renderBinaryChallenge();
        }, 2000);
    }
}

// ==================== NETWORKING CHALLENGE ====================
function getNetworkingGame() {
    const questions = [
        { q: "What protocol is used for web browsing?", a: "HTTP" },
        { q: "What does DNS stand for?", a: "Domain Name System" },
        { q: "What port does HTTPS use?", a: "443" },
        { q: "What is the most common IP version?", a: "IPv4" }
    ];
    
    gameQuestions = questions;
    currentQuestion = 0;
    gameScore = 0;
    totalQuestions = questions.length;
    
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #ffa500; margin-bottom: 0.5rem;">🌐 Networking Challenge</h2>
        <p style="color: #8892b0;">Answer these networking questions.</p>
        <div id="networkingContent">
            ${renderNetworkingQuestion()}
        </div>
    `;
}

function renderNetworkingQuestion() {
    if (currentQuestion >= gameQuestions.length) {
        const percentage = Math.round((gameScore / gameQuestions.length) * 100);
        const message = percentage >= 70 ? "Networking pro!" : 
                       percentage >= 50 ? "Good network knowledge!" : 
                       "Keep learning networking!";
        showScoreNotification(percentage, message);
        
        return `
            <h3 style="color: #00ff88;">🎉 Quiz Complete!</h3>
            <p style="font-size: 1.5rem; color: #fff; margin: 1rem 0;">Score: ${gameScore}/${gameQuestions.length}</p>
            <button onclick="startGame('networking')" style="padding: 0.8rem 2rem; background: #ffa500; border: none; border-radius: 8px; color: #000; font-weight: 600; cursor: pointer;">
                Retry
            </button>
        `;
    }
    
    const q = gameQuestions[currentQuestion];
    const progress = `${currentQuestion + 1}/${gameQuestions.length}`;
    
    return `
        <div style="color: #8892b0; margin-bottom: 1.5rem;">Progress: ${progress} | Score: ${gameScore}</div>
        <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="font-size: 1.1rem; color: #fff;">${q.q}</p>
        </div>
        <div style="display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
            <input type="text" id="networkingAnswer" placeholder="Enter your answer..." style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="checkNetworkingAnswer()" style="padding: 0.8rem 1.5rem; background: #ffa500; border: none; border-radius: 8px; color: #000; cursor: pointer; font-weight: 600;">
                Submit
            </button>
        </div>
        <div id="networkingFeedback" style="margin-top: 1rem; color: #8892b0;"></div>
    `;
}

function checkNetworkingAnswer() {
    const input = document.getElementById("networkingAnswer");
    const feedback = document.getElementById("networkingFeedback");
    const q = gameQuestions[currentQuestion];
    
    if (input.value.toLowerCase().trim() === q.a.toLowerCase()) {
        gameScore++;
        feedback.innerHTML = '<span style="color: #00ff88;">✅ Correct!</span>';
    } else {
        feedback.innerHTML = `<span style="color: #ff6b6b;">❌ The answer is: ${q.a}</span>`;
    }
    
    setTimeout(() => {
        currentQuestion++;
        document.getElementById("networkingContent").innerHTML = renderNetworkingQuestion();
    }, 1500);
}

// ==================== INTERNET QUIZ ====================
function getInternetQuizGame() {
    const questions = [
        { q: "What year was the World Wide Web invented?", a: "1989" },
        { q: "Who invented the World Wide Web?", a: "Tim Berners-Lee" },
        { q: "What was the first web browser called?", a: "WorldWideWeb" },
        { q: "What year was Google founded?", a: "1998" }
    ];
    
    gameQuestions = questions;
    currentQuestion = 0;
    gameScore = 0;
    totalQuestions = questions.length;
    
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #ff0064; margin-bottom: 0.5rem;">📡 Internet Quiz</h2>
        <p style="color: #8892b0;">Test your knowledge of internet history.</p>
        <div id="internetQuizContent">
            ${renderInternetQuizQuestion()}
        </div>
    `;
}

function renderInternetQuizQuestion() {
    if (currentQuestion >= gameQuestions.length) {
        const percentage = Math.round((gameScore / gameQuestions.length) * 100);
        const message = percentage >= 70 ? "Internet expert!" : 
                       percentage >= 50 ? "Good internet knowledge!" : 
                       "Keep learning internet history!";
        showScoreNotification(percentage, message);
        
        return `
            <h3 style="color: #00ff88;">🎉 Quiz Complete!</h3>
            <p style="font-size: 1.5rem; color: #fff; margin: 1rem 0;">Score: ${gameScore}/${gameQuestions.length}</p>
            <button onclick="startGame('internet-quiz')" style="padding: 0.8rem 2rem; background: #ff0064; border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer;">
                Retry
            </button>
        `;
    }
    
    const q = gameQuestions[currentQuestion];
    const progress = `${currentQuestion + 1}/${gameQuestions.length}`;
    
    return `
        <div style="color: #8892b0; margin-bottom: 1.5rem;">Progress: ${progress} | Score: ${gameScore}</div>
        <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="font-size: 1.1rem; color: #fff;">${q.q}</p>
        </div>
        <div style="display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
            <input type="text" id="internetAnswer" placeholder="Enter your answer..." style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="checkInternetAnswer()" style="padding: 0.8rem 1.5rem; background: #ff0064; border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Submit
            </button>
        </div>
        <div id="internetFeedback" style="margin-top: 1rem; color: #8892b0;"></div>
    `;
}

function checkInternetAnswer() {
    const input = document.getElementById("internetAnswer");
    const feedback = document.getElementById("internetFeedback");
    const q = gameQuestions[currentQuestion];
    
    if (input.value.toLowerCase().trim() === q.a.toLowerCase()) {
        gameScore++;
        feedback.innerHTML = '<span style="color: #00ff88;">✅ Correct!</span>';
    } else {
        feedback.innerHTML = `<span style="color: #ff6b6b;">❌ The answer is: ${q.a}</span>`;
    }
    
    setTimeout(() => {
        currentQuestion++;
        document.getElementById("internetQuizContent").innerHTML = renderInternetQuizQuestion();
    }, 1500);
}

// Add hover effects to game cards
document.querySelectorAll(".game-card").forEach(card => {
    card.addEventListener("mouseenter", function() {
        this.style.transform = "translateY(-6px)";
        this.style.borderColor = "rgba(0, 212, 255, 0.3)";
        this.style.boxShadow = "0 8px 30px rgba(0, 0, 0, 0.3)";
    });
    card.addEventListener("mouseleave", function() {
        this.style.transform = "translateY(0)";
        this.style.borderColor = "rgba(255, 255, 255, 0.06)";
        this.style.boxShadow = "none";
    });
});

// Add glow animation
const style = document.createElement("style");
style.textContent = `
    @keyframes glowPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .game-card {
        transition: all 0.3s ease;
    }
    #gameModal {
        animation: fadeIn 0.3s ease;
    }
    #scoreNotification {
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translate(-50%, -50%) scale(0.9); }
        to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    }
`;
document.head.appendChild(style);

JS;

$pageCSS = '
.game-card {
    transition: all 0.3s ease;
    position: relative;
}

.game-card:hover {
    transform: translateY(-6px);
    border-color: rgba(0, 212, 255, 0.3);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.game-card button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
}

#gameModal::-webkit-scrollbar {
    width: 6px;
}

#gameModal::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

#gameModal::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

#gameModal::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

#scoreNotification {
    animation: fadeIn 0.3s ease;
}

@media (max-width: 768px) {
    #gameModal {
        padding: 1rem !important;
    }
    
    #gameModal > div {
        padding: 1.5rem !important;
        max-height: 95vh;
    }
    
    .game-card {
        padding: 1rem !important;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>