<?php
// privacy.php
// Digital History - Privacy Policy

require_once 'includes/config.php';

$pageTitle = 'Privacy Policy';
$pageDescription = 'Privacy policy for Digital History.';

trackPageView('privacy');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>🔒 Privacy Policy</h2>
        <p>How we collect, use, and protect your personal information.</p>
    </div>
</section>

<!-- Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Last updated: <?php echo date('F d, Y'); ?></p>
        
        <h3 style="color: #00d4ff; margin-top: 1.5rem; font-size: 1.2rem;">1. Information We Collect</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We collect information you provide directly to us, such as when you create an account, 
            update your profile, or contact us. This may include your name, email address, username, 
            and any other information you choose to provide.
        </p>
        
        <h3 style="color: #7b2ffc; margin-top: 1.5rem; font-size: 1.2rem;">2. How We Use Your Information</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We use your information to provide and improve our services, communicate with you, 
            personalize your experience, and maintain the security of our platform.
        </p>
        
        <h3 style="color: #00ff88; margin-top: 1.5rem; font-size: 1.2rem;">3. Data Security</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We implement appropriate security measures to protect your personal information. 
            This includes encryption, secure servers, and regular security audits.
        </p>
        
        <h3 style="color: #ffa500; margin-top: 1.5rem; font-size: 1.2rem;">4. Cookies</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We use cookies to enhance your experience, remember your preferences, and analyze site traffic. 
            You can control cookie settings in your browser.
        </p>
        
        <h3 style="color: #ff0064; margin-top: 1.5rem; font-size: 1.2rem;">5. Third-Party Services</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We may use third-party services for analytics, authentication, or other functions. 
            These services have their own privacy policies.
        </p>
        
        <h3 style="color: #00d4ff; margin-top: 1.5rem; font-size: 1.2rem;">6. Your Rights</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            You have the right to access, modify, or delete your personal information. 
            You can do this through your profile settings or by contacting us.
        </p>
        
        <h3 style="color: #7b2ffc; margin-top: 1.5rem; font-size: 1.2rem;">7. Contact Us</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            If you have questions about this privacy policy, please <a href="contact.php" style="color: #00d4ff;">contact us</a>.
        </p>
        
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);">
            <p style="color: #8892b0; font-size: 0.85rem;">
                We may update this policy from time to time. We will notify you of any changes by posting the new policy on this page.
            </p>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>