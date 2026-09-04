<?php
// era.php
// Digital History - Era Page

require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
$era = null;

if (!empty($slug)) {
    $era = getEra($slug);
}

if (!$era) {
    http_response_code(404);
    include '404.php';
    exit;
}

$pageTitle = $era['name'];
$pageDescription = truncate($era['description'], 160);

$events = getTimelineEvents($era['id']);

trackPageView('era_' . $era['id']);

ob_start();
?>

<!-- Era Hero -->
<section class="section" style="padding-top: 2rem; padding-bottom: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <a href="timeline.php" style="color: #8892b0; text-decoration: none; font-size: 0.85rem;">← Back to Timeline</a>
        </div>
        
        <div>
            <div style="font-family: 'Orbitron', monospace; color: <?php echo $era['color'] ?? '#00d4ff'; ?>; font-size: 1.2rem; margin-bottom: 0.3rem;">
                <?php echo $era['start_year']; ?> - <?php echo $era['end_year'] ?: 'Present'; ?>
            </div>
            <h1 style="font-size: clamp(2rem, 4vw, 3rem); color: #fff; margin-bottom: 0.5rem;">
                <?php echo escape($era['name']); ?>
            </h1>
            <p style="color: #8892b0; font-size: 1.1rem;"><?php echo escape($era['description']); ?></p>
        </div>
    </div>
</section>

<!-- Events -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 1rem;">📅 Events in this Era</h3>
            
            <?php if (empty($events)): ?>
            <p style="color: #8892b0;">No events found for this era.</p>
            <?php else: ?>
            <div style="display: grid; gap: 1rem;">
                <?php foreach ($events as $event): ?>
                <a href="event.php?slug=<?php echo $event['slug']; ?>" style="display: flex; justify-content: space-between; align-items: start; background: rgba(255,255,255,0.02); padding: 1rem 1.2rem; border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.03);">
                    <div style="flex: 1;">
                        <div style="color: #fff; font-weight: 500;"><?php echo escape($event['title']); ?></div>
                        <div style="color: #8892b0; font-size: 0.9rem; margin-top: 0.3rem;"><?php echo escape(truncate($event['description'], 120)); ?></div>
                        <?php if ($event['is_featured']): ?>
                        <span style="font-size: 0.7rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.1rem 0.6rem; border-radius: 12px; margin-top: 0.3rem; display: inline-block;">⭐ Featured</span>
                        <?php endif; ?>
                    </div>
                    <div style="font-family: 'Orbitron', monospace; color: <?php echo $era['color'] ?? '#00d4ff'; ?>; font-size: 0.9rem; margin-left: 1rem; flex-shrink: 0;">
                        <?php echo $event['year']; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>