<?php
// login.php
// Digital History - User Login

require_once 'includes/config.php';

$pageTitle = 'Login';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            $result = loginUser($username, $password, $remember);
            if ($result['success']) {
                // Check if there's a redirect URL
                $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redirect);
            } else {
                $error = $result['error'];
            }
        }
    }
}

ob_start();
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 2.5rem; max-width: 400px; width: 100%;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <span style="font-family: 'Orbitron', monospace; font-size: 2rem; font-weight: 700; color: #fff;">
                DIGITAL<span style="color: #00d4ff;">HISTORY</span>
            </span>
        </div>
        
        <h2 style="font-family: 'Orbitron', monospace; text-align: center; color: #fff; font-size: 1.5rem; margin-bottom: 0.3rem;">
            Welcome Back
        </h2>
        <p style="text-align: center; color: #8892b0; margin-bottom: 2rem;">Login to continue your journey</p>
        
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
            
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Username or Email</label>
                <input type="text" name="username" value="<?php echo escape($_POST['username'] ?? ''); ?>" required placeholder="Enter your username or email" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Password</label>
                <input type="password" name="password" required placeholder="Enter your password" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
                <label style="color: #8892b0; font-size: 0.85rem; cursor: pointer;">
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
                <a href="forgot-password.php" style="color: #00d4ff; font-size: 0.85rem; text-decoration: none;">Forgot password?</a>
            </div>
            
            <button type="submit" style="width: 100%; padding: 0.9rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 1.5rem; color: #8892b0; font-size: 0.9rem;">
            Don't have an account? <a href="register.php" style="color: #00d4ff; text-decoration: none;">Sign Up</a>
        </div>
        
        <div style="text-align: center; margin-top: 1rem;">
            <a href="index.php" style="color: #8892b0; font-size: 0.85rem; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
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