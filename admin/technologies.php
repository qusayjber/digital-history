<?php
// admin/technologies.php
// Digital History Admin - Technologies Management

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Manage Technologies';

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
            'category' => trim($_POST['category'] ?? ''),
            'category_ar' => trim($_POST['category_ar'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'description_ar' => trim($_POST['description_ar'] ?? ''),
            'year_introduced' => !empty($_POST['year_introduced']) ? intval($_POST['year_introduced']) : null,
            'inventor' => trim($_POST['inventor'] ?? ''),
            'icon' => trim($_POST['icon'] ?? '')
        ];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $validation = validateFileUpload($_FILES['image']);
            if ($validation['valid']) {
                $filename = sanitizeFilename($_FILES['image']['name']);
                $destination = UPLOADS_PATH . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $data['image'] = $filename;
                }
            }
        }
        
        if (empty($data['name']) || empty($data['slug'])) {
            $error = 'Please fill in all required fields.';
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update existing technology
                $id = intval($_POST['id']);
                db()->update('technologies', $data, 'id = ?', [$id]);
                logActivity('admin_update_technology', ['technology_id' => $id]);
                $message = 'Technology updated successfully!';
            } else {
                // Insert new technology
                $data['is_active'] = 1;
                $id = db()->insert('technologies', $data);
                logActivity('admin_create_technology', ['technology_id' => $id]);
                $message = 'Technology created successfully!';
            }
            $action = 'list';
        }
    }
}

// Delete technology
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    db()->delete('technologies', 'id = ?', [$id]);
    logActivity('admin_delete_technology', ['technology_id' => $id]);
    $message = 'Technology deleted successfully.';
    $action = 'list';
}

$technologies = db()->fetchAll("SELECT * FROM technologies ORDER BY category, name");

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-microchip"></i> Technologies Management</h2>
        <a href="technologies.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Technology
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
    $technology = null;
    if ($action === 'edit' && isset($_GET['id'])) {
        $technology = db()->fetch("SELECT * FROM technologies WHERE id = ?", [$_GET['id']]);
    }
    ?>
    <div class="admin-form-container">
        <h3><?php echo $action === 'add' ? 'Add New Technology' : 'Edit Technology'; ?></h3>
        <form method="POST" action="technologies.php?action=save" enctype="multipart/form-data">
            <?php echo getCSRFField(); ?>
            <?php if ($technology): ?>
            <input type="hidden" name="id" value="<?php echo $technology['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="<?php echo $technology ? escape($technology['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Name (Arabic)</label>
                    <input type="text" name="name_ar" value="<?php echo $technology ? escape($technology['name_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Slug *</label>
                    <input type="text" name="slug" value="<?php echo $technology ? escape($technology['slug']) : ''; ?>" required>
                    <small>URL-friendly version (e.g., "tcp-ip")</small>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" value="<?php echo $technology ? escape($technology['category']) : ''; ?>" placeholder="Networking, Hardware, Security, etc.">
                </div>
                <div class="form-group">
                    <label>Category (Arabic)</label>
                    <input type="text" name="category_ar" value="<?php echo $technology ? escape($technology['category_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Year Introduced</label>
                    <input type="number" name="year_introduced" value="<?php echo $technology ? $technology['year_introduced'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Inventor</label>
                    <input type="text" name="inventor" value="<?php echo $technology ? escape($technology['inventor']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Icon</label>
                    <input type="text" name="icon" value="<?php echo $technology ? escape($technology['icon']) : ''; ?>" placeholder="🔧 or emoji">
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo $technology ? escape($technology['description']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Description (Arabic)</label>
                    <textarea name="description_ar" rows="4"><?php echo $technology ? escape($technology['description_ar']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Image</label>
                    <input type="file" name="image" accept="image/*">
                    <?php if ($technology && $technology['image']): ?>
                    <div style="margin-top: 0.5rem;">
                        <img src="<?php echo UPLOADS_URL . $technology['image']; ?>" alt="Current image" style="max-width: 200px; border-radius: 8px;">
                        <br><small>Current image</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Technology</button>
                <a href="technologies.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
    <div class="admin-table-container">
        <div class="table-toolbar">
            <div class="table-search">
                <input type="text" id="tableSearch" placeholder="Search technologies..." onkeyup="filterTable()">
            </div>
            <div class="table-info">
                Total: <?php echo count($technologies); ?> technologies
            </div>
        </div>
        
        <table class="admin-table" id="technologiesTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Year</th>
                    <th>Inventor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($technologies)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #8892b0;">No technologies found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($technologies as $tech): ?>
                <tr>
                    <td>
                        <strong><?php echo escape($tech['name']); ?></strong>
                        <?php if ($tech['icon']): ?>
                        <span style="margin-left: 0.5rem;"><?php echo $tech['icon']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo escape($tech['category']); ?></td>
                    <td><?php echo $tech['year_introduced'] ?: 'N/A'; ?></td>
                    <td><?php echo escape($tech['inventor']); ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="../technology.php?slug=<?php echo $tech['slug']; ?>" target="_blank" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="technologies.php?action=edit&id=<?php echo $tech['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="technologies.php?action=delete&id=<?php echo $tech['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this technology?')"><i class="fas fa-trash"></i></a>
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
    color: #00ff88;
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
    border-color: #00ff88;
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
    border-color: #00ff88;
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
    background: linear-gradient(135deg, #00ff88, #00d4ff);
    color: #000;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(0, 255, 136, 0.2);
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
    const table = document.getElementById('technologiesTable');
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