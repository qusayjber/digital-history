<?php
// includes/header.php
// Digital History - Site Header

// Start output buffering if not already started
if (!ob_get_level()) {
    ob_start();
}

// Send security headers BEFORE any HTML output
// Check if headers haven't been sent yet
if (!headers_sent() && function_exists('setSecurityHeaders')) {
    setSecurityHeaders();
}

// Check if required functions exist, otherwise define fallbacks
if (!function_exists('getCurrentLanguage')) {
    function getCurrentLanguage() {
        return 'en';
    }
}

if (!function_exists('getLanguageDirection')) {
    function getLanguageDirection() {
        return 'ltr';
    }
}

if (!function_exists('isRTL')) {
    function isRTL() {
        return false;
    }
}

if (!function_exists('t')) {
    function t($key, $lang = null) {
        return $key;
    }
}

if (!function_exists('languageSwitcherHTML')) {
    function languageSwitcherHTML() {
        return '';
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}

if (!function_exists('escape')) {
    function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('getCurrentURL')) {
    function getCurrentURL() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}

// Define constants if not defined
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Digital History');
}

if (!defined('SITE_URL')) {
    define('SITE_URL', '/');
}

if (!defined('SITE_DESCRIPTION')) {
    define('SITE_DESCRIPTION', 'The interactive museum of computing, programming, the internet and the future.');
}

if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', 'assets/');
}

?>
<!DOCTYPE html>
<html lang="<?php echo function_exists('getCurrentLanguage') ? getCurrentLanguage() : 'en'; ?>" dir="<?php echo function_exists('getLanguageDirection') ? getLanguageDirection() : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle . ' - ' . SITE_NAME, ENT_QUOTES, 'UTF-8') : htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : htmlspecialchars(SITE_DESCRIPTION, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="digital history, computing, programming, internet, technology, museum, timeline">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle . ' - ' . SITE_NAME, ENT_QUOTES, 'UTF-8') : htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : htmlspecialchars(SITE_DESCRIPTION, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars(getCurrentURL(), ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo htmlspecialchars(ASSETS_URL . 'images/og-image.jpg', ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle . ' - ' . SITE_NAME, ENT_QUOTES, 'UTF-8') : htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : htmlspecialchars(SITE_DESCRIPTION, ENT_QUOTES, 'UTF-8'); ?>">
    
    <link rel="canonical" href="<?php echo htmlspecialchars(getCurrentURL(), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="icon" href="<?php echo htmlspecialchars(ASSETS_URL . 'icons/favicon.ico', ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars(ASSETS_URL . 'css/style.css', ENT_QUOTES, 'UTF-8'); ?>">
    <?php if (function_exists('isRTL') && isRTL()): ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(ASSETS_URL . 'css/rtl.css', ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>
    
    <!-- Dynamic CSS for page specific styles -->
    <?php if (isset($pageCSS) && !empty($pageCSS)): ?>
    <style><?php echo $pageCSS; ?></style>
    <?php endif; ?>
</head>
<body>
    
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loader-container">
            <div class="loader-text">INITIALIZING DIGITAL HISTORY...</div>
            <div class="loader-bar">
                <div class="loader-progress" id="loaderProgress"></div>
            </div>
            <div class="loader-status" id="loaderStatus">Loading Timeline...</div>
        </div>
    </div>
    
    <!-- Navigation -->
    <header class="site-header" role="banner">
        <div class="header-container">
            <div class="header-logo">
                <a href="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="logo-icon">🌐</span>
                    <span class="logo-text">DIGITAL<span class="logo-highlight">HISTORY</span></span>
                </a>
            </div>
            
            <nav class="main-nav" role="navigation" aria-label="Main Navigation">
                <ul class="nav-list">
                    <li><a href="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.home') : 'Home'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'timeline.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.timeline') : 'Timeline'; ?></a></li>
                    <li class="dropdown">
                        <a href="<?php echo htmlspecialchars(SITE_URL . 'computing.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.computing') : 'Computing'; ?> <span class="dropdown-arrow">▾</span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'computing.php', ENT_QUOTES, 'UTF-8'); ?>">Computing History</a></li>
                            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'programming.php', ENT_QUOTES, 'UTF-8'); ?>">Programming</a></li>
                            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'os-history.php', ENT_QUOTES, 'UTF-8'); ?>">Operating Systems</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'internet.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.internet') : 'Internet'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'web.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.web') : 'Web'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'cybersecurity.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.cybersecurity') : 'Cybersecurity'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'ai.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.ai') : 'AI'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'people.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.people') : 'People'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'museum.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.museum') : 'Museum'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'lab.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.lab') : 'Lab'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'games.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.games') : 'Games'; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(SITE_URL . 'future.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo function_exists('t') ? t('nav.future') : 'Future'; ?></a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <button class="search-toggle" aria-label="Search" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                
                <?php if (function_exists('languageSwitcherHTML')): ?>
                <?php echo languageSwitcherHTML(); ?>
                <?php endif; ?>
                
                <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                <div class="user-menu">
                    <button class="user-btn" aria-label="User menu">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'User'; ?></span>
                        <span class="dropdown-arrow">▾</span>
                    </button>
                    <ul class="user-dropdown">
                        <li><a href="<?php echo htmlspecialchars(SITE_URL . 'profile.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="<?php echo htmlspecialchars(SITE_URL . 'profile.php?tab=achievements', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-trophy"></i> Achievements</a></li>
                        <li><a href="<?php echo htmlspecialchars(SITE_URL . 'profile.php?tab=favorites', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-star"></i> Favorites</a></li>
                        <?php if (function_exists('isAdmin') && isAdmin()): ?>
                        <li><a href="<?php echo htmlspecialchars(SITE_URL . 'admin/', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-cog"></i> Admin Panel</a></li>
                        <?php endif; ?>
                        <li><hr></li>
                        <li><a href="<?php echo htmlspecialchars(SITE_URL . 'logout.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="auth-buttons">
                    <a href="<?php echo htmlspecialchars(SITE_URL . 'login.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline">Login</a>
                    <a href="<?php echo htmlspecialchars(SITE_URL . 'register.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">Sign Up</a>
                </div>
                <?php endif; ?>
                
                <button class="mobile-menu-toggle" aria-label="Toggle menu" id="mobileMenuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="search-bar" id="searchBar">
            <div class="search-container">
                <form action="<?php echo htmlspecialchars(SITE_URL . 'search.php', ENT_QUOTES, 'UTF-8'); ?>" method="GET" role="search">
                    <input type="text" name="q" placeholder="Search technology, people, events..." aria-label="Search" autocomplete="off">
                    <button type="submit" aria-label="Submit search"><i class="fas fa-search"></i></button>
                </form>
                <button class="search-close" id="searchClose"><i class="fas fa-times"></i></button>
            </div>
        </div>
    </header>
    
    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-header">
            <span class="mobile-nav-title">Menu</span>
            <button class="mobile-nav-close" id="mobileNavClose"><i class="fas fa-times"></i></button>
        </div>
        <ul class="mobile-nav-list">
            <li><a href="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-home"></i> <?php echo function_exists('t') ? t('nav.home') : 'Home'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'timeline.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-timeline"></i> <?php echo function_exists('t') ? t('nav.timeline') : 'Timeline'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'computing.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-desktop"></i> <?php echo function_exists('t') ? t('nav.computing') : 'Computing'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'programming.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-code"></i> <?php echo function_exists('t') ? t('nav.programming') : 'Programming'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'internet.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-globe"></i> <?php echo function_exists('t') ? t('nav.internet') : 'Internet'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'web.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-window-maximize"></i> <?php echo function_exists('t') ? t('nav.web') : 'Web'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'cybersecurity.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-shield"></i> <?php echo function_exists('t') ? t('nav.cybersecurity') : 'Cybersecurity'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'ai.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-brain"></i> <?php echo function_exists('t') ? t('nav.ai') : 'AI'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'people.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-users"></i> <?php echo function_exists('t') ? t('nav.people') : 'People'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'museum.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-landmark"></i> <?php echo function_exists('t') ? t('nav.museum') : 'Museum'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'lab.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-flask"></i> <?php echo function_exists('t') ? t('nav.lab') : 'Lab'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'games.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-gamepad"></i> <?php echo function_exists('t') ? t('nav.games') : 'Games'; ?></a></li>
            <li><a href="<?php echo htmlspecialchars(SITE_URL . 'future.php', ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-rocket"></i> <?php echo function_exists('t') ? t('nav.future') : 'Future'; ?></a></li>
        </ul>
        
        <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
        <div class="mobile-user-info">
            <p>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'User'; ?></p>
            <a href="<?php echo htmlspecialchars(SITE_URL . 'logout.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline btn-sm">Logout</a>
        </div>
        <?php else: ?>
        <div class="mobile-auth">
            <a href="<?php echo htmlspecialchars(SITE_URL . 'login.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline">Login</a>
            <a href="<?php echo htmlspecialchars(SITE_URL . 'register.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">Sign Up</a>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
    
    <main class="site-main">