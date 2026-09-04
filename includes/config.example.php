<?php
// includes/config.example.php
// Digital History - Example Configuration
//
// Copy this file to config.php and update the values for your local environment.

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Site Configuration
define('SITE_NAME', 'Digital History');
define('SITE_URL', 'http://localhost/digital-history/');
define('SITE_DESCRIPTION', 'The interactive museum of computing, programming, the internet and the future.');
define('ADMIN_EMAIL', 'admin@example.com');

// Paths
define('ROOT_PATH', __DIR__ . '/../');
define('ADMIN_PATH', ROOT_PATH . 'admin/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');
define('API_PATH', ROOT_PATH . 'api/');

// URLs
define('ASSETS_URL', SITE_URL . 'assets/');
define('UPLOADS_URL', SITE_URL . 'uploads/');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'digital_history');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_CHARSET', 'utf8mb4');

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_NAME', 'digital_history_session');
define('SESSION_LIFETIME', 86400);

// Upload Settings
define('MAX_FILE_SIZE', 5242880);

define('ALLOWED_EXTENSIONS', [
    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp',
    'mp4',
    'webm',
    'pdf',
    'doc',
    'docx'
]);

define('ALLOWED_MIME_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
    'video/mp4',
    'video/webm',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]);

// Timezone
date_default_timezone_set('UTC');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);

    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

// Load other includes
require_once INCLUDES_PATH . 'database.php';
require_once INCLUDES_PATH . 'functions.php';
require_once INCLUDES_PATH . 'auth.php';
require_once INCLUDES_PATH . 'security.php';
require_once INCLUDES_PATH . 'language.php';

// Set default language
$current_lang = getCurrentLanguage();
?>
