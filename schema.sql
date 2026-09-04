-- ======================================================
-- DIGITAL HISTORY - COMPLETE DATABASE SCHEMA (FIXED)
-- ======================================================

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS digital_history 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE digital_history;

-- ======================================================
-- 1. جداول المستخدمين والمشرفين
-- ======================================================

-- جدول المستخدمين
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    bio TEXT,
    avatar VARCHAR(255),
    role ENUM('user', 'admin', 'editor') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    preferences JSON,
    INDEX idx_email (email),
    INDEX idx_username (username)
);

-- جدول المشرفين
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_admin_user (user_id)
);

-- ======================================================
-- 2. جداول العصور والأحداث
-- ======================================================

-- جدول العصور
CREATE TABLE IF NOT EXISTS eras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    start_year INT,
    end_year INT,
    description TEXT,
    description_ar TEXT,
    icon VARCHAR(50),
    color VARCHAR(20),
    background_image VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_sort (sort_order)
);

-- جدول الأحداث الزمنية
CREATE TABLE IF NOT EXISTS timeline_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    era_id INT,
    title VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    year INT NOT NULL,
    year_end INT NULL,
    description TEXT,
    description_ar TEXT,
    significance TEXT,
    significance_ar TEXT,
    image VARCHAR(255),
    video_url VARCHAR(255),
    event_type ENUM('invention', 'person', 'milestone', 'technology', 'company', 'other') DEFAULT 'milestone',
    importance_score INT DEFAULT 1,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (era_id) REFERENCES eras(id) ON DELETE SET NULL,
    INDEX idx_era (era_id),
    INDEX idx_year (year),
    INDEX idx_slug (slug),
    INDEX idx_featured (is_featured)
);

-- ======================================================
-- 3. جداول الأشخاص
-- ======================================================

-- جدول الأشخاص
CREATE TABLE IF NOT EXISTS people (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    full_name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    birth_year INT,
    death_year INT,
    nationality VARCHAR(100),
    nationality_ar VARCHAR(100),
    biography TEXT,
    biography_ar TEXT,
    contributions TEXT,
    contributions_ar TEXT,
    portrait VARCHAR(255),
    known_for VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_birth (birth_year)
);

-- جدول علاقات الأشخاص بالأحداث
CREATE TABLE IF NOT EXISTS person_events (
    person_id INT NOT NULL,
    event_id INT NOT NULL,
    role VARCHAR(100),
    PRIMARY KEY (person_id, event_id),
    FOREIGN KEY (person_id) REFERENCES people(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES timeline_events(id) ON DELETE CASCADE
);

-- ======================================================
-- 4. جداول التقنيات
-- ======================================================

-- جدول التقنيات
CREATE TABLE IF NOT EXISTS technologies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    category VARCHAR(50),
    category_ar VARCHAR(50),
    description TEXT,
    description_ar TEXT,
    year_introduced INT,
    inventor VARCHAR(100),
    image VARCHAR(255),
    icon VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_category (category),
    INDEX idx_year (year_introduced)
);

-- جدول علاقات التقنيات بالأحداث
CREATE TABLE IF NOT EXISTS technology_events (
    technology_id INT NOT NULL,
    event_id INT NOT NULL,
    PRIMARY KEY (technology_id, event_id),
    FOREIGN KEY (technology_id) REFERENCES technologies(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES timeline_events(id) ON DELETE CASCADE
);

-- ======================================================
-- 5. جداول لغات البرمجة
-- ======================================================

-- جدول لغات البرمجة
CREATE TABLE IF NOT EXISTS programming_languages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    year_created INT,
    creator VARCHAR(100),
    creator_ar VARCHAR(100),
    paradigm VARCHAR(100),
    typing_discipline VARCHAR(100),
    description TEXT,
    description_ar TEXT,
    use_cases TEXT,
    use_cases_ar TEXT,
    code_example TEXT,
    logo VARCHAR(255),
    popularity_score INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_year (year_created),
    INDEX idx_popularity (popularity_score)
);

-- ======================================================
-- 6. جداول أنظمة التشغيل
-- ======================================================

-- جدول أنظمة التشغيل
CREATE TABLE IF NOT EXISTS operating_systems (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    company VARCHAR(100),
    year_released INT,
    version VARCHAR(50),
    type ENUM('desktop', 'server', 'mobile', 'embedded', 'other') DEFAULT 'desktop',
    description TEXT,
    description_ar TEXT,
    screenshot VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_year (year_released),
    INDEX idx_type (type)
);

-- ======================================================
-- 7. جداول المقالات
-- ======================================================

-- جدول المقالات
CREATE TABLE IF NOT EXISTS articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    category VARCHAR(50),
    category_ar VARCHAR(50),
    content LONGTEXT,
    content_ar LONGTEXT,
    excerpt TEXT,
    excerpt_ar TEXT,
    featured_image VARCHAR(255),
    author_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    view_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_published (published_at),
    FULLTEXT INDEX idx_search (title, content),
    FULLTEXT INDEX idx_search_ar (title_ar, content_ar)
);

-- جدول التصنيفات
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    description_ar TEXT,
    parent_id INT NULL,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id)
);

-- جدول علاقات المقالات بالتصنيفات
CREATE TABLE IF NOT EXISTS article_categories (
    article_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- ======================================================
-- 8. جداول التجارب والاختبارات
-- ======================================================

-- جدول التجارب
CREATE TABLE IF NOT EXISTS experiments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    description_ar TEXT,
    type VARCHAR(50) NOT NULL,
    config JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_type (type)
);

-- جدول الاختبارات
CREATE TABLE IF NOT EXISTS quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    description_ar TEXT,
    category VARCHAR(50),
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
    time_limit INT DEFAULT 300,
    passing_score INT DEFAULT 70,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_category (category)
);

-- جدول أسئلة الاختبارات
CREATE TABLE IF NOT EXISTS quiz_questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT NOT NULL,
    question TEXT NOT NULL,
    question_ar TEXT,
    type ENUM('multiple_choice', 'true_false', 'fill_blank') DEFAULT 'multiple_choice',
    points INT DEFAULT 1,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    INDEX idx_quiz (quiz_id)
);

-- جدول إجابات الاختبارات
CREATE TABLE IF NOT EXISTS quiz_answers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT NOT NULL,
    answer TEXT NOT NULL,
    answer_ar TEXT,
    is_correct BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE,
    INDEX idx_question (question_id)
);

-- ======================================================
-- 9. جداول الإنجازات والمستخدمين
-- ======================================================

-- جدول الإنجازات
CREATE TABLE IF NOT EXISTS achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    description_ar TEXT,
    icon VARCHAR(50),
    points INT DEFAULT 10,
    requirement_type VARCHAR(50),
    requirement_value JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
);

-- جدول إنجازات المستخدمين
CREATE TABLE IF NOT EXISTS user_achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_achievement (user_id, achievement_id),
    INDEX idx_user (user_id)
);

-- جدول المفضلات
CREATE TABLE IF NOT EXISTS user_favorites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    item_type VARCHAR(50) NOT NULL,
    item_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, item_type, item_id),
    INDEX idx_user (user_id),
    INDEX idx_item (item_type, item_id)
);

-- جدول محاولات الاختبارات
CREATE TABLE IF NOT EXISTS user_quiz_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT DEFAULT 0,
    total_possible INT DEFAULT 0,
    time_taken INT DEFAULT 0,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_quiz (quiz_id)
);

-- ======================================================
-- 10. جداول التحليلات والسجلات
-- ======================================================

-- جدول مشاهدات الصفحات
CREATE TABLE IF NOT EXISTS page_views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page VARCHAR(255) NOT NULL,
    user_id INT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referrer VARCHAR(255),
    session_id VARCHAR(100),
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_page (page),
    INDEX idx_viewed (viewed_at),
    INDEX idx_ip (ip_address)
);

-- جدول سجلات النشاط
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    details JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
);

-- ======================================================
-- 11. جداول الترجمة والإعدادات
-- ======================================================

-- جدول الترجمات
CREATE TABLE IF NOT EXISTS translations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(255) NOT NULL,
    en TEXT,
    ar TEXT,
    fr TEXT,
    es TEXT,
    de TEXT,
    zh TEXT,
    ru TEXT,
    ja TEXT,
    ko TEXT,
    pt TEXT,
    it TEXT,
    nl TEXT,
    pl TEXT,
    tr TEXT,
    vi TEXT,
    hi TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_key (key_name),
    INDEX idx_key (key_name)
);

-- جدول الإعدادات
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    group_name VARCHAR(50),
    is_public BOOLEAN DEFAULT FALSE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (key_name),
    INDEX idx_group (group_name)
);

-- ======================================================
-- 12. جدول الوسائط
-- ======================================================

-- جدول الملفات
CREATE TABLE IF NOT EXISTS media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_size INT NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    alt_text_ar VARCHAR(255),
    caption TEXT,
    caption_ar TEXT,
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_type (file_type),
    INDEX idx_uploaded (created_at)
);

-- ======================================================
-- 13. جدول إعادة تعيين كلمة المرور
-- ======================================================

-- جدول طلبات إعادة تعيين كلمة المرور
CREATE TABLE IF NOT EXISTS password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
);

-- ======================================================
-- الإعدادات الأساسية
-- ======================================================

INSERT INTO settings (key_name, value, group_name, is_public) VALUES
('site_name', 'Digital History', 'general', 1),
('site_description', 'The interactive museum of computing, programming, the internet and the future.', 'general', 1),
('default_language', 'en', 'general', 1),
('enable_registration', 'true', 'general', 1),
('maintenance_mode', 'false', 'general', 0),
('contact_email', 'contact@digitalhistory.com', 'contact', 1),
('meta_keywords', 'digital history, computing, programming, internet, technology, museum, timeline', 'seo', 1);

-- ======================================================
-- البيانات الأساسية - العصور
-- ======================================================

INSERT INTO eras (name, name_ar, slug, start_year, end_year, description, description_ar, icon, color, sort_order) VALUES
('Mechanical Computing', 'الحوسبة الميكانيكية', 'mechanical-computing', 1800, 1939, 'The era of mechanical calculators and early computing machines using gears and levers.', 'عصر الآلات الحاسبة الميكانيكية وأجهزة الحوسبة المبكرة باستخدام التروس والروافع.', '⚙️', '#ffa500', 1),
('Early Computers', 'أجهزة الكمبيوتر المبكرة', 'early-computers', 1940, 1959, 'The birth of electronic computers using vacuum tubes and early transistors.', 'ولادة أجهزة الكمبيوتر الإلكترونية باستخدام الأنابيب المفرغة والترانزستورات المبكرة.', '💡', '#ff6b6b', 2),
('Programming Begins', 'بداية البرمجة', 'programming-begins', 1950, 1969, 'The development of early programming languages and software.', 'تطوير لغات البرمجة المبكرة والبرمجيات.', '💻', '#00d4ff', 3),
('Birth of the Internet', 'ولادة الإنترنت', 'birth-of-internet', 1960, 1979, 'The creation of ARPANET and the foundations of the internet.', 'إنشاء ARPANET وأسس الإنترنت.', '🌐', '#7b2ffc', 4),
('World Wide Web', 'شبكة الويب العالمية', 'world-wide-web', 1980, 1999, 'The invention of the World Wide Web and the dot-com boom.', 'اختراع شبكة الويب العالمية وطفرة الدوت كوم.', '🌍', '#00ff88', 5),
('Personal Computing', 'الحوسبة الشخصية', 'personal-computing', 1980, 1999, 'The rise of personal computers and the home computing revolution.', 'صعود أجهزة الكمبيوتر الشخصية وثورة الحوسبة المنزلية.', '🖥️', '#ff0064', 6),
('Web 2.0', 'الويب 2.0', 'web-20', 2000, 2009, 'The interactive web, social media, and user-generated content.', 'الويب التفاعلي ووسائل التواصل الاجتماعي والمحتوى الذي ينشئه المستخدم.', '📱', '#00d4ff', 7),
('Mobile Revolution', 'الثورة المحمولة', 'mobile-revolution', 2007, 2019, 'The smartphone revolution and mobile-first internet.', 'ثورة الهواتف الذكية والإنترنت الذي يعطي الأولوية للجوال.', '📱', '#ffa500', 8),
('Cloud Computing', 'الحوسبة السحابية', 'cloud-computing', 2006, 2020, 'The shift to cloud infrastructure and scalable computing.', 'التحول إلى البنية التحتية السحابية والحوسبة القابلة للتطوير.', '☁️', '#00ff88', 9),
('AI Revolution', 'ثورة الذكاء الاصطناعي', 'ai-revolution', 2010, 2030, 'The rise of artificial intelligence, deep learning, and generative AI.', 'صعود الذكاء الاصطناعي والتعلم العميق والذكاء الاصطناعي التوليدي.', '🧠', '#7b2ffc', 10),
('Future Internet', 'إنترنت المستقبل', 'future-internet', 2030, 2100, 'Predictions for quantum internet, AI agents, and beyond.', 'توقعات لإنترنت الكم والوكلاء الذكائيين وما بعد ذلك.', '🚀', '#00d4ff', 11);

-- ======================================================
-- الأحداث الزمنية الرئيسية
-- ======================================================

INSERT INTO timeline_events (era_id, title, title_ar, slug, year, description, description_ar, significance, event_type, is_featured) VALUES
-- العصر الميكانيكي
(1, 'Jacquard Loom', 'منسج جاكار', 'jacquard-loom', 1801, 'The first programmable machine using punch cards to control weaving patterns.', 'أول آلة قابلة للبرمجة باستخدام البطاقات المثقبة للتحكم في أنماط النسيج.', 'Revolutionized textile manufacturing and introduced programmable machines.', 'invention', 1),
(1, 'Difference Engine', 'محرك الفروقات', 'difference-engine', 1822, 'Charles Babbage\'s mechanical calculator designed to compute polynomial functions.', 'الآلة الحاسبة الميكانيكية لتشارلز بابيج المصممة لحساب الدوال متعددة الحدود.', 'First concept of a mechanical computing device.', 'invention', 1),
(1, 'Analytical Engine', 'المحرك التحليلي', 'analytical-engine', 1837, 'Babbage\'s design for a general-purpose mechanical computer with memory and arithmetic logic.', 'تصميم بابيج لكمبيوتر ميكانيكي للأغراض العامة مع ذاكرة ومنطق حسابي.', 'Considered the first design for a general-purpose computer.', 'invention', 1),

-- العصر الإلكتروني
(2, 'ENIAC', 'إينياك', 'eniac', 1945, 'The Electronic Numerical Integrator and Computer, first electronic general-purpose computer.', 'المدمج العددي الإلكتروني والكمبيوتر، أول كمبيوتر إلكتروني للأغراض العامة.', 'Revolutionized computing and led to modern computers.', 'invention', 1),
(2, 'UNIVAC I', 'يونيفاك الأول', 'univac-i', 1951, 'The Universal Automatic Computer, first commercially produced computer in the US.', 'الكمبيوتر التلقائي العالمي، أول كمبيوتر يتم إنتاجه تجارياً في الولايات المتحدة.', 'Paved the way for the commercial computer industry.', 'invention', 1),
(2, 'IBM 701', 'آي بي إم 701', 'ibm-701', 1952, 'IBM\'s first commercial scientific computer.', 'أول كمبيوتر علمي تجاري من آي بي إم.', 'Established IBM as a major player in computing.', 'invention', 0),
(2, 'First Transistor Computer', 'أول كمبيوتر بالترانزستور', 'first-transistor-computer', 1953, 'The University of Manchester builds the first transistor computer.', 'تطور جامعة مانشستر أول كمبيوتر بالترانزستور.', 'Demonstrated the potential of transistors for computing.', 'milestone', 0),

-- بداية البرمجة
(3, 'FORTRAN Created', 'إنشاء فورتران', 'fortran-created', 1957, 'The first high-level programming language, designed for scientific computing.', 'أول لغة برمجة عالية المستوى، مصممة للحوسبة العلمية.', 'Made programming more accessible to scientists and engineers.', 'technology', 1),
(3, 'LISP Created', 'إنشاء ليسب', 'lisp-created', 1958, 'The second high-level language, designed for artificial intelligence research.', 'ثاني لغة عالية المستوى، مصممة لأبحاث الذكاء الاصطناعي.', 'Became the language of choice for AI research.', 'technology', 1),
(3, 'COBOL Created', 'إنشاء كوبول', 'cobol-created', 1959, 'A programming language designed for business data processing.', 'لغة برمجة مصممة لمعالجة البيانات التجارية.', 'Became the dominant business programming language.', 'technology', 1),
(3, 'BASIC Created', 'إنشاء بيسيك', 'basic-created', 1964, 'Beginner\'s All-purpose Symbolic Instruction Code, designed for beginners.', 'لغة برمجة مصممة للمبتدئين.', 'Made programming accessible to everyone.', 'technology', 0),

-- ولادة الإنترنت
(4, 'ARPANET', 'أربانت', 'arpanet', 1969, 'The first packet-switching network, precursor to the internet.', 'أول شبكة لتبديل الحزم، سابقة للإنترنت.', 'The foundation of the modern internet.', 'invention', 1),
(4, 'First Email', 'أول بريد إلكتروني', 'first-email', 1971, 'Ray Tomlinson sends the first email on ARPANET.', 'يرسل راي توملينسون أول بريد إلكتروني على ARPANET.', 'Revolutionized communication.', 'milestone', 1),
(4, 'TCP/IP Developed', 'تطوير TCP/IP', 'tcpip-developed', 1974, 'Vint Cerf and Bob Kahn create the TCP/IP protocol suite.', 'ينشئ فينت سيرف وبوب كان مجموعة بروتوكولات TCP/IP.', 'The foundation of the internet\'s communication system.', 'technology', 1),
(4, 'DNS Introduced', 'إدخال DNS', 'dns-introduced', 1983, 'The Domain Name System makes internet addresses human-readable.', 'نظام اسم المجال يجعل عناوين الإنترنت قابلة للقراءة.', 'Made the internet more user-friendly.', 'technology', 1),

-- الويب العالمي
(5, 'World Wide Web Invented', 'اختراع شبكة الويب العالمية', 'world-wide-web-invented', 1989, 'Tim Berners-Lee invents the World Wide Web at CERN.', 'يخترع تيم بيرنرز لي شبكة الويب العالمية في سيرن.', 'Revolutionized information sharing and access.', 'invention', 1),
(5, 'First Website', 'أول موقع ويب', 'first-website', 1991, 'The first website goes online at CERN.', 'أول موقع ويب يظهر على الإنترنت في سيرن.', 'Marked the beginning of the public web.', 'milestone', 1),
(5, 'Mosaic Browser', 'متصفح موزاييك', 'mosaic-browser', 1993, 'The first popular web browser with graphics.', 'أول متصفح ويب شعبي مع رسومات.', 'Made the web accessible to everyone.', 'technology', 1),
(5, 'Netscape Navigator', 'نتسكيب نافيجيتور', 'netscape-navigator', 1994, 'The first commercial web browser, dominating the early web market.', 'أول متصفح ويب تجاري، يهيمن على سوق الويب المبكر.', 'Popularized the web browser.', 'technology', 0),

-- الحوسبة الشخصية
(5, 'Altair 8800', 'ألتير 8800', 'altair-8800', 1975, 'The first commercially successful personal computer.', 'أول كمبيوتر شخصي ناجح تجارياً.', 'Inspired the personal computer revolution.', 'invention', 1),
(5, 'Apple II', 'آبل الثاني', 'apple-ii', 1977, 'One of the first successful mass-produced microcomputers.', 'أحد أول أجهزة الكمبيوتر الصغيرة الناجحة المنتجة بكميات كبيرة.', 'Brought computing to the masses.', 'invention', 1),
(5, 'IBM PC Released', 'إطلاق IBM PC', 'ibm-pc-released', 1981, 'IBM releases the IBM Personal Computer.', 'تطلق IBM الكمبيوتر الشخصي IBM.', 'Set the standard for personal computing architecture.', 'invention', 1),
(5, 'Macintosh Introduced', 'إطلاق ماكنتوش', 'macintosh-introduced', 1984, 'Apple introduces the Macintosh with a graphical user interface.', 'تقدم آبل جهاز ماكنتوش بواجهة مستخدم رسومية.', 'Made graphical computing mainstream.', 'invention', 1),
(5, 'Linux Kernel', 'نواة لينكس', 'linux-kernel', 1991, 'Linus Torvalds creates the Linux kernel.', 'يصنع لينوس تورفالدس نواة لينكس.', 'Became the foundation of open-source computing.', 'technology', 1),
(5, 'Windows 95', 'ويندوز 95', 'windows-95', 1995, 'Microsoft releases Windows 95 with significant UI improvements.', 'تطلق مايكروسوفت ويندوز 95 مع تحسينات كبيرة في واجهة المستخدم.', 'Became one of the most successful operating systems.', 'technology', 1),

-- ويب 2.0
(7, 'Google Founded', 'تأسيس جوجل', 'google-founded', 1998, 'Larry Page and Sergey Brin found Google.', 'يؤسس لاري بيج وسيرجي برين جوجل.', 'Became the world\'s dominant search engine.', 'company', 1),
(7, 'Facebook Launched', 'إطلاق فيسبوك', 'facebook-launched', 2004, 'Mark Zuckerberg launches Facebook.', 'يطلق مارك زوكربيرج فيسبوك.', 'Revolutionized social media.', 'company', 1),
(7, 'YouTube Created', 'إنشاء يوتيوب', 'youtube-created', 2005, 'Chad Hurley, Steve Chen, and Jawed Karim create YouTube.', 'ينشئ تشاد هيرلي وستيف تشين وجاويد كريم يوتيوب.', 'Changed how we consume video content.', 'company', 1),
(7, 'Twitter Launched', 'إطلاق تويتر', 'twitter-launched', 2006, 'Twitter introduces microblogging and real-time updates.', 'يقدم تويتر التدوين المصغر والتحديثات في الوقت الفعلي.', 'Revolutionized real-time communication.', 'company', 0),
(7, 'Wikipedia Launched', 'إطلاق ويكيبيديا', 'wikipedia-launched', 2001, 'Wikipedia launches as the free online encyclopedia.', 'تطلق ويكيبيديا كموسوعة مجانية على الإنترنت.', 'Became the largest free encyclopedia.', 'company', 0),

-- الثورة المحمولة
(8, 'iPhone Released', 'إطلاق آيفون', 'iphone-released', 2007, 'Apple releases the first iPhone.', 'تطلق آبل أول آيفون.', 'Revolutionized the smartphone industry.', 'invention', 1),
(8, 'Android Released', 'إطلاق أندرويد', 'android-released', 2008, 'Google releases the Android operating system.', 'تطلق جوجل نظام التشغيل أندرويد.', 'Became the world\'s most popular mobile OS.', 'technology', 1),
(8, 'iPad Released', 'إطلاق آيباد', 'ipad-released', 2010, 'Apple releases the iPad, popularizing tablet computing.', 'تطلق آبل آيباد، مما يروج للحوسبة اللوحية.', 'Created the tablet market.', 'invention', 0),

-- الحوسبة السحابية
(9, 'AWS Launched', 'إطلاق AWS', 'aws-launched', 2006, 'Amazon launches Amazon Web Services cloud computing platform.', 'تطلق أمازون منصة الحوسبة السحابية Amazon Web Services.', 'Pioneered cloud computing services.', 'technology', 1),

-- ثورة الذكاء الاصطناعي
(10, 'Deep Blue Defeats Kasparov', 'ديب بلو يهزم كاسباروف', 'deep-blue-defeats-kasparov', 1997, 'IBM\'s Deep Blue defeats world chess champion Garry Kasparov.', 'يهزم ديب بلو بطل العالم في الشطرنج غاري كاسباروف.', 'Demonstrated AI\'s ability to master strategic games.', 'milestone', 1),
(10, 'AlphaGo Wins', 'فوز ألفا جو', 'alphago-wins', 2016, 'AlphaGo defeats world champion Lee Sedol in Go.', 'يهزم ألفا جو بطل العالم لي سيدول في لعبة غو.', 'Demonstrated AI\'s ability to master complex strategy games.', 'milestone', 1),
(10, 'ChatGPT Released', 'إطلاق تشات جي بي تي', 'chatgpt-released', 2022, 'OpenAI releases ChatGPT, a conversational AI model.', 'تطلق أوبن إيه آي تشات جي بي تي، نموذج ذكاء اصطناعي محادثة.', 'Brought generative AI to the mainstream.', 'technology', 1),
(10, 'GPT-4 Released', 'إطلاق GPT-4', 'gpt4-released', 2023, 'OpenAI releases GPT-4, a powerful multimodal AI model.', 'تطلق أوبن إيه آي GPT-4، نموذج ذكاء اصطناعي متعدد الوسائط قوي.', 'Pushed the boundaries of AI capabilities.', 'technology', 1);

-- ======================================================
-- الأشخاص البارزون
-- ======================================================

INSERT INTO people (full_name, full_name_ar, slug, birth_year, death_year, nationality, biography, biography_ar, contributions, known_for, portrait) VALUES
('Ada Lovelace', 'آدا لوفلايس', 'ada-lovelace', 1815, 1852, 'British', 'Ada Lovelace is considered the first computer programmer. She wrote the first algorithm intended for implementation on Charles Babbage\'s Analytical Engine.', 'تعتبر آدا لوفلايس أول مبرمجة كمبيوتر. كتبت أول خوارزمية مخصصة للتنفيذ على المحرك التحليلي لتشارلز بابيج.', 'Wrote the first computer algorithm. Foresaw the potential of computers beyond pure calculation.', 'First Computer Programmer', NULL),
('Alan Turing', 'آلان تورينج', 'alan-turing', 1912, 1954, 'British', 'Alan Turing was a mathematician, computer scientist, and cryptanalyst. He is considered the father of theoretical computer science and artificial intelligence.', 'كان آلان تورينج عالم رياضيات وعالم كمبيوتر ومحلل شفرات. يعتبر أبو علوم الكمبيوتر النظرية والذكاء الاصطناعي.', 'Turing Machine concept. Turing Test for AI. Cracked the Enigma code.', 'Turing Machine, AI Theory', NULL),
('Grace Hopper', 'جريس هوبر', 'grace-hopper', 1906, 1992, 'American', 'Grace Hopper was a computer scientist and United States Navy rear admiral. She developed the first compiler, leading to the development of COBOL.', 'كانت جريس هوبر عالمة كمبيوتر وأدميرال خلفي في البحرية الأمريكية. طورت أول مترجم، مما أدى إلى تطوير كوبول.', 'Created the first compiler. Developed COBOL. Popularized the term "debugging".', 'COBOL, First Compiler', NULL),
('Charles Babbage', 'تشارلز بابيج', 'charles-babbage', 1791, 1871, 'British', 'Charles Babbage was a mathematician and mechanical engineer who originated the concept of a programmable computer.', 'كان تشارلز بابيج عالم رياضيات ومهندس ميكانيكي الذي ابتكر مفهوم الكمبيوتر القابل للبرمجة.', 'Designed the first mechanical computers. Conceptualized programmable computers.', 'Analytical Engine, Difference Engine', NULL),
('Tim Berners-Lee', 'تيم بيرنرز لي', 'tim-berners-lee', 1955, NULL, 'British', 'Tim Berners-Lee is the inventor of the World Wide Web. He wrote the first web browser and web server.', 'تيم بيرنرز لي هو مخترع شبكة الويب العالمية. كتب أول متصفح ويب وخادم ويب.', 'Invented the World Wide Web. Created HTTP, HTML, and URLs.', 'World Wide Web', NULL),
('Bill Gates', 'بيل غيتس', 'bill-gates', 1955, NULL, 'American', 'Bill Gates is the co-founder of Microsoft Corporation. He played a key role in the personal computer revolution.', 'بيل غيتس هو المؤسس المشارك لشركة مايكروسوفت. لعب دوراً رئيسياً في ثورة الكمبيوتر الشخصي.', 'Co-founded Microsoft. Developed MS-DOS and Windows.', 'Microsoft, Windows', NULL),
('Steve Jobs', 'ستيف جوبز', 'steve-jobs', 1955, 2011, 'American', 'Steve Jobs was the co-founder of Apple Inc. He revolutionized personal computing, music, and mobile phones.', 'ستيف جوبز هو المؤسس المشارك لشركة آبل. أحدث ثورة في الحوسبة الشخصية والموسيقى والهواتف المحمولة.', 'Co-founded Apple. Created Macintosh, iPod, iPhone, and iPad.', 'Apple, iPhone, Macintosh', NULL),
('Linus Torvalds', 'لينوس تورفالدس', 'linus-torvalds', 1969, NULL, 'Finnish', 'Linus Torvalds is the creator of the Linux kernel and the Git version control system.', 'لينوس تورفالدس هو مبتكر نواة لينكس ونظام التحكم في الإصدارات جيت.', 'Created Linux kernel. Created Git version control system.', 'Linux, Git', NULL),
('Dennis Ritchie', 'دنيس ريتشي', 'dennis-ritchie', 1941, 2011, 'American', 'Dennis Ritchie created the C programming language and co-created the Unix operating system.', 'أنشأ دنيس ريتشي لغة البرمجة سي وشارك في إنشاء نظام التشغيل يونكس.', 'Created C programming language. Co-created Unix operating system.', 'C Language, Unix', NULL),
('Vint Cerf', 'فينت سيرف', 'vint-cerf', 1943, NULL, 'American', 'Vint Cerf is one of the fathers of the internet. He co-designed the TCP/IP protocol suite.', 'فينت سيرف هو أحد آباء الإنترنت. شارك في تصميم مجموعة بروتوكولات TCP/IP.', 'Co-designed TCP/IP. Contributed to the development of the internet.', 'TCP/IP, Internet', NULL),
('Bob Kahn', 'بوب كان', 'bob-kahn', 1938, NULL, 'American', 'Bob Kahn co-invented the TCP/IP protocols with Vint Cerf. He also worked on the ARPANET.', 'شارك بوب كان في اختراع بروتوكولات TCP/IP مع فينت سيرف. عمل أيضاً على ARPANET.', 'Co-invented TCP/IP. Worked on ARPANET.', 'TCP/IP, Internet', NULL),
('Guido van Rossum', 'جويدو فان روسوم', 'guido-van-rossum', 1956, NULL, 'Dutch', 'Guido van Rossum is the creator of the Python programming language.', 'جويدو فان روسوم هو مبتكر لغة البرمجة بايثون.', 'Created Python programming language.', 'Python', NULL),
('James Gosling', 'جيمس جوسلينج', 'james-gosling', 1955, NULL, 'Canadian', 'James Gosling is the creator of the Java programming language.', 'جيمس جوسلينج هو مبتكر لغة البرمجة جافا.', 'Created Java programming language.', 'Java', NULL),
('Brendan Eich', 'بريندان إيك', 'brendan-eich', 1961, NULL, 'American', 'Brendan Eich created the JavaScript programming language in 1995 while working at Netscape.', 'أنشأ بريندان إيك لغة البرمجة جافا سكريبت في عام 1995 أثناء عمله في نتسكيب.', 'Created JavaScript programming language.', 'JavaScript', NULL),
('Ken Thompson', 'كين طومسون', 'ken-thompson', 1943, NULL, 'American', 'Ken Thompson co-created Unix and the B programming language. He also created the Go programming language.', 'شارك كين طومسون في إنشاء يونكس ولغة البرمجة بي. كما أنشأ لغة البرمجة جو.', 'Co-created Unix. Created B language. Created Go language.', 'Unix, Go', NULL),
('Bjarne Stroustrup', 'بيارن ستروستروب', 'bjarne-stroustrup', 1950, NULL, 'Danish', 'Bjarne Stroustrup is the creator of the C++ programming language.', 'بيارن ستروستروب هو مبتكر لغة البرمجة سي بلس بلس.', 'Created C++ programming language.', 'C++', NULL),
('John von Neumann', 'جون فون نيومان', 'john-von-neumann', 1903, 1957, 'Hungarian-American', 'John von Neumann developed the von Neumann architecture, which is the basis for most modern computers.', 'طور جون فون نيومان بنية فون نيومان، التي هي أساس معظم أجهزة الكمبيوتر الحديثة.', 'Developed von Neumann architecture. Contributed to game theory and quantum mechanics.', 'Von Neumann Architecture', NULL),
('Steve Wozniak', 'ستيف وزنياك', 'steve-wozniak', 1950, NULL, 'American', 'Steve Wozniak co-founded Apple Computer and designed the Apple I and Apple II.', 'شارك ستيف وزنياك في تأسيس شركة آبل للكمبيوتر وصمم آبل الأول وآبل الثاني.', 'Co-founded Apple. Designed Apple I and Apple II.', 'Apple II', NULL);

-- ======================================================
-- علاقات الأشخاص بالأحداث
-- ======================================================

INSERT INTO person_events (person_id, event_id, role) VALUES
((SELECT id FROM people WHERE slug = 'ada-lovelace'), (SELECT id FROM timeline_events WHERE slug = 'analytical-engine'), 'First Programmer'),
((SELECT id FROM people WHERE slug = 'charles-babbage'), (SELECT id FROM timeline_events WHERE slug = 'difference-engine'), 'Designer'),
((SELECT id FROM people WHERE slug = 'charles-babbage'), (SELECT id FROM timeline_events WHERE slug = 'analytical-engine'), 'Designer'),
((SELECT id FROM people WHERE slug = 'alan-turing'), (SELECT id FROM timeline_events WHERE slug = 'eniac'), 'Researcher'),
((SELECT id FROM people WHERE slug = 'grace-hopper'), (SELECT id FROM timeline_events WHERE slug = 'cobol-created'), 'Creator'),
((SELECT id FROM people WHERE slug = 'tim-berners-lee'), (SELECT id FROM timeline_events WHERE slug = 'world-wide-web-invented'), 'Inventor'),
((SELECT id FROM people WHERE slug = 'bill-gates'), (SELECT id FROM timeline_events WHERE slug = 'ibm-pc-released'), 'Co-founder'),
((SELECT id FROM people WHERE slug = 'steve-jobs'), (SELECT id FROM timeline_events WHERE slug = 'macintosh-introduced'), 'Co-founder'),
((SELECT id FROM people WHERE slug = 'steve-jobs'), (SELECT id FROM timeline_events WHERE slug = 'iphone-released'), 'Co-founder'),
((SELECT id FROM people WHERE slug = 'linus-torvalds'), (SELECT id FROM timeline_events WHERE slug = 'linux-kernel'), 'Creator'),
((SELECT id FROM people WHERE slug = 'vint-cerf'), (SELECT id FROM timeline_events WHERE slug = 'tcpip-developed'), 'Co-inventor'),
((SELECT id FROM people WHERE slug = 'bob-kahn'), (SELECT id FROM timeline_events WHERE slug = 'tcpip-developed'), 'Co-inventor'),
((SELECT id FROM people WHERE slug = 'dennis-ritchie'), (SELECT id FROM timeline_events WHERE slug = 'linux-kernel'), 'Contributor'),
((SELECT id FROM people WHERE slug = 'guido-van-rossum'), (SELECT id FROM timeline_events WHERE slug = 'chatgpt-released'), 'Influencer'),
((SELECT id FROM people WHERE slug = 'steve-wozniak'), (SELECT id FROM timeline_events WHERE slug = 'apple-ii'), 'Designer');

-- ======================================================
-- لغات البرمجة
-- ======================================================

INSERT INTO programming_languages (name, name_ar, slug, year_created, creator, paradigm, typing_discipline, description, description_ar, use_cases, popularity_score, code_example) VALUES
('Machine Code', 'رمز الآلة', 'machine-code', 1940, 'Various', 'Imperative', 'None', 'The lowest-level programming language directly executed by the CPU.', 'أدنى لغة برمجة يتم تنفيذها مباشرة بواسطة وحدة المعالجة المركزية.', 'Systems programming, embedded systems', 60, '10110000 01100001'),
('Assembly', 'أسمبلي', 'assembly', 1949, 'Various', 'Imperative', 'None', 'A low-level programming language with a strong correspondence to machine code instructions.', 'لغة برمجة منخفضة المستوى مع تطابق قوي مع تعليمات رمز الآلة.', 'Systems programming, reverse engineering', 65, 'MOV AL, 61h'),
('FORTRAN', 'فورتران', 'fortran', 1957, 'John Backus', 'Imperative, Procedural', 'Static', 'The first high-level programming language designed for scientific and engineering computing.', 'أول لغة برمجة عالية المستوى مصممة للحوسبة العلمية والهندسية.', 'Scientific computing, numerical analysis', 70, 'PROGRAM HELLO\nPRINT *, "Hello, World!"\nEND'),
('COBOL', 'كوبول', 'cobol', 1959, 'Grace Hopper', 'Imperative, Procedural', 'Static', 'A programming language designed for business data processing.', 'لغة برمجة مصممة لمعالجة البيانات التجارية.', 'Business applications, financial systems', 60, 'IDENTIFICATION DIVISION.\nPROGRAM-ID. HELLO.\nPROCEDURE DIVISION.\nDISPLAY "Hello, World!"\nSTOP RUN.'),
('LISP', 'ليسب', 'lisp', 1958, 'John McCarthy', 'Functional', 'Dynamic', 'One of the oldest programming languages, known for its use of parentheses and recursive functions.', 'من أقدم لغات البرمجة، المعروفة باستخدامها للأقواس والوظائف العودية.', 'AI research, symbolic computation', 65, '(print "Hello, World!")'),
('C', 'سي', 'c', 1972, 'Dennis Ritchie', 'Imperative, Procedural', 'Static', 'A powerful system programming language that influenced many other languages.', 'لغة برمجة أنظمة قوية أثرت على العديد من اللغات الأخرى.', 'Systems programming, embedded systems', 90, '#include <stdio.h>\nint main() {\n    printf("Hello, World!");\n    return 0;\n}'),
('C++', 'سي بلس بلس', 'cpp', 1985, 'Bjarne Stroustrup', 'Multi-paradigm', 'Static', 'An extension of C with object-oriented features.', 'امتداد للغة C مع ميزات البرمجة الكائنية.', 'Systems programming, game development', 85, '#include <iostream>\nusing namespace std;\nint main() {\n    cout << "Hello, World!";\n    return 0;\n}'),
('Java', 'جافا', 'java', 1995, 'James Gosling', 'Object-oriented', 'Static', 'A popular language designed for platform-independent applications.', 'لغة شعبية مصممة للتطبيقات المستقلة عن المنصة.', 'Enterprise applications, Android development', 90, 'public class HelloWorld {\n    public static void main(String[] args) {\n        System.out.println("Hello, World!");\n    }\n}'),
('JavaScript', 'جافا سكريبت', 'javascript', 1995, 'Brendan Eich', 'Multi-paradigm', 'Dynamic', 'The programming language of the web, essential for frontend development.', 'لغة برمجة الويب، أساسية لتطوير الواجهة الأمامية.', 'Web development, Node.js', 95, 'console.log("Hello, World!");'),
('PHP', 'بي إتش بي', 'php', 1995, 'Rasmus Lerdorf', 'Imperative, Object-oriented', 'Dynamic', 'A popular server-side scripting language for web development.', 'لغة برمجة نصية شعبية من جانب الخادم لتطوير الويب.', 'Web development, CMS', 80, '<?php\necho "Hello, World!";\n?>'),
('Python', 'بايثون', 'python', 1991, 'Guido van Rossum', 'Multi-paradigm', 'Dynamic', 'A versatile language known for its readability and ease of use.', 'لغة متعددة الاستخدامات معروفة بسهولة قراءتها وسهولة استخدامها.', 'Web development, data science, AI', 95, 'print("Hello, World!")'),
('Ruby', 'روبي', 'ruby', 1995, 'Yukihiro Matsumoto', 'Object-oriented', 'Dynamic', 'A dynamic language known for its elegant syntax and Rails framework.', 'لغة ديناميكية معروفة بقواعدها الأنيقة وإطار Rails.', 'Web development, prototyping', 70, 'puts "Hello, World!"'),
('C#', 'سي شارب', 'csharp', 2000, 'Anders Hejlsberg', 'Multi-paradigm', 'Static', 'A modern language developed by Microsoft for the .NET framework.', 'لغة حديثة طورتها مايكروسوفت لإطار .NET.', 'Windows applications, game development', 80, 'using System;\nclass Program {\n    static void Main() {\n        Console.WriteLine("Hello, World!");\n    }\n}'),
('Go', 'جو', 'go', 2009, 'Robert Griesemer, Rob Pike, Ken Thompson', 'Concurrent', 'Static', 'A language designed by Google for concurrent systems and microservices.', 'لغة صممتها جوجل للأنظمة المتزامنة والخدمات الصغيرة.', 'Microservices, cloud applications', 75, 'package main\nimport "fmt"\nfunc main() {\n    fmt.Println("Hello, World!")\n}'),
('Rust', 'راست', 'rust', 2010, 'Graydon Hoare', 'Multi-paradigm', 'Static', 'A language focused on safety and performance, created by Mozilla.', 'لغة تركز على الأمان والأداء، أنشأتها موزيلا.', 'Systems programming, web assembly', 70, 'fn main() {\n    println!("Hello, World!");\n}'),
('Swift', 'سويفت', 'swift', 2014, 'Chris Lattner', 'Multi-paradigm', 'Static', 'Apple\'s modern language for iOS and macOS development.', 'لغة آبل الحديثة لتطوير iOS و macOS.', 'iOS/macOS development', 75, 'print("Hello, World!")'),
('TypeScript', 'تايب سكريبت', 'typescript', 2012, 'Anders Hejlsberg', 'Multi-paradigm', 'Static', 'A typed superset of JavaScript developed by Microsoft.', 'مجموعة فرعية مكتوبة من جافا سكريبت طورتها مايكروسوفت.', 'Large-scale web applications', 80, 'console.log("Hello, World!");'),
('Kotlin', 'كوتلن', 'kotlin', 2011, 'JetBrains', 'Object-oriented, Functional', 'Static', 'A language designed for the JVM, often used for Android development.', 'لغة مصممة لـ JVM، تستخدم غالباً لتطوير Android.', 'Android development', 70, 'fun main() {\n    println("Hello, World!")\n}');

-- ======================================================
-- أنظمة التشغيل
-- ======================================================

INSERT INTO operating_systems (name, name_ar, slug, company, year_released, version, type, description, description_ar) VALUES
('UNIX', 'يونكس', 'unix', 'Bell Labs', 1969, 'Version 6', 'server', 'A multitasking, multi-user operating system that influenced many modern OSes.', 'نظام تشغيل متعدد المهام ومتعدد المستخدمين أثر على العديد من أنظمة التشغيل الحديثة.'),
('MS-DOS', 'إم إس دوس', 'ms-dos', 'Microsoft', 1981, '1.0', 'desktop', 'Microsoft\'s command-line operating system for IBM PCs.', 'نظام تشغيل مايكروسوفت بواجهة سطر أوامر لأجهزة IBM PC.'),
('Windows 1.0', 'ويندوز 1.0', 'windows-1', 'Microsoft', 1985, '1.0', 'desktop', 'The first graphical user interface operating system from Microsoft.', 'أول نظام تشغيل بواجهة مستخدم رسومية من مايكروسوفت.'),
('Windows 3.1', 'ويندوز 3.1', 'windows-31', 'Microsoft', 1992, '3.1', 'desktop', 'A major release with improved graphics and true type fonts support.', 'إصدار رئيسي مع رسومات محسنة ودعم خطوط TrueType.'),
('Windows 95', 'ويندوز 95', 'windows-95', 'Microsoft', 1995, '4.0', 'desktop', 'A major release with significant UI improvements and 32-bit support.', 'إصدار رئيسي مع تحسينات كبيرة في واجهة المستخدم ودعم 32 بت.'),
('Windows 98', 'ويندوز 98', 'windows-98', 'Microsoft', 1998, '4.1', 'desktop', 'An update to Windows 95 with better hardware support.', 'تحديث لويندوز 95 مع دعم أفضل للأجهزة.'),
('Windows XP', 'ويندوز إكس بي', 'windows-xp', 'Microsoft', 2001, '5.1', 'desktop', 'One of the most successful and beloved versions of Windows.', 'واحدة من أنجح وأحب إصدارات ويندوز.'),
('Windows 7', 'ويندوز 7', 'windows-7', 'Microsoft', 2009, '6.1', 'desktop', 'A major release with improved performance and a redesigned taskbar.', 'إصدار رئيسي مع أداء محسّن وشريط مهام معاد تصميمه.'),
('Windows 10', 'ويندوز 10', 'windows-10', 'Microsoft', 2015, '10.0', 'desktop', 'A unified operating system for PCs, tablets, and phones.', 'نظام تشغيل موحد لأجهزة الكمبيوتر والأجهزة اللوحية والهواتف.'),
('Windows 11', 'ويندوز 11', 'windows-11', 'Microsoft', 2021, '11.0', 'desktop', 'The latest Windows release with a modernized interface and performance improvements.', 'أحدث إصدار من ويندوز مع واجهة حديثة وتحسينات في الأداء.'),
('macOS', 'ماك أو إس', 'macos', 'Apple', 2001, '10.0', 'desktop', 'Apple\'s operating system for Mac computers, based on Unix.', 'نظام تشغيل آبل لأجهزة ماك، المستند على يونكس.'),
('Linux', 'لينكس', 'linux', 'Linus Torvalds', 1991, '0.01', 'server', 'An open-source operating system kernel used in many distributions.', 'نواة نظام تشغيل مفتوحة المصدر تستخدم في العديد من التوزيعات.'),
('Android', 'أندرويد', 'android', 'Google', 2008, '1.0', 'mobile', 'Google\'s mobile operating system based on Linux.', 'نظام تشغيل جوجل للهواتف المحمولة المستند على لينكس.'),
('iOS', 'آي أو إس', 'ios', 'Apple', 2007, '1.0', 'mobile', 'Apple\'s mobile operating system for iPhone and iPad.', 'نظام تشغيل آبل للهواتف المحمولة لأجهزة آيفون وآيباد.'),
('ChromeOS', 'كروم أو إس', 'chromeos', 'Google', 2011, '1.0', 'desktop', 'A lightweight operating system based on the Chrome browser.', 'نظام تشغيل خفيف الوزن مستند على متصفح كروم.');

-- ======================================================
-- التقنيات
-- ======================================================

INSERT INTO technologies (name, name_ar, slug, category, category_ar, description, description_ar, year_introduced, inventor, icon) VALUES
('Vacuum Tube', 'الأنبوب المفرغ', 'vacuum-tube', 'Hardware', 'الأجهزة', 'An electronic device that controls the flow of electrons in a vacuum, used in early computers.', 'جهاز إلكتروني يتحكم في تدفق الإلكترونات في الفراغ، يستخدم في أجهزة الكمبيوتر المبكرة.', 1940, 'John Ambrose Fleming', '💡'),
('Transistor', 'الترانزستور', 'transistor', 'Hardware', 'الأجهزة', 'A semiconductor device used to amplify or switch electronic signals, replacing vacuum tubes.', 'جهاز شبه موصل يستخدم لتضخيم أو تبديل الإشارات الإلكترونية، ليحل محل الأنابيب المفرغة.', 1947, 'John Bardeen, Walter Brattain, William Shockley', '🔌'),
('Integrated Circuit', 'الدائرة المتكاملة', 'integrated-circuit', 'Hardware', 'الأجهزة', 'A miniaturized electronic circuit consisting of transistors, resistors, and capacitors on a semiconductor chip.', 'دائرة إلكترونية مصغرة تتكون من ترانزستورات ومقاومات ومكثفات على شريحة شبه موصلة.', 1958, 'Jack Kilby', '🔬'),
('Microprocessor', 'المعالج الدقيق', 'microprocessor', 'Hardware', 'الأجهزة', 'A central processing unit (CPU) on a single integrated circuit, the heart of modern computers.', 'وحدة معالجة مركزية (CPU) على دائرة متكاملة واحدة، قلب أجهزة الكمبيوتر الحديثة.', 1971, 'Intel (Ted Hoff)', '⚡'),
('RAM', 'ذاكرة الوصول العشوائي', 'ram', 'Hardware', 'الأجهزة', 'Random Access Memory, the primary memory used for storing data being actively used.', 'ذاكرة الوصول العشوائي، الذاكرة الأساسية المستخدمة لتخزين البيانات التي يتم استخدامها بنشاط.', 1968, 'Intel', '🧮'),
('Hard Disk Drive', 'محرك القرص الصلب', 'hard-disk-drive', 'Hardware', 'الأجهزة', 'A data storage device using magnetic storage to store digital information.', 'جهاز تخزين بيانات باستخدام التخزين المغناطيسي لتخزين المعلومات الرقمية.', 1956, 'IBM', '💾'),
('Solid State Drive', 'محرك الحالة الصلبة', 'solid-state-drive', 'Hardware', 'الأجهزة', 'A storage device using integrated circuit assemblies to store data persistently.', 'جهاز تخزين يستخدم تجميعات الدوائر المتكاملة لتخزين البيانات بشكل دائم.', 1991, 'SanDisk', '⚡'),
('GPU', 'وحدة معالجة الرسومات', 'gpu', 'Hardware', 'الأجهزة', 'Graphics Processing Unit, specialized for rendering graphics and parallel computing.', 'وحدة معالجة الرسومات، متخصصة في عرض الرسومات والحوسبة المتوازية.', 1999, 'NVIDIA', '🎮'),
('Quantum Processor', 'المعالج الكمومي', 'quantum-processor', 'Hardware', 'الأجهزة', 'A processor using quantum bits (qubits) for computation, enabling quantum computing.', 'معالج يستخدم البتات الكمومية (كيوبتات) للحوسبة، مما يمكن الحوسبة الكمومية.', 2019, 'Google, IBM, Intel', '⚛️'),
('AI Accelerator', 'مسرع الذكاء الاصطناعي', 'ai-accelerator', 'Hardware', 'الأجهزة', 'A specialized hardware designed to accelerate AI and machine learning workloads.', 'أجهزة متخصصة مصممة لتسريع أحمال عمل الذكاء الاصطناعي والتعلم الآلي.', 2010, 'NVIDIA, Google, Intel', '🧠'),
('TCP/IP', 'TCP/IP', 'tcp-ip', 'Networking', 'الشبكات', 'The standard communication protocol suite for the internet.', 'مجموعة بروتوكولات الاتصال القياسية للإنترنت.', 1974, 'Vint Cerf & Bob Kahn', '🌐'),
('HTTP', 'HTTP', 'http', 'Web', 'الويب', 'The Hypertext Transfer Protocol for web communication.', 'بروتوكول نقل النص التشعبي للاتصال بالويب.', 1991, 'Tim Berners-Lee', '📡'),
('HTML', 'HTML', 'html', 'Web', 'الويب', 'The standard markup language for creating web pages.', 'لغة الترميز القياسية لإنشاء صفحات الويب.', 1993, 'Tim Berners-Lee', '📄'),
('CSS', 'CSS', 'css', 'Web', 'الويب', 'Cascading Style Sheets for web page design.', 'أوراق الأنماط المتتالية لتصميم صفحات الويب.', 1996, 'Håkon Wium Lie', '🎨'),
('JavaScript', 'جافا سكريبت', 'javascript-tech', 'Web', 'الويب', 'The programming language of the web browser.', 'لغة برمجة متصفح الويب.', 1995, 'Brendan Eich', '⚡'),
('DNS', 'DNS', 'dns', 'Networking', 'الشبكات', 'The Domain Name System that translates domain names to IP addresses.', 'نظام اسم المجال الذي يترجم أسماء النطاقات إلى عناوين IP.', 1983, 'Paul Mockapetris', '📋'),
('SSL/TLS', 'SSL/TLS', 'ssl-tls', 'Security', 'الأمن', 'Secure communication protocols for internet security.', 'بروتوكولات اتصال آمنة لأمان الإنترنت.', 1995, 'Netscape', '🔐'),
('Wi-Fi', 'واي فاي', 'wi-fi', 'Networking', 'الشبكات', 'Wireless networking technology for local area networks.', 'تقنية الشبكات اللاسلكية للشبكات المحلية.', 1997, 'IEEE', '📶'),
('Cloud Computing', 'الحوسبة السحابية', 'cloud-computing-tech', 'Infrastructure', 'البنية التحتية', 'On-demand computing resources over the internet.', 'موارد حوسبة حسب الطلب عبر الإنترنت.', 2006, 'Various', '☁️');

-- ======================================================
-- العلاقات بين التقنيات والأحداث (FIXED)
-- ======================================================

-- أولاً نضيف الأحداث المفقودة
INSERT INTO timeline_events (era_id, title, title_ar, slug, year, description, description_ar, significance, event_type, is_featured) VALUES
(3, 'First Commercial Transistor Computer', 'أول كمبيوتر ترانزستور تجاري', 'first-commercial-transistor-computer', 1959, 'IBM introduces the first commercial transistor computer.', 'تقدم آي بي إم أول كمبيوتر ترانزستور تجاري.', 'Transistors replaced vacuum tubes in commercial computers.', 'milestone', 0);

-- ثم نضيف العلاقات
INSERT INTO technology_events (technology_id, event_id) VALUES
((SELECT id FROM technologies WHERE slug = 'transistor'), (SELECT id FROM timeline_events WHERE slug = 'first-transistor-computer')),
((SELECT id FROM technologies WHERE slug = 'integrated-circuit'), (SELECT id FROM timeline_events WHERE slug = 'eniac')),
((SELECT id FROM technologies WHERE slug = 'microprocessor'), (SELECT id FROM timeline_events WHERE slug = 'ibm-pc-released')),
((SELECT id FROM technologies WHERE slug = 'tcp-ip'), (SELECT id FROM timeline_events WHERE slug = 'tcpip-developed')),
((SELECT id FROM technologies WHERE slug = 'http'), (SELECT id FROM timeline_events WHERE slug = 'world-wide-web-invented')),
((SELECT id FROM technologies WHERE slug = 'html'), (SELECT id FROM timeline_events WHERE slug = 'world-wide-web-invented')),
((SELECT id FROM technologies WHERE slug = 'dns'), (SELECT id FROM timeline_events WHERE slug = 'dns-introduced'));

-- ======================================================
-- التصنيفات
-- ======================================================

INSERT INTO categories (name, name_ar, slug, description, description_ar, icon) VALUES
('Computers', 'أجهزة الكمبيوتر', 'computers', 'The history of computing devices from mechanical to modern.', 'تاريخ أجهزة الحوسبة من الميكانيكية إلى الحديثة.', '🖥️'),
('Internet', 'الإنترنت', 'internet', 'The history of the internet from ARPANET to the modern web.', 'تاريخ الإنترنت من ARPANET إلى الويب الحديث.', '🌐'),
('Programming', 'البرمجة', 'programming', 'The evolution of programming languages and software development.', 'تطور لغات البرمجة وتطوير البرمجيات.', '💻'),
('Networking', 'الشبكات', 'networking', 'The history of computer networks and communication protocols.', 'تاريخ شبكات الكمبيوتر وبروتوكولات الاتصال.', '🔗'),
('Security', 'الأمن', 'security', 'The history of cybersecurity and information security.', 'تاريخ الأمن السيبراني وأمن المعلومات.', '🛡️'),
('AI', 'الذكاء الاصطناعي', 'ai', 'The history of artificial intelligence and machine learning.', 'تاريخ الذكاء الاصطناعي والتعلم الآلي.', '🧠'),
('Hardware', 'الأجهزة', 'hardware', 'The evolution of computer hardware and components.', 'تطور أجهزة الكمبيوتر ومكوناتها.', '🔧'),
('Software', 'البرمجيات', 'software', 'The history of software development and applications.', 'تاريخ تطوير البرمجيات والتطبيقات.', '💿');

-- ======================================================
-- الترجمات
-- ======================================================

INSERT INTO translations (key_name, en, ar) VALUES
('nav.home', 'Home', 'الرئيسية'),
('nav.timeline', 'Timeline', 'الجدول الزمني'),
('nav.computing', 'Computing', 'الحوسبة'),
('nav.programming', 'Programming', 'البرمجة'),
('nav.internet', 'Internet', 'الإنترنت'),
('nav.web', 'Web', 'الويب'),
('nav.cybersecurity', 'Cybersecurity', 'الأمن السيبراني'),
('nav.ai', 'AI', 'الذكاء الاصطناعي'),
('nav.people', 'People', 'الشخصيات'),
('nav.museum', 'Museum', 'المتحف'),
('nav.lab', 'Lab', 'المختبر'),
('nav.games', 'Games', 'الألعاب'),
('nav.future', 'Future', 'المستقبل'),
('nav.search', 'Search', 'بحث'),
('hero.title', 'DIGITAL HISTORY', 'التاريخ الرقمي'),
('hero.subtitle', 'The Story of Computing, Programming & The Internet', 'قصة الحوسبة والبرمجة والإنترنت'),
('hero.description', 'Explore the technology that changed humanity.', 'استكشف التكنولوجيا التي غيرت البشرية.'),
('hero.start', 'START THE JOURNEY', 'ابدأ الرحلة'),
('hero.explore', 'EXPLORE TIMELINE', 'استكشف الجدول الزمني'),
('hero.scroll', 'SCROLL TO BEGIN', 'مرر للبدء'),
('footer.about', 'About', 'عن الموقع'),
('footer.contact', 'Contact', 'اتصل بنا'),
('footer.privacy', 'Privacy Policy', 'سياسة الخصوصية'),
('footer.terms', 'Terms of Service', 'شروط الخدمة'),
('footer.sources', 'Sources', 'المصادر'),
('footer.copyright', '© 2024 Digital History. All rights reserved.', '© 2024 التاريخ الرقمي. جميع الحقوق محفوظة.'),
('timeline.title', 'Global Timeline', 'الجدول الزمني العالمي'),
('timeline.subtitle', 'Explore the history of technology from 1800 to the future.', 'استكشف تاريخ التكنولوجيا من 1800 إلى المستقبل.'),
('lab.title', 'Digital Lab', 'المختبر الرقمي'),
('lab.subtitle', 'Experiment with interactive technology simulations.', 'جرب محاكاة التكنولوجيا التفاعلية.'),
('games.title', 'Digital Games', 'الألعاب الرقمية'),
('games.subtitle', 'Learn while playing interactive technology quizzes.', 'تعلم أثناء لعب اختبارات التكنولوجيا التفاعلية.'),
('future.title', 'The Future', 'المستقبل'),
('future.subtitle', 'Explore predictions for technology in 2030, 2040, 2050, and beyond.', 'استكشف توقعات التكنولوجيا في 2030 و 2040 و 2050 وما بعدها.'),
('people.title', 'Famous People', 'الشخصيات المشهورة'),
('people.subtitle', 'Meet the pioneers who shaped technology.', 'تعرف على الرواد الذين شكلوا التكنولوجيا.'),
('404.title', '404 - Packet Lost', '404 - الحزمة ضاعت'),
('404.description', 'The packet you were looking for got lost in the network.', 'الحزمة التي كنت تبحث عنها ضاعت في الشبكة.'),
('404.home', 'Return Home', 'العودة إلى الرئيسية'),
('404.timeline', 'Explore Timeline', 'استكشف الجدول الزمني');

-- ======================================================
-- الإنجازات
-- ======================================================

INSERT INTO achievements (name, name_ar, slug, description, description_ar, icon, points, requirement_type, requirement_value) VALUES
('First Journey', 'الرحلة الأولى', 'first-journey', 'Complete your first journey through history.', 'أكمل رحلتك الأولى عبر التاريخ.', '🚀', 10, 'timeline_view', '{"count": 1}'),
('Internet Explorer', 'مستكشف الإنترنت', 'internet-explorer', 'Explore 5 different internet history pages.', 'استكشف 5 صفحات مختلفة من تاريخ الإنترنت.', '🌐', 20, 'page_views', '{"pages": ["internet", "web", "networking", "dns", "http"], "count": 5}'),
('Code Historian', 'مؤرخ الكود', 'code-historian', 'Learn about 10 programming languages.', 'تعرف على 10 لغات برمجة.', '💻', 25, 'languages_viewed', '{"count": 10}'),
('Network Master', 'سيد الشبكات', 'network-master', 'Complete all networking experiments in the lab.', 'أكمل جميع تجارب الشبكات في المختبر.', '🔗', 30, 'experiments_completed', '{"type": "networking", "count": 3}'),
('AI Explorer', 'مستكشف الذكاء الاصطناعي', 'ai-explorer', 'Explore 5 AI milestones.', 'استكشف 5 معالم من الذكاء الاصطناعي.', '🤖', 25, 'ai_milestones', '{"count": 5}'),
('Retro User', 'مستخدم رجعي', 'retro-user', 'Visit the Retro Internet experience.', 'قم بزيارة تجربة الإنترنت الرجعي.', '🕹️', 15, 'page_visit', '{"page": "retro"}'),
('Digital Scientist', 'عالم رقمي', 'digital-scientist', 'Complete 10 digital lab experiments.', 'أكمل 10 تجارب في المختبر الرقمي.', '🔬', 40, 'experiments_completed', '{"count": 10}'),
('Timeline Master', 'سيد الجدول الزمني', 'timeline-master', 'Explore all major eras in the timeline.', 'استكشف جميع العصور الرئيسية في الجدول الزمني.', '📜', 35, 'eras_explored', '{"count": 10}'),
('Quiz Master', 'سيد الاختبارات', 'quiz-master', 'Complete 5 quizzes successfully.', 'أكمل 5 اختبارات بنجاح.', '🏆', 30, 'quizzes_completed', '{"count": 5}');

-- ======================================================

SELECT 'Database created successfully!' AS message;