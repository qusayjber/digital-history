<?php
// person.php
// Digital History - Single Person Page

require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
$person = null;

if (!empty($slug)) {
    $person = getPerson($slug);
}

if (!$person) {
    http_response_code(404);
    include '404.php';
    exit;
}

$pageTitle = $person['full_name'];
$pageDescription = truncate($person['biography'], 160);

$events = getPersonEvents($person['id']);

trackPageView('person_' . $person['id']);

ob_start();
?>

<!-- Person Hero -->
<section class="section" style="padding-top: 2rem; padding-bottom: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <a href="people.php" style="color: #8892b0; text-decoration: none; font-size: 0.85rem;">← Back to People</a>
        </div>
        
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div style="font-size: 6rem; flex-shrink: 0;">
                <?php 
                if ($person['portrait'] && file_exists(UPLOADS_PATH . $person['portrait'])) {
                    echo '<img src="' . UPLOADS_URL . $person['portrait'] . '" alt="' . escape($person['full_name']) . '" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #00d4ff;">';
                } else {
                    echo '👤';
                }
                ?>
            </div>
            <div>
                <h1 style="font-size: clamp(2rem, 4vw, 3rem); color: #fff; margin-bottom: 0.3rem;"><?php echo escape($person['full_name']); ?></h1>
                <div style="color: #00d4ff; font-size: 1.1rem; margin-bottom: 0.3rem;"><?php echo escape($person['known_for']); ?></div>
                <div style="color: #8892b0;">
                    <?php 
                    $years = [];
                    if ($person['birth_year']) $years[] = $person['birth_year'];
                    if ($person['death_year']) $years[] = $person['death_year'];
                    echo implode(' - ', $years);
                    if ($person['nationality']) echo ' · ' . escape($person['nationality']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Person Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Biography -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 0.5rem;">📖 Biography</h3>
            <p style="color: #ccd6f6; line-height: 1.8; font-size: 1.05rem;">
                <?php echo nl2br(escape($person['biography'])); ?>
            </p>
            
            <?php if ($person['contributions']): ?>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);">
                <h4 style="color: #7b2ffc; margin-bottom: 0.5rem;">🏆 Key Contributions</h4>
                <p style="color: #8892b0; line-height: 1.8;"><?php echo nl2br(escape($person['contributions'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Related Events -->
        <?php if (!empty($events)): ?>
        <div style="margin-top: 2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #00ff88; margin-bottom: 1rem;">📅 Related Events</h3>
            <div style="display: grid; gap: 0.8rem;">
                <?php foreach ($events as $event): ?>
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
            <a href="people.php" style="color: #8892b0; text-decoration: none; transition: color 0.3s;">
                <i class="fas fa-arrow-left"></i> Back to People
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