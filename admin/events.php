<?php
// admin/events.php
// Digital History Admin - Events Management

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Manage Events';

// Handle actions
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
            'year' => intval($_POST['year'] ?? 0),
            'year_end' => !empty($_POST['year_end']) ? intval($_POST['year_end']) : null,
            'era_id' => !empty($_POST['era_id']) ? intval($_POST['era_id']) : null,
            'description' => trim($_POST['description'] ?? ''),
            'description_ar' => trim($_POST['description_ar'] ?? ''),
            'significance' => trim($_POST['significance'] ?? ''),
            'event_type' => $_POST['event_type'] ?? 'milestone',
            'is_featured' => isset($_POST['is_featured']) ? intval($_POST['is_featured']) : 0,
            'video_url' => trim($_POST['video_url'] ?? '')
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
        
        if (empty($data['title']) || empty($data['slug']) || empty($data['year']) || empty($data['description'])) {
            $error = 'Please fill in all required fields.';
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update existing event
                $id = intval($_POST['id']);
                db()->update('timeline_events', $data, 'id = ?', [$id]);
                logActivity('admin_update_event', ['event_id' => $id]);
                $message = 'Event updated successfully!';
            } else {
                // Insert new event
                $id = db()->insert('timeline_events', $data);
                logActivity('admin_create_event', ['event_id' => $id]);
                $message = 'Event created successfully!';
            }
            $action = 'list';
        }
    }
}

// Delete event
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    db()->delete('timeline_events', 'id = ?', [$id]);
    logActivity('admin_delete_event', ['event_id' => $id]);
    $message = 'Event deleted successfully.';
    $action = 'list';
}

// Get all events with era names
$events = db()->fetchAll(
    "SELECT e.*, er.name as era_name 
     FROM timeline_events e 
     LEFT JOIN eras er ON e.era_id = er.id 
     ORDER BY e.year DESC"
);

$eras = getEras();

// Use admin header instead of main header
$pageCSS = '';
$pageJS = '';

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-timeline"></i> Events Management</h2>
        <a href="events.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Event
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
    $event = null;
    if ($action === 'edit' && isset($_GET['id'])) {
        $event = db()->fetch("SELECT * FROM timeline_events WHERE id = ?", [$_GET['id']]);
    }
    ?>
    <div class="admin-form-container">
        <h3><?php echo $action === 'add' ? 'Add New Event' : 'Edit Event'; ?></h3>
        <form method="POST" action="events.php?action=save" enctype="multipart/form-data">
            <?php echo getCSRFField(); ?>
            <?php if ($event): ?>
            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?php echo $event ? escape($event['title']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Title (Arabic)</label>
                    <input type="text" name="title_ar" value="<?php echo $event ? escape($event['title_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Slug *</label>
                    <input type="text" name="slug" value="<?php echo $event ? escape($event['slug']) : ''; ?>" required>
                    <small>URL-friendly version of the title (e.g., "eniac-1945")</small>
                </div>
                <div class="form-group">
                    <label>Year *</label>
                    <input type="number" name="year" value="<?php echo $event ? $event['year'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Year End (optional)</label>
                    <input type="number" name="year_end" value="<?php echo $event ? $event['year_end'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Era</label>
                    <select name="era_id">
                        <option value="">None</option>
                        <?php foreach ($eras as $era): ?>
                        <option value="<?php echo $era['id']; ?>" <?php echo ($event && $event['era_id'] == $era['id']) ? 'selected' : ''; ?>>
                            <?php echo escape($era['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Event Type</label>
                    <select name="event_type">
                        <option value="milestone" <?php echo ($event && $event['event_type'] === 'milestone') ? 'selected' : ''; ?>>Milestone</option>
                        <option value="invention" <?php echo ($event && $event['event_type'] === 'invention') ? 'selected' : ''; ?>>Invention</option>
                        <option value="person" <?php echo ($event && $event['event_type'] === 'person') ? 'selected' : ''; ?>>Person</option>
                        <option value="technology" <?php echo ($event && $event['event_type'] === 'technology') ? 'selected' : ''; ?>>Technology</option>
                        <option value="company" <?php echo ($event && $event['event_type'] === 'company') ? 'selected' : ''; ?>>Company</option>
                        <option value="other" <?php echo ($event && $event['event_type'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Featured</label>
                    <select name="is_featured">
                        <option value="0" <?php echo ($event && !$event['is_featured']) ? 'selected' : ''; ?>>No</option>
                        <option value="1" <?php echo ($event && $event['is_featured']) ? 'selected' : ''; ?>>Yes</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Description *</label>
                    <textarea name="description" rows="4" required><?php echo $event ? escape($event['description']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Description (Arabic)</label>
                    <textarea name="description_ar" rows="4"><?php echo $event ? escape($event['description_ar']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Significance</label>
                    <textarea name="significance" rows="3"><?php echo $event ? escape($event['significance']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Image</label>
                    <input type="file" name="image" accept="image/*">
                    <?php if ($event && $event['image']): ?>
                    <div style="margin-top: 0.5rem;">
                        <img src="<?php echo UPLOADS_URL . $event['image']; ?>" alt="Current image" style="max-width: 200px; border-radius: 8px;">
                        <br><small>Current image</small>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group full-width">
                    <label>Video URL</label>
                    <input type="url" name="video_url" value="<?php echo $event ? escape($event['video_url']) : ''; ?>" placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Event</button>
                <a href="events.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- List -->
    <?php if ($action === 'list'): ?>
    <div class="admin-table-container">
        <div class="table-toolbar">
            <div class="table-search">
                <input type="text" id="tableSearch" placeholder="Search events..." onkeyup="filterTable()">
            </div>
            <div class="table-info">
                Total: <?php echo count($events); ?> events
            </div>
        </div>
        
        <table class="admin-table" id="eventsTable">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Title</th>
                    <th>Era</th>
                    <th>Type</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($events)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #8892b0;">No events found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td style="font-family: 'Orbitron', monospace; color: #00d4ff;"><?php echo $event['year']; ?></td>
                    <td><strong><?php echo escape($event['title']); ?></strong></td>
                    <td><?php echo escape($event['era_name'] ?? 'N/A'); ?></td>
                    <td><span style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 0.1rem 0.6rem; border-radius: 12px;"><?php echo $event['event_type']; ?></span></td>
                    <td><?php echo $event['is_featured'] ? '⭐' : ''; ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="../event.php?slug=<?php echo $event['slug']; ?>" target="_blank" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="events.php?action=edit&id=<?php echo $event['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="events.php?action=delete&id=<?php echo $event['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this event?')"><i class="fas fa-trash"></i></a>
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
    color: #00d4ff;
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
    border-color: #00d4ff;
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
    border-color: #00d4ff;
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
    background: linear-gradient(135deg, #00d4ff, #7b2ffc);
    color: #fff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(0, 212, 255, 0.2);
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
    const table = document.getElementById('eventsTable');
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