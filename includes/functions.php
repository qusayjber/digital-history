<?php
// includes/functions.php
// Digital History - Core Functions

// Pagination
function paginate($totalItems, $itemsPerPage = 20, $currentPage = null) {
    if ($currentPage === null) {
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    }
    
    $totalPages = max(1, ceil($totalItems / $itemsPerPage));
    $currentPage = min($currentPage, $totalPages);
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    return [
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'limit' => $itemsPerPage,
        'has_previous' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'previous_page' => $currentPage - 1,
        'next_page' => $currentPage + 1,
        'items_per_page' => $itemsPerPage,
        'total_items' => $totalItems
    ];
}

function renderPagination($pagination, $baseUrl) {
    if ($pagination['total_pages'] <= 1) {
        return '';
    }
    
    $html = '<nav class="pagination" aria-label="Page navigation">';
    $html .= '<ul class="pagination-list">';
    
    // Previous
    if ($pagination['has_previous']) {
        $html .= '<li><a href="' . $baseUrl . '?page=' . $pagination['previous_page'] . '" aria-label="Previous">&laquo;</a></li>';
    } else {
        $html .= '<li class="disabled"><span>&laquo;</span></li>';
    }
    
    // Page numbers
    $start = max(1, $pagination['current_page'] - 2);
    $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
    
    if ($start > 1) {
        $html .= '<li><a href="' . $baseUrl . '?page=1">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="ellipsis"><span>...</span></li>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $isActive = $i === $pagination['current_page'] ? ' class="active"' : '';
        $html .= '<li' . $isActive . '><a href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }
    
    if ($end < $pagination['total_pages']) {
        if ($end < $pagination['total_pages'] - 1) {
            $html .= '<li class="ellipsis"><span>...</span></li>';
        }
        $html .= '<li><a href="' . $baseUrl . '?page=' . $pagination['total_pages'] . '">' . $pagination['total_pages'] . '</a></li>';
    }
    
    // Next
    if ($pagination['has_next']) {
        $html .= '<li><a href="' . $baseUrl . '?page=' . $pagination['next_page'] . '" aria-label="Next">&raquo;</a></li>';
    } else {
        $html .= '<li class="disabled"><span>&raquo;</span></li>';
    }
    
    $html .= '</ul>';
    $html .= '</nav>';
    
    return $html;
}

// URL Helpers
function getCurrentURL() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function getBaseURL() {
    return SITE_URL;
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function redirectBack() {
    if (isset($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        redirect(SITE_URL);
    }
}

// Slug generation
function createSlug($string, $maxLength = 100) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/\s+/', '-', $string);
    $string = trim($string, '-');
    return substr($string, 0, $maxLength);
}

// Date formatting
function formatDate($timestamp, $format = 'F j, Y') {
    return date($format, strtotime($timestamp));
}

function formatYear($year) {
    if ($year < 0) {
        return abs($year) . ' BCE';
    }
    return $year;
}

function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } elseif ($diff < 2592000) {
        return floor($diff / 604800) . ' weeks ago';
    } elseif ($diff < 31536000) {
        return floor($diff / 2592000) . ' months ago';
    } else {
        return floor($diff / 31536000) . ' years ago';
    }
}

// Text truncation
function truncate($text, $length = 150, $ellipsis = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $ellipsis;
}

// Get era by slug
function getEra($slug) {
    return db()->fetch("SELECT * FROM eras WHERE slug = ? AND is_active = 1", [$slug]);
}

function getEras() {
    return db()->fetchAll("SELECT * FROM eras WHERE is_active = 1 ORDER BY sort_order, start_year");
}

// Get timeline events
function getTimelineEvents($eraId = null, $limit = null) {
    $sql = "SELECT * FROM timeline_events WHERE is_active = 1";
    $params = [];
    
    if ($eraId !== null) {
        $sql .= " AND era_id = ?";
        $params[] = $eraId;
    }
    
    $sql .= " ORDER BY year, id";
    
    if ($limit !== null) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
    }
    
    return db()->fetchAll($sql, $params);
}

function getFeaturedEvents($limit = 6) {
    return db()->fetchAll(
        "SELECT * FROM timeline_events WHERE is_active = 1 AND is_featured = 1 ORDER BY year DESC LIMIT ?",
        [$limit]
    );
}

// Get people
function getPeople($limit = null) {
    $sql = "SELECT * FROM people WHERE is_active = 1 ORDER BY birth_year";
    if ($limit !== null) {
        $sql .= " LIMIT ?";
        return db()->fetchAll($sql, [$limit]);
    }
    return db()->fetchAll($sql);
}

function getPerson($slug) {
    return db()->fetch("SELECT * FROM people WHERE slug = ? AND is_active = 1", [$slug]);
}

function getPersonEvents($personId) {
    return db()->fetchAll(
        "SELECT e.* FROM timeline_events e 
         JOIN person_events pe ON e.id = pe.event_id 
         WHERE pe.person_id = ? AND e.is_active = 1 
         ORDER BY e.year",
        [$personId]
    );
}

// Get programming languages
function getProgrammingLanguages() {
    return db()->fetchAll("SELECT * FROM programming_languages WHERE is_active = 1 ORDER BY year_created");
}

function getProgrammingLanguage($slug) {
    return db()->fetch("SELECT * FROM programming_languages WHERE slug = ? AND is_active = 1", [$slug]);
}

// Get technologies
function getTechnologies($category = null) {
    $sql = "SELECT * FROM technologies WHERE is_active = 1";
    $params = [];
    if ($category !== null) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY year_introduced";
    return db()->fetchAll($sql, $params);
}

function getTechnology($slug) {
    return db()->fetch("SELECT * FROM technologies WHERE slug = ? AND is_active = 1", [$slug]);
}

// Get operating systems
function getOperatingSystems() {
    return db()->fetchAll("SELECT * FROM operating_systems WHERE is_active = 1 ORDER BY year_released");
}

function getOperatingSystem($slug) {
    return db()->fetch("SELECT * FROM operating_systems WHERE slug = ? AND is_active = 1", [$slug]);
}

// Get articles
function getArticles($category = null, $limit = null) {
    $sql = "SELECT * FROM articles WHERE status = 'published'";
    $params = [];
    if ($category !== null) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY published_at DESC";
    if ($limit !== null) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
    }
    return db()->fetchAll($sql, $params);
}

function getArticle($slug) {
    return db()->fetch("SELECT * FROM articles WHERE slug = ? AND status = 'published'", [$slug]);
}

// Get categories
function getCategories($parentId = null) {
    $sql = "SELECT * FROM categories WHERE is_active = 1";
    $params = [];
    if ($parentId !== null) {
        $sql .= " AND parent_id = ?";
        $params[] = $parentId;
    }
    $sql .= " ORDER BY sort_order, name";
    return db()->fetchAll($sql, $params);
}

// Get quizzes
function getQuizzes($category = null) {
    $sql = "SELECT * FROM quizzes WHERE is_active = 1";
    $params = [];
    if ($category !== null) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY id";
    return db()->fetchAll($sql, $params);
}

function getQuiz($slug) {
    return db()->fetch("SELECT * FROM quizzes WHERE slug = ? AND is_active = 1", [$slug]);
}

function getQuizQuestions($quizId) {
    return db()->fetchAll(
        "SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY sort_order",
        [$quizId]
    );
}

function getQuizAnswers($questionId) {
    return db()->fetchAll(
        "SELECT * FROM quiz_answers WHERE question_id = ? ORDER BY sort_order",
        [$questionId]
    );
}

// Get achievements
function getAchievements() {
    return db()->fetchAll("SELECT * FROM achievements WHERE is_active = 1 ORDER BY points");
}

function getUserAchievements($userId) {
    return db()->fetchAll(
        "SELECT a.*, ua.unlocked_at FROM achievements a 
         JOIN user_achievements ua ON a.id = ua.achievement_id 
         WHERE ua.user_id = ? 
         ORDER BY ua.unlocked_at",
        [$userId]
    );
}

function unlockAchievement($userId, $achievementSlug) {
    $achievement = db()->fetch("SELECT id FROM achievements WHERE slug = ?", [$achievementSlug]);
    if (!$achievement) return false;
    
    // Check if already unlocked
    $existing = db()->fetch(
        "SELECT id FROM user_achievements WHERE user_id = ? AND achievement_id = ?",
        [$userId, $achievement['id']]
    );
    if ($existing) return false;
    
    return db()->insert('user_achievements', [
        'user_id' => $userId,
        'achievement_id' => $achievement['id']
    ]);
}

// Search
function search($query) {
    $results = [
        'events' => [],
        'people' => [],
        'technologies' => [],
        'languages' => [],
        'articles' => [],
        'os' => []
    ];
    
    $searchTerm = '%' . $query . '%';
    
    // Search events
    $results['events'] = db()->fetchAll(
        "SELECT * FROM timeline_events WHERE is_active = 1 AND (title LIKE ? OR description LIKE ?) ORDER BY year LIMIT 10",
        [$searchTerm, $searchTerm]
    );
    
    // Search people
    $results['people'] = db()->fetchAll(
        "SELECT * FROM people WHERE is_active = 1 AND (full_name LIKE ? OR biography LIKE ?) LIMIT 10",
        [$searchTerm, $searchTerm]
    );
    
    // Search technologies
    $results['technologies'] = db()->fetchAll(
        "SELECT * FROM technologies WHERE is_active = 1 AND (name LIKE ? OR description LIKE ?) LIMIT 10",
        [$searchTerm, $searchTerm]
    );
    
    // Search programming languages
    $results['languages'] = db()->fetchAll(
        "SELECT * FROM programming_languages WHERE is_active = 1 AND (name LIKE ? OR description LIKE ?) LIMIT 10",
        [$searchTerm, $searchTerm]
    );
    
    // Search articles
    $results['articles'] = db()->fetchAll(
        "SELECT * FROM articles WHERE status = 'published' AND (title LIKE ? OR content LIKE ?) LIMIT 10",
        [$searchTerm, $searchTerm]
    );
    
    // Search operating systems
    $results['os'] = db()->fetchAll(
        "SELECT * FROM operating_systems WHERE is_active = 1 AND (name LIKE ? OR description LIKE ?) LIMIT 10",
        [$searchTerm, $searchTerm]
    );
    
    return $results;
}

// Get statistics
function getStatistics() {
    $stats = [];
    
    $stats['total_users'] = db()->fetch("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'] ?? 0;
    $stats['total_events'] = db()->fetch("SELECT COUNT(*) as count FROM timeline_events WHERE is_active = 1")['count'] ?? 0;
    $stats['total_people'] = db()->fetch("SELECT COUNT(*) as count FROM people WHERE is_active = 1")['count'] ?? 0;
    $stats['total_technologies'] = db()->fetch("SELECT COUNT(*) as count FROM technologies WHERE is_active = 1")['count'] ?? 0;
    $stats['total_languages'] = db()->fetch("SELECT COUNT(*) as count FROM programming_languages WHERE is_active = 1")['count'] ?? 0;
    $stats['total_articles'] = db()->fetch("SELECT COUNT(*) as count FROM articles WHERE status = 'published'")['count'] ?? 0;
    
    return $stats;
}

// Track page view
function trackPageView($page) {
    $userId = $_SESSION['user_id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $referrer = $_SERVER['HTTP_REFERER'] ?? null;
    $sessionId = session_id();
    
    return db()->insert('page_views', [
        'page' => $page,
        'user_id' => $userId,
        'ip_address' => $ip,
        'user_agent' => $userAgent,
        'referrer' => $referrer,
        'session_id' => $sessionId
    ]);
}

// Log activity
function logActivity($action, $details = []) {
    $userId = $_SESSION['user_id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    return db()->insert('activity_logs', [
        'user_id' => $userId,
        'action' => $action,
        'details' => json_encode($details),
        'ip_address' => $ip,
        'user_agent' => $userAgent
    ]);
}

// Breadcrumbs
function generateBreadcrumbs($items) {
    $html = '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    $html .= '<ol>';
    $html .= '<li><a href="' . SITE_URL . '">' . t('nav.home') . '</a></li>';
    
    foreach ($items as $name => $url) {
        if ($url) {
            $html .= '<li><a href="' . $url . '">' . $name . '</a></li>';
        } else {
            $html .= '<li aria-current="page">' . $name . '</li>';
        }
    }
    
    $html .= '</ol>';
    $html .= '</nav>';
    return $html;
}
?>