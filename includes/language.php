<?php
// includes/language.php
// Digital History - Localization Functions

// Supported languages
$SUPPORTED_LANGUAGES = [
    'en' => [
        'name' => 'English',
        'flag' => '🇬🇧',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'ar' => [
        'name' => 'العربية',
        'flag' => '🇸🇦',
        'direction' => 'rtl',
        'is_rtl' => true
    ],
    'fr' => [
        'name' => 'Français',
        'flag' => '🇫🇷',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'es' => [
        'name' => 'Español',
        'flag' => '🇪🇸',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'de' => [
        'name' => 'Deutsch',
        'flag' => '🇩🇪',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'zh' => [
        'name' => '中文',
        'flag' => '🇨🇳',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'ja' => [
        'name' => '日本語',
        'flag' => '🇯🇵',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'ru' => [
        'name' => 'Русский',
        'flag' => '🇷🇺',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'hi' => [
        'name' => 'हिन्दी',
        'flag' => '🇮🇳',
        'direction' => 'ltr',
        'is_rtl' => false
    ],
    'pt' => [
        'name' => 'Português',
        'flag' => '🇵🇹',
        'direction' => 'ltr',
        'is_rtl' => false
    ]
];

// Translation cache
$translationCache = [];
$translationCacheLoaded = false;

function loadTranslations() {
    global $translationCache, $translationCacheLoaded;
    
    if ($translationCacheLoaded) {
        return;
    }
    
    try {
        $result = db()->fetchAll("SELECT key_name, en, ar, fr, es, de, zh, ja, ru, hi, pt FROM translations");
        foreach ($result as $row) {
            $translationCache[$row['key_name']] = [
                'en' => $row['en'],
                'ar' => $row['ar'],
                'fr' => $row['fr'],
                'es' => $row['es'],
                'de' => $row['de'],
                'zh' => $row['zh'],
                'ja' => $row['ja'],
                'ru' => $row['ru'],
                'hi' => $row['hi'],
                'pt' => $row['pt']
            ];
        }
        $translationCacheLoaded = true;
    } catch (Exception $e) {
        // If database is not available, use empty cache
        $translationCache = [];
        $translationCacheLoaded = true;
    }
}

function getCurrentLanguage() {
    if (isset($_SESSION['language']) && isset($GLOBALS['SUPPORTED_LANGUAGES'][$_SESSION['language']])) {
        return $_SESSION['language'];
    }
    
    // Check browser language
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if (isset($GLOBALS['SUPPORTED_LANGUAGES'][$browserLang])) {
            return $browserLang;
        }
    }
    
    return 'en'; // Default
}

function setLanguage($lang) {
    if (isset($GLOBALS['SUPPORTED_LANGUAGES'][$lang])) {
        $_SESSION['language'] = $lang;
        return true;
    }
    return false;
}

function t($key, $lang = null) {
    global $translationCache;
    
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    loadTranslations();
    
    if (isset($translationCache[$key]) && isset($translationCache[$key][$lang]) && !empty($translationCache[$key][$lang])) {
        return $translationCache[$key][$lang];
    }
    
    // Fallback to English
    if (isset($translationCache[$key]) && isset($translationCache[$key]['en'])) {
        return $translationCache[$key]['en'];
    }
    
    // Return the key if no translation found
    return $key;
}

function t_safe($key, $lang = null) {
    return escape(t($key, $lang));
}

function getLanguageDirection($lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    $languages = $GLOBALS['SUPPORTED_LANGUAGES'];
    return isset($languages[$lang]['is_rtl']) && $languages[$lang]['is_rtl'] ? 'rtl' : 'ltr';
}

function isRTL($lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    $languages = $GLOBALS['SUPPORTED_LANGUAGES'];
    return isset($languages[$lang]['is_rtl']) && $languages[$lang]['is_rtl'];
}

function getLanguageName($lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    $languages = $GLOBALS['SUPPORTED_LANGUAGES'];
    return isset($languages[$lang]['name']) ? $languages[$lang]['name'] : $lang;
}

function getLanguageFlag($lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    $languages = $GLOBALS['SUPPORTED_LANGUAGES'];
    return isset($languages[$lang]['flag']) ? $languages[$lang]['flag'] : '🌐';
}

function getSupportedLanguages() {
    return $GLOBALS['SUPPORTED_LANGUAGES'];
}

function languageSwitcherHTML() {
    $currentLang = getCurrentLanguage();
    $languages = getSupportedLanguages();
    
    $html = '<div class="language-switcher">';
    $html .= '<button class="lang-current" aria-label="' . t('nav.language') . '">';
    $html .= getLanguageFlag($currentLang) . ' ' . getLanguageName($currentLang);
    $html .= ' <span class="lang-arrow">▼</span>';
    $html .= '</button>';
    $html .= '<ul class="lang-dropdown">';
    
    foreach ($languages as $code => $info) {
        $isActive = ($code === $currentLang) ? ' class="active"' : '';
        $html .= '<li' . $isActive . '>';
        $html .= '<a href="#" data-lang="' . $code . '">';
        $html .= $info['flag'] . ' ' . $info['name'];
        $html .= '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    
    return $html;
}
?>