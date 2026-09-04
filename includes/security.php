<?php
// includes/security.php
// Digital History - Security Functions

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function validateCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function getCSRFField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCSRFToken()) . '">';
}

// XSS Protection
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function escapeJS($string) {
    return json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

// SQL Injection Protection (handled by PDO prepared statements)
// Input Validation
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function validateInt($value, $min = null, $max = null) {
    if (!filter_var($value, FILTER_VALIDATE_INT)) {
        return false;
    }
    if ($min !== null && $value < $min) return false;
    if ($max !== null && $value > $max) return false;
    return true;
}

function validateString($value, $minLength = 0, $maxLength = null) {
    if (!is_string($value)) return false;
    $length = mb_strlen($value);
    if ($length < $minLength) return false;
    if ($maxLength !== null && $length > $maxLength) return false;
    return true;
}

// Password Hashing
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Rate Limiting
function checkRateLimit($key, $limit = 5, $timeWindow = 60) {
    $sessionKey = 'rate_limit_' . $key;
    $timestampKey = 'rate_limit_time_' . $key;
    
    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = 1;
        $_SESSION[$timestampKey] = time();
        return true;
    }
    
    if (time() - $_SESSION[$timestampKey] > $timeWindow) {
        $_SESSION[$sessionKey] = 1;
        $_SESSION[$timestampKey] = time();
        return true;
    }
    
    if ($_SESSION[$sessionKey] >= $limit) {
        return false;
    }
    
    $_SESSION[$sessionKey]++;
    return true;
}

// Secure Headers - MODIFIED: Check if headers already sent
function setSecurityHeaders() {
    // Only send headers if they haven't been sent yet
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; connect-src 'self'; frame-ancestors 'none'; form-action 'self'");
    }
}

// File Upload Security
function validateFileUpload($file, $maxSize = MAX_FILE_SIZE) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'Upload failed with error code: ' . $file['error']];
    }
    
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'File size exceeds maximum allowed size.'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['valid' => false, 'error' => 'File type not allowed.'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
        return ['valid' => false, 'error' => 'MIME type not allowed.'];
    }
    
    return ['valid' => true];
}

function sanitizeFilename($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    return uniqid() . '_' . $filename;
}

// Session Security
function regenerateSession() {
    session_regenerate_id(true);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Password Policy
function validatePassword($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    return true;
}
?>