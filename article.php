<?php
// article.php
// Digital History - Single Article Page

require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
$article = null;

if (!empty($slug)) {
    $article = getArticle($slug);
}

if (!$article) {
    http_response_code(404);
    include '404.php';
    exit;
}

// Increment view count
db()->query("UPDATE articles SET view_count = view_count + 1 WHERE id = ?", [$article['id']]);

$pageTitle = $article['title'];
$pageDescription = truncate($article['excerpt'] ?? $article['content'], 160);

// Get related articles (same category)
$relatedArticles = db()->fetchAll(
    "SELECT * FROM articles WHERE category = ? AND id != ? AND status = 'published' ORDER BY created_at DESC LIMIT 5",
    [$article['category'], $article['id']]
);

// Get author info
$author = null;
if ($article['author_id']) {
    $author = getUserById($article['author_id']);
}

trackPageView('article_' . $article['id']);

ob_start();
?>

<!-- Article Header -->
<section class="section" style="padding-top: 2rem; padding-bottom: 0;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <a href="articles.php" style="color: #8892b0; text-decoration: none; font-size: 0.85rem;">← Back to Articles</a>
            <?php if ($article['category']): ?>
            <span style="color: #8892b0; font-size: 0.85rem;">/</span>
            <a href="articles.php?category=<?php echo urlencode($article['category']); ?>" style="color: #00d4ff; text-decoration: none; font-size: 0.85rem;">
                <?php echo escape($article['category']); ?>
            </a>
            <?php endif; ?>
        </div>
        
        <?php if ($article['is_featured']): ?>
        <span style="background: rgba(0,212,255,0.1); color: #00d4ff; padding: 0.2rem 0.8rem; border-radius: 12px; font-size: 0.8rem; border: 1px solid rgba(0,212,255,0.2);">
            ⭐ Featured
        </span>
        <?php endif; ?>
        
        <h1 style="font-size: clamp(2rem, 4vw, 3rem); color: #fff; margin-top: 0.5rem; margin-bottom: 0.5rem;">
            <?php echo escape($article['title']); ?>
        </h1>
        
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; color: #8892b0; font-size: 0.9rem; margin-bottom: 1.5rem;">
            <?php if ($author): ?>
            <span><i class="fas fa-user"></i> <?php echo escape($author['full_name'] ?: $author['username']); ?></span>
            <?php endif; ?>
            <span><i class="fas fa-calendar"></i> <?php echo formatDate($article['published_at'] ?: $article['created_at']); ?></span>
            <span><i class="fas fa-eye"></i> <?php echo number_format($article['view_count'] ?? 0); ?> views</span>
            <?php if ($article['category']): ?>
            <span><i class="fas fa-tag"></i> <?php echo escape($article['category']); ?></span>
            <?php endif; ?>
        </div>
        
        <?php if ($article['featured_image']): ?>
        <div style="margin-top: 1rem; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.06);">
            <img src="<?php echo UPLOADS_URL . $article['featured_image']; ?>" alt="<?php echo escape($article['title']); ?>" style="width: 100%; max-height: 400px; object-fit: cover;">
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Article Content -->
<section class="section" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Article Body -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <?php if ($article['excerpt']): ?>
            <div style="font-size: 1.1rem; color: #8892b0; border-left: 3px solid #00d4ff; padding-left: 1.2rem; margin-bottom: 1.5rem; font-style: italic;">
                <?php echo nl2br(escape($article['excerpt'])); ?>
            </div>
            <?php endif; ?>
            
            <div style="color: #ccd6f6; line-height: 1.8; font-size: 1.05rem;">
                <?php echo nl2br(escape($article['content'])); ?>
            </div>
        </div>
        
        <!-- Related Articles -->
        <?php if (!empty($relatedArticles)): ?>
        <div style="margin-top: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 1rem;">📚 Related Articles</h3>
            <div style="display: grid; gap: 0.8rem;">
                <?php foreach ($relatedArticles as $rel): ?>
                <a href="article.php?slug=<?php echo $rel['slug']; ?>" style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); padding: 0.8rem 1.2rem; border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.03);">
                    <div>
                        <div style="color: #fff;"><?php echo escape($rel['title']); ?></div>
                        <div style="color: #8892b0; font-size: 0.85rem;"><?php echo escape(truncate($rel['excerpt'] ?? $rel['content'], 80)); ?></div>
                    </div>
                    <div style="font-size: 0.8rem; color: #8892b0;"><?php echo formatDate($rel['created_at']); ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div style="margin-top: 2rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <a href="articles.php" style="color: #8892b0; text-decoration: none; transition: color 0.3s;">
                <i class="fas fa-arrow-left"></i> Back to Articles
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
    .site-header, .site-footer, .breadcrumbs, .hero-scroll {
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