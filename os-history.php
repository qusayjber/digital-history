<?php
// os-history.php
// Digital History - Operating Systems History (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'History of Operating Systems';
$pageDescription = 'Explore the complete evolution of operating systems from UNIX to modern OSes - the story of digital platforms.';

$operatingSystems = getOperatingSystems();

// Get OS-related events
$osEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE is_active = 1 
     AND (title LIKE '%OS%' OR title LIKE '%operating%' OR title LIKE '%Windows%' OR title LIKE '%Linux%' OR title LIKE '%Unix%' OR title LIKE '%Mac%' OR title LIKE '%Android%' OR title LIKE '%iOS%')
     ORDER BY year ASC LIMIT 10"
);

// Get OS-related people
$osPeople = db()->fetchAll(
    "SELECT p.* FROM people p 
     WHERE p.is_active = 1 
     AND (p.known_for LIKE '%Windows%' OR p.known_for LIKE '%Linux%' OR p.known_for LIKE '%Unix%' OR p.known_for LIKE '%Mac%' OR p.known_for LIKE '%OS%' OR p.known_for LIKE '%operating%')
     ORDER BY p.birth_year ASC LIMIT 8"
);

trackPageView('os-history');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            💿 History of Operating Systems
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">From UNIX to modern OSes - the evolution of digital platforms.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($operatingSystems); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Operating Systems</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($osEvents); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Historical Events</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;"><?php echo count($osPeople); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">OS Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">50+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Years of Evolution</div>
        </div>
    </div>
</section>

<!-- OS Timeline -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem; background: rgba(255,255,255,0.01); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h3 style="color: #00d4ff; margin: 0; font-size: 1.5rem;">
            <i class="fas fa-timeline"></i> Operating Systems Timeline
        </h3>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="scrollOSTimeline('left')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollOSTimeline('right')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="os-timeline" id="osTimeline">
        <?php
        $osMilestones = [
            [
                'year' => '1969',
                'name' => 'UNIX',
                'description' => 'The first portable operating system, designed for multi-user and multi-tasking.',
                'icon' => '🐧',
                'color' => '#00d4ff',
                'category' => 'Server',
                'impact' => 'Revolutionary',
                'company' => 'Bell Labs'
            ],
            [
                'year' => '1977',
                'name' => 'Apple DOS',
                'description' => 'Apple\'s first operating system for the Apple II, based on disk operating system.',
                'icon' => '🍎',
                'color' => '#7b2ffc',
                'category' => 'Desktop',
                'impact' => 'Influential',
                'company' => 'Apple'
            ],
            [
                'year' => '1981',
                'name' => 'MS-DOS',
                'description' => 'Microsoft\'s command-line operating system that became the foundation of IBM PC.',
                'icon' => '💾',
                'color' => '#ff6b6b',
                'category' => 'Desktop',
                'impact' => 'Revolutionary',
                'company' => 'Microsoft'
            ],
            [
                'year' => '1984',
                'name' => 'Mac OS',
                'description' => 'Apple\'s first graphical user interface operating system, revolutionizing personal computing.',
                'icon' => '🖥️',
                'color' => '#7b2ffc',
                'category' => 'Desktop',
                'impact' => 'Revolutionary',
                'company' => 'Apple'
            ],
            [
                'year' => '1985',
                'name' => 'Windows 1.0',
                'description' => 'Microsoft\'s first graphical operating system, introducing the Windows interface.',
                'icon' => '🪟',
                'color' => '#00ff88',
                'category' => 'Desktop',
                'impact' => 'Influential',
                'company' => 'Microsoft'
            ],
            [
                'year' => '1991',
                'name' => 'Linux Kernel',
                'description' => 'Linus Torvalds creates the Linux kernel, an open-source operating system.',
                'icon' => '🐧',
                'color' => '#ffa500',
                'category' => 'Server',
                'impact' => 'Revolutionary',
                'company' => 'Open Source'
            ],
            [
                'year' => '1995',
                'name' => 'Windows 95',
                'description' => 'A major release with significant UI improvements and 32-bit support.',
                'icon' => '🪟',
                'color' => '#00d4ff',
                'category' => 'Desktop',
                'impact' => 'Revolutionary',
                'company' => 'Microsoft'
            ],
            [
                'year' => '2000',
                'name' => 'Windows 2000',
                'description' => 'A Windows NT-based operating system designed for business and enterprise use.',
                'icon' => '🪟',
                'color' => '#7b2ffc',
                'category' => 'Server',
                'impact' => 'High',
                'company' => 'Microsoft'
            ],
            [
                'year' => '2001',
                'name' => 'Mac OS X',
                'description' => 'Apple\'s modern operating system based on UNIX, with a new interface.',
                'icon' => '🍎',
                'color' => '#7b2ffc',
                'category' => 'Desktop',
                'impact' => 'Revolutionary',
                'company' => 'Apple'
            ],
            [
                'year' => '2001',
                'name' => 'Windows XP',
                'description' => 'One of the most successful and beloved versions of Windows.',
                'icon' => '🪟',
                'color' => '#00ff88',
                'category' => 'Desktop',
                'impact' => 'Revolutionary',
                'company' => 'Microsoft'
            ],
            [
                'year' => '2007',
                'name' => 'iOS',
                'description' => 'Apple\'s mobile operating system for iPhone, revolutionizing smartphones.',
                'icon' => '📱',
                'color' => '#00ff88',
                'category' => 'Mobile',
                'impact' => 'Revolutionary',
                'company' => 'Apple'
            ],
            [
                'year' => '2008',
                'name' => 'Android',
                'description' => 'Google\'s open-source mobile operating system, becoming the most popular mobile OS.',
                'icon' => '🤖',
                'color' => '#00d4ff',
                'category' => 'Mobile',
                'impact' => 'Revolutionary',
                'company' => 'Google'
            ],
            [
                'year' => '2009',
                'name' => 'Windows 7',
                'description' => 'A major release with improved performance and a redesigned taskbar.',
                'icon' => '🪟',
                'color' => '#7b2ffc',
                'category' => 'Desktop',
                'impact' => 'High',
                'company' => 'Microsoft'
            ],
            [
                'year' => '2011',
                'name' => 'ChromeOS',
                'description' => 'Google\'s lightweight operating system based on the Chrome browser.',
                'icon' => '🌐',
                'color' => '#00ff88',
                'category' => 'Desktop',
                'impact' => 'Influential',
                'company' => 'Google'
            ],
            [
                'year' => '2015',
                'name' => 'Windows 10',
                'description' => 'A unified operating system for PCs, tablets, and phones.',
                'icon' => '🪟',
                'color' => '#7b2ffc',
                'category' => 'Desktop',
                'impact' => 'High',
                'company' => 'Microsoft'
            ],
            [
                'year' => '2017',
                'name' => 'macOS High Sierra',
                'description' => 'Apple\'s latest macOS version with performance and security improvements.',
                'icon' => '🍎',
                'color' => '#7b2ffc',
                'category' => 'Desktop',
                'impact' => 'Medium',
                'company' => 'Apple'
            ],
            [
                'year' => '2021',
                'name' => 'Windows 11',
                'description' => 'The latest Windows release with a modernized interface and performance improvements.',
                'icon' => '🪟',
                'color' => '#00d4ff',
                'category' => 'Desktop',
                'impact' => 'High',
                'company' => 'Microsoft'
            ],
            [
                'year' => '2023',
                'name' => 'AI-Integrated OS',
                'description' => 'Operating systems integrating artificial intelligence for enhanced user experience.',
                'icon' => '🧠',
                'color' => '#ff0064',
                'category' => 'Desktop',
                'impact' => 'Emerging',
                'company' => 'Multiple'
            ]
        ];
        
        foreach ($osMilestones as $index => $os):
        ?>
        <div class="os-item <?php echo $index % 2 === 0 ? 'left' : 'right'; ?> animate-on-scroll" data-index="<?php echo $index; ?>">
            <div class="os-dot" style="background: <?php echo $os['color']; ?>; box-shadow: 0 0 20px <?php echo $os['color']; ?>40;"></div>
            <div class="os-content" style="border-color: <?php echo $os['color']; ?>20;">
                <div class="os-year" style="color: <?php echo $os['color']; ?>;"><?php echo $os['year']; ?></div>
                <div class="os-icon"><?php echo $os['icon']; ?></div>
                <h3><?php echo $os['name']; ?></h3>
                <p style="color: #8892b0; font-size: 0.8rem; margin: 0.3rem 0;"><?php echo $os['company']; ?></p>
                <p style="color: #8892b0; font-size: 0.85rem; margin: 0.3rem 0;"><?php echo $os['description']; ?></p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; background: <?php echo $os['color']; ?>20; color: <?php echo $os['color']; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $os['category']; ?>
                    </span>
                    <?php
                    if ($os['impact'] === 'Revolutionary') {
                        $impactBg = 'rgba(255,0,100,0.1)';
                        $impactColor = '#ff0064';
                    } elseif ($os['impact'] === 'Influential') {
                        $impactBg = 'rgba(0,212,255,0.1)';
                        $impactColor = '#00d4ff';
                    } elseif ($os['impact'] === 'High') {
                        $impactBg = 'rgba(0,255,136,0.1)';
                        $impactColor = '#00ff88';
                    } else {
                        $impactBg = 'rgba(255,165,0,0.1)';
                        $impactColor = '#ffa500';
                    }
                    ?>
                    <span style="font-size: 0.6rem; background: <?php echo $impactBg; ?>; color: <?php echo $impactColor; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $os['impact']; ?>
                    </span>
                </div>
                <button onclick="showOSDetails('<?php echo addslashes($os['name']); ?>', '<?php echo addslashes($os['description']); ?>', '<?php echo $os['year']; ?>', '<?php echo $os['icon']; ?>', '<?php echo $os['category']; ?>', '<?php echo $os['company']; ?>')" style="margin-top: 0.8rem; padding: 0.3rem 1rem; background: <?php echo $os['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; font-size: 0.8rem; transition: all 0.3s;">
                    Learn More
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- OS Categories -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📚 Operating System Categories</h2>
        <p style="color: #8892b0;">Different types of operating systems designed for various purposes.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🖥️</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Desktop OS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Windows, macOS, Linux</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Personal computers</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📱</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">Mobile OS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Android, iOS</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Smartphones, tablets</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🌐</div>
            <h4 style="color: #00ff88; font-size: 1rem;">Server OS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Linux, Windows Server</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Data centers, cloud</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔌</div>
            <h4 style="color: #ffa500; font-size: 1rem;">Embedded OS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">FreeRTOS, VxWorks</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">IoT, devices</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">☁️</div>
            <h4 style="color: #ff0064; font-size: 1rem;">Cloud OS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">ChromeOS, CloudReady</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Cloud-native computing</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">AI OS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">AI-Integrated OS</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Intelligent systems</p>
        </div>
    </div>
</section>

<!-- OS Cards -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">🖥️ All Operating Systems</h2>
        <p style="color: #8892b0;">Detailed information about major operating systems and their features.</p>
    </div>
    
    <!-- Search Bar -->
    <div style="max-width: 500px; margin: 1.5rem auto 2rem;">
        <input type="text" id="osSearch" placeholder="Search operating systems..." onkeyup="filterOS()" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem;">
    </div>
    
    <div class="card-grid" id="osGrid">
        <?php foreach ($operatingSystems as $os): ?>
        <div class="card animate-on-scroll os-card" data-name="<?php echo strtolower($os['name']); ?>" data-company="<?php echo strtolower($os['company']); ?>">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div class="card-title" style="font-size: 1.2rem;"><?php echo escape($os['name']); ?></div>
                    <div style="color: #8892b0; font-size: 0.8rem;">
                        <?php echo escape($os['company']); ?> · <?php echo $os['year_released']; ?>
                    </div>
                </div>
                <span style="font-size: 0.65rem; background: rgba(255,255,255,0.05); padding: 0.15rem 0.6rem; border-radius: 12px; color: #8892b0; text-transform: capitalize;"><?php echo $os['type']; ?></span>
            </div>
            <div class="card-description" style="margin-top: 0.5rem;"><?php echo escape(truncate($os['description'], 120)); ?></div>
            <?php if ($os['version']): ?>
            <div style="font-size: 0.8rem; color: #8892b0; margin-top: 0.3rem;">
                <i class="fas fa-tag"></i> Version: <?php echo escape($os['version']); ?>
            </div>
            <?php endif; ?>
            <?php if ($os['screenshot']): ?>
            <div style="margin-top: 0.5rem; border-radius: 6px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                <img src="<?php echo UPLOADS_URL . $os['screenshot']; ?>" alt="<?php echo escape($os['name']); ?>" style="width: 100%; height: 120px; object-fit: cover;">
            </div>
            <?php endif; ?>
            <div style="margin-top: 0.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.6rem; background: rgba(255,255,255,0.05); padding: 0.1rem 0.5rem; border-radius: 4px; color: #8892b0;">
                    <i class="fas fa-calendar"></i> <?php echo $os['year_released']; ?>
                </span>
                <span style="font-size: 0.6rem; background: rgba(255,255,255,0.05); padding: 0.1rem 0.5rem; border-radius: 4px; color: #8892b0;">
                    <i class="fas fa-building"></i> <?php echo escape($os['company']); ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- OS Market Share -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">📈 OS Market Share</h2>
        <p style="color: #8892b0;">Approximate market share of major operating systems in desktop computing.</p>
    </div>
    
    <div style="max-width: 700px; margin: 0 auto;">
        <div class="market-bar" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; opacity: 0; transform: translateX(-20px); transition: all 0.5s ease;">
            <div style="width: 120px; color: #8892b0; font-size: 0.85rem; text-align: right;">Windows</div>
            <div style="flex: 1; height: 24px; background: rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden;">
                <div style="height: 100%; width: 72%; background: linear-gradient(90deg, #00d4ff, #7b2ffc); border-radius: 12px; transition: width 1.5s ease;"></div>
            </div>
            <div style="width: 40px; color: #ccd6f6; font-size: 0.85rem; font-weight: 600;">72%</div>
        </div>
        <div class="market-bar" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; opacity: 0; transform: translateX(-20px); transition: all 0.5s ease 0.1s;">
            <div style="width: 120px; color: #8892b0; font-size: 0.85rem; text-align: right;">macOS</div>
            <div style="flex: 1; height: 24px; background: rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden;">
                <div style="height: 100%; width: 15%; background: linear-gradient(90deg, #7b2ffc, #00ff88); border-radius: 12px; transition: width 1.5s ease;"></div>
            </div>
            <div style="width: 40px; color: #ccd6f6; font-size: 0.85rem; font-weight: 600;">15%</div>
        </div>
        <div class="market-bar" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; opacity: 0; transform: translateX(-20px); transition: all 0.5s ease 0.2s;">
            <div style="width: 120px; color: #8892b0; font-size: 0.85rem; text-align: right;">Linux</div>
            <div style="flex: 1; height: 24px; background: rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden;">
                <div style="height: 100%; width: 4%; background: linear-gradient(90deg, #ffa500, #ff6b6b); border-radius: 12px; transition: width 1.5s ease;"></div>
            </div>
            <div style="width: 40px; color: #ccd6f6; font-size: 0.85rem; font-weight: 600;">4%</div>
        </div>
        <div class="market-bar" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; opacity: 0; transform: translateX(-20px); transition: all 0.5s ease 0.3s;">
            <div style="width: 120px; color: #8892b0; font-size: 0.85rem; text-align: right;">ChromeOS</div>
            <div style="flex: 1; height: 24px; background: rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden;">
                <div style="height: 100%; width: 3%; background: linear-gradient(90deg, #00ff88, #00d4ff); border-radius: 12px; transition: width 1.5s ease;"></div>
            </div>
            <div style="width: 40px; color: #ccd6f6; font-size: 0.85rem; font-weight: 600;">3%</div>
        </div>
        <div style="margin-top: 1rem; color: #8892b0; font-size: 0.8rem; text-align: center;">* Approximate desktop market share data</div>
    </div>
</section>

<!-- OS Pioneers -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">👨‍💻 Operating System Pioneers</h2>
        <p style="color: #8892b0;">The brilliant minds who created the operating systems we use today.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <?php
        $osPioneers = [
            ['name' => 'Ken Thompson', 'role' => 'UNIX Creator', 'icon' => '🐧', 'color' => '#00d4ff', 'os' => 'UNIX'],
            ['name' => 'Dennis Ritchie', 'role' => 'UNIX Co-Creator', 'icon' => '⚡', 'color' => '#7b2ffc', 'os' => 'UNIX'],
            ['name' => 'Bill Gates', 'role' => 'Windows Founder', 'icon' => '🪟', 'color' => '#00d4ff', 'os' => 'Windows'],
            ['name' => 'Paul Allen', 'role' => 'Microsoft Co-Founder', 'icon' => '💻', 'color' => '#7b2ffc', 'os' => 'MS-DOS'],
            ['name' => 'Steve Jobs', 'role' => 'Mac OS Visionary', 'icon' => '🍎', 'color' => '#7b2ffc', 'os' => 'macOS'],
            ['name' => 'Linus Torvalds', 'role' => 'Linux Creator', 'icon' => '🐧', 'color' => '#ffa500', 'os' => 'Linux'],
            ['name' => 'Andy Rubin', 'role' => 'Android Creator', 'icon' => '🤖', 'color' => '#00ff88', 'os' => 'Android'],
            ['name' => 'Scott Forstall', 'role' => 'iOS Creator', 'icon' => '📱', 'color' => '#00d4ff', 'os' => 'iOS']
        ];
        
        foreach ($osPioneers as $pioneer):
        ?>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 3rem; margin-bottom: 0.3rem;"><?php echo $pioneer['icon']; ?></div>
            <h4 style="color: <?php echo $pioneer['color']; ?>; font-size: 0.95rem; margin-bottom: 0.2rem;"><?php echo $pioneer['name']; ?></h4>
            <p style="color: #8892b0; font-size: 0.75rem;"><?php echo $pioneer['role']; ?></p>
            <span style="font-size: 0.65rem; background: <?php echo $pioneer['color']; ?>20; color: <?php echo $pioneer['color']; ?>; padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">
                <?php echo $pioneer['os']; ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Interactive Quiz -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff0064;">🧠 Operating System Quiz</h2>
        <p style="color: #8892b0;">Test your knowledge about operating systems and their history.</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <div id="osQuiz">
            <div id="osQuizQuestion" style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem;">
                What is the first version of Windows?
            </div>
            <div id="osQuizOptions" style="display: grid; gap: 0.5rem;">
                <button onclick="checkOSQuiz('Windows 1.0')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    A. Windows 1.0
                </button>
                <button onclick="checkOSQuiz('Windows 3.0')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    B. Windows 3.0
                </button>
                <button onclick="checkOSQuiz('Windows 95')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    C. Windows 95
                </button>
                <button onclick="checkOSQuiz('Windows NT')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    D. Windows NT
                </button>
            </div>
            <div id="osQuizFeedback" style="margin-top: 1rem; padding: 0.8rem; border-radius: 8px; display: none;"></div>
            <button onclick="nextOSQuizQuestion()" id="osQuizNextBtn" style="margin-top: 1rem; padding: 0.5rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600; display: none;">
                Next Question →
            </button>
        </div>
    </div>
</section>

<!-- OS Details Modal -->
<div id="osModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeOSModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="osModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Timeline scrolling
function scrollOSTimeline(direction) {
    const timeline = document.getElementById("osTimeline");
    const scrollAmount = 300;
    if (direction === "left") {
        timeline.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
        timeline.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
}

// OS details modal
function showOSDetails(name, description, year, icon, category, company) {
    const modal = document.getElementById("osModal");
    const content = document.getElementById("osModalContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 0.5rem;">${icon}</div>
            <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 0.5rem;">${name}</h2>
            <div style="font-family: 'Orbitron', monospace; color: #7b2ffc; font-size: 1.2rem; margin-bottom: 0.3rem;">${year}</div>
            <div style="color: #8892b0; margin-bottom: 0.3rem;">Company: ${company}</div>
            <div style="color: #8892b0; margin-bottom: 1rem;">Category: ${category}</div>
            <p style="color: #ccd6f6; line-height: 1.8;">${description}</p>
            <div style="margin-top: 1rem; padding: 0.8rem; background: rgba(0,212,255,0.05); border-radius: 8px; border: 1px solid rgba(0,212,255,0.1);">
                <span style="color: #00d4ff;">💡</span>
                <span style="color: #8892b0;">${name} was a ${category} operating system developed by ${company} that ${description.toLowerCase().includes("revolution") ? "revolutionized" : "influenced"} the computing world.</span>
            </div>
            <button onclick="closeOSModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Close
            </button>
        </div>
    `;
}

function closeOSModal() {
    document.getElementById("osModal").style.display = "none";
    document.body.style.overflow = "";
}

// Search OS
function filterOS() {
    const input = document.getElementById("osSearch");
    const filter = input.value.toLowerCase();
    const cards = document.querySelectorAll(".os-card");
    
    cards.forEach(card => {
        const name = card.dataset.name || "";
        const company = card.dataset.company || "";
        if (name.includes(filter) || company.includes(filter)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

// OS Quiz functionality
let osQuizQuestions = [
    { question: "What is the first version of Windows?", options: ["Windows 1.0", "Windows 3.0", "Windows 95", "Windows NT"], correct: 0 },
    { question: "Who created the Linux kernel?", options: ["Linus Torvalds", "Bill Gates", "Steve Jobs", "Ken Thompson"], correct: 0 },
    { question: "What year was macOS introduced?", options: ["1984", "1991", "2001", "2007"], correct: 2 },
    { question: "Which OS is based on UNIX?", options: ["Windows", "macOS", "Android", "iOS"], correct: 1 },
    { question: "What is the most popular mobile OS?", options: ["iOS", "Android", "Windows Phone", "BlackBerry"], correct: 1 },
    { question: "Who founded Microsoft?", options: ["Bill Gates", "Steve Jobs", "Linus Torvalds", "Ken Thompson"], correct: 0 },
    { question: "What year was Android released?", options: ["2005", "2006", "2007", "2008"], correct: 3 },
    { question: "Which OS is open-source?", options: ["Windows", "macOS", "Linux", "iOS"], correct: 2 }
];

let currentOSQuiz = 0;
let osQuizScore = 0;

function checkOSQuiz(selected) {
    const correct = osQuizQuestions[currentOSQuiz].options[osQuizQuestions[currentOSQuiz].correct];
    const feedback = document.getElementById("osQuizFeedback");
    const options = document.querySelectorAll("#osQuizOptions button");
    
    options.forEach(btn => btn.style.pointerEvents = "none");
    
    options.forEach((btn, index) => {
        if (osQuizQuestions[currentOSQuiz].options[index] === correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (btn.textContent.includes(selected) && selected !== correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    if (selected === correct) {
        osQuizScore++;
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
    
    document.getElementById("osQuizNextBtn").style.display = "inline-block";
}

function nextOSQuizQuestion() {
    currentOSQuiz++;
    
    if (currentOSQuiz >= osQuizQuestions.length) {
        const container = document.getElementById("osQuiz");
        const percentage = Math.round((osQuizScore / osQuizQuestions.length) * 100);
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
                    You got ${osQuizScore} out of ${osQuizQuestions.length} questions correct.
                </p>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Try Again
                </button>
            </div>
        `;
        return;
    }
    
    const q = osQuizQuestions[currentOSQuiz];
    document.getElementById("osQuizQuestion").textContent = q.question;
    const optionsContainer = document.getElementById("osQuizOptions");
    optionsContainer.innerHTML = "";
    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.textContent = String.fromCharCode(65 + index) + ". " + option;
        btn.style.cssText = "padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;";
        btn.onclick = function() { checkOSQuiz(option); };
        optionsContainer.appendChild(btn);
    });
    
    document.getElementById("osQuizFeedback").style.display = "none";
    document.getElementById("osQuizNextBtn").style.display = "none";
}

// Keyboard shortcuts
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeOSModal();
});

// Close modal on overlay click
document.getElementById("osModal").addEventListener("click", function(e) {
    if (e.target === this) closeOSModal();
});

// Animate market share bars on scroll
const marketBars = document.querySelectorAll(".market-bar");
const barObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const bar = entry.target;
            bar.style.opacity = "1";
            bar.style.transform = "translateX(0)";
        }
    });
}, { threshold: 0.1 });

marketBars.forEach(bar => barObserver.observe(bar));

// Intersection Observer for timeline items
document.querySelectorAll(".os-item").forEach((item, index) => {
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

document.querySelectorAll(".os-item").forEach(item => observer.observe(item));

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
.os-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
    max-height: 800px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.os-timeline::-webkit-scrollbar {
    width: 6px;
}

.os-timeline::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

.os-timeline::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

.os-timeline::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

.os-timeline::before {
    content: "";
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #00d4ff, #7b2ffc, transparent);
    transform: translateX(-50%);
}

.os-item {
    display: flex;
    padding: 1.5rem 0;
    position: relative;
    width: 50%;
}

.os-item.left {
    padding-right: 3rem;
    justify-content: flex-end;
}

.os-item.right {
    padding-left: 3rem;
    margin-left: 50%;
}

.os-dot {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid #0a0a0f;
    z-index: 2;
    transition: all 0.3s;
}

.os-item:hover .os-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.os-content {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
    width: 100%;
}

.os-content:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

.os-year {
    font-family: "Orbitron", monospace;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.os-icon {
    font-size: 2.5rem;
    margin-bottom: 0.3rem;
}

.os-content h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.3rem;
}

.os-card {
    transition: all 0.3s;
}

.os-card:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

#osSearch:focus {
    outline: none;
    border-color: #00d4ff;
}

#osQuizOptions button:hover {
    border-color: #00d4ff !important;
    background: rgba(0, 212, 255, 0.05) !important;
}

#osModal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .os-timeline {
        max-height: 600px;
        padding: 1rem 0;
    }
    
    .os-timeline::before {
        left: 20px;
    }
    
    .os-item {
        width: 100%;
        padding: 1rem 0;
    }
    
    .os-item.left {
        padding-right: 0;
    }
    
    .os-item.right {
        padding-left: 0;
        margin-left: 0;
    }
    
    .os-dot {
        left: 20px;
    }
    
    .os-content {
        margin-left: 40px;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>