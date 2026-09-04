<?php
// terms.php
// Digital History - Terms of Service

require_once 'includes/config.php';

$pageTitle = 'Terms of Service';
$pageDescription = 'Terms of service for Digital History.';

trackPageView('terms');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>📜 Terms of Service</h2>
        <p>Please read these terms carefully before using Digital History.</p>
    </div>
</section>

<!-- Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Last updated: <?php echo date('F d, Y'); ?></p>
        
        <h3 style="color: #00d4ff; margin-top: 1.5rem; font-size: 1.2rem;">1. Acceptance of Terms</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            By using Digital History, you agree to be bound by these terms of service. 
            If you do not agree to these terms, please do not use our service.
        </p>
        
        <h3 style="color: #7b2ffc; margin-top: 1.5rem; font-size: 1.2rem;">2. User Accounts</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            You are responsible for maintaining the confidentiality of your account credentials. 
            You agree to notify us immediately of any unauthorized use of your account.
        </p>
        
        <h3 style="color: #00ff88; margin-top: 1.5rem; font-size: 1.2rem;">3. Content</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            All content on Digital History is for informational and educational purposes. 
            We strive for accuracy but make no guarantees about the completeness or reliability of the content.
        </p>
        
        <h3 style="color: #ffa500; margin-top: 1.5rem; font-size: 1.2rem;">4. User-Generated Content</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            If you submit content to Digital History (comments, etc.), you grant us the right to use, 
            modify, and publish that content. You retain ownership of your content.
        </p>
        
        <h3 style="color: #ff0064; margin-top: 1.5rem; font-size: 1.2rem;">5. Prohibited Activities</h3>
        <ul style="color: #ccd6f6; line-height: 1.8; padding-left: 1.5rem;">
            <li>Attempting to compromise the security of the service</li>
            <li>Distributing malware or malicious code</li>
            <li>Harassing or abusing other users</li>
            <li>Using automated systems to access the service</li>
            <li>Violating any applicable laws or regulations</li>
        </ul>
        
        <h3 style="color: #00d4ff; margin-top: 1.5rem; font-size: 1.2rem;">6. Intellectual Property</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            Digital History and its original content are protected by intellectual property laws. 
            You may not reproduce, distribute, or create derivative works without permission.
        </p>
        
        <h3 style="color: #7b2ffc; margin-top: 1.5rem; font-size: 1.2rem;">7. Limitation of Liability</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            Digital History is provided "as is" without warranties. We are not liable for any damages 
            arising from your use of the service.
        </p>
        
        <h3 style="color: #00ff88; margin-top: 1.5rem; font-size: 1.2rem;">8. Termination</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We reserve the right to suspend or terminate your account for violations of these terms 
            or for any other reason at our discretion.
        </p>
        
        <h3 style="color: #ffa500; margin-top: 1.5rem; font-size: 1.2rem;">9. Changes to Terms</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We may update these terms from time to time. Continued use of the service constitutes 
            acceptance of the updated terms.
        </p>
        
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);">
            <p style="color: #8892b0; font-size: 0.85rem;">
                If you have any questions about these terms, please <a href="contact.php" style="color: #00d4ff;">contact us</a>.
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