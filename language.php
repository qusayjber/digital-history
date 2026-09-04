<?php
// language.php
// Digital History - Single Programming Language Page

require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
$language = null;

if (!empty($slug)) {
    $language = getProgrammingLanguage($slug);
}

if (!$language) {
    http_response_code(404);
    include '404.php';
    exit;
}

$pageTitle = $language['name'];
$pageDescription = truncate($language['description'], 160);

trackPageView('language_' . $language['id']);

ob_start();
?>

<!-- Language Hero -->
<section class="section" style="padding-top: 2rem; padding-bottom: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <a href="programming.php" style="color: #8892b0; text-decoration: none; font-size: 0.85rem;">← Back to Programming Languages</a>
        </div>
        
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div style="font-size: 4rem; flex-shrink: 0;">
                <?php 
                if ($language['logo'] && file_exists(UPLOADS_PATH . $language['logo'])) {
                    echo '<img src="' . UPLOADS_URL . $language['logo'] . '" alt="' . escape($language['name']) . '" style="width: 100px; height: 100px; border-radius: 12px; object-fit: contain; background: rgba(255,255,255,0.05); padding: 0.5rem;">';
                } else {
                    echo '💻';
                }
                ?>
            </div>
            <div>
                <h1 style="font-size: clamp(2rem, 4vw, 3rem); color: #fff; margin-bottom: 0.3rem;"><?php echo escape($language['name']); ?></h1>
                <div style="color: #00d4ff; font-size: 1rem; margin-bottom: 0.3rem;">
                    <?php echo escape($language['paradigm']); ?>
                    <?php if ($language['typing_discipline']): ?>
                    · <?php echo escape($language['typing_discipline']); ?>
                    <?php endif; ?>
                </div>
                <div style="color: #8892b0;">
                    Created: <?php echo $language['year_created']; ?> by <?php echo escape($language['creator']); ?>
                </div>
                <div style="margin-top: 0.3rem;">
                    <span style="background: <?php echo $language['popularity_score'] > 80 ? 'rgba(0,212,255,0.1)' : 'rgba(255,255,255,0.05)'; ?>; color: <?php echo $language['popularity_score'] > 80 ? '#00d4ff' : '#8892b0'; ?>; padding: 0.2rem 0.8rem; border-radius: 12px; font-size: 0.8rem;">
                        Popularity: <?php echo $language['popularity_score']; ?>%
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Language Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Description -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 0.5rem;">📖 About <?php echo escape($language['name']); ?></h3>
            <p style="color: #ccd6f6; line-height: 1.8; font-size: 1.05rem;">
                <?php echo nl2br(escape($language['description'])); ?>
            </p>
            
            <?php if ($language['use_cases']): ?>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);">
                <h4 style="color: #7b2ffc; margin-bottom: 0.5rem;">🎯 Use Cases</h4>
                <p style="color: #8892b0; line-height: 1.8;"><?php echo nl2br(escape($language['use_cases'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Code Example -->
        <?php if ($language['code_example']): ?>
        <div style="margin-top: 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00ff88; margin-bottom: 0.5rem;">💻 Code Example</h3>
            <pre style="background: rgba(0,0,0,0.4); padding: 1.5rem; border-radius: 8px; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 0.9rem; color: #ccd6f6; border: 1px solid rgba(255,255,255,0.05); margin: 0;">
<?php echo escape($language['code_example']); ?>
            </pre>
            <button onclick="navigator.clipboard.writeText(document.querySelector('pre').textContent)" style="margin-top: 1rem; padding: 0.4rem 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; color: #8892b0; cursor: pointer; transition: all 0.3s;">
                <i class="fas fa-copy"></i> Copy Code
            </button>
        </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div style="margin-top: 2rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <a href="programming.php" style="color: #8892b0; text-decoration: none; transition: color 0.3s;">
                <i class="fas fa-arrow-left"></i> Back to Languages
            </a>
            <a href="#" onclick="window.print(); return false;" style="color: #8892b0; text-decoration: none; transition: color 0.3s;">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
$pageCSS = '
@media print {
    .site-header, .site-footer {
        display: none !important;
    }
    .section {
        padding: 0 !important;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>