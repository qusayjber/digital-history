<?php
// disclaimer.php
// Digital History - Disclaimer

require_once 'includes/config.php';

$pageTitle = 'Disclaimer';
$pageDescription = 'Disclaimer for Digital History.';

trackPageView('disclaimer');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>⚖️ Disclaimer</h2>
        <p>Important legal information about Digital History.</p>
    </div>
</section>

<!-- Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Last updated: <?php echo date('F d, Y'); ?></p>
        
        <h3 style="color: #00d4ff; margin-top: 1.5rem; font-size: 1.2rem;">Educational Purpose</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            Digital History is an educational platform designed to inform and inspire. 
            The content provided is for informational and entertainment purposes only.
        </p>
        
        <h3 style="color: #7b2ffc; margin-top: 1.5rem; font-size: 1.2rem;">Accuracy of Information</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            While we strive for accuracy and completeness, we cannot guarantee that all information 
            on this site is entirely accurate, up-to-date, or error-free. History is complex, 
            and interpretations may vary.
        </p>
        
        <h3 style="color: #00ff88; margin-top: 1.5rem; font-size: 1.2rem;">External Links</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            Digital History may contain links to external websites. We are not responsible for 
            the content, accuracy, or practices of these external sites. Inclusion of a link 
            does not imply endorsement.
        </p>
        
        <h3 style="color: #ffa500; margin-top: 1.5rem; font-size: 1.2rem;">User-Generated Content</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            Users may submit content (comments, feedback, etc.) to Digital History. We reserve 
            the right to moderate, edit, or remove any user-generated content that violates 
            our terms of service or community guidelines.
        </p>
        
        <h3 style="color: #ff0064; margin-top: 1.5rem; font-size: 1.2rem;">Copyright</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            All original content on Digital History is protected by copyright. 
            You may not reproduce, distribute, or modify our content without permission.
        </p>
        
        <h3 style="color: #00d4ff; margin-top: 1.5rem; font-size: 1.2rem;">Future Predictions</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            Predictions about future technologies are speculative and based on current trends 
            and expert opinions. They are not guarantees of future outcomes.
        </p>
        
        <h3 style="color: #7b2ffc; margin-top: 1.5rem; font-size: 1.2rem;">No Professional Advice</h3>
        <p style="color: #ccd6f6; line-height: 1.8;">
            The information on Digital History is not intended as professional advice. 
            For specific technical, legal, or other professional matters, please consult 
            a qualified professional.
        </p>
        
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);">
            <p style="color: #8892b0; font-size: 0.85rem;">
                If you have any questions about this disclaimer, please <a href="contact.php" style="color: #00d4ff;">contact us</a>.
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