<?php
// admin/people.php
// Digital History Admin - People Management

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Manage People';

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle save (add/edit)
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'full_name_ar' => trim($_POST['full_name_ar'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'known_for' => trim($_POST['known_for'] ?? ''),
            'birth_year' => !empty($_POST['birth_year']) ? intval($_POST['birth_year']) : null,
            'death_year' => !empty($_POST['death_year']) ? intval($_POST['death_year']) : null,
            'nationality' => trim($_POST['nationality'] ?? ''),
            'nationality_ar' => trim($_POST['nationality_ar'] ?? ''),
            'biography' => trim($_POST['biography'] ?? ''),
            'biography_ar' => trim($_POST['biography_ar'] ?? ''),
            'contributions' => trim($_POST['contributions'] ?? '')
        ];
        
        // Handle portrait upload
        if (isset($_FILES['portrait']) && $_FILES['portrait']['error'] === UPLOAD_ERR_OK) {
            $validation = validateFileUpload($_FILES['portrait']);
            if ($validation['valid']) {
                $filename = sanitizeFilename($_FILES['portrait']['name']);
                $destination = UPLOADS_PATH . $filename;
                if (move_uploaded_file($_FILES['portrait']['tmp_name'], $destination)) {
                    $data['portrait'] = $filename;
                }
            }
        }
        
        if (empty($data['full_name']) || empty($data['slug'])) {
            $error = 'Please fill in all required fields.';
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update existing person
                $id = intval($_POST['id']);
                db()->update('people', $data, 'id = ?', [$id]);
                logActivity('admin_update_person', ['person_id' => $id]);
                $message = 'Person updated successfully!';
            } else {
                // Insert new person
                $data['is_active'] = 1;
                $id = db()->insert('people', $data);
                logActivity('admin_create_person', ['person_id' => $id]);
                $message = 'Person created successfully!';
            }
            $action = 'list';
        }
    }
}

// Delete person
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    db()->delete('people', 'id = ?', [$id]);
    logActivity('admin_delete_person', ['person_id' => $id]);
    $message = 'Person deleted successfully.';
    $action = 'list';
}

$people = db()->fetchAll("SELECT * FROM people ORDER BY birth_year DESC");

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-users"></i> People Management</h2>
        <a href="people.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Person
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
    $person = null;
    if ($action === 'edit' && isset($_GET['id'])) {
        $person = db()->fetch("SELECT * FROM people WHERE id = ?", [$_GET['id']]);
    }
    ?>
    <div class="admin-form-container">
        <h3><?php echo $action === 'add' ? 'Add New Person' : 'Edit Person'; ?></h3>
        <form method="POST" action="people.php?action=save" enctype="multipart/form-data">
            <?php echo getCSRFField(); ?>
            <?php if ($person): ?>
            <input type="hidden" name="id" value="<?php echo $person['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" value="<?php echo $person ? escape($person['full_name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Full Name (Arabic)</label>
                    <input type="text" name="full_name_ar" value="<?php echo $person ? escape($person['full_name_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Slug *</label>
                    <input type="text" name="slug" value="<?php echo $person ? escape($person['slug']) : ''; ?>" required>
                    <small>URL-friendly version (e.g., "alan-turing")</small>
                </div>
                <div class="form-group">
                    <label>Known For</label>
                    <input type="text" name="known_for" value="<?php echo $person ? escape($person['known_for']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Birth Year</label>
                    <input type="number" name="birth_year" value="<?php echo $person ? $person['birth_year'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Death Year</label>
                    <input type="number" name="death_year" value="<?php echo $person ? $person['death_year'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" value="<?php echo $person ? escape($person['nationality']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Nationality (Arabic)</label>
                    <input type="text" name="nationality_ar" value="<?php echo $person ? escape($person['nationality_ar']) : ''; ?>">
                </div>
                <div class="form-group full-width">
                    <label>Biography</label>
                    <textarea name="biography" rows="4"><?php echo $person ? escape($person['biography']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Biography (Arabic)</label>
                    <textarea name="biography_ar" rows="4"><?php echo $person ? escape($person['biography_ar']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Contributions</label>
                    <textarea name="contributions" rows="3"><?php echo $person ? escape($person['contributions']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Portrait Image</label>
                    <input type="file" name="portrait" accept="image/*">
                    <?php if ($person && $person['portrait']): ?>
                    <div style="margin-top: 0.5rem;">
                        <img src="<?php echo UPLOADS_URL . $person['portrait']; ?>" alt="Current portrait" style="max-width: 100px; border-radius: 50%;">
                        <br><small>Current image</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Person</button>
                <a href="people.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
    <div class="admin-table-container">
        <div class="table-toolbar">
            <div class="table-search">
                <input type="text" id="tableSearch" placeholder="Search people..." onkeyup="filterTable()">
            </div>
            <div class="table-info">
                Total: <?php echo count($people); ?> people
            </div>
        </div>
        
        <table class="admin-table" id="peopleTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Known For</th>
                    <th>Years</th>
                    <th>Nationality</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($people)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #8892b0;">No people found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($people as $person): ?>
                <tr>
                    <td><strong><?php echo escape($person['full_name']); ?></strong></td>
                    <td><?php echo escape($person['known_for']); ?></td>
                    <td>
                        <?php 
                        $years = [];
                        if ($person['birth_year']) $years[] = $person['birth_year'];
                        if ($person['death_year']) $years[] = $person['death_year'];
                        echo implode(' - ', $years);
                        ?>
                    </td>
                    <td><?php echo escape($person['nationality']); ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="../person.php?slug=<?php echo $person['slug']; ?>" target="_blank" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="people.php?action=edit&id=<?php echo $person['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="people.php?action=delete&id=<?php echo $person['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this person?')"><i class="fas fa-trash"></i></a>
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
    color: #7b2ffc;
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
    border-color: #7b2ffc;
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
    border-color: #7b2ffc;
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
    background: linear-gradient(135deg, #7b2ffc, #00d4ff);
    color: #fff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(123, 47, 252, 0.2);
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
    const table = document.getElementById('peopleTable');
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