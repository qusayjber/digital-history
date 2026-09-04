<?php
// 404.php
// Digital History - 404 Page

require_once 'includes/config.php';

http_response_code(404);
$pageTitle = 'Page Not Found';

trackPageView('404');

ob_start();
?>

<div class="error-page">
    <div>
        <div class="error-code">404</div>
        <h1 class="error-title"><?php echo t('404.title'); ?></h1>
        <p class="error-description"><?php echo t('404.description'); ?></p>
        
        <!-- Network animation -->
        <div style="margin: 2rem auto; max-width: 400px; position: relative; height: 100px;">
            <div style="position: absolute; top: 50%; left: 0; right: 0; height: 2px; background: rgba(255,255,255,0.05);"></div>
            <?php for ($i = 0; $i < 8; $i++): ?>
            <div style="position: absolute; top: <?php echo 10 + ($i % 3) * 30; ?>%; left: <?php echo ($i / 7) * 100; ?>%; width: 8px; height: 8px; border-radius: 50%; background: <?php echo $i % 2 === 0 ? '#00d4ff' : '#7b2ffc'; ?>; box-shadow: 0 0 20px <?php echo $i % 2 === 0 ? 'rgba(0, 212, 255, 0.4)' : 'rgba(123, 47, 252, 0.4)'; ?>; animation: pulse 2s ease-in-out <?php echo $i * 0.2; ?>s infinite;"></div>
            <?php endfor; ?>
        </div>
        
        <div class="error-buttons">
            <a href="<?php echo SITE_URL; ?>" class="btn btn-primary"><?php echo t('404.home'); ?></a>
            <a href="timeline.php" class="btn btn-outline"><?php echo t('404.timeline'); ?></a>
        </div>
    </div>
</div>

<style>
.error-page .error-code {
    font-family: 'Orbitron', monospace;
    font-size: clamp(6rem, 15vw, 12rem);
    font-weight: 900;
    background: linear-gradient(135deg, #00d4ff, #7b2ffc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.error-page .error-title {
    font-size: clamp(1.5rem, 3vw, 2.5rem);
    margin: 1rem 0;
}

.error-page .error-description {
    color: #8892b0;
    max-width: 500px;
    margin: 0 auto 2rem;
}

.error-page .error-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
</style>

<?php
$pageContent = ob_get_clean();
require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>