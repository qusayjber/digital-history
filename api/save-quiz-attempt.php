<?php
// api/save-quiz-attempt.php
// Digital History - Save Quiz Attempt API

require_once '../includes/config.php';

header('Content-Type: application/json');

// Require login
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$quiz_id = intval($_POST['quiz_id'] ?? 0);
$score = intval($_POST['score'] ?? 0);
$total = intval($_POST['total'] ?? 0);
$answers = $_POST['answers'] ?? '[]';

if (!$quiz_id) {
    echo json_encode(['success' => false, 'error' => 'Missing quiz ID']);
    exit;
}

// Save attempt
try {
    db()->insert('user_quiz_attempts', [
        'user_id' => $_SESSION['user_id'],
        'quiz_id' => $quiz_id,
        'score' => $score,
        'total_possible' => $total,
        'time_taken' => 0,
        'completed_at' => date('Y-m-d H:i:s')
    ]);
    
    // Check for achievements
    $attempts = db()->fetchAll(
        "SELECT COUNT(*) as count FROM user_quiz_attempts WHERE user_id = ? AND quiz_id = ?",
        [$_SESSION['user_id'], $quiz_id]
    );
    
    if ($attempts[0]['count'] >= 5) {
        unlockAchievement($_SESSION['user_id'], 'quiz-master');
    }
    
    logActivity('quiz_completed', ['quiz_id' => $quiz_id, 'score' => $score, 'total' => $total]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>