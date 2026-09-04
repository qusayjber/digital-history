<?php
// register.php
// Digital History - User Registration

require_once 'includes/config.php';

$pageTitle = 'Sign Up';
$error = '';
$success = '';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');
        
        if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
            $error = 'All fields are required.';
        } elseif ($password !== $password_confirm) {
            $error = 'Passwords do not match.';
        } elseif (!validateEmail($email)) {
            $error = 'Invalid email address.';
        } elseif (!validatePassword($password)) {
            $error = 'Password must be at least 8 characters with uppercase, lowercase, and numbers.';
        } else {
            $result = registerUser($username, $email, $password, $full_name);
            if ($result['success']) {
                $success = 'Registration successful! You can now <a href="login.php">login</a>.';
                // Auto-login
                loginUser($username, $password);
            } else {
                $error = $result['error'];
            }
        }
    }
}

ob_start();
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 2.5rem; max-width: 440px; width: 100%;">
        <h2 style="font-family: 'Orbitron', monospace; text-align: center; color: #fff; font-size: 1.8rem; margin-bottom: 0.3rem;">
            Create Account
        </h2>
        <p style="text-align: center; color: #8892b0; margin-bottom: 2rem;">Join the Digital History community</p>
        
        <?php if ($error): ?>
        <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.2); color: #ff6b6b; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div style="background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); color: #00d4ff; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <?php echo getCSRFField(); ?>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Username</label>
                <input type="text" name="username" value="<?php echo escape($_POST['username'] ?? ''); ?>" required placeholder="Choose a username" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Full Name (optional)</label>
                <input type="text" name="full_name" value="<?php echo escape($_POST['full_name'] ?? ''); ?>" placeholder="Your full name" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Email</label>
                <input type="email" name="email" value="<?php echo escape($_POST['email'] ?? ''); ?>" required placeholder="Your email address" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Password</label>
                <input type="password" name="password" required placeholder="Create a password" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
                <div style="font-size: 0.75rem; color: #8892b0; margin-top: 0.3rem;">Must be at least 8 characters with uppercase, lowercase, and numbers.</div>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Confirm Password</label>
                <input type="password" name="password_confirm" required placeholder="Confirm your password" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 0.9rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 1.5rem; color: #8892b0; font-size: 0.9rem;">
            Already have an account? <a href="login.php" style="color: #00d4ff; text-decoration: none;">Login</a>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
$pageJS = '';
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