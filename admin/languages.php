<?php
// admin/languages.php
// Digital History Admin - Programming Languages Management

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Manage Programming Languages';

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle save (add/edit)
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'name_ar' => trim($_POST['name_ar'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'year_created' => !empty($_POST['year_created']) ? intval($_POST['year_created']) : null,
            'creator' => trim($_POST['creator'] ?? ''),
            'creator_ar' => trim($_POST['creator_ar'] ?? ''),
            'paradigm' => trim($_POST['paradigm'] ?? ''),
            'typing_discipline' => trim($_POST['typing_discipline'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'description_ar' => trim($_POST['description_ar'] ?? ''),
            'use_cases' => trim($_POST['use_cases'] ?? ''),
            'code_example' => trim($_POST['code_example'] ?? ''),
            'popularity_score' => !empty($_POST['popularity_score']) ? intval($_POST['popularity_score']) : 0
        ];
        
        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $validation = validateFileUpload($_FILES['logo']);
            if ($validation['valid']) {
                $filename = sanitizeFilename($_FILES['logo']['name']);
                $destination = UPLOADS_PATH . $filename;
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                    $data['logo'] = $filename;
                }
            }
        }
        
        if (empty($data['name']) || empty($data['slug']) || empty($data['year_created'])) {
            $error = 'Please fill in all required fields.';
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update existing language
                $id = intval($_POST['id']);
                db()->update('programming_languages', $data, 'id = ?', [$id]);
                logActivity('admin_update_language', ['language_id' => $id]);
                $message = 'Programming language updated successfully!';
            } else {
                // Insert new language
                $data['is_active'] = 1;
                $id = db()->insert('programming_languages', $data);
                logActivity('admin_create_language', ['language_id' => $id]);
                $message = 'Programming language created successfully!';
            }
            $action = 'list';
        }
    }
}

// Delete language
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    db()->delete('programming_languages', 'id = ?', [$id]);
    logActivity('admin_delete_language', ['language_id' => $id]);
    $message = 'Programming language deleted successfully.';
    $action = 'list';
}

$languages = db()->fetchAll("SELECT * FROM programming_languages ORDER BY year_created");

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-code"></i> Programming Languages Management</h2>
        <a href="languages.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Language
        </a>
    </div>
    
    <?php if ($message): ?>
    <div class="admin-message success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="admin-message error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <?php 
    $language = null;
    if ($action === 'edit' && isset($_GET['id'])) {
        $language = db()->fetch("SELECT * FROM programming_languages WHERE id = ?", [$_GET['id']]);
    }
    ?>
    <div class="admin-form-container">
        <h3><?php echo $action === 'add' ? 'Add New Programming Language' : 'Edit Programming Language'; ?></h3>
        <form method="POST" action="languages.php?action=save" enctype="multipart/form-data">
            <?php echo getCSRFField(); ?>
            <?php if ($language): ?>
            <input type="hidden" name="id" value="<?php echo $language['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="<?php echo $language ? escape($language['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Name (Arabic)</label>
                    <input type="text" name="name_ar" value="<?php echo $language ? escape($language['name_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Slug *</label>
                    <input type="text" name="slug" value="<?php echo $language ? escape($language['slug']) : ''; ?>" required>
                    <small>URL-friendly version (e.g., "python")</small>
                </div>
                <div class="form-group">
                    <label>Year Created *</label>
                    <input type="number" name="year_created" value="<?php echo $language ? $language['year_created'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Creator</label>
                    <input type="text" name="creator" value="<?php echo $language ? escape($language['creator']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Creator (Arabic)</label>
                    <input type="text" name="creator_ar" value="<?php echo $language ? escape($language['creator_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Paradigm</label>
                    <input type="text" name="paradigm" value="<?php echo $language ? escape($language['paradigm']) : ''; ?>" placeholder="Object-oriented, Functional, etc.">
                </div>
                <div class="form-group">
                    <label>Typing Discipline</label>
                    <input type="text" name="typing_discipline" value="<?php echo $language ? escape($language['typing_discipline']) : ''; ?>" placeholder="Static, Dynamic, etc.">
                </div>
                <div class="form-group">
                    <label>Popularity Score</label>
                    <input type="number" name="popularity_score" value="<?php echo $language ? $language['popularity_score'] : ''; ?>" min="0" max="100" placeholder="0-100">
                </div>
                <div class="form-group">
                    <label>Logo Image</label>
                    <input type="file" name="logo" accept="image/*">
                    <?php if ($language && $language['logo']): ?>
                    <div style="margin-top: 0.5rem;">
                        <img src="<?php echo UPLOADS_URL . $language['logo']; ?>" alt="Current logo" style="max-width: 80px; border-radius: 8px;">
                        <br><small>Current logo</small>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo $language ? escape($language['description']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Description (Arabic)</label>
                    <textarea name="description_ar" rows="4"><?php echo $language ? escape($language['description_ar']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Use Cases</label>
                    <textarea name="use_cases" rows="3" placeholder="Web development, Data science, Systems programming, etc."><?php echo $language ? escape($language['use_cases']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Code Example</label>
                    <textarea name="code_example" rows="6" placeholder="Print 'Hello, World!'"><?php echo $language ? escape($language['code_example']) : ''; ?></textarea>
                    <small>Example code snippet showing the language syntax</small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Language</button>
                <a href="languages.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
    <div class="admin-table-container">
        <div class="table-toolbar">
            <div class="table-search">
                <input type="text" id="tableSearch" placeholder="Search languages..." onkeyup="filterTable()">
            </div>
            <div class="table-info">
                Total: <?php echo count($languages); ?> languages
            </div>
        </div>
        
        <table class="admin-table" id="languagesTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Creator</th>
                    <th>Paradigm</th>
                    <th>Popularity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($languages)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #8892b0;">No programming languages found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($languages as $lang): ?>
                <tr>
                    <td><strong><?php echo escape($lang['name']); ?></strong></td>
                    <td><?php echo $lang['year_created']; ?></td>
                    <td><?php echo escape($lang['creator']); ?></td>
                    <td><?php echo escape($lang['paradigm']); ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 60px; height: 6px; background: rgba(255,255,255,0.05); border-radius: 3px; overflow: hidden;">
                                <div style="height: 100%; width: <?php echo $lang['popularity_score']; ?>%; background: <?php 
                                    // Fixed nested ternary - using if-else
                                    if ($lang['popularity_score'] > 80) {
                                        echo '#00d4ff';
                                    } elseif ($lang['popularity_score'] > 60) {
                                        echo '#7b2ffc';
                                    } else {
                                        echo '#8892b0';
                                    }
                                ?>; border-radius: 3px;"></div>
                            </div>
                            <span style="font-size: 0.85rem; color: #8892b0;"><?php echo $lang['popularity_score']; ?>%</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="../language.php?slug=<?php echo $lang['slug']; ?>" target="_blank" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="languages.php?action=edit&id=<?php echo $lang['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="languages.php?action=delete&id=<?php echo $lang['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this language?')"><i class="fas fa-trash"></i></a>
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
    color: #ffa500;
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
    border-color: #ffa500;
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
    border-color: #ffa500;
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
    background: linear-gradient(135deg, #ffa500, #ff6b6b);
    color: #fff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(255, 165, 0, 0.2);
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
    const table = document.getElementById('languagesTable');
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