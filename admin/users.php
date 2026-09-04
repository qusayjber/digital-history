<?php
// admin/users.php
// Digital History Admin - Users Management

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Manage Users';

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle user role update
if ($action === 'role' && isset($_GET['id']) && isset($_GET['role'])) {
    $id = intval($_GET['id']);
    $role = $_GET['role'];
    
    if ($id != $_SESSION['user_id']) { // Prevent self-role-change
        if (in_array($role, ['user', 'admin', 'editor'])) {
            db()->update('users', ['role' => $role], 'id = ?', [$id]);
            logActivity('admin_update_user_role', ['user_id' => $id, 'role' => $role]);
            $message = 'User role updated successfully.';
            
            // If user is now admin, add to admins table
            if ($role === 'admin') {
                $exists = db()->fetch("SELECT id FROM admins WHERE user_id = ?", [$id]);
                if (!$exists) {
                    db()->insert('admins', ['user_id' => $id, 'permissions' => '{"all": true}']);
                }
            }
        } else {
            $error = 'Invalid role.';
        }
    } else {
        $error = 'You cannot change your own role.';
    }
    $action = 'list';
}

// Delete user
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id != $_SESSION['user_id']) { // Prevent self-deletion
        db()->delete('users', 'id = ?', [$id]);
        logActivity('admin_delete_user', ['user_id' => $id]);
        $message = 'User deleted successfully.';
    } else {
        $error = 'You cannot delete your own account.';
    }
    $action = 'list';
}

// Toggle user status
if ($action === 'toggle' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id != $_SESSION['user_id']) { // Prevent self-deactivation
        $user = db()->fetch("SELECT is_active FROM users WHERE id = ?", [$id]);
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            db()->update('users', ['is_active' => $newStatus], 'id = ?', [$id]);
            logActivity('admin_toggle_user', ['user_id' => $id, 'status' => $newStatus]);
            $message = 'User status updated.';
        }
    } else {
        $error = 'You cannot deactivate your own account.';
    }
    $action = 'list';
}

$users = db()->fetchAll("SELECT * FROM users ORDER BY created_at DESC");

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-user-cog"></i> Users Management</h2>
        <div class="admin-page-stats">
            <span><i class="fas fa-users"></i> Total: <?php echo count($users); ?></span>
            <span><i class="fas fa-user-shield"></i> Admin: <?php echo count(array_filter($users, function($u) { return $u['role'] === 'admin'; })); ?></span>
            <span><i class="fas fa-user-edit"></i> Editor: <?php echo count(array_filter($users, function($u) { return $u['role'] === 'editor'; })); ?></span>
            <span><i class="fas fa-user"></i> User: <?php echo count(array_filter($users, function($u) { return $u['role'] === 'user'; })); ?></span>
        </div>
    </div>
    
    <?php if ($message): ?>
    <div class="admin-message success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="admin-message error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="admin-table-container">
        <div class="table-toolbar">
            <div class="table-search">
                <input type="text" id="tableSearch" placeholder="Search users by name, email, or username..." onkeyup="filterTable()">
            </div>
            <div class="table-actions-bulk">
                <span style="color: #8892b0; font-size: 0.8rem;">
                    <i class="fas fa-info-circle"></i> Click role to change
                </span>
            </div>
        </div>
        
        <table class="admin-table" id="usersTable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #8892b0;">No users found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <span style="font-size: 1.5rem;">
                                <?php if ($user['avatar']): ?>
                                <img src="<?php echo UPLOADS_URL . $user['avatar']; ?>" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                👤
                                <?php endif; ?>
                            </span>
                            <div>
                                <div style="color: #fff; font-weight: 500;"><?php echo escape($user['full_name'] ?: $user['username']); ?></div>
                                <div style="color: #8892b0; font-size: 0.75rem;">@<?php echo escape($user['username']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo escape($user['email']); ?></td>
                    <td>
                        <?php
                        // Fixed: Using if-elseif-else instead of nested ternary
                        if ($user['role'] === 'admin') {
                            $roleBg = 'rgba(0,212,255,0.1)';
                            $roleColor = '#00d4ff';
                            $roleBorder = 'rgba(0,212,255,0.2)';
                        } elseif ($user['role'] === 'editor') {
                            $roleBg = 'rgba(0,255,136,0.1)';
                            $roleColor = '#00ff88';
                            $roleBorder = 'rgba(0,255,136,0.2)';
                        } else {
                            $roleBg = 'rgba(255,255,255,0.05)';
                            $roleColor = '#8892b0';
                            $roleBorder = 'rgba(255,255,255,0.1)';
                        }
                        ?>
                        <select onchange="changeRole(<?php echo $user['id']; ?>, this.value)" style="background: <?php echo $roleBg; ?>; color: <?php echo $roleColor; ?>; border: 1px solid <?php echo $roleBorder; ?>; border-radius: 6px; padding: 0.15rem 0.5rem; font-size: 0.8rem; cursor: pointer; <?php echo $user['id'] == $_SESSION['user_id'] ? 'pointer-events: none; opacity: 0.6;' : ''; ?>">
                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="editor" <?php echo $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        <?php if ($user['id'] == $_SESSION['user_id']): ?>
                        <span style="font-size: 0.6rem; color: #8892b0; margin-left: 0.3rem;">(you)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="font-size: 0.8rem; color: <?php echo $user['is_active'] ? '#00ff88' : '#ff6b6b'; ?>;">
                            <?php echo $user['is_active'] ? '✅ Active' : '❌ Inactive'; ?>
                        </span>
                    </td>
                    <td style="font-size: 0.85rem; color: #8892b0;"><?php echo formatDate($user['created_at']); ?></td>
                    <td style="font-size: 0.85rem; color: #8892b0;">
                        <?php echo $user['last_login'] ? formatDate($user['last_login']) : 'Never'; ?>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="../profile.php?user=<?php echo $user['id']; ?>" target="_blank" class="btn-icon" title="View Profile"><i class="fas fa-eye"></i></a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="users.php?action=toggle&id=<?php echo $user['id']; ?>" class="btn-icon" title="<?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>" onclick="return confirm('Are you sure you want to <?php echo $user['is_active'] ? 'deactivate' : 'activate'; ?> this user?')">
                                <i class="fas <?php echo $user['is_active'] ? 'fa-pause' : 'fa-play'; ?>"></i>
                            </a>
                            <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
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

.admin-page-stats {
    display: flex;
    gap: 0.8rem;
    color: #8892b0;
    font-size: 0.85rem;
    flex-wrap: wrap;
}

.admin-page-stats span {
    background: rgba(255, 255, 255, 0.03);
    padding: 0.3rem 0.8rem;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.admin-page-stats span i {
    font-size: 0.8rem;
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
    min-width: 250px;
}

.table-search input:focus {
    outline: none;
    border-color: #00d4ff;
}

.table-actions-bulk {
    color: #8892b0;
    font-size: 0.8rem;
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
    font-size: 0.75rem;
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
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-icon:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
}

.btn-icon.delete:hover {
    color: #ff6b6b;
    background: rgba(255, 0, 0, 0.1);
}

select {
    transition: all 0.3s;
}

select:hover:not([style*="pointer-events: none"]) {
    border-color: #00d4ff !important;
}

select option {
    background: #0d1117;
    color: #ccd6f6;
}

@media (max-width: 768px) {
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
    
    .admin-page-stats {
        font-size: 0.75rem;
        gap: 0.3rem;
    }
    
    .admin-page-stats span {
        padding: 0.2rem 0.5rem;
    }
}
</style>

<script>
function filterTable() {
    const input = document.getElementById('tableSearch');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('usersTable');
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

function changeRole(userId, role) {
    if (confirm('Are you sure you want to change this user\'s role to ' + role + '?')) {
        window.location.href = 'users.php?action=role&id=' + userId + '&role=' + role;
    } else {
        // Reload to revert the select
        window.location.reload();
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