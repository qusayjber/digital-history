<?php
// contact.php
// Digital History - Contact Page

require_once 'includes/config.php';

$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with the Digital History team.';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            $error = 'Please fill in all required fields.';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($message) < 10) {
            $error = 'Message must be at least 10 characters long.';
        } else {
            // In a real implementation, you would send an email here
            // For now, we just log the contact
            logActivity('contact_form', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject
            ]);
            $success = 'Thank you for your message! We\'ll get back to you soon.';
        }
    }
}

trackPageView('contact');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>📧 Contact Us</h2>
        <p>Have questions, feedback, or suggestions? We\'d love to hear from you.</p>
    </div>
</section>

<!-- Contact Form -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 600px; margin: 0 auto;">
        <?php if ($success): ?>
        <div style="background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); color: #00d4ff; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.2); color: #ff6b6b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <?php echo getCSRFField(); ?>
            
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Full Name *</label>
                <input type="text" name="name" value="<?php echo escape($_POST['name'] ?? ''); ?>" required placeholder="Your full name" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Email Address *</label>
                <input type="email" name="email" value="<?php echo escape($_POST['email'] ?? ''); ?>" required placeholder="your@email.com" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Subject (Optional)</label>
                <input type="text" name="subject" value="<?php echo escape($_POST['subject'] ?? ''); ?>" placeholder="What is this about?" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Message *</label>
                <textarea name="message" rows="5" required placeholder="Write your message here..." style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s; resize: vertical; font-family: inherit;"><?php echo escape($_POST['message'] ?? ''); ?></textarea>
            </div>
            
            <button type="submit" style="width: 100%; padding: 0.9rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
        </form>
        
        <!-- Contact Info -->
        <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
            <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📧</div>
                <h4 style="color: #00d4ff; font-size: 0.9rem;">Email</h4>
                <p style="color: #8892b0; font-size: 0.85rem;">contact@digitalhistory.com</p>
            </div>
            <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐦</div>
                <h4 style="color: #7b2ffc; font-size: 0.9rem;">Twitter</h4>
                <p style="color: #8892b0; font-size: 0.85rem;">@DigitalHistory</p>
            </div>
            <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">💬</div>
                <h4 style="color: #00ff88; font-size: 0.9rem;">Discord</h4>
                <p style="color: #8892b0; font-size: 0.85rem;">Join our community</p>
            </div>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
$pageCSS = '
input:focus, textarea:focus {
    outline: none;
    border-color: #00d4ff !important;
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>