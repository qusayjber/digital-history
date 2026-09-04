<?php
// api/set-language.php
// Digital History - Set Language API

require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$lang = $_POST['lang'] ?? '';

if (empty($lang) || !isset($SUPPORTED_LANGUAGES[$lang])) {
    echo json_encode(['success' => false, 'error' => 'Invalid language']);
    exit;
}

setLanguage($lang);
echo json_encode(['success' => true]);
?>