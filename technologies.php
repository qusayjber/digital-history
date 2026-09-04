<?php
// technologies.php
// Digital History - Technologies Directory

require_once 'includes/config.php';

$pageTitle = 'Technology Encyclopedia';
$pageDescription = 'Browse the complete encyclopedia of technologies that shaped the digital world.';

$technologies = getTechnologies();
$categories = [];

// Group technologies by category
foreach ($technologies as $tech) {
    $cat = $tech['category'] ?? 'Other';
    if (!isset($categories[$cat])) {
        $categories[$cat] = [];
    }
    $categories[$cat][] = $tech;
}

trackPageView('technologies');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>🔧 Technology Encyclopedia</h2>
        <p>Browse the complete encyclopedia of technologies that shaped the digital world.</p>
    </div>
</section>

<!-- Categories Navigation -->
<section class="section" style="padding-top: 0;">
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; max-width: 900px; margin: 0 auto;">
        <?php foreach (array_keys($categories) as $cat): ?>
        <a href="#cat-<?php echo createSlug($cat); ?>" style="padding: 0.4rem 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; color: #8892b0; text-decoration: none; font-size: 0.85rem; transition: all 0.3s;">
            <?php echo escape($cat); ?>
            <span style="font-size: 0.7rem; color: #00d4ff;">(<?php echo count($categories[$cat]); ?>)</span>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Technologies by Category -->
<section class="section" style="padding-top: 0;">
    <?php foreach ($categories as $category => $techs): ?>
    <div id="cat-<?php echo createSlug($category); ?>" style="margin-bottom: 2rem;">
        <h3 style="color: #00d4ff; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.5rem;">
            <?php echo escape($category); ?>
            <span style="font-size: 0.8rem; color: #8892b0; font-weight: normal;">(<?php echo count($techs); ?>)</span>
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
            <?php foreach ($techs as $tech): ?>
            <a href="technology.php?slug=<?php echo $tech['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.2rem; text-decoration: none; color: inherit; transition: all 0.3s;">
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <span style="font-size: 1.5rem;"><?php echo $tech['icon'] ?? '🔧'; ?></span>
                    <div>
                        <div style="color: #fff; font-weight: 500;"><?php echo escape($tech['name']); ?></div>
                        <?php if ($tech['year_introduced']): ?>
                        <div style="color: #8892b0; font-size: 0.75rem;"><?php echo $tech['year_introduced']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div style="color: #8892b0; font-size: 0.8rem; margin-top: 0.3rem;"><?php echo escape(truncate($tech['description'], 80)); ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<!-- Quick Stats -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; max-width: 800px; margin: 0 auto;">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($technologies); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Total Technologies</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($categories); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Categories</div>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
$pageJS = '
// Smooth scroll to categories
document.querySelectorAll("[href^=\"#cat-\"]").forEach(link => {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "start" });
            target.style.background = "rgba(0, 212, 255, 0.05)";
            target.style.borderRadius = "8px";
            setTimeout(() => {
                target.style.background = "transparent";
            }, 2000);
        }
    });
});
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>