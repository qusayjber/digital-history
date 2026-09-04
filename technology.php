<?php
// technology.php
// Digital History - Single Technology Page

require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
$technology = null;

if (!empty($slug)) {
    $technology = getTechnology($slug);
}

if (!$technology) {
    http_response_code(404);
    include '404.php';
    exit;
}

$pageTitle = $technology['name'];
$pageDescription = truncate($technology['description'], 160);

// Get related events
$relatedEvents = db()->fetchAll(
    "SELECT e.* FROM timeline_events e 
     JOIN technology_events te ON e.id = te.event_id 
     WHERE te.technology_id = ? AND e.is_active = 1 
     ORDER BY e.year",
    [$technology['id']]
);

trackPageView('technology_' . $technology['id']);

ob_start();
?>

<!-- Technology Hero -->
<section class="section" style="padding-top: 2rem; padding-bottom: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <a href="technologies.php" style="color: #8892b0; text-decoration: none; font-size: 0.85rem;">← Back to Technologies</a>
        </div>
        
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div style="font-size: 4rem; flex-shrink: 0;">
                <?php 
                if ($technology['icon']) {
                    echo $technology['icon'];
                } elseif ($technology['image'] && file_exists(UPLOADS_PATH . $technology['image'])) {
                    echo '<img src="' . UPLOADS_URL . $technology['image'] . '" alt="' . escape($technology['name']) . '" style="width: 120px; height: 120px; border-radius: 12px; object-fit: cover; border: 2px solid #00d4ff;">';
                } else {
                    echo '🔧';
                }
                ?>
            </div>
            <div>
                <h1 style="font-size: clamp(2rem, 4vw, 3rem); color: #fff; margin-bottom: 0.3rem;"><?php echo escape($technology['name']); ?></h1>
                <div style="color: #00d4ff; font-size: 1rem; margin-bottom: 0.3rem;">
                    <?php echo escape($technology['category']); ?>
                    <?php if ($technology['year_introduced']): ?>
                    · Introduced: <?php echo $technology['year_introduced']; ?>
                    <?php endif; ?>
                </div>
                <?php if ($technology['inventor']): ?>
                <div style="color: #8892b0;">Inventor: <?php echo escape($technology['inventor']); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Technology Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Description -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 0.5rem;">📖 About This Technology</h3>
            <p style="color: #ccd6f6; line-height: 1.8; font-size: 1.05rem;">
                <?php echo nl2br(escape($technology['description'])); ?>
            </p>
        </div>
        
        <!-- Related Events -->
        <?php if (!empty($relatedEvents)): ?>
        <div style="margin-top: 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #7b2ffc; margin-bottom: 1rem;">📅 Related Events</h3>
            <div style="display: grid; gap: 0.8rem;">
                <?php foreach ($relatedEvents as $event): ?>
                <a href="event.php?slug=<?php echo $event['slug']; ?>" style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); padding: 0.8rem 1.2rem; border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.03);">
                    <div>
                        <div style="color: #fff;"><?php echo escape($event['title']); ?></div>
                        <div style="color: #8892b0; font-size: 0.85rem;"><?php echo escape(truncate($event['description'], 80)); ?></div>
                    </div>
                    <div style="font-family: 'Orbitron', monospace; color: #00d4ff; font-size: 0.9rem;"><?php echo $event['year']; ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div style="margin-top: 2rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <a href="technologies.php" style="color: #8892b0; text-decoration: none; transition: color 0.3s;">
                <i class="fas fa-arrow-left"></i> Back to Technologies
            </a>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>