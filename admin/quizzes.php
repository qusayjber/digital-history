<?php
// admin/quizzes.php
// Digital History Admin - Quizzes Management

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Manage Quizzes';

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
            'description' => trim($_POST['description'] ?? ''),
            'description_ar' => trim($_POST['description_ar'] ?? ''),
            'difficulty' => $_POST['difficulty'] ?? 'medium',
            'time_limit' => intval($_POST['time_limit'] ?? 300),
            'passing_score' => intval($_POST['passing_score'] ?? 70),
            'is_active' => isset($_POST['is_active']) ? intval($_POST['is_active']) : 1
        ];
        
        if (empty($data['title']) || empty($data['slug'])) {
            $error = 'Please fill in all required fields.';
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update existing quiz
                $id = intval($_POST['id']);
                db()->update('quizzes', $data, 'id = ?', [$id]);
                logActivity('admin_update_quiz', ['quiz_id' => $id]);
                $message = 'Quiz updated successfully!';
            } else {
                // Insert new quiz
                $id = db()->insert('quizzes', $data);
                logActivity('admin_create_quiz', ['quiz_id' => $id]);
                $message = 'Quiz created successfully!';
            }
            $action = 'list';
        }
    }
}

// Delete quiz
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    db()->delete('quizzes', 'id = ?', [$id]);
    logActivity('admin_delete_quiz', ['quiz_id' => $id]);
    $message = 'Quiz deleted successfully.';
    $action = 'list';
}

$quizzes = db()->fetchAll("SELECT * FROM quizzes ORDER BY created_at DESC");

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-question-circle"></i> Quizzes Management</h2>
        <a href="quizzes.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Quiz
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
    $quiz = null;
    if ($action === 'edit' && isset($_GET['id'])) {
        $quiz = db()->fetch("SELECT * FROM quizzes WHERE id = ?", [$_GET['id']]);
    }
    ?>
    <div class="admin-form-container">
        <h3><?php echo $action === 'add' ? 'Add New Quiz' : 'Edit Quiz'; ?></h3>
        <form method="POST" action="quizzes.php?action=save">
            <?php echo getCSRFField(); ?>
            <?php if ($quiz): ?>
            <input type="hidden" name="id" value="<?php echo $quiz['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid-2">
                <div class="form-group full-width">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?php echo $quiz ? escape($quiz['title']) : ''; ?>" required>
                </div>
                <div class="form-group full-width">
                    <label>Title (Arabic)</label>
                    <input type="text" name="title_ar" value="<?php echo $quiz ? escape($quiz['title_ar']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Slug *</label>
                    <input type="text" name="slug" value="<?php echo $quiz ? escape($quiz['slug']) : ''; ?>" required>
                    <small>URL-friendly version (e.g., "tech-quiz")</small>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" value="<?php echo $quiz ? escape($quiz['category']) : ''; ?>" placeholder="technology, programming, internet, etc.">
                </div>
                <div class="form-group">
                    <label>Difficulty</label>
                    <select name="difficulty">
                        <option value="easy" <?php echo ($quiz && $quiz['difficulty'] === 'easy') ? 'selected' : ''; ?>>Easy</option>
                        <option value="medium" <?php echo ($quiz && $quiz['difficulty'] === 'medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="hard" <?php echo ($quiz && $quiz['difficulty'] === 'hard') ? 'selected' : ''; ?>>Hard</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Time Limit (seconds)</label>
                    <input type="number" name="time_limit" value="<?php echo $quiz ? $quiz['time_limit'] : '300'; ?>" min="10">
                </div>
                <div class="form-group">
                    <label>Passing Score (%)</label>
                    <input type="number" name="passing_score" value="<?php echo $quiz ? $quiz['passing_score'] : '70'; ?>" min="0" max="100">
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" rows="3"><?php echo $quiz ? escape($quiz['description']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Description (Arabic)</label>
                    <textarea name="description_ar" rows="3"><?php echo $quiz ? escape($quiz['description_ar']) : ''; ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Active</label>
                    <select name="is_active">
                        <option value="1" <?php echo ($quiz && $quiz['is_active']) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($quiz && !$quiz['is_active']) ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Quiz</button>
                <a href="quizzes.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
    <div class="admin-table-container">
        <div class="table-toolbar">
            <div class="table-search">
                <input type="text" id="tableSearch" placeholder="Search quizzes..." onkeyup="filterTable()">
            </div>
            <div class="table-info">
                Total: <?php echo count($quizzes); ?> quizzes
            </div>
        </div>
        
        <table class="admin-table" id="quizzesTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Difficulty</th>
                    <th>Questions</th>
                    <th>Passing Score</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quizzes)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #8892b0;">No quizzes found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($quizzes as $quiz): 
                    $questionCount = db()->fetch("SELECT COUNT(*) as count FROM quiz_questions WHERE quiz_id = ?", [$quiz['id']])['count'] ?? 0;
                ?>
                <tr>
                    <td><strong><?php echo escape($quiz['title']); ?></strong></td>
                    <td><?php echo escape($quiz['category']); ?></td>
                    <td>
                        <span style="font-size: 0.8rem; color: <?php 
                            if ($quiz['difficulty'] === 'easy') {
                                echo '#00ff88';
                            } elseif ($quiz['difficulty'] === 'medium') {
                                echo '#ffa500';
                            } else {
                                echo '#ff6b6b';
                            }
                        ?>;">
                            <?php echo ucfirst($quiz['difficulty']); ?>
                        </span>
                    </td>
                    <td><?php echo $questionCount; ?></td>
                    <td><?php echo $quiz['passing_score']; ?>%</td>
                    <td>
                        <span style="font-size: 0.8rem; color: <?php echo $quiz['is_active'] ? '#00ff88' : '#ff6b6b'; ?>;">
                            <?php echo $quiz['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="../quiz.php?slug=<?php echo $quiz['slug']; ?>" target="_blank" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="quizzes.php?action=edit&id=<?php echo $quiz['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="quizzes.php?action=questions&id=<?php echo $quiz['id']; ?>" class="btn-icon" title="Manage Questions"><i class="fas fa-list"></i></a>
                            <a href="quizzes.php?action=delete&id=<?php echo $quiz['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this quiz?')"><i class="fas fa-trash"></i></a>
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
    const table = document.getElementById('quizzesTable');
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