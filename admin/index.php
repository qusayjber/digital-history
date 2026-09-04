<?php
// admin/index.php
// Digital History Admin - Dashboard

require_once '../includes/config.php';
require_once '../includes/security.php';
requireAdmin();

$pageTitle = 'Admin Dashboard';

// Get statistics
$stats = getStatistics();

// Recent activity
$recentActivity = db()->fetchAll(
    "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10"
);

// Recent users
$recentUsers = db()->fetchAll(
    "SELECT id, username, email, full_name, created_at FROM users WHERE is_active = 1 ORDER BY created_at DESC LIMIT 5"
);

// Page views today
$todayViews = db()->fetch(
    "SELECT COUNT(*) as count FROM page_views WHERE DATE(viewed_at) = CURDATE()"
)['count'] ?? 0;

// Total page views
$totalViews = db()->fetch(
    "SELECT COUNT(*) as count FROM page_views"
)['count'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Digital History</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <span>DIGITAL<span>HISTORY</span></span>
                <small>Admin</small>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active"><a href="index.php"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
                    <li><a href="events.php"><i class="fas fa-timeline"></i> Events</a></li>
                    <li><a href="people.php"><i class="fas fa-users"></i> People</a></li>
                    <li><a href="technologies.php"><i class="fas fa-microchip"></i> Technologies</a></li>
                    <li><a href="languages.php"><i class="fas fa-code"></i> Languages</a></li>
                    <li><a href="os.php"><i class="fas fa-desktop"></i> OS</a></li>
                    <li><a href="articles.php"><i class="fas fa-newspaper"></i> Articles</a></li>
                    <li><a href="media.php"><i class="fas fa-images"></i> Media</a></li>
                    <li><a href="quizzes.php"><i class="fas fa-question-circle"></i> Quizzes</a></li>
                    <li><a href="users.php"><i class="fas fa-user-cog"></i> Users</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <h1>Dashboard</h1>
                    <span class="header-date"><?php echo date('F j, Y'); ?></span>
                </div>
                <div class="header-right">
                    <span class="admin-user"><i class="fas fa-user-circle"></i> <?php echo escape($_SESSION['username']); ?></span>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(0, 212, 255, 0.1); color: #00d4ff;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['total_users']); ?></span>
                        <span class="stat-label">Total Users</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(123, 47, 252, 0.1); color: #7b2ffc;">
                        <i class="fas fa-timeline"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['total_events']); ?></span>
                        <span class="stat-label">Events</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(0, 255, 0, 0.1); color: #00ff88;">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['total_languages']); ?></span>
                        <span class="stat-label">Languages</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(255, 165, 0, 0.1); color: #ffa500;">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['total_articles']); ?></span>
                        <span class="stat-label">Articles</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(255, 0, 100, 0.1); color: #ff0064;">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($todayViews); ?></span>
                        <span class="stat-label">Views Today</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(0, 212, 255, 0.1); color: #00d4ff;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($totalViews); ?></span>
                        <span class="stat-label">Total Views</span>
                    </div>
                </div>
            </div>
            
            <!-- Two Column Layout -->
            <div class="admin-grid-2">
                <!-- Recent Activity -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3><i class="fas fa-clock"></i> Recent Activity</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentActivity)): ?>
                        <p class="empty-state">No activity recorded yet.</p>
                        <?php else: ?>
                        <ul class="activity-list">
                            <?php foreach ($recentActivity as $activity): ?>
                            <li>
                                <span class="activity-icon">
                                    <?php
                                    $icon = 'fa-circle';
                                    if (strpos($activity['action'], 'login') !== false) $icon = 'fa-sign-in-alt';
                                    elseif (strpos($activity['action'], 'create') !== false) $icon = 'fa-plus-circle';
                                    elseif (strpos($activity['action'], 'update') !== false) $icon = 'fa-edit';
                                    elseif (strpos($activity['action'], 'delete') !== false) $icon = 'fa-trash';
                                    ?>
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </span>
                                <span class="activity-text">
                                    <?php echo escape($activity['action']); ?>
                                    <?php if ($activity['details']): ?>
                                    <small><?php echo escape(json_encode(json_decode($activity['details'], true))); ?></small>
                                    <?php endif; ?>
                                </span>
                                <span class="activity-time"><?php echo timeAgo($activity['created_at']); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Users -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-plus"></i> Recent Users</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentUsers)): ?>
                        <p class="empty-state">No users registered yet.</p>
                        <?php else: ?>
                        <ul class="user-list">
                            <?php foreach ($recentUsers as $user): ?>
                            <li>
                                <span class="user-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                                <span class="user-info">
                                    <strong><?php echo escape($user['full_name'] ?: $user['username']); ?></strong>
                                    <small><?php echo escape($user['email']); ?></small>
                                </span>
                                <span class="user-date"><?php echo formatDate($user['created_at']); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="admin-card">
                <div class="card-header">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="events.php?action=add" class="quick-action">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Event</span>
                        </a>
                        <a href="people.php?action=add" class="quick-action">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Person</span>
                        </a>
                        <a href="technologies.php?action=add" class="quick-action">
                            <i class="fas fa-microchip"></i>
                            <span>Add Technology</span>
                        </a>
                        <a href="languages.php?action=add" class="quick-action">
                            <i class="fas fa-code"></i>
                            <span>Add Language</span>
                        </a>
                        <a href="articles.php?action=add" class="quick-action">
                            <i class="fas fa-plus"></i>
                            <span>New Article</span>
                        </a>
                        <a href="media.php" class="quick-action">
                            <i class="fas fa-images"></i>
                            <span>Media Manager</span>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <style>
        /* Admin CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            color: #ccd6f6;
            display: flex;
        }
        
        .admin-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: 240px;
            background: rgba(13, 17, 23, 0.95);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.5rem 0;
            min-height: 100vh;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            flex-shrink: 0;
        }
        
        .sidebar-brand {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .sidebar-brand span {
            font-family: 'Orbitron', monospace;
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            display: block;
        }
        
        .sidebar-brand span span {
            color: #00d4ff;
            display: inline;
        }
        
        .sidebar-brand small {
            color: #8892b0;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .sidebar-nav ul {
            list-style: none;
            padding: 0 0.8rem;
        }
        
        .sidebar-nav ul li {
            margin-bottom: 0.2rem;
        }
        
        .sidebar-nav ul li a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 1rem;
            color: #8892b0;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        .sidebar-nav ul li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-nav ul li.active a {
            color: #00d4ff;
            background: rgba(0, 212, 255, 0.08);
        }
        
        .sidebar-nav ul li a i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        /* Main */
        .admin-main {
            flex: 1;
            padding: 2rem;
            overflow-x: hidden;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .admin-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
        }
        
        .header-date {
            color: #8892b0;
            font-size: 0.9rem;
            margin-left: 1rem;
        }
        
        .admin-user {
            color: #8892b0;
            font-size: 0.95rem;
        }
        
        .admin-user i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
            color: #00d4ff;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .stat-info {
            display: flex;
            flex-direction: column;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #8892b0;
        }
        
        /* Cards */
        .admin-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        
        .card-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
        }
        
        .card-header h3 i {
            margin-right: 0.5rem;
            color: #00d4ff;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .empty-state {
            color: #8892b0;
            text-align: center;
            padding: 1rem 0;
        }
        
        /* Grid */
        .admin-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        /* Activity List */
        .activity-list {
            list-style: none;
        }
        
        .activity-list li {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }
        
        .activity-list li:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8892b0;
            flex-shrink: 0;
        }
        
        .activity-text {
            flex: 1;
            font-size: 0.9rem;
        }
        
        .activity-text small {
            display: block;
            color: #8892b0;
            font-size: 0.75rem;
        }
        
        .activity-time {
            font-size: 0.75rem;
            color: #8892b0;
            white-space: nowrap;
        }
        
        /* User List */
        .user-list {
            list-style: none;
        }
        
        .user-list li {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }
        
        .user-list li:last-child {
            border-bottom: none;
        }
        
        .user-avatar {
            font-size: 2rem;
            color: #8892b0;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-info strong {
            display: block;
            color: #fff;
            font-size: 0.95rem;
        }
        
        .user-info small {
            color: #8892b0;
            font-size: 0.8rem;
        }
        
        .user-date {
            font-size: 0.75rem;
            color: #8892b0;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.8rem;
        }
        
        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 8px;
            color: #ccd6f6;
            text-decoration: none;
            transition: all 0.3s;
            gap: 0.5rem;
        }
        
        .quick-action:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: #00d4ff;
            transform: translateY(-2px);
        }
        
        .quick-action i {
            font-size: 1.5rem;
            color: #00d4ff;
        }
        
        .quick-action span {
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .admin-grid-2 {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 60px;
                padding: 1rem 0;
            }
            
            .sidebar-brand span,
            .sidebar-brand small {
                display: none;
            }
            
            .sidebar-nav ul li a span {
                display: none;
            }
            
            .sidebar-nav ul li a {
                justify-content: center;
                padding: 0.7rem;
            }
            
            .sidebar-nav ul li a i {
                font-size: 1.2rem;
                margin: 0;
            }
            
            .admin-main {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</body>
</html>