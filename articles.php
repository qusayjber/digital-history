<?php
// articles.php
// Digital History - Articles Directory

require_once 'includes/config.php';

$pageTitle = 'Articles';
$pageDescription = 'Read articles about technology history, computing, programming, and more.';

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$articles = getArticles($category ?: null);

// Filter by search if provided
if ($search) {
    $articles = array_filter($articles, function($a) use ($search) {
        return stripos($a['title'], $search) !== false || 
               stripos($a['content'], $search) !== false ||
               stripos($a['excerpt'], $search) !== false;
    });
}

$categories = getCategories();

trackPageView('articles');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>📚 Articles</h2>
        <p>Read in-depth articles about technology history, computing, programming, and more.</p>
    </div>
</section>

<!-- Filters -->
<section class="section" style="padding-top: 0;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
            <a href="articles.php" style="padding: 0.3rem 1rem; background: <?php echo !$category ? 'rgba(0,212,255,0.1)' : 'rgba(255,255,255,0.03)'; ?>; border: 1px solid <?php echo !$category ? 'rgba(0,212,255,0.2)' : 'rgba(255,255,255,0.06)'; ?>; border-radius: 20px; color: <?php echo !$category ? '#00d4ff' : '#8892b0'; ?>; text-decoration: none; font-size: 0.85rem; transition: all 0.3s;">
                All
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="articles.php?category=<?php echo urlencode($cat['slug']); ?>" style="padding: 0.3rem 1rem; background: <?php echo $category === $cat['slug'] ? 'rgba(0,212,255,0.1)' : 'rgba(255,255,255,0.03)'; ?>; border: 1px solid <?php echo $category === $cat['slug'] ? 'rgba(0,212,255,0.2)' : 'rgba(255,255,255,0.06)'; ?>; border-radius: 20px; color: <?php echo $category === $cat['slug'] ? '#00d4ff' : '#8892b0'; ?>; text-decoration: none; font-size: 0.85rem; transition: all 0.3s;">
                <?php echo escape($cat['name']); ?>
            </a>
            <?php endforeach; ?>
        </div>
        
        <form method="GET" action="articles.php" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search articles..." value="<?php echo escape($search); ?>" style="flex: 1; padding: 0.6rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 0.95rem;">
            <button type="submit" style="padding: 0.6rem 1.2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer;">
                <i class="fas fa-search"></i>
            </button>
            <?php if ($search): ?>
            <a href="articles.php<?php echo $category ? '?category=' . urlencode($category) : ''; ?>" style="padding: 0.6rem 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #8892b0; text-decoration: none; display: flex; align-items: center;">
                <i class="fas fa-times"></i>
            </a>
            <?php endif; ?>
        </form>
    </div>
</section>

<!-- Articles Grid -->
<section class="section" style="padding-top: 0;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <?php if (empty($articles)): ?>
        <div style="text-align: center; padding: 3rem 0; background: rgba(255,255,255,0.02); border-radius: 12px;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">📚</div>
            <p style="color: #8892b0;">No articles found.</p>
            <?php if ($search || $category): ?>
            <p style="color: #8892b0; font-size: 0.9rem;">Try adjusting your filters or search terms.</p>
            <a href="articles.php" style="display: inline-block; margin-top: 1rem; color: #00d4ff; text-decoration: none;">View all articles →</a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div style="display: grid; gap: 1.5rem;">
            <?php foreach ($articles as $article): ?>
            <a href="article.php?slug=<?php echo $article['slug']; ?>" style="display: block; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-decoration: none; color: inherit; transition: all 0.3s;">
                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                    <?php if ($article['featured_image']): ?>
                    <div style="flex-shrink: 0; width: 120px; height: 120px; border-radius: 8px; overflow: hidden;">
                        <img src="<?php echo UPLOADS_URL . $article['featured_image']; ?>" alt="<?php echo escape($article['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <?php endif; ?>
                    <div style="flex: 1; min-width: 200px;">
                        <?php if ($article['is_featured']): ?>
                        <span style="background: rgba(0,212,255,0.1); color: #00d4ff; padding: 0.1rem 0.6rem; border-radius: 12px; font-size: 0.7rem; border: 1px solid rgba(0,212,255,0.2);">⭐ Featured</span>
                        <?php endif; ?>
                        <h3 style="color: #fff; margin-top: 0.3rem; margin-bottom: 0.3rem; font-size: 1.2rem;"><?php echo escape($article['title']); ?></h3>
                        <p style="color: #8892b0; font-size: 0.9rem; margin-bottom: 0.5rem;"><?php echo escape(truncate($article['excerpt'] ?? $article['content'], 150)); ?></p>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap; color: #8892b0; font-size: 0.8rem;">
                            <?php if ($article['category']): ?>
                            <span><i class="fas fa-tag"></i> <?php echo escape($article['category']); ?></span>
                            <?php endif; ?>
                            <span><i class="fas fa-calendar"></i> <?php echo formatDate($article['published_at'] ?: $article['created_at']); ?></span>
                            <span><i class="fas fa-eye"></i> <?php echo number_format($article['view_count'] ?? 0); ?> views</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; color: #00d4ff;">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>