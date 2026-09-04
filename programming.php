<?php
// programming.php
// Digital History - Programming Languages (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'History of Programming';
$pageDescription = 'Explore the evolution of programming languages from machine code to modern languages - the complete story of programming.';

$languages = getProgrammingLanguages();
$selectedLanguage = $_GET['lang'] ?? '';

// Get programming events
$programmingEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE is_active = 1 AND event_type IN ('technology', 'milestone') 
     AND (title LIKE '%program%' OR title LIKE '%language%' OR title LIKE '%code%' OR title LIKE '%compiler%')
     ORDER BY year ASC LIMIT 15"
);

// Get programming people
$programmingPeople = db()->fetchAll(
    "SELECT p.* FROM people p 
     WHERE p.is_active = 1 
     AND (p.known_for LIKE '%program%' OR p.known_for LIKE '%language%' OR p.known_for LIKE '%code%' OR p.known_for LIKE '%compiler%')
     ORDER BY p.birth_year ASC LIMIT 10"
);

trackPageView('programming');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            💻 History of Programming
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">From machine code to AI - the evolution of programming languages.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($languages); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Programming Languages</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($programmingEvents); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Historical Events</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;"><?php echo count($programmingPeople); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Programming Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">80+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Years of Evolution</div>
        </div>
    </div>
</section>

<!-- Programming Timeline -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem; background: rgba(255,255,255,0.01); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h3 style="color: #00d4ff; margin: 0; font-size: 1.5rem;">
            <i class="fas fa-timeline"></i> Programming Language Timeline
        </h3>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="scrollTimeline('left')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollTimeline('right')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="programming-timeline" id="programmingTimeline">
        <?php
        $programmingMilestones = [
            [
                'year' => '1940s',
                'title' => 'Machine Code',
                'description' => 'The first programming languages were machine code, directly understood by the CPU.',
                'icon' => '💾',
                'color' => '#ff6b6b',
                'category' => 'Low-Level',
                'impact' => 'Foundational'
            ],
            [
                'year' => '1949',
                'title' => 'Assembly Language',
                'description' => 'Assembly language introduced mnemonic codes to represent machine instructions.',
                'icon' => '🔧',
                'color' => '#ffa500',
                'category' => 'Low-Level',
                'impact' => 'Foundational'
            ],
            [
                'year' => '1957',
                'title' => 'FORTRAN',
                'description' => 'The first high-level programming language, designed for scientific computing.',
                'icon' => '📐',
                'color' => '#ffd93d',
                'category' => 'High-Level',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1958',
                'title' => 'LISP',
                'description' => 'The second high-level language, designed for artificial intelligence research.',
                'icon' => '🧠',
                'color' => '#00d4ff',
                'category' => 'Functional',
                'impact' => 'Influential'
            ],
            [
                'year' => '1959',
                'title' => 'COBOL',
                'description' => 'Designed for business data processing, COBOL became the language of business.',
                'icon' => '🏢',
                'color' => '#7b2ffc',
                'category' => 'Business',
                'impact' => 'High'
            ],
            [
                'year' => '1964',
                'title' => 'BASIC',
                'description' => 'Beginner\'s All-purpose Symbolic Instruction Code, designed for beginners.',
                'icon' => '📚',
                'color' => '#00ff88',
                'category' => 'Educational',
                'impact' => 'High'
            ],
            [
                'year' => '1972',
                'title' => 'C Language',
                'description' => 'A powerful system programming language that became the foundation of modern programming.',
                'icon' => '⚡',
                'color' => '#00d4ff',
                'category' => 'Systems',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1983',
                'title' => 'C++',
                'description' => 'An extension of C with object-oriented features, used in games and systems.',
                'icon' => '🎮',
                'color' => '#7b2ffc',
                'category' => 'Object-Oriented',
                'impact' => 'Influential'
            ],
            [
                'year' => '1991',
                'title' => 'Python',
                'description' => 'A versatile, readable language that became popular for data science and AI.',
                'icon' => '🐍',
                'color' => '#00ff88',
                'category' => 'Multi-Paradigm',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1995',
                'title' => 'Java',
                'description' => '"Write once, run anywhere" platform-independent language.',
                'icon' => '☕',
                'color' => '#ff0064',
                'category' => 'Platform-Independent',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1995',
                'title' => 'JavaScript',
                'description' => 'The language of the web, making interactive websites possible.',
                'icon' => '🌐',
                'color' => '#ffa500',
                'category' => 'Web',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '2009',
                'title' => 'Go',
                'description' => 'Google\'s language for concurrent systems and microservices.',
                'icon' => '🐹',
                'color' => '#00d4ff',
                'category' => 'Concurrent',
                'impact' => 'Influential'
            ],
            [
                'year' => '2010',
                'title' => 'Rust',
                'description' => 'A language focused on safety and performance from Mozilla.',
                'icon' => '🦀',
                'color' => '#ff6b6b',
                'category' => 'Systems',
                'impact' => 'Emerging'
            ],
            [
                'year' => '2014',
                'title' => 'Swift',
                'description' => 'Apple\'s modern language for iOS and macOS development.',
                'icon' => '🍎',
                'color' => '#ffa500',
                'category' => 'Mobile',
                'impact' => 'Influential'
            ],
            [
                'year' => '2015',
                'title' => 'TypeScript',
                'description' => 'A typed superset of JavaScript, improving large-scale web development.',
                'icon' => '📘',
                'color' => '#7b2ffc',
                'category' => 'Web',
                'impact' => 'Influential'
            ]
        ];
        
        foreach ($programmingMilestones as $index => $event):
        ?>
        <div class="programming-item <?php echo $index % 2 === 0 ? 'left' : 'right'; ?> animate-on-scroll" data-index="<?php echo $index; ?>">
            <div class="programming-dot" style="background: <?php echo $event['color']; ?>; box-shadow: 0 0 20px <?php echo $event['color']; ?>40;"></div>
            <div class="programming-content" style="border-color: <?php echo $event['color']; ?>20;">
                <div class="programming-year" style="color: <?php echo $event['color']; ?>;"><?php echo $event['year']; ?></div>
                <div class="programming-icon"><?php echo $event['icon']; ?></div>
                <h3><?php echo $event['title']; ?></h3>
                <p><?php echo $event['description']; ?></p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.65rem; background: <?php echo $event['color']; ?>20; color: <?php echo $event['color']; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $event['category']; ?>
                    </span>
                    <?php
                    // Fixed: Using if-elseif-else instead of nested ternary
                    if ($event['impact'] === 'Revolutionary') {
                        $impactBg = 'rgba(255,0,100,0.1)';
                        $impactColor = '#ff0064';
                    } elseif ($event['impact'] === 'Influential') {
                        $impactBg = 'rgba(0,212,255,0.1)';
                        $impactColor = '#00d4ff';
                    } else {
                        $impactBg = 'rgba(255,165,0,0.1)';
                        $impactColor = '#ffa500';
                    }
                    ?>
                    <span style="font-size: 0.65rem; background: <?php echo $impactBg; ?>; color: <?php echo $impactColor; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $event['impact']; ?>
                    </span>
                </div>
                <button onclick="showLanguageDetails('<?php echo addslashes($event['title']); ?>', '<?php echo addslashes($event['description']); ?>', '<?php echo $event['year']; ?>', '<?php echo $event['icon']; ?>', '<?php echo $event['category']; ?>')" style="margin-top: 0.8rem; padding: 0.3rem 1rem; background: <?php echo $event['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; font-size: 0.8rem; transition: all 0.3s;">
                    Learn More
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Language Categories -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📚 Programming Language Paradigms</h2>
        <p style="color: #8892b0;">Different approaches to programming that shape how we write code.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📝</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Imperative</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">C, FORTRAN, BASIC</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Step-by-step instructions</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🎯</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">Object-Oriented</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">C++, Java, Python</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Objects and classes</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧮</div>
            <h4 style="color: #00ff88; font-size: 1rem;">Functional</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">LISP, Haskell, Clojure</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Functions as first-class</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🌐</div>
            <h4 style="color: #ffa500; font-size: 1rem;">Scripting</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">JavaScript, PHP, Ruby</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Dynamic and interpreted</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">⚡</div>
            <h4 style="color: #ff0064; font-size: 1rem;">Concurrent</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Go, Erlang, Rust</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Parallel execution</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧬</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Multi-Paradigm</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Python, Java, C++</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Multiple approaches</p>
        </div>
    </div>
</section>

<!-- Programming Languages Grid -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">🖥️ All Programming Languages</h2>
        <p style="color: #8892b0;">Browse the complete list of programming languages with detailed information.</p>
    </div>
    
    <!-- Search Bar -->
    <div style="max-width: 500px; margin: 1.5rem auto 2rem;">
        <input type="text" id="languageSearch" placeholder="Search programming languages..." onkeyup="filterLanguages()" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem;">
    </div>
    
    <div class="card-grid" id="languageGrid">
        <?php foreach ($languages as $lang): ?>
        <div class="card animate-on-scroll language-card" id="lang-<?php echo $lang['slug']; ?>" data-name="<?php echo strtolower($lang['name']); ?>" data-creator="<?php echo strtolower($lang['creator']); ?>">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div class="card-title" style="font-size: 1.2rem;"><?php echo escape($lang['name']); ?></div>
                    <div style="color: #8892b0; font-size: 0.8rem;">
                        Created: <?php echo $lang['year_created']; ?> by <?php echo escape($lang['creator']); ?>
                    </div>
                </div>
                <div style="background: <?php echo $lang['popularity_score'] > 80 ? 'rgba(0,212,255,0.1)' : 'rgba(255,255,255,0.03)'; ?>; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.7rem; color: <?php echo $lang['popularity_score'] > 80 ? '#00d4ff' : '#8892b0'; ?>;">
                    <?php echo $lang['popularity_score']; ?>%
                </div>
            </div>
            
            <div style="margin: 0.5rem 0; display: flex; flex-wrap: wrap; gap: 0.3rem;">
                <span style="font-size: 0.7rem; background: rgba(255,255,255,0.05); padding: 0.1rem 0.5rem; border-radius: 4px; color: #8892b0;"><?php echo escape($lang['paradigm']); ?></span>
                <span style="font-size: 0.7rem; background: rgba(255,255,255,0.05); padding: 0.1rem 0.5rem; border-radius: 4px; color: #8892b0;"><?php echo escape($lang['typing_discipline']); ?></span>
            </div>
            
            <div class="card-description"><?php echo escape(truncate($lang['description'], 120)); ?></div>
            
            <?php if ($lang['code_example']): ?>
            <details style="margin-top: 0.8rem;">
                <summary style="color: #00d4ff; cursor: pointer; font-size: 0.85rem;">
                    <i class="fas fa-code"></i> View Code Example
                </summary>
                <pre style="background: rgba(0,0,0,0.3); padding: 0.8rem; border-radius: 6px; margin-top: 0.5rem; font-size: 0.8rem; color: #ccd6f6; overflow-x: auto; border: 1px solid rgba(255,255,255,0.05);"><?php echo escape($lang['code_example']); ?></pre>
            </details>
            <?php endif; ?>
            
            <div style="margin-top: 0.8rem; display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                <span style="font-size: 0.7rem; color: #8892b0;">Uses: <?php echo escape(truncate($lang['use_cases'], 50)); ?></span>
                <button onclick="copyCodeExample('<?php echo $lang['slug']; ?>')" style="margin-left: auto; padding: 0.2rem 0.6rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 4px; color: #8892b0; cursor: pointer; font-size: 0.7rem; transition: all 0.3s;">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Language Popularity Chart -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">📊 Language Popularity</h2>
        <p style="color: #8892b0;">Relative popularity of programming languages based on historical data and usage.</p>
    </div>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <?php 
        $sortedLanguages = $languages;
        usort($sortedLanguages, function($a, $b) {
            return $b['popularity_score'] - $a['popularity_score'];
        });
        $topLanguages = array_slice($sortedLanguages, 0, 15);
        ?>
        
        <?php foreach ($topLanguages as $lang): ?>
        <div class="popularity-bar" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; opacity: 0; transform: translateX(-20px); transition: all 0.5s ease;">
            <div style="width: 100px; text-align: right; color: #8892b0; font-size: 0.85rem;"><?php echo escape($lang['name']); ?></div>
            <div style="flex: 1; height: 24px; background: rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden; position: relative;">
                <div style="height: 100%; width: <?php echo $lang['popularity_score']; ?>%; background: linear-gradient(90deg, <?php 
                    if ($lang['popularity_score'] > 80) {
                        echo '#00d4ff';
                    } elseif ($lang['popularity_score'] > 60) {
                        echo '#7b2ffc';
                    } else {
                        echo '#8892b0';
                    }
                ?>, <?php 
                    if ($lang['popularity_score'] > 80) {
                        echo '#7b2ffc';
                    } elseif ($lang['popularity_score'] > 60) {
                        echo '#00d4ff';
                    } else {
                        echo '#8892b0';
                    }
                ?>); border-radius: 12px; transition: width 1.5s ease;">
                </div>
            </div>
            <div style="width: 40px; color: #ccd6f6; font-size: 0.85rem; font-weight: 600;"><?php echo $lang['popularity_score']; ?>%</div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Programming Pioneers -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">👨‍💻 Programming Pioneers</h2>
        <p style="color: #8892b0;">The brilliant minds who created the languages we use today.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <?php
        $programmingPioneers = [
            ['name' => 'Ada Lovelace', 'role' => 'First Programmer', 'icon' => '👩‍💻', 'color' => '#ff6b6b', 'language' => 'Algorithm'],
            ['name' => 'Grace Hopper', 'role' => 'COBOL Creator', 'icon' => '👩‍🔬', 'color' => '#7b2ffc', 'language' => 'COBOL'],
            ['name' => 'Dennis Ritchie', 'role' => 'C Creator', 'icon' => '⚡', 'color' => '#00d4ff', 'language' => 'C'],
            ['name' => 'Bjarne Stroustrup', 'role' => 'C++ Creator', 'icon' => '🎯', 'color' => '#00ff88', 'language' => 'C++'],
            ['name' => 'Guido van Rossum', 'role' => 'Python Creator', 'icon' => '🐍', 'color' => '#ffa500', 'language' => 'Python'],
            ['name' => 'James Gosling', 'role' => 'Java Creator', 'icon' => '☕', 'color' => '#ff0064', 'language' => 'Java'],
            ['name' => 'Brendan Eich', 'role' => 'JavaScript Creator', 'icon' => '🌐', 'color' => '#ffd93d', 'language' => 'JavaScript'],
            ['name' => 'Ken Thompson', 'role' => 'Go Creator', 'icon' => '🐹', 'color' => '#00d4ff', 'language' => 'Go'],
            ['name' => 'Graydon Hoare', 'role' => 'Rust Creator', 'icon' => '🦀', 'color' => '#ff6b6b', 'language' => 'Rust'],
            ['name' => 'Chris Lattner', 'role' => 'Swift Creator', 'icon' => '🍎', 'color' => '#ffa500', 'language' => 'Swift']
        ];
        
        foreach ($programmingPioneers as $pioneer):
        ?>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 3rem; margin-bottom: 0.3rem;"><?php echo $pioneer['icon']; ?></div>
            <h4 style="color: <?php echo $pioneer['color']; ?>; font-size: 0.95rem; margin-bottom: 0.2rem;"><?php echo $pioneer['name']; ?></h4>
            <p style="color: #8892b0; font-size: 0.75rem;"><?php echo $pioneer['role']; ?></p>
            <span style="font-size: 0.65rem; background: <?php echo $pioneer['color']; ?>20; color: <?php echo $pioneer['color']; ?>; padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">
                <?php echo $pioneer['language']; ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Code Playground -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff0064;">💻 Code Playground</h2>
        <p style="color: #8892b0;">See the same program written in different languages. Compare syntax and style.</p>
    </div>
    
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; justify-content: center;">
            <button onclick="showCodeExample('hello_world')" style="padding: 0.5rem 1.2rem; background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); border-radius: 8px; color: #00d4ff; cursor: pointer; transition: all 0.3s;">
                Hello World
            </button>
            <button onclick="showCodeExample('fibonacci')" style="padding: 0.5rem 1.2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; color: #8892b0; cursor: pointer; transition: all 0.3s;">
                Fibonacci
            </button>
            <button onclick="showCodeExample('sorting')" style="padding: 0.5rem 1.2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; color: #8892b0; cursor: pointer; transition: all 0.3s;">
                Sorting Algorithm
            </button>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; justify-content: center;">
            <select id="languageSelect" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 0.95rem;">
                <?php foreach ($languages as $lang): ?>
                <option value="<?php echo $lang['name']; ?>"><?php echo escape($lang['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1.5rem; overflow: hidden;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; gap: 0.5rem;">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background: #ff6b6b;"></span>
                    <span style="width: 12px; height: 12px; border-radius: 50%; background: #ffa500;"></span>
                    <span style="width: 12px; height: 12px; border-radius: 50%; background: #00ff88;"></span>
                </div>
                <span style="color: #8892b0; font-size: 0.8rem;" id="codeLanguageLabel">Python</span>
            </div>
            <pre style="font-family: 'Courier New', monospace; font-size: 0.9rem; color: #ccd6f6; overflow-x: auto; margin: 0; padding: 0;" id="codeDisplay">
# Hello World in Python
print("Hello, World!")
            </pre>
            <div style="margin-top: 0.8rem; padding-top: 0.8rem; border-top: 1px solid rgba(255,255,255,0.05); display: flex; gap: 0.5rem;">
                <button onclick="copyCodeDisplay()" style="padding: 0.3rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 4px; color: #8892b0; cursor: pointer; transition: all 0.3s; font-size: 0.8rem;">
                    <i class="fas fa-copy"></i> Copy
                </button>
                <button onclick="runCodeDisplay()" style="padding: 0.3rem 1rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 4px; color: #fff; cursor: pointer; transition: all 0.3s; font-size: 0.8rem;">
                    <i class="fas fa-play"></i> Run
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Quiz -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">🧠 Programming Quiz</h2>
        <p style="color: #8892b0;">Test your knowledge about programming languages and their history.</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <div id="programmingQuiz">
            <div id="quizQuestion" style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem;">
                What is the first high-level programming language?
            </div>
            <div id="quizOptions" style="display: grid; gap: 0.5rem;">
                <button onclick="checkProgrammingQuiz('C')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    A. C
                </button>
                <button onclick="checkProgrammingQuiz('FORTRAN')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    B. FORTRAN
                </button>
                <button onclick="checkProgrammingQuiz('Python')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    C. Python
                </button>
                <button onclick="checkProgrammingQuiz('Java')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    D. Java
                </button>
            </div>
            <div id="quizFeedback" style="margin-top: 1rem; padding: 0.8rem; border-radius: 8px; display: none;"></div>
            <button onclick="nextProgrammingQuizQuestion()" id="quizNextBtn" style="margin-top: 1rem; padding: 0.5rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600; display: none;">
                Next Question →
            </button>
        </div>
    </div>
</section>

<!-- Language Details Modal -->
<div id="languageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeLanguageModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="languageModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

// ===== PAGE JAVASCRIPT =====
$pageJS = <<<'JS'
// Timeline scrolling
function scrollTimeline(direction) {
    const timeline = document.getElementById("programmingTimeline");
    const scrollAmount = 300;
    if (direction === "left") {
        timeline.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
        timeline.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
}

// Language details modal
function showLanguageDetails(title, description, year, icon, category) {
    const modal = document.getElementById("languageModal");
    const content = document.getElementById("languageModalContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 0.5rem;">${icon}</div>
            <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 0.5rem;">${title}</h2>
            <div style="font-family: 'Orbitron', monospace; color: #7b2ffc; font-size: 1.2rem; margin-bottom: 0.3rem;">${year}</div>
            <div style="color: #8892b0; margin-bottom: 1rem;">Category: ${category}</div>
            <p style="color: #ccd6f6; line-height: 1.8;">${description}</p>
            <div style="margin-top: 1rem; padding: 0.8rem; background: rgba(0,212,255,0.05); border-radius: 8px; border: 1px solid rgba(0,212,255,0.1);">
                <span style="color: #00d4ff;">💡</span>
                <span style="color: #8892b0;">This language was a ${category} programming language that ${description.toLowerCase().includes("revolution") ? "revolutionized" : "influenced"} the programming world.</span>
            </div>
            <button onclick="closeLanguageModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Close
            </button>
        </div>
    `;
}

function closeLanguageModal() {
    document.getElementById("languageModal").style.display = "none";
    document.body.style.overflow = "";
}

// Search languages
function filterLanguages() {
    const input = document.getElementById("languageSearch");
    const filter = input.value.toLowerCase();
    const cards = document.querySelectorAll(".language-card");
    
    cards.forEach(card => {
        const name = card.dataset.name || "";
        const creator = card.dataset.creator || "";
        if (name.includes(filter) || creator.includes(filter)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

// Copy code example
function copyCodeExample(slug) {
    const card = document.getElementById("lang-" + slug);
    const code = card.querySelector("pre");
    if (code) {
        navigator.clipboard.writeText(code.textContent).then(() => {
            const btn = card.querySelector("button[onclick^=\"copyCodeExample\"]");
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-copy"></i> Copy';
                }, 2000);
            }
        });
    }
}

// Code Playground
const codeExamples = {
    hello_world: {
        Python: 'print("Hello, World!")',
        Java: 'public class HelloWorld {\\n    public static void main(String[] args) {\\n        System.out.println("Hello, World!");\\n    }\\n}',
        C: '#include <stdio.h>\\n\\nint main() {\\n    printf("Hello, World!\\n");\\n    return 0;\\n}',
        "C++": '#include <iostream>\\n\\nint main() {\\n    std::cout << "Hello, World!" << std::endl;\\n    return 0;\\n}',
        JavaScript: 'console.log("Hello, World!");',
        PHP: '<?php\\necho "Hello, World!";\\n?>',
        Ruby: 'puts "Hello, World!"',
        Go: 'package main\\n\\nimport "fmt"\\n\\nfunc main() {\\n    fmt.Println("Hello, World!")\\n}',
        Rust: 'fn main() {\\n    println!("Hello, World!");\\n}',
        Swift: 'print("Hello, World!")'
    },
    fibonacci: {
        Python: 'def fibonacci(n):\\n    if n <= 1:\\n        return n\\n    return fibonacci(n-1) + fibonacci(n-2)\\n\\nfor i in range(10):\\n    print(fibonacci(i))',
        Java: 'public class Fibonacci {\\n    public static int fibonacci(int n) {\\n        if (n <= 1) return n;\\n        return fibonacci(n-1) + fibonacci(n-2);\\n    }\\n    \\n    public static void main(String[] args) {\\n        for (int i = 0; i < 10; i++) {\\n            System.out.println(fibonacci(i));\\n        }\\n    }\\n}',
        C: '#include <stdio.h>\\n\\nint fibonacci(int n) {\\n    if (n <= 1) return n;\\n    return fibonacci(n-1) + fibonacci(n-2);\\n}\\n\\nint main() {\\n    for (int i = 0; i < 10; i++) {\\n        printf("%d\\n", fibonacci(i));\\n    }\\n    return 0;\\n}',
        JavaScript: 'function fibonacci(n) {\\n    if (n <= 1) return n;\\n    return fibonacci(n-1) + fibonacci(n-2);\\n}\\n\\nfor (let i = 0; i < 10; i++) {\\n    console.log(fibonacci(i));\\n}'
    },
    sorting: {
        Python: 'def bubble_sort(arr):\\n    n = len(arr)\\n    for i in range(n):\\n        for j in range(0, n-i-1):\\n            if arr[j] > arr[j+1]:\\n                arr[j], arr[j+1] = arr[j+1], arr[j]\\n    return arr\\n\\narr = [64, 34, 25, 12, 22, 11, 90]\\nprint(bubble_sort(arr))',
        Java: 'public class BubbleSort {\\n    public static void bubbleSort(int[] arr) {\\n        int n = arr.length;\\n        for (int i = 0; i < n-1; i++) {\\n            for (int j = 0; j < n-i-1; j++) {\\n                if (arr[j] > arr[j+1]) {\\n                    int temp = arr[j];\\n                    arr[j] = arr[j+1];\\n                    arr[j+1] = temp;\\n                }\\n            }\\n        }\\n    }\\n    \\n    public static void main(String[] args) {\\n        int[] arr = {64, 34, 25, 12, 22, 11, 90};\\n        bubbleSort(arr);\\n        System.out.println(Arrays.toString(arr));\\n    }\\n}',
        C: '#include <stdio.h>\\n\\nvoid bubbleSort(int arr[], int n) {\\n    for (int i = 0; i < n-1; i++) {\\n        for (int j = 0; j < n-i-1; j++) {\\n            if (arr[j] > arr[j+1]) {\\n                int temp = arr[j];\\n                arr[j] = arr[j+1];\\n                arr[j+1] = temp;\\n            }\\n        }\\n    }\\n}\\n\\nint main() {\\n    int arr[] = {64, 34, 25, 12, 22, 11, 90};\\n    int n = sizeof(arr)/sizeof(arr[0]);\\n    bubbleSort(arr, n);\\n    for (int i = 0; i < n; i++) {\\n        printf("%d ", arr[i]);\\n    }\\n    return 0;\\n}'
    }
};

let currentCodeExample = "hello_world";

function showCodeExample(type) {
    currentCodeExample = type;
    updateCodeDisplay();
}

function updateCodeDisplay() {
    const languageSelect = document.getElementById("languageSelect");
    const language = languageSelect ? languageSelect.value : "Python";
    const examples = codeExamples[currentCodeExample] || codeExamples.hello_world;
    const code = examples[language] || examples.Python || "// No example available for this language";
    
    const codeDisplay = document.getElementById("codeDisplay");
    const codeLabel = document.getElementById("codeLanguageLabel");
    
    if (codeDisplay) codeDisplay.textContent = code;
    if (codeLabel) codeLabel.textContent = language;
}

// Initialize code display when DOM is ready
document.addEventListener("DOMContentLoaded", function() {
    const languageSelect = document.getElementById("languageSelect");
    if (languageSelect) {
        languageSelect.addEventListener("change", updateCodeDisplay);
    }
    updateCodeDisplay();
});

function copyCodeDisplay() {
    const codeDisplay = document.getElementById("codeDisplay");
    if (!codeDisplay) return;
    
    const code = codeDisplay.textContent;
    navigator.clipboard.writeText(code).then(function() {
        const btn = document.querySelector("#codeDisplay + div button:first-child");
        if (btn) {
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            setTimeout(function() {
                btn.innerHTML = '<i class="fas fa-copy"></i> Copy';
            }, 2000);
        }
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement("textarea");
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        alert("Code copied to clipboard!");
    });
}

function runCodeDisplay() {
    const codeDisplay = document.getElementById("codeDisplay");
    if (!codeDisplay) return;
    
    const code = codeDisplay.textContent;
    alert("💻 Running code:\\n\\n" + code + "\\n\\n⚠️ This is a simulation. The code is not actually executed for security reasons.");
}

// Quiz functionality
let programmingQuizQuestions = [
    { question: "What is the first high-level programming language?", options: ["C", "FORTRAN", "Python", "Java"], correct: 1 },
    { question: "Who created the C programming language?", options: ["Dennis Ritchie", "Bjarne Stroustrup", "Ken Thompson", "Alan Turing"], correct: 0 },
    { question: "What language is known as the 'language of the web'?", options: ["Python", "Java", "JavaScript", "Ruby"], correct: 2 },
    { question: "Which language was created by Guido van Rossum?", options: ["Ruby", "Python", "Perl", "PHP"], correct: 1 },
    { question: "What does 'Java' stand for?", options: ["Just Another Virtual Application", "Java is a programming language", "James Gosling's language", "It was named after coffee"], correct: 3 },
    { question: "Which language introduced the concept of 'write once, run anywhere'?", options: ["C++", "Java", "Python", "JavaScript"], correct: 1 },
    { question: "What is the first object-oriented programming language?", options: ["C++", "Simula", "Smalltalk", "Java"], correct: 1 },
    { question: "Which language was created by Brendan Eich in 10 days?", options: ["TypeScript", "JavaScript", "CoffeeScript", "Dart"], correct: 1 }
];

let currentProgrammingQuiz = 0;
let programmingQuizScore = 0;

function checkProgrammingQuiz(selected) {
    const correct = programmingQuizQuestions[currentProgrammingQuiz].options[programmingQuizQuestions[currentProgrammingQuiz].correct];
    const feedback = document.getElementById("quizFeedback");
    const options = document.querySelectorAll("#quizOptions button");
    
    options.forEach(btn => btn.style.pointerEvents = "none");
    
    options.forEach((btn, index) => {
        if (programmingQuizQuestions[currentProgrammingQuiz].options[index] === correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (btn.textContent.includes(selected) && selected !== correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    if (selected === correct) {
        programmingQuizScore++;
        feedback.innerHTML = '<span style="color: #00ff88;">✅ Correct! Great job!</span>';
        feedback.style.background = "rgba(0, 255, 136, 0.05)";
    } else {
        feedback.innerHTML = '<span style="color: #ff6b6b;">❌ The correct answer was: ' + correct + '</span>';
        feedback.style.background = "rgba(255, 0, 0, 0.05)";
    }
    
    feedback.style.display = "block";
    feedback.style.border = "1px solid rgba(255,255,255,0.05)";
    feedback.style.padding = "0.8rem";
    feedback.style.borderRadius = "8px";
    
    document.getElementById("quizNextBtn").style.display = "inline-block";
}

function nextProgrammingQuizQuestion() {
    currentProgrammingQuiz++;
    
    if (currentProgrammingQuiz >= programmingQuizQuestions.length) {
        const container = document.getElementById("programmingQuiz");
        const percentage = Math.round((programmingQuizScore / programmingQuizQuestions.length) * 100);
        container.innerHTML = `
            <div style="text-align: center; padding: 1rem 0;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">${percentage >= 70 ? "🎉" : "📚"}</div>
                <h3 style="color: ${percentage >= 70 ? "#00ff88" : "#ffa500"}; margin-bottom: 0.5rem;">
                    ${percentage >= 70 ? "Great Job!" : "Keep Learning!"}
                </h3>
                <div style="font-size: 2rem; font-weight: 700; color: #00d4ff; margin: 0.5rem 0;">
                    ${percentage}%
                </div>
                <p style="color: #8892b0;">
                    You got ${programmingQuizScore} out of ${programmingQuizQuestions.length} questions correct.
                </p>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Try Again
                </button>
            </div>
        `;
        return;
    }
    
    const q = programmingQuizQuestions[currentProgrammingQuiz];
    document.getElementById("quizQuestion").textContent = q.question;
    const optionsContainer = document.getElementById("quizOptions");
    optionsContainer.innerHTML = "";
    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.textContent = String.fromCharCode(65 + index) + ". " + option;
        btn.style.cssText = "padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;";
        btn.onclick = function() { checkProgrammingQuiz(option); };
        optionsContainer.appendChild(btn);
    });
    
    document.getElementById("quizFeedback").style.display = "none";
    document.getElementById("quizNextBtn").style.display = "none";
}

// Keyboard shortcuts
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeLanguageModal();
});

// Close modal on overlay click
document.getElementById("languageModal").addEventListener("click", function(e) {
    if (e.target === this) closeLanguageModal();
});

// Animate popularity bars on scroll
const popularityBars = document.querySelectorAll(".popularity-bar");
const barObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const bar = entry.target;
            bar.style.opacity = "1";
            bar.style.transform = "translateX(0)";
            const innerBar = bar.querySelector("div > div");
            if (innerBar) {
                const width = innerBar.style.width;
                innerBar.style.width = "0%";
                setTimeout(() => {
                    innerBar.style.width = width;
                }, 100);
            }
        }
    });
}, { threshold: 0.1 });

popularityBars.forEach(bar => barObserver.observe(bar));

// Add hover effects
document.querySelectorAll(".programming-content button").forEach(btn => {
    btn.addEventListener("mouseenter", function() {
        this.style.transform = "scale(1.05)";
    });
    btn.addEventListener("mouseleave", function() {
        this.style.transform = "scale(1)";
    });
});

// Intersection Observer for timeline items
document.querySelectorAll(".programming-item").forEach((item, index) => {
    item.style.opacity = "0";
    item.style.transform = "translateX(" + (index % 2 === 0 ? "-40px" : "40px") + ")";
    item.style.transition = "all 0.6s ease " + (index * 0.08) + "s";
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateX(0)";
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll(".programming-item").forEach(item => observer.observe(item));

// Add glow animation
const style = document.createElement("style");
style.textContent = `
    @keyframes glowPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
`;
document.head.appendChild(style);

JS;

$pageCSS = '
.programming-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
    max-height: 800px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.programming-timeline::-webkit-scrollbar {
    width: 6px;
}

.programming-timeline::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

.programming-timeline::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

.programming-timeline::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

.programming-timeline::before {
    content: "";
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #00d4ff, #7b2ffc, transparent);
    transform: translateX(-50%);
}

.programming-item {
    display: flex;
    padding: 1.5rem 0;
    position: relative;
    width: 50%;
}

.programming-item.left {
    padding-right: 3rem;
    justify-content: flex-end;
}

.programming-item.right {
    padding-left: 3rem;
    margin-left: 50%;
}

.programming-dot {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #0a0a0f;
    z-index: 2;
    transition: all 0.3s;
}

.programming-item:hover .programming-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.programming-content {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
    width: 100%;
}

.programming-content:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

.programming-year {
    font-family: "Orbitron", monospace;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.programming-icon {
    font-size: 2.5rem;
    margin-bottom: 0.3rem;
}

.programming-content h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.3rem;
}

.programming-content p {
    color: #8892b0;
    font-size: 0.9rem;
    margin: 0;
}

.language-card {
    transition: all 0.3s;
}

.language-card:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

#languageSearch:focus {
    outline: none;
    border-color: #00d4ff;
}

#quizOptions button:hover {
    border-color: #00d4ff !important;
    background: rgba(0, 212, 255, 0.05) !important;
}

#languageModal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

#codeDisplay {
    min-height: 60px;
    white-space: pre-wrap;
    word-break: break-word;
}

@media (max-width: 768px) {
    .programming-timeline {
        max-height: 600px;
        padding: 1rem 0;
    }
    
    .programming-timeline::before {
        left: 20px;
    }
    
    .programming-item {
        width: 100%;
        padding: 1rem 0;
    }
    
    .programming-item.left {
        padding-right: 0;
    }
    
    .programming-item.right {
        padding-left: 0;
        margin-left: 0;
    }
    
    .programming-dot {
        left: 20px;
    }
    
    .programming-content {
        margin-left: 40px;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>