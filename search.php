<?php
// search.php
// Digital History - Search Results

require_once 'includes/config.php';

$pageTitle = 'Search';
$query = $_GET['q'] ?? '';
$results = [];

if (!empty($query)) {
    $results = search($query);
}

trackPageView('search');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2><i class="fas fa-search"></i> Search Results</h2>
        <?php if (!empty($query)): ?>
        <p>Showing results for: <strong style="color: #00d4ff;">"<?php echo escape($query); ?>"</strong></p>
        <?php else: ?>
        <p>Enter a search term to find technology, people, events, and more.</p>
        <?php endif; ?>
    </div>
    
    <!-- Search Form -->
    <div style="max-width: 600px; margin: 1.5rem auto 0;">
        <form method="GET" action="" style="display: flex; gap: 0.5rem;">
            <input type="text" name="q" placeholder="Search technology, people, events..." value="<?php echo escape($query); ?>" style="flex: 1; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button type="submit" style="padding: 0.8rem 1.5rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Search
            </button>
        </form>
    </div>
</section>

<!-- Results -->
<section class="section" style="padding-top: 1rem;">
    <?php if (!empty($query)): ?>
        <?php 
        $totalResults = count($results['events']) + count($results['people']) + count($results['technologies']) + 
                        count($results['languages']) + count($results['articles']) + count($results['os']);
        ?>
        
        <?php if ($totalResults === 0): ?>
        <div style="text-align: center; padding: 3rem 0;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
            <h3 style="color: #fff; margin-bottom: 0.5rem;">No results found</h3>
            <p style="color: #8892b0;">Try adjusting your search terms or browse our categories.</p>
            <div style="margin-top: 1.5rem; display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
                <a href="timeline.php" class="btn btn-outline">Explore Timeline</a>
                <a href="museum.php" class="btn btn-outline">Visit Museum</a>
                <a href="people.php" class="btn btn-outline">Browse People</a>
            </div>
        </div>
        <?php else: ?>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Found <strong style="color: #fff;"><?php echo $totalResults; ?></strong> results</p>
        
        <!-- Events -->
        <?php if (!empty($results['events'])): ?>
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 1rem; font-size: 1.2rem;">
                <i class="fas fa-timeline"></i> Events (<?php echo count($results['events']); ?>)
            </h3>
            <?php foreach ($results['events'] as $event): ?>
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; margin-bottom: 0.8rem; transition: all 0.3s; hover:background: rgba(255,255,255,0.06);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <a href="event.php?slug=<?php echo $event['slug']; ?>" style="font-weight: 600; color: #fff; text-decoration: none; font-size: 1.05rem;">
                        <?php echo escape($event['title']); ?>
                    </a>
                    <span style="font-family: 'Orbitron', monospace; color: #00d4ff; font-size: 0.85rem;"><?php echo $event['year']; ?></span>
                </div>
                <p style="color: #8892b0; font-size: 0.9rem; margin-top: 0.3rem;"><?php echo escape(truncate($event['description'], 150)); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- People -->
        <?php if (!empty($results['people'])): ?>
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #7b2ffc; margin-bottom: 1rem; font-size: 1.2rem;">
                <i class="fas fa-users"></i> People (<?php echo count($results['people']); ?>)
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.8rem;">
                <?php foreach ($results['people'] as $person): ?>
                <a href="person.php?slug=<?php echo $person['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; text-decoration: none; color: inherit; transition: all 0.3s; text-align: center;">
                    <div style="font-size: 2.5rem;">👤</div>
                    <div style="font-weight: 600; color: #fff;"><?php echo escape($person['full_name']); ?></div>
                    <div style="color: #8892b0; font-size: 0.8rem;"><?php echo escape($person['known_for']); ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Technologies -->
        <?php if (!empty($results['technologies'])): ?>
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #00ff88; margin-bottom: 1rem; font-size: 1.2rem;">
                <i class="fas fa-microchip"></i> Technologies (<?php echo count($results['technologies']); ?>)
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.8rem;">
                <?php foreach ($results['technologies'] as $tech): ?>
                <a href="technology.php?slug=<?php echo $tech['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; text-decoration: none; color: inherit; transition: all 0.3s;">
                    <div style="font-weight: 600; color: #fff;"><?php echo escape($tech['name']); ?></div>
                    <div style="color: #8892b0; font-size: 0.8rem;"><?php echo escape($tech['category'] ?? 'Technology'); ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Programming Languages -->
        <?php if (!empty($results['languages'])): ?>
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #ffa500; margin-bottom: 1rem; font-size: 1.2rem;">
                <i class="fas fa-code"></i> Programming Languages (<?php echo count($results['languages']); ?>)
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.8rem;">
                <?php foreach ($results['languages'] as $lang): ?>
                <a href="language.php?slug=<?php echo $lang['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; text-decoration: none; color: inherit; transition: all 0.3s; text-align: center;">
                    <div style="font-weight: 600; color: #fff;"><?php echo escape($lang['name']); ?></div>
                    <div style="color: #8892b0; font-size: 0.8rem;">Created: <?php echo $lang['year_created'] ?? 'N/A'; ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Articles -->
        <?php if (!empty($results['articles'])): ?>
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #ff0064; margin-bottom: 1rem; font-size: 1.2rem;">
                <i class="fas fa-newspaper"></i> Articles (<?php echo count($results['articles']); ?>)
            </h3>
            <?php foreach ($results['articles'] as $article): ?>
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; margin-bottom: 0.8rem;">
                <a href="article.php?slug=<?php echo $article['slug']; ?>" style="font-weight: 600; color: #fff; text-decoration: none; font-size: 1.05rem;">
                    <?php echo escape($article['title']); ?>
                </a>
                <p style="color: #8892b0; font-size: 0.9rem; margin-top: 0.3rem;"><?php echo escape(truncate($article['excerpt'] ?? $article['content'], 150)); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- Operating Systems -->
        <?php if (!empty($results['os'])): ?>
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 1rem; font-size: 1.2rem;">
                <i class="fas fa-desktop"></i> Operating Systems (<?php echo count($results['os']); ?>)
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.8rem;">
                <?php foreach ($results['os'] as $os): ?>
                <a href="os.php?slug=<?php echo $os['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; text-decoration: none; color: inherit; transition: all 0.3s; text-align: center;">
                    <div style="font-weight: 600; color: #fff;"><?php echo escape($os['name']); ?></div>
                    <div style="color: #8892b0; font-size: 0.8rem;"><?php echo escape($os['company']); ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php endif; ?>
    <?php else: ?>
    <div style="text-align: center; padding: 4rem 0;">
        <div style="font-size: 5rem; margin-bottom: 1rem;">🔎</div>
        <h3 style="color: #fff; margin-bottom: 0.5rem;">Search Digital History</h3>
        <p style="color: #8892b0; max-width: 500px; margin: 0 auto;">
            Find information about computing, programming, internet, people, and technologies.
        </p>
        <div style="margin-top: 2rem; display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
            <a href="timeline.php" class="btn btn-outline">Explore Timeline</a>
            <a href="people.php" class="btn btn-outline">Browse People</a>
            <a href="lab.php" class="btn btn-outline">Digital Lab</a>
        </div>
    </div>
    <?php endif; ?>
</section>

<?php
$pageContent = ob_get_clean();
$pageJS = '
// Auto-focus search input if query is empty
document.addEventListener("DOMContentLoaded", function() {
    const query = "' . addslashes($query) . '";
    if (!query) {
        const input = document.querySelector("input[name=\'q\']");
        if (input) input.focus();
    }
});
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>