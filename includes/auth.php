<?php
// includes/auth.php
// Digital History - Authentication Functions

function registerUser($username, $email, $password, $fullName = null) {
    // Validate inputs
    if (!validateString($username, 3, 50)) {
        return ['success' => false, 'error' => 'Username must be between 3 and 50 characters.'];
    }
    
    if (!validateEmail($email)) {
        return ['success' => false, 'error' => 'Invalid email address.'];
    }
    
    if (!validatePassword($password)) {
        return ['success' => false, 'error' => 'Password must be at least 8 characters and contain uppercase, lowercase, and numbers.'];
    }
    
    // Check if username or email already exists
    $existing = db()->fetch(
        "SELECT id FROM users WHERE username = ? OR email = ?",
        [$username, $email]
    );
    
    if ($existing) {
        return ['success' => false, 'error' => 'Username or email already taken.'];
    }
    
    // Hash password
    $hashedPassword = hashPassword($password);
    
    // Insert user
    try {
        $userId = db()->insert('users', [
            'username' => $username,
            'email' => $email,
            'password_hash' => $hashedPassword,
            'full_name' => $fullName ?? $username,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return ['success' => true, 'user_id' => $userId];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Registration failed: ' . $e->getMessage()];
    }
}

function loginUser($username, $password, $remember = false) {
    // Rate limiting
    if (!checkRateLimit('login_' . $_SERVER['REMOTE_ADDR'], 5, 300)) {
        return ['success' => false, 'error' => 'Too many login attempts. Please try again later.'];
    }
    
    // Find user
    $user = db()->fetch(
        "SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1",
        [$username, $username]
    );
    
    if (!$user) {
        return ['success' => false, 'error' => 'Invalid credentials.'];
    }
    
    // Verify password
    if (!verifyPassword($password, $user['password_hash'])) {
        return ['success' => false, 'error' => 'Invalid credentials.'];
    }
    
    // Update last login
    db()->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);
    
    // Regenerate session
    regenerateSession();
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['full_name'];
    
    // Remember me
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + 86400 * 30, '/', '', isset($_SERVER['HTTPS']), true);
        // Store token in database (you'd need a remember_tokens table)
    }
    
    logActivity('login', ['user_id' => $user['id']]);
    
    return ['success' => true, 'user' => $user];
}

function logoutUser() {
    // Clear session
    $_SESSION = [];
    session_destroy();
    
    // Clear remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
    }
    
    logActivity('logout');
    return true;
}

function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $user = db()->fetch(
        "SELECT id, username, email, full_name, role, avatar, bio, created_at FROM users WHERE id = ? AND is_active = 1",
        [$_SESSION['user_id']]
    );
    
    return $user;
}

function getUserById($userId) {
    return db()->fetch(
        "SELECT id, username, email, full_name, role, avatar, bio, created_at FROM users WHERE id = ? AND is_active = 1",
        [$userId]
    );
}

function updateUser($userId, $data) {
    $allowedFields = ['full_name', 'bio', 'email'];
    $updateData = [];
    
    foreach ($data as $key => $value) {
        if (in_array($key, $allowedFields)) {
            $updateData[$key] = $value;
        }
    }
    
    if (isset($data['password']) && !empty($data['password'])) {
        if (validatePassword($data['password'])) {
            $updateData['password_hash'] = hashPassword($data['password']);
        } else {
            return ['success' => false, 'error' => 'Invalid password format.'];
        }
    }
    
    if (isset($data['avatar']) && !empty($data['avatar'])) {
        $updateData['avatar'] = $data['avatar'];
    }
    
    if (empty($updateData)) {
        return ['success' => false, 'error' => 'No valid fields to update.'];
    }
    
    try {
        db()->update('users', $updateData, 'id = ?', [$userId]);
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Update failed: ' . $e->getMessage()];
    }
}

function isUserAdmin($userId = null) {
    if ($userId === null) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    $user = getUserById($userId);
    return $user && $user['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = getCurrentURL();
        redirect(SITE_URL . 'login.php');
    }
}

function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        redirect(SITE_URL . 'login.php');
    }
}

function getLoginURL() {
    return SITE_URL . 'login.php';
}

function getRegisterURL() {
    return SITE_URL . 'register.php';
}
?>