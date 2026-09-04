<?php
// admin/articles.php
// Digital History Admin - Articles Management

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Manage Articles';

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle save (add/edit)
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'title_ar' => trim($_POST['title_ar'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'category_ar' => trim($_POST['category_ar'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'excerpt_ar' => trim($_POST['excerpt_ar'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'content_ar' => trim($_POST['content_ar'] ?? ''),
            'status' => $_POST['status'] ?? 'draft',
            'is_featured' => isset($_POST['is_featured']) ? intval($_POST['is_featured']) : 0,
            'author_id' => $_SESSION['user_id'] ?? null
        ];
        
        // Handle featured image upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $validation = validateFileUpload($_FILES['featured_image']);
            if ($validation['valid']) {
                $filename = sanitizeFilename($_FILES['featured_image']['name']);
                $destination = UPLOADS_PATH . $filename;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $destination)) {
                    $data['featured_image'] = $filename;
                }
            }
        }
        
        if (empty($data['title']) || empty($data['slug']) || empty($data['content'])) {
            $error = 'Please fill in all required fields.';
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update existing article
                $id = intval($_POST['id']);
                // If status is published, set published_at
                if ($data['status'] === 'published') {
                    $data['published_at'] = date('Y-m-d H:i:s');
                }
                db()->update('articles', $data, 'id = ?', [$id]);
                logActivity('admin_update_article', ['article_id' => $id]);
                $message = 'Article updated successfully!';
            } else {
                // Insert new article
                if ($data['status'] === 'published') {
                    $data['published_at'] = date('Y-m-d H:i:s');
                }
                $data['is_active'] = 1;
                $data['view_count'] = 0;
                $id = db()->insert('articles', $data);
                logActivity('admin_create_article', ['article_id' => $id]);
                $message = 'Article created successfully!';
            }
            $action = 'list';
        }
    }
}

// Delete article
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    db()->delete('articles', 'id = ?', [$id]);
    logActivity('admin_delete_article', ['article_id' => $id]);
    $message = 'Article deleted successfully.';
    $action = 'list';
}

// Get all articles
$articles = db()->fetchAll("SELECT * FROM articles ORDER BY created_at DESC");
$categories = getCategories();

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-newspaper"></i> Articles Management</h2>
        <a href="articles.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Article
        </a>
    </div>
    
    <?php if ($message): ?>
    <div class="admin-message success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="admin-message error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Add/Edit Form -->
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <?php 
    $article = null;
    if ($action === 'edit' && isset($_GET['id'])) {
        $article = db()->fetch("SELECT * FROM articles WHERE id = ?", [$_GET['id']]);
    }
    ?>
    <div class="admin-form-container">
        <h3><?php echo $action === 'add' ? 'Add New Article' : 'Edit Article'; ?></h3>
        <form method="POST" action="articles.php?action=save" enctype="multipart/form-data">
            <?php echo getCSRFField(); ?>
            <?php if ($article): ?>
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid-2">
                <div class="form-group full-width">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?php echo $article ? escape($article['title']) : ''; ?>" required>
                </div>
                <div class="form-group full-width">
                    <label>Title (Arabic)</label>
                    <input type="text" name="title_ar" value="<?php echo $article ? escape($article['title_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Slug *</label>
                    <input type="text" name="slug" value="<?php echo $article ? escape($article['slug']) : ''; ?>" required>
                    <small>URL-friendly version (e.g., "history-of-python")</small>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="">None</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo escape($cat['slug']); ?>" <?php echo ($article && $article['category'] === $cat['slug']) ? 'selected' : ''; ?>>
                            <?php echo escape($cat['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Category (Arabic)</label>
                    <input type="text" name="category_ar" value="<?php echo $article ? escape($article['category_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="draft" <?php echo ($article && $article['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($article && $article['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                        <option value="archived" <?php echo ($article && $article['status'] === 'archived') ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Featured</label>
                    <select name="is_featured">
                        <option value="0" <?php echo ($article && !$article['is_featured']) ? 'selected' : ''; ?>>No</option>
                        <option value="1" <?php echo ($article && $article['is_featured']) ? 'selected' : ''; ?>>Yes</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Excerpt</label>
                    <textarea name="excerpt" rows="3"><?php echo $article ? escape($article['excerpt']) : ''; ?></textarea>
                    <small>Short summary displayed in listings</small>
                </div>
                <div class="form-group full-width">
                    <label>Excerpt (Arabic)</label>
                    <textarea name="excerpt_ar" rows="3"><?php echo $article ? escape($article['excerpt_ar']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Content *</label>
                    <textarea name="content" rows="12" required><?php echo $article ? escape($article['content']) : ''; ?></textarea>
                    <small>Full article content. Use HTML for formatting.</small>
                </div>
                <div class="form-group full-width">
                    <label>Content (Arabic)</label>
                    <textarea name="content_ar" rows="12"><?php echo $article ? escape($article['content_ar']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*">
                    <?php if ($article && $article['featured_image']): ?>
                    <div style="margin-top: 0.5rem;">
                        <img src="<?php echo UPLOADS_URL . $article['featured_image']; ?>" alt="Current image" style="max-width: 200px; border-radius: 8px;">
                        <br><small>Current image</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Article</button>
                <a href="articles.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- List -->
    <?php if ($action === 'list'): ?>
    <div class="admin-table-container">
        <div class="table-toolbar">
            <div class="table-search">
                <input type="text" id="tableSearch" placeholder="Search articles..." onkeyup="filterTable()">
            </div>
            <div class="table-info">
                Total: <?php echo count($articles); ?> articles
            </div>
        </div>
        
        <table class="admin-table" id="articlesTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Views</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #8892b0;">No articles found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($articles as $article): ?>
                <tr>
                    <td><strong><?php echo escape(truncate($article['title'], 50)); ?></strong></td>
                    <td><?php echo escape($article['category']); ?></td>
                    <td>
                        <span style="font-size: 0.8rem; background: <?php 
                            if ($article['status'] === 'published') {
                                echo 'rgba(0,212,255,0.1)';
                                $statusColor = '#00d4ff';
                            } elseif ($article['status'] === 'draft') {
                                echo 'rgba(255,165,0,0.1)';
                                $statusColor = '#ffa500';
                            } else {
                                echo 'rgba(255,0,0,0.1)';
                                $statusColor = '#ff6b6b';
                            }
                        ?>; color: <?php echo $statusColor; ?>; padding: 0.1rem 0.8rem; border-radius: 12px;">
                            <?php echo $article['status']; ?>
                        </span>
                    </td>
                    <td><?php echo $article['is_featured'] ? '⭐' : ''; ?></td>
                    <td><?php echo number_format($article['view_count'] ?? 0); ?></td>
                    <td style="font-size: 0.85rem; color: #8892b0;"><?php echo formatDate($article['created_at']); ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="../article.php?slug=<?php echo $article['slug']; ?>" target="_blank" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="articles.php?action=edit&id=<?php echo $article['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="articles.php?action=delete&id=<?php echo $article['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this article?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<style>
.admin-page {
    padding: 1rem 0;
}

.admin-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.admin-page-header h2 {
    color: #fff;
    font-size: 1.5rem;
    margin: 0;
}

.admin-message {
    padding: 0.8rem 1.2rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.admin-message.success {
    background: rgba(0, 212, 255, 0.1);
    border: 1px solid rgba(0, 212, 255, 0.2);
    color: #00d4ff;
}

.admin-message.error {
    background: rgba(255, 0, 0, 0.1);
    border: 1px solid rgba(255, 0, 0, 0.2);
    color: #ff6b6b;
}

.admin-form-container {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.admin-form-container h3 {
    color: #ff0064;
    margin-top: 0;
    margin-bottom: 1.5rem;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.2rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    color: #ccd6f6;
    margin-bottom: 0.3rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.6rem 0.8rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    color: #fff;
    font-size: 0.95rem;
    transition: border-color 0.3s;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #ff0064;
}

.form-group textarea {
    resize: vertical;
}

.form-group small {
    color: #8892b0;
    font-size: 0.75rem;
    display: block;
    margin-top: 0.2rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.admin-table-container {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    overflow: hidden;
}

.table-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    flex-wrap: wrap;
    gap: 0.5rem;
}

.table-search input {
    padding: 0.4rem 0.8rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 6px;
    color: #fff;
    font-size: 0.9rem;
    min-width: 200px;
}

.table-search input:focus {
    outline: none;
    border-color: #ff0064;
}

.table-info {
    color: #8892b0;
    font-size: 0.85rem;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    text-align: left;
    padding: 0.8rem 1.2rem;
    color: #8892b0;
    font-weight: 500;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.admin-table td {
    padding: 0.8rem 1.2rem;
    color: #ccd6f6;
    border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    font-size: 0.9rem;
}

.admin-table tr:hover td {
    background: rgba(255, 255, 255, 0.02);
}

.table-actions {
    display: flex;
    gap: 0.3rem;
}

.btn-icon {
    padding: 0.3rem 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    border: none;
    border-radius: 4px;
    color: #8892b0;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.9rem;
}

.btn-icon:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
}

.btn-icon.delete:hover {
    color: #ff6b6b;
    background: rgba(255, 0, 0, 0.1);
}

.btn {
    padding: 0.5rem 1.2rem;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #ff0064, #ff6b6b);
    color: #fff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(255, 0, 100, 0.2);
}

.btn-outline {
    background: transparent;
    color: #8892b0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-outline:hover {
    color: #fff;
    border-color: rgba(255, 255, 255, 0.3);
}

@media (max-width: 768px) {
    .form-grid-2 {
        grid-template-columns: 1fr;
    }
    
    .admin-table-container {
        overflow-x: auto;
    }
    
    .table-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .table-search input {
        width: 100%;
        min-width: auto;
    }
}
</style>

<script>
function filterTable() {
    const input = document.getElementById('tableSearch');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('articlesTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            const text = cells[j].textContent || cells[j].innerText;
            if (text.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
}
</script>

<?php
$pageContent = ob_get_clean();

// Instead of using the main header, we'll use a simplified admin header
// since we're in the admin directory and need to avoid path issues
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Digital History Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Admin Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            color: #ccd6f6;
            min-height: 100vh;
        }
        
        <?php echo $pageCSS ?? ''; ?>
    </style>
</head>
<body>
    <?php echo $pageContent; ?>
    
    <script src="../assets/js/main.js"></script>
    <?php if (isset($pageJS)): ?>
    <script><?php echo $pageJS; ?></script>
    <?php endif; ?>
</body>
</html>