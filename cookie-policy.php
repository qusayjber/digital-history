<?php
// cookie-policy.php
// Digital History - Cookie Policy

require_once 'includes/config.php';

$pageTitle = 'Cookie Policy';
$pageDescription = 'Cookie policy for Digital History.';

trackPageView('cookie-policy');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>🍪 Cookie Policy</h2>
        <p>How we use cookies to improve your experience.</p>
    </div>
</section>

<!-- Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Last updated: <?php echo date('F d, Y'); ?></p>
        
        <h3 style="color: #00d4ff; margin-top: 1.5rem; font-size: 1.2rem;">What Are Cookies?</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            Cookies are small text files that are stored on your device when you visit a website. 
            They help us provide you with a better experience by remembering your preferences and understanding how you use our site.
        </p>
        
        <h3 style="color: #7b2ffc; margin-top: 1.5rem; font-size: 1.2rem;">How We Use Cookies</h3>
        <ul style="color: #ccd6f6; line-height: 1.8; padding-left: 1.5rem;">
            <li><strong>Essential Cookies:</strong> Required for the website to function properly (login, session management).</li>
            <li><strong>Preference Cookies:</strong> Remember your language preferences and settings.</li>
            <li><strong>Analytics Cookies:</strong> Help us understand how visitors interact with our site.</li>
            <li><strong>Performance Cookies:</strong> Optimize the website's performance and loading speed.</li>
        </ul>
        
        <h3 style="color: #00ff88; margin-top: 1.5rem; font-size: 1.2rem;">Types of Cookies We Use</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 0.5rem;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <th style="padding: 0.5rem; text-align: left; color: #8892b0; font-size: 0.85rem;">Cookie Name</th>
                        <th style="padding: 0.5rem; text-align: left; color: #8892b0; font-size: 0.85rem;">Purpose</th>
                        <th style="padding: 0.5rem; text-align: left; color: #8892b0; font-size: 0.85rem;">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 0.5rem; color: #ccd6f6; font-size: 0.85rem;">session_id</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">Maintains user session</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">Session</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 0.5rem; color: #ccd6f6; font-size: 0.85rem;">language</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">Remember language preference</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">1 year</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 0.5rem; color: #ccd6f6; font-size: 0.85rem;">remember_token</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">Remember me functionality</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">30 days</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem; color: #ccd6f6; font-size: 0.85rem;">_ga</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">Google Analytics tracking</td>
                        <td style="padding: 0.5rem; color: #8892b0; font-size: 0.85rem;">2 years</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <h3 style="color: #ffa500; margin-top: 1.5rem; font-size: 1.2rem;">Managing Cookies</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            You can control and manage cookies in your browser settings. Most browsers allow you to:
        </p>
        <ul style="color: #ccd6f6; line-height: 1.8; padding-left: 1.5rem;">
            <li>View cookies stored on your device</li>
            <li>Delete individual or all cookies</li>
            <li>Block cookies from specific websites</li>
            <li>Set your browser to reject all cookies</li>
        </ul>
        <p style="color: #8892b0; font-size: 0.9rem; margin-top: 0.5rem;">
            Please note that disabling cookies may affect the functionality of our website.
        </p>
        
        <h3 style="color: #ff0064; margin-top: 1.5rem; font-size: 1.2rem;">Third-Party Cookies</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            We use Google Analytics to understand how visitors interact with our site. 
            Google Analytics uses cookies to collect anonymous information about your browsing behavior.
        </p>
        
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);">
            <p style="color: #8892b0; font-size: 0.85rem;">
                If you have any questions about our cookie policy, please <a href="contact.php" style="color: #00d4ff;">contact us</a>.
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