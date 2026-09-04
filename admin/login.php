<?php
// admin/login.php
// Digital History Admin - Login

require_once '../includes/config.php';
require_once '../includes/security.php';

// If already logged in as admin, redirect to dashboard
if (isLoggedIn() && isAdmin()) {
    redirect('index.php');
}

$error = '';
$success = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Check admin credentials
        $user = db()->fetch(
            "SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1 AND role = 'admin'",
            [$username, $username]
        );
        
        if ($user && verifyPassword($password, $user['password_hash'])) {
            // Login successful
            regenerateSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['full_name'];
            
            logActivity('admin_login', ['user_id' => $user['id']]);
            
            redirect('index.php');
        } else {
            $error = 'Invalid credentials or insufficient permissions.';
        }
    }
}

$pageTitle = 'Admin Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Digital History</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            color: #ccd6f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                radial-gradient(ellipse at 20% 50%, rgba(0, 212, 255, 0.05) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 50%, rgba(123, 47, 252, 0.05) 0%, transparent 50%);
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 3rem;
            max-width: 420px;
            width: 90%;
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo span {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
        }
        
        .login-logo .highlight {
            color: #00d4ff;
        }
        
        .login-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: #fff;
        }
        
        .login-subtitle {
            text-align: center;
            color: #8892b0;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
        }
        
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #ccd6f6;
            margin-bottom: 0.3rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #00d4ff;
        }
        
        .form-group input::placeholder {
            color: #8892b0;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #00d4ff, #7b2ffc);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.2);
        }
        
        .error-message {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.2);
            color: #ff6b6b;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #8892b0;
            font-size: 0.85rem;
        }
        
        .login-footer a {
            color: #00d4ff;
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #8892b0;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: #fff;
        }
        
        .back-link i {
            margin-right: 0.3rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }
            
            .login-logo span {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <span>DIGITAL<span class="highlight">HISTORY</span></span>
        </div>
        
        <h1 class="login-title">Admin Login</h1>
        <p class="login-subtitle">Access the administrative dashboard</p>
        
        <?php if ($error): ?>
        <div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo escape($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="error-message" style="background: rgba(0, 212, 255, 0.1); border-color: rgba(0, 212, 255, 0.2); color: #00d4ff;">
            <i class="fas fa-check-circle"></i> <?php echo escape($success); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <?php echo getCSRFField(); ?>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" placeholder="Enter your username or email" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-lock"></i> Login to Admin
            </button>
        </form>
        
        <div class="login-footer">
            <a href="<?php echo SITE_URL; ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Site
            </a>
        </div>
    </div>
</body>
</html>