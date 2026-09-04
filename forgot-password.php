<?php
// forgot-password.php
// Digital History - Forgot Password

require_once 'includes/config.php';

$pageTitle = 'Forgot Password';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$message = '';
$error = '';
$step = 'request'; // request | reset

// Handle password reset request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    
    if (!validateEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if user exists
        $user = db()->fetch("SELECT id, username, email FROM users WHERE email = ? AND is_active = 1", [$email]);
        
        if ($user) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store token in database (you need a password_resets table)
            db()->insert('password_resets', [
                'user_id' => $user['id'],
                'token' => $token,
                'expires_at' => $expires
            ]);
            
            // In a real implementation, send email with reset link
            // For now, just show success message
            $message = 'Password reset instructions have been sent to your email.';
            logActivity('password_reset_requested', ['user_id' => $user['id']]);
        } else {
            // Don't reveal if email exists or not
            $message = 'If an account exists with this email, password reset instructions have been sent.';
        }
    }
}

// Handle password reset
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    $reset = db()->fetch(
        "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()",
        [$token]
    );
    
    if ($reset) {
        $step = 'reset';
        $resetUserId = $reset['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            
            if (empty($password) || empty($password_confirm)) {
                $error = 'Please enter a password.';
            } elseif ($password !== $password_confirm) {
                $error = 'Passwords do not match.';
            } elseif (!validatePassword($password)) {
                $error = 'Password must be at least 8 characters with uppercase, lowercase, and numbers.';
            } else {
                // Update password
                $hashedPassword = hashPassword($password);
                db()->update('users', ['password_hash' => $hashedPassword], 'id = ?', [$resetUserId]);
                
                // Delete used token
                db()->delete('password_resets', 'token = ?', [$token]);
                
                logActivity('password_reset_completed', ['user_id' => $resetUserId]);
                $message = 'Password has been reset successfully. You can now <a href="login.php">login</a>.';
                $step = 'complete';
            }
        }
    } else {
        $error = 'Invalid or expired reset token. Please request a new password reset.';
        $step = 'request';
    }
}

trackPageView('forgot-password');

ob_start();
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 2.5rem; max-width: 440px; width: 100%;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <span style="font-family: 'Orbitron', monospace; font-size: 2rem; font-weight: 700; color: #fff;">
                DIGITAL<span style="color: #00d4ff;">HISTORY</span>
            </span>
        </div>
        
        <?php if ($step === 'request'): ?>
        <h2 style="font-family: 'Orbitron', monospace; text-align: center; color: #fff; font-size: 1.5rem; margin-bottom: 0.3rem;">
            Forgot Password
        </h2>
        <p style="text-align: center; color: #8892b0; margin-bottom: 2rem;">
            Enter your email to receive password reset instructions.
        </p>
        
        <?php if ($message): ?>
        <div style="background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); color: #00d4ff; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <i class="fas fa-check-circle"></i> <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.2); color: #ff6b6b; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <?php echo getCSRFField(); ?>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Email Address</label>
                <input type="email" name="email" value="<?php echo escape($_POST['email'] ?? ''); ?>" required placeholder="your@email.com" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 0.9rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="login.php" style="color: #8892b0; font-size: 0.9rem; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
        <?php endif; ?>
        
        <?php if ($step === 'reset'): ?>
        <h2 style="font-family: 'Orbitron', monospace; text-align: center; color: #fff; font-size: 1.5rem; margin-bottom: 0.3rem;">
            Reset Password
        </h2>
        <p style="text-align: center; color: #8892b0; margin-bottom: 2rem;">
            Enter your new password below.
        </p>
        
        <?php if ($error): ?>
        <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.2); color: #ff6b6b; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="?token=<?php echo urlencode($_GET['token']); ?>">
            <?php echo getCSRFField(); ?>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">New Password</label>
                <input type="password" name="password" required placeholder="Enter new password" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
                <div style="font-size: 0.75rem; color: #8892b0; margin-top: 0.3rem;">Must be at least 8 characters with uppercase, lowercase, and numbers.</div>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Confirm Password</label>
                <input type="password" name="password_confirm" required placeholder="Confirm new password" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 0.9rem; background: linear-gradient(135deg, #00ff88, #00d4ff); border: none; border-radius: 8px; color: #000; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                <i class="fas fa-key"></i> Reset Password
            </button>
        </form>
        <?php endif; ?>
        
        <?php if ($step === 'complete'): ?>
        <div style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
            <h2 style="color: #00ff88; margin-bottom: 0.5rem;">Password Reset Complete!</h2>
            <p style="color: #8892b0; margin-bottom: 1.5rem;"><?php echo $message; ?></p>
            <a href="login.php" style="display: inline-block; padding: 0.8rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-weight: 600; text-decoration: none;">
                <i class="fas fa-sign-in-alt"></i> Login Now
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
$pageCSS = '
input:focus {
    outline: none;
    border-color: #00d4ff !important;
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>