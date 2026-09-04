<?php
// event.php
// Digital History - Single Event Page

require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
$event = null;

if (!empty($slug)) {
    $event = db()->fetch(
        "SELECT e.*, er.name as era_name, er.name_ar as era_name_ar 
         FROM timeline_events e 
         LEFT JOIN eras er ON e.era_id = er.id 
         WHERE e.slug = ? AND e.is_active = 1",
        [$slug]
    );
}

if (!$event) {
    http_response_code(404);
    include '404.php';
    exit;
}

$pageTitle = $event['title'];
$pageDescription = truncate($event['description'], 160);

// Get related people
$people = db()->fetchAll(
    "SELECT p.* FROM people p 
     JOIN person_events pe ON p.id = pe.person_id 
     WHERE pe.event_id = ? AND p.is_active = 1",
    [$event['id']]
);

// Get related technologies
$technologies = db()->fetchAll(
    "SELECT t.* FROM technologies t 
     JOIN technology_events te ON t.id = te.technology_id 
     WHERE te.event_id = ? AND t.is_active = 1",
    [$event['id']]
);

// Get related events (same era)
$relatedEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE era_id = ? AND id != ? AND is_active = 1 
     ORDER BY year LIMIT 5",
    [$event['era_id'], $event['id']]
);

trackPageView('event_' . $event['id']);

ob_start();
?>

<!-- Event Hero -->
<section class="section" style="padding-top: 2rem; padding-bottom: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <a href="timeline.php" style="color: #8892b0; text-decoration: none; font-size: 0.85rem;">← Back to Timeline</a>
            <?php if ($event['era_name']): ?>
            <span style="color: #8892b0; font-size: 0.85rem;">/</span>
            <a href="era.php?slug=<?php echo $event['era_slug'] ?? ''; ?>" style="color: #00d4ff; text-decoration: none; font-size: 0.85rem;">
                <?php echo escape($event['era_name']); ?>
            </a>
            <?php endif; ?>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-family: 'Orbitron', monospace; color: #00d4ff; font-size: 1.2rem; margin-bottom: 0.5rem;">
                    <?php echo $event['year']; ?>
                    <?php if ($event['year_end']): ?>
                    - <?php echo $event['year_end']; ?>
                    <?php endif; ?>
                </div>
                <h1 style="font-size: clamp(2rem, 4vw, 3rem); color: #fff; margin-bottom: 0.5rem;"><?php echo escape($event['title']); ?></h1>
            </div>
            <?php if ($event['is_featured']): ?>
            <span style="background: rgba(0,212,255,0.1); color: #00d4ff; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(0,212,255,0.2);">
                ⭐ Featured
            </span>
            <?php endif; ?>
        </div>
        
        <?php if ($event['image']): ?>
        <div style="margin-top: 1.5rem; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.06);">
            <img src="<?php echo UPLOADS_URL . $event['image']; ?>" alt="<?php echo escape($event['title']); ?>" style="width: 100%; max-height: 400px; object-fit: cover;">
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Event Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 0.5rem;">📖 About This Event</h3>
            <p style="color: #ccd6f6; line-height: 1.8; font-size: 1.05rem;">
                <?php echo nl2br(escape($event['description'])); ?>
            </p>
            
            <?php if ($event['significance']): ?>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);">
                <h4 style="color: #7b2ffc; margin-bottom: 0.5rem;">🌟 Significance</h4>
                <p style="color: #8892b0; line-height: 1.8;"><?php echo nl2br(escape($event['significance'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Related People -->
        <?php if (!empty($people)): ?>
        <div style="margin-top: 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00ff88; margin-bottom: 1rem;">👤 Related People</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
                <?php foreach ($people as $person): ?>
                <a href="person.php?slug=<?php echo $person['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: inherit; transition: all 0.3s;">
                    <div style="font-size: 2.5rem;">👤</div>
                    <div style="color: #fff; font-weight: 500;"><?php echo escape($person['full_name']); ?></div>
                    <div style="color: #8892b0; font-size: 0.8rem;"><?php echo escape($person['known_for']); ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Related Technologies -->
        <?php if (!empty($technologies)): ?>
        <div style="margin-top: 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #ffa500; margin-bottom: 1rem;">🔧 Related Technologies</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <?php foreach ($technologies as $tech): ?>
                <a href="technology.php?slug=<?php echo $tech['slug']; ?>" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 0.3rem 1rem; color: #ccd6f6; text-decoration: none; font-size: 0.85rem; transition: all 0.3s;">
                    <?php echo escape($tech['name']); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Related Events -->
        <?php if (!empty($relatedEvents)): ?>
        <div style="margin-top: 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #7b2ffc; margin-bottom: 1rem;">📅 Related Events</h3>
            <div style="display: grid; gap: 0.8rem;">
                <?php foreach ($relatedEvents as $rel): ?>
                <a href="event.php?slug=<?php echo $rel['slug']; ?>" style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); padding: 0.8rem 1.2rem; border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.03);">
                    <div>
                        <div style="color: #fff;"><?php echo escape($rel['title']); ?></div>
                        <div style="color: #8892b0; font-size: 0.85rem;"><?php echo escape(truncate($rel['description'], 80)); ?></div>
                    </div>
                    <div style="font-family: 'Orbitron', monospace; color: #00d4ff; font-size: 0.9rem;"><?php echo $rel['year']; ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div style="margin-top: 2rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <a href="timeline.php" style="color: #8892b0; text-decoration: none; transition: color 0.3s;">
                <i class="fas fa-arrow-left"></i> Back to Timeline
            </a>
            <a href="#" onclick="window.print(); return false;" style="color: #8892b0; text-decoration: none; transition: color 0.3s;">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
$pageJS = '';
$pageCSS = '
@media print {
    .site-header, .site-footer, .hero-scroll, .breadcrumbs {
        display: none !important;
    }
    .site-main {
        margin-top: 0 !important;
    }
    body {
        background: #fff !important;
        color: #000 !important;
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