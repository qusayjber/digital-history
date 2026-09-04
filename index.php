<?php
// index.php
// Digital History - Homepage (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'Home';
$pageDescription = 'The interactive museum of computing, programming, the internet and the future.';

// Get featured events for the homepage
$featuredEvents = getFeaturedEvents(6);
$eras = getEras();
$statistics = getStatistics();

// Get latest articles
$latestArticles = getArticles(null, 3);

// Get random people
$allPeople = getPeople();
shuffle($allPeople);
$randomPeople = array_slice($allPeople, 0, 4);

// Get programming languages for showcase
$languages = getProgrammingLanguages();
usort($languages, function($a, $b) {
    return $b['popularity_score'] - $a['popularity_score'];
});
$topLanguages = array_slice($languages, 0, 6);

// Track page view
trackPageView('home');

ob_start();
?>

<!-- ====================================================== -->
<!-- HERO SECTION -->
<!-- ====================================================== -->
<section class="hero" id="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <h1><?php echo t('hero.title'); ?></h1>
        <p class="subtitle"><?php echo t('hero.subtitle'); ?></p>
        <p class="description"><?php echo t('hero.description'); ?></p>
        <div class="hero-buttons">
            <a href="timeline.php" class="btn btn-hero-primary"><?php echo t('hero.start'); ?></a>
            <a href="timeline.php" class="btn btn-hero-secondary"><?php echo t('hero.explore'); ?></a>
        </div>
    </div>
    <div class="hero-scroll">
        <span><?php echo t('hero.scroll'); ?></span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- ====================================================== -->
<!-- STATISTICS BAR -->
<!-- ====================================================== -->
<section class="section stats-section" style="padding: 2rem 1rem; text-align: center;">
    <div class="stats-bar">
        <div class="stat-item">
            <div class="counter" data-target="<?php echo $statistics['total_events'] ?: 50; ?>" data-duration="2000">0</div>
            <p>Historical Events</p>
        </div>
        <div class="stat-item">
            <div class="counter" data-target="<?php echo $statistics['total_people'] ?: 30; ?>" data-duration="2000">0</div>
            <p>Famous People</p>
        </div>
        <div class="stat-item">
            <div class="counter" data-target="<?php echo $statistics['total_languages'] ?: 20; ?>" data-duration="2000">0</div>
            <p>Programming Languages</p>
        </div>
        <div class="stat-item">
            <div class="counter" data-target="<?php echo $statistics['total_technologies'] ?: 25; ?>" data-duration="2000">0</div>
            <p>Technologies</p>
        </div>
        <div class="stat-item">
            <div class="counter" data-target="<?php echo $statistics['total_articles'] ?: 15; ?>" data-duration="2000">0</div>
            <p>Articles</p>
        </div>
    </div>
</section>

<!-- ====================================================== -->
<!-- FEATURED EVENTS -->
<!-- ====================================================== -->
<section class="section">
    <div class="section-header">
        <h2 style="color: #00d4ff;">⭐ Major Milestones</h2>
        <p style="color: #8892b0;">Key events that shaped the digital world</p>
    </div>
    <div class="card-grid">
        <?php if (empty($featuredEvents)): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #8892b0;">
            <p>No featured events found. Check back later!</p>
        </div>
        <?php else: ?>
        <?php foreach ($featuredEvents as $event): ?>
        <div class="card animate-on-scroll">
            <?php if ($event['image']): ?>
            <div style="margin: -1.5rem -1.5rem 1rem -1.5rem; overflow: hidden; border-radius: 12px 12px 0 0; position: relative;">
                <img src="<?php echo UPLOADS_URL . $event['image']; ?>" alt="<?php echo escape($event['title']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <div style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(0,212,255,0.1); color: #00d4ff; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(0,212,255,0.2); font-size: 0.6rem;">Featured</div>
            </div>
            <?php endif; ?>
            <div class="card-icon">📅</div>
            <div class="card-title"><?php echo escape($event['title']); ?></div>
            <div style="color: #00d4ff; font-family: 'Orbitron', monospace; font-size: 0.9rem; margin-bottom: 0.5rem;">
                <?php echo $event['year']; ?>
                <?php if ($event['year_end']): ?>
                - <?php echo $event['year_end']; ?>
                <?php endif; ?>
            </div>
            <div class="card-description"><?php echo escape(truncate($event['description'], 120)); ?></div>
            <a href="event.php?slug=<?php echo $event['slug']; ?>" style="display: inline-block; margin-top: 0.8rem; padding: 0.3rem 1.2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border-radius: 6px; color: #fff; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                Learn More →
            </a>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a href="timeline.php" style="color: #00d4ff; text-decoration: none; font-weight: 500; transition: all 0.3s;">
            View Full Timeline <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- ====================================================== -->
<!-- ERA TIMELINE PREVIEW -->
<!-- ====================================================== -->
<section class="section" style="background: rgba(255, 255, 255, 0.02); border-top: 1px solid rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">🚀 Journey Through Time</h2>
        <p style="color: #8892b0;">Explore the eras of technology history</p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; max-width: 1200px; margin: 0 auto;">
        <?php foreach ($eras as $era): ?>
        <a href="era.php?slug=<?php echo $era['slug']; ?>" class="card" style="text-align: center; text-decoration: none; color: inherit; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?php echo $era['icon'] ?? '📅'; ?></div>
            <div style="font-weight: 600; font-size: 0.95rem; color: <?php echo $era['color'] ?? '#fff'; ?>;"><?php echo escape($era['name']); ?></div>
            <div style="color: #8892b0; font-size: 0.75rem;">
                <?php echo $era['start_year'] . ' - ' . ($era['end_year'] ?: 'Present'); ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ====================================================== -->
<!-- TOP PROGRAMMING LANGUAGES -->
<!-- ====================================================== -->
<section class="section">
    <div class="section-header">
        <h2 style="color: #00ff88;">💻 Top Programming Languages</h2>
        <p style="color: #8892b0;">Most popular programming languages based on historical data</p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; max-width: 1200px; margin: 0 auto;">
        <?php foreach ($topLanguages as $lang): ?>
        <a href="language.php?slug=<?php echo $lang['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: inherit; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-family: 'Orbitron', monospace; font-size: 1.5rem; font-weight: 700; color: <?php echo $lang['popularity_score'] > 80 ? '#00d4ff' : '#8892b0'; ?>;">
                <?php echo escape($lang['name']); ?>
            </div>
            <div style="color: #8892b0; font-size: 0.75rem;"><?php echo $lang['year_created']; ?></div>
            <div style="margin-top: 0.3rem; height: 4px; background: rgba(255,255,255,0.05); border-radius: 2px; overflow: hidden;">
                <div style="height: 100%; width: <?php echo $lang['popularity_score']; ?>%; background: <?php echo $lang['popularity_score'] > 80 ? '#00d4ff' : '#7b2ffc'; ?>; border-radius: 2px;"></div>
            </div>
            <div style="font-size: 0.7rem; color: #8892b0; margin-top: 0.2rem;"><?php echo $lang['popularity_score']; ?>%</div>
        </a>
        <?php endforeach; ?>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a href="programming.php" style="color: #00ff88; text-decoration: none; font-weight: 500; transition: all 0.3s;">
            View All Languages <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- ====================================================== -->
<!-- RANDOM PEOPLE -->
<!-- ====================================================== -->
<section class="section" style="background: rgba(255, 255, 255, 0.02); border-top: 1px solid rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">👨‍💻 Meet the Pioneers</h2>
        <p style="color: #8892b0;">The visionaries who shaped the world of technology</p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
        <?php foreach ($randomPeople as $person): ?>
        <a href="person.php?slug=<?php echo $person['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; text-decoration: none; color: inherit; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 3rem; margin-bottom: 0.3rem;">
                <?php 
                $portrait = $person['portrait'] ?? '';
                if ($portrait && file_exists(UPLOADS_PATH . $portrait)) {
                    echo '<img src="' . UPLOADS_URL . $portrait . '" alt="' . escape($person['full_name']) . '" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #00d4ff;">';
                } else {
                    echo '👤';
                }
                ?>
            </div>
            <div style="font-weight: 600; color: #fff; font-size: 0.95rem;"><?php echo escape($person['full_name']); ?></div>
            <div style="color: #00d4ff; font-size: 0.8rem;"><?php echo escape($person['known_for']); ?></div>
            <div style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">
                <?php 
                $years = [];
                if ($person['birth_year']) $years[] = $person['birth_year'];
                if ($person['death_year']) $years[] = $person['death_year'];
                echo implode(' - ', $years);
                ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a href="people.php" style="color: #ffa500; text-decoration: none; font-weight: 500; transition: all 0.3s;">
            View All People <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- ====================================================== -->
<!-- LATEST ARTICLES -->
<!-- ====================================================== -->
<?php if (!empty($latestArticles)): ?>
<section class="section">
    <div class="section-header">
        <h2 style="color: #ff0064;">📚 Latest Articles</h2>
        <p style="color: #8892b0;">Read the latest stories from the world of technology</p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
        <?php foreach ($latestArticles as $article): ?>
        <a href="article.php?slug=<?php echo $article['slug']; ?>" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-decoration: none; color: inherit; transition: all 0.3s; hover:transform: translateY(-4px);">
            <?php if ($article['featured_image']): ?>
            <div style="margin: -1.5rem -1.5rem 1rem -1.5rem; overflow: hidden; border-radius: 12px 12px 0 0;">
                <img src="<?php echo UPLOADS_URL . $article['featured_image']; ?>" alt="<?php echo escape($article['title']); ?>" style="width: 100%; height: 160px; object-fit: cover;">
            </div>
            <?php endif; ?>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <span style="font-size: 0.7rem; color: #8892b0;">
                    <?php if ($article['category']): ?>
                    <i class="fas fa-tag"></i> <?php echo escape($article['category']); ?>
                    <?php endif; ?>
                </span>
                <?php if ($article['is_featured']): ?>
                <span style="font-size: 0.6rem; background: rgba(255,215,0,0.1); color: #ffd700; padding: 0.1rem 0.5rem; border-radius: 12px; border: 1px solid rgba(255,215,0,0.2);">⭐ Featured</span>
                <?php endif; ?>
            </div>
            <div style="font-weight: 600; color: #fff; font-size: 1.05rem; margin-top: 0.5rem;"><?php echo escape($article['title']); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem; margin-top: 0.5rem;"><?php echo escape(truncate($article['excerpt'] ?? $article['content'], 100)); ?></div>
            <div style="color: #8892b0; font-size: 0.7rem; margin-top: 0.5rem;">
                <i class="fas fa-calendar"></i> <?php echo formatDate($article['published_at'] ?: $article['created_at']); ?>
                <span style="margin-left: 0.5rem;"><i class="fas fa-eye"></i> <?php echo number_format($article['view_count'] ?? 0); ?></span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a href="articles.php" style="color: #ff0064; text-decoration: none; font-weight: 500; transition: all 0.3s;">
            View All Articles <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- ====================================================== -->
<!-- CALL TO ACTION -->
<!-- ====================================================== -->
<section class="section" style="background: rgba(255, 255, 255, 0.02); border-top: 1px solid rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 4rem 2rem; text-align: center;">
    <div style="max-width: 700px; margin: 0 auto;">
        <h2 style="color: #00d4ff; font-size: clamp(2rem, 3vw, 2.8rem);">🌐 Explore Everything</h2>
        <p style="color: #8892b0; margin: 1rem 0 2rem; font-size: 1.1rem;">
            Dive deeper into the Digital History museum. From early computing to AI and the future.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="museum.php" class="btn btn-primary" style="padding: 0.8rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); color: #fff; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                <i class="fas fa-landmark"></i> Visit Museum
            </a>
            <a href="lab.php" class="btn btn-outline" style="padding: 0.8rem 2rem; background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                <i class="fas fa-flask"></i> Digital Lab
            </a>
            <a href="games.php" class="btn btn-outline" style="padding: 0.8rem 2rem; background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                <i class="fas fa-gamepad"></i> Play Games
            </a>
        </div>
    </div>
</section>

<!-- ====================================================== -->
<!-- QUICK LINKS -->
<!-- ====================================================== -->
<section class="section" style="padding: 3rem 2rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1rem; max-width: 900px; margin: 0 auto;">
        <a href="computing.php" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: #8892b0; transition: all 0.3s; hover:color: #fff; hover:border-color: #00d4ff;">
            <div style="font-size: 1.5rem;">🖥️</div>
            <div style="font-size: 0.8rem;">Computing</div>
        </a>
        <a href="internet.php" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: #8892b0; transition: all 0.3s; hover:color: #fff; hover:border-color: #00d4ff;">
            <div style="font-size: 1.5rem;">🌐</div>
            <div style="font-size: 0.8rem;">Internet</div>
        </a>
        <a href="web.php" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: #8892b0; transition: all 0.3s; hover:color: #fff; hover:border-color: #00d4ff;">
            <div style="font-size: 1.5rem;">🌍</div>
            <div style="font-size: 0.8rem;">Web</div>
        </a>
        <a href="cybersecurity.php" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: #8892b0; transition: all 0.3s; hover:color: #fff; hover:border-color: #00d4ff;">
            <div style="font-size: 1.5rem;">🛡️</div>
            <div style="font-size: 0.8rem;">Cybersecurity</div>
        </a>
        <a href="ai.php" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: #8892b0; transition: all 0.3s; hover:color: #fff; hover:border-color: #00d4ff;">
            <div style="font-size: 1.5rem;">🧠</div>
            <div style="font-size: 0.8rem;">AI</div>
        </a>
        <a href="future.php" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; text-decoration: none; color: #8892b0; transition: all 0.3s; hover:color: #fff; hover:border-color: #00d4ff;">
            <div style="font-size: 1.5rem;">🚀</div>
            <div style="font-size: 0.8rem;">Future</div>
        </a>
    </div>
</section>

<?php
$pageContent = ob_get_clean();

$pageJS = '';

$pageCSS = '
.stats-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.stat-item {
    text-align: center;
}

.stat-item .counter {
    font-family: "Orbitron", monospace;
    font-size: 2.5rem;
    font-weight: 700;
    color: #00d4ff;
    background: linear-gradient(135deg, #00d4ff, #7b2ffc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-item p {
    color: #8892b0;
    font-size: 0.85rem;
    margin-top: 0.3rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.hero-content .btn-hero-primary,
.hero-content .btn-hero-secondary {
    transition: all 0.3s ease;
}

.hero-content .btn-hero-primary:hover,
.hero-content .btn-hero-secondary:hover {
    transform: translateY(-3px);
}

@media (max-width: 768px) {
    .stats-bar {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .stat-item .counter {
        font-size: 1.8rem;
    }
}

@media (max-width: 480px) {
    .stats-bar {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-item .counter {
        font-size: 1.5rem;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>