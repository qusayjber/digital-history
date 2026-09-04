<?php
// web.php
// Digital History - Web History (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'History of the Web';
$pageDescription = 'Explore the complete history of the World Wide Web from Web 1.0 to the modern intelligent web.';

// Get web-related events
$webEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE is_active = 1 
     AND (title LIKE '%web%' OR title LIKE '%browser%' OR title LIKE '%HTML%' OR title LIKE '%CSS%' OR title LIKE '%JavaScript%' OR title LIKE '%HTTP%' OR title LIKE '%website%' OR title LIKE '%social%')
     ORDER BY year ASC LIMIT 15"
);

// Get web-related people
$webPeople = db()->fetchAll(
    "SELECT p.* FROM people p 
     WHERE p.is_active = 1 
     AND (p.known_for LIKE '%web%' OR p.known_for LIKE '%browser%' OR p.known_for LIKE '%HTML%' OR p.known_for LIKE '%social%' OR p.known_for LIKE '%search%')
     ORDER BY p.birth_year ASC LIMIT 10"
);

trackPageView('web');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            🌍 History of the Web
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">From static pages to the modern intelligent web - the evolution of the World Wide Web.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($webEvents); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Web Events</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($webPeople); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Web Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">4</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Web Generations</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">35+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Years of Evolution</div>
        </div>
    </div>
</section>

<!-- Web Evolution Timeline -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem; background: rgba(255,255,255,0.01); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h3 style="color: #00d4ff; margin: 0; font-size: 1.5rem;">
            <i class="fas fa-timeline"></i> Web Evolution Timeline
        </h3>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="scrollWebTimeline('left')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollWebTimeline('right')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="web-timeline" id="webTimeline">
        <?php
        $webMilestones = [
            [
                'year' => '1989',
                'title' => 'World Wide Web Invented',
                'description' => 'Tim Berners-Lee proposes the World Wide Web at CERN, revolutionizing information sharing.',
                'icon' => '🌍',
                'color' => '#ff6b6b',
                'category' => 'Foundation',
                'impact' => 'Revolutionary',
                'company' => 'CERN'
            ],
            [
                'year' => '1990',
                'title' => 'First Web Browser',
                'description' => 'Tim Berners-Lee creates the first web browser called WorldWideWeb (later Nexus).',
                'icon' => '🖥️',
                'color' => '#ffa500',
                'category' => 'Browser',
                'impact' => 'Revolutionary',
                'company' => 'CERN'
            ],
            [
                'year' => '1991',
                'title' => 'First Website',
                'description' => 'The first website goes online at CERN, explaining the World Wide Web project.',
                'icon' => '💻',
                'color' => '#ffd93d',
                'category' => 'Content',
                'impact' => 'High',
                'company' => 'CERN'
            ],
            [
                'year' => '1993',
                'title' => 'HTML 1.0',
                'description' => 'The first version of HTML is released, standardizing web page creation.',
                'icon' => '📄',
                'color' => '#00d4ff',
                'category' => 'Standard',
                'impact' => 'High',
                'company' => 'IETF'
            ],
            [
                'year' => '1993',
                'title' => 'Mosaic Browser',
                'description' => 'The first popular web browser with graphics and images, making the web accessible.',
                'icon' => '🌐',
                'color' => '#7b2ffc',
                'category' => 'Browser',
                'impact' => 'Revolutionary',
                'company' => 'NCSA'
            ],
            [
                'year' => '1994',
                'title' => 'Netscape Navigator',
                'description' => 'Netscape Navigator becomes the dominant web browser of the early web.',
                'icon' => '🚀',
                'color' => '#00ff88',
                'category' => 'Browser',
                'impact' => 'High',
                'company' => 'Netscape'
            ],
            [
                'year' => '1995',
                'title' => 'JavaScript Created',
                'description' => 'Brendan Eich creates JavaScript in 10 days, enabling interactive web pages.',
                'icon' => '⚡',
                'color' => '#ff0064',
                'category' => 'Language',
                'impact' => 'Revolutionary',
                'company' => 'Netscape'
            ],
            [
                'year' => '1995',
                'title' => 'Internet Explorer',
                'description' => 'Microsoft releases Internet Explorer, starting the browser wars.',
                'icon' => '🪟',
                'color' => '#00d4ff',
                'category' => 'Browser',
                'impact' => 'High',
                'company' => 'Microsoft'
            ],
            [
                'year' => '1996',
                'title' => 'CSS 1.0',
                'description' => 'Cascading Style Sheets 1.0 is released, separating content from presentation.',
                'icon' => '🎨',
                'color' => '#7b2ffc',
                'category' => 'Standard',
                'impact' => 'High',
                'company' => 'W3C'
            ],
            [
                'year' => '1998',
                'title' => 'Google Founded',
                'description' => 'Larry Page and Sergey Brin found Google, revolutionizing web search.',
                'icon' => '🔍',
                'color' => '#00d4ff',
                'category' => 'Search',
                'impact' => 'Revolutionary',
                'company' => 'Google'
            ],
            [
                'year' => '1999',
                'title' => 'Web 1.0 Era',
                'description' => 'The read-only web with static pages and simple hyperlinks.',
                'icon' => '📄',
                'color' => '#8892b0',
                'category' => 'Era',
                'impact' => 'Foundational',
                'company' => 'Multiple'
            ],
            [
                'year' => '2001',
                'title' => 'Wikipedia Launched',
                'description' => 'Wikipedia launches as the free online encyclopedia, embodying Web 2.0 principles.',
                'icon' => '📚',
                'color' => '#7b2ffc',
                'category' => 'Content',
                'impact' => 'High',
                'company' => 'Wikimedia'
            ],
            [
                'year' => '2004',
                'title' => 'Web 2.0 Emerges',
                'description' => 'The interactive web with social media, user-generated content, and collaboration.',
                'icon' => '📱',
                'color' => '#00d4ff',
                'category' => 'Era',
                'impact' => 'Revolutionary',
                'company' => 'Multiple'
            ],
            [
                'year' => '2004',
                'title' => 'Facebook Launched',
                'description' => 'Facebook launches, revolutionizing social media and user interaction.',
                'icon' => '👤',
                'color' => '#00ff88',
                'category' => 'Social',
                'impact' => 'Revolutionary',
                'company' => 'Meta'
            ],
            [
                'year' => '2005',
                'title' => 'YouTube Launched',
                'description' => 'YouTube launches, transforming video sharing and online content consumption.',
                'icon' => '📺',
                'color' => '#ff0064',
                'category' => 'Media',
                'impact' => 'Revolutionary',
                'company' => 'Google'
            ],
            [
                'year' => '2006',
                'title' => 'Twitter Launched',
                'description' => 'Twitter introduces microblogging and real-time communication.',
                'icon' => '🐦',
                'color' => '#00d4ff',
                'category' => 'Social',
                'impact' => 'High',
                'company' => 'X Corp'
            ],
            [
                'year' => '2008',
                'title' => 'Chrome Browser',
                'description' => 'Google releases Chrome browser, becoming the most popular web browser.',
                'icon' => '🌐',
                'color' => '#00ff88',
                'category' => 'Browser',
                'impact' => 'High',
                'company' => 'Google'
            ],
            [
                'year' => '2010',
                'title' => 'Instagram Launched',
                'description' => 'Instagram launches, revolutionizing visual content and mobile photography.',
                'icon' => '📸',
                'color' => '#ffa500',
                'category' => 'Social',
                'impact' => 'High',
                'company' => 'Meta'
            ],
            [
                'year' => '2015',
                'title' => 'Web 3.0 Concept',
                'description' => 'The decentralized web concept emerges with blockchain and semantic technologies.',
                'icon' => '🔗',
                'color' => '#7b2ffc',
                'category' => 'Era',
                'impact' => 'Emerging',
                'company' => 'Multiple'
            ],
            [
                'year' => '2016',
                'title' => 'TikTok Launched',
                'description' => 'TikTok launches, transforming short-form video content and social media.',
                'icon' => '🎵',
                'color' => '#ff6b6b',
                'category' => 'Social',
                'impact' => 'High',
                'company' => 'ByteDance'
            ],
            [
                'year' => '2020',
                'title' => 'AI Web Emerges',
                'description' => 'AI-powered web services transform how we interact with the internet.',
                'icon' => '🧠',
                'color' => '#7b2ffc',
                'category' => 'Era',
                'impact' => 'Revolutionary',
                'company' => 'Multiple'
            ],
            [
                'year' => '2022',
                'title' => 'ChatGPT Released',
                'description' => 'ChatGPT brings AI to the mainstream, transforming web interaction.',
                'icon' => '🤖',
                'color' => '#00d4ff',
                'category' => 'AI',
                'impact' => 'Revolutionary',
                'company' => 'OpenAI'
            ],
            [
                'year' => '2023',
                'title' => 'Modern Web Era',
                'description' => 'The intelligent web with AI-first, real-time, and cloud-native applications.',
                'icon' => '🚀',
                'color' => '#00ff88',
                'category' => 'Era',
                'impact' => 'Current',
                'company' => 'Multiple'
            ]
        ];
        
        foreach ($webMilestones as $index => $event):
        ?>
        <div class="web-item <?php echo $index % 2 === 0 ? 'left' : 'right'; ?> animate-on-scroll" data-index="<?php echo $index; ?>">
            <div class="web-dot" style="background: <?php echo $event['color']; ?>; box-shadow: 0 0 20px <?php echo $event['color']; ?>40;"></div>
            <div class="web-content" style="border-color: <?php echo $event['color']; ?>20;">
                <div class="web-year" style="color: <?php echo $event['color']; ?>;"><?php echo $event['year']; ?></div>
                <div class="web-icon"><?php echo $event['icon']; ?></div>
                <h3><?php echo $event['title']; ?></h3>
                <p><?php echo $event['description']; ?></p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; background: <?php echo $event['color']; ?>20; color: <?php echo $event['color']; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $event['category']; ?>
                    </span>
                    <?php
                    if ($event['impact'] === 'Revolutionary') {
                        $impactBg = 'rgba(255,0,100,0.1)';
                        $impactColor = '#ff0064';
                    } elseif ($event['impact'] === 'High') {
                        $impactBg = 'rgba(0,212,255,0.1)';
                        $impactColor = '#00d4ff';
                    } elseif ($event['impact'] === 'Foundational') {
                        $impactBg = 'rgba(255,165,0,0.1)';
                        $impactColor = '#ffa500';
                    } else {
                        $impactBg = 'rgba(0,255,136,0.1)';
                        $impactColor = '#00ff88';
                    }
                    ?>
                    <span style="font-size: 0.6rem; background: <?php echo $impactBg; ?>; color: <?php echo $impactColor; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $event['impact']; ?>
                    </span>
                </div>
                <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">
                    <?php echo $event['company']; ?>
                </div>
                <button onclick="showWebDetails('<?php echo addslashes($event['title']); ?>', '<?php echo addslashes($event['description']); ?>', '<?php echo $event['year']; ?>', '<?php echo $event['icon']; ?>', '<?php echo $event['category']; ?>', '<?php echo $event['company']; ?>')" style="margin-top: 0.8rem; padding: 0.3rem 1rem; background: <?php echo $event['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; font-size: 0.8rem; transition: all 0.3s;">
                    Learn More
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Web Evolution Cards -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📚 Web Generations</h2>
        <p style="color: #8892b0;">The evolution of the World Wide Web across four generations.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
        <!-- Web 1.0 -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">📄</div>
            <h3 style="color: #8892b0; font-size: 1.3rem;">Web 1.0</h3>
            <div style="font-size: 0.8rem; color: #8892b0;">1990-2000</div>
            <div style="margin-top: 1rem; text-align: left;">
                <ul style="list-style: none; padding: 0; color: #8892b0; font-size: 0.85rem;">
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">📄 Static Pages</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">📖 Read-Only</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">🔗 Hyperlinks</li>
                    <li style="padding: 0.3rem 0;">📰 Content Publishing</li>
                </ul>
            </div>
            <div style="margin-top: 1rem; padding: 0.3rem 1rem; background: rgba(255,255,255,0.05); border-radius: 20px; font-size: 0.7rem; color: #8892b0;">
                The "Read-Only" Web
            </div>
        </div>
        
        <!-- Web 2.0 -->
        <div style="background: rgba(0,212,255,0.03); border: 1px solid rgba(0,212,255,0.1); border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">📱</div>
            <h3 style="color: #00d4ff; font-size: 1.3rem;">Web 2.0</h3>
            <div style="font-size: 0.8rem; color: #8892b0;">2000-2015</div>
            <div style="margin-top: 1rem; text-align: left;">
                <ul style="list-style: none; padding: 0; color: #8892b0; font-size: 0.85rem;">
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">💬 Interactive</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">👥 Social Media</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">✏️ User Content</li>
                    <li style="padding: 0.3rem 0;">📱 Mobile-First</li>
                </ul>
            </div>
            <div style="margin-top: 1rem; padding: 0.3rem 1rem; background: rgba(0,212,255,0.1); border-radius: 20px; font-size: 0.7rem; color: #00d4ff;">
                The "Read-Write" Web
            </div>
        </div>
        
        <!-- Web 3.0 -->
        <div style="background: rgba(123,47,252,0.03); border: 1px solid rgba(123,47,252,0.1); border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🔗</div>
            <h3 style="color: #7b2ffc; font-size: 1.3rem;">Web 3.0</h3>
            <div style="font-size: 0.8rem; color: #8892b0;">2015-2025</div>
            <div style="margin-top: 1rem; text-align: left;">
                <ul style="list-style: none; padding: 0; color: #8892b0; font-size: 0.85rem;">
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">🔗 Decentralized</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">🧠 Semantic</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">🤖 AI-Powered</li>
                    <li style="padding: 0.3rem 0;">💎 Digital Assets</li>
                </ul>
            </div>
            <div style="margin-top: 1rem; padding: 0.3rem 1rem; background: rgba(123,47,252,0.1); border-radius: 20px; font-size: 0.7rem; color: #7b2ffc;">
                The "Decentralized" Web
            </div>
        </div>
        
        <!-- Modern Web -->
        <div style="background: rgba(0,255,136,0.03); border: 1px solid rgba(0,255,136,0.1); border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🚀</div>
            <h3 style="color: #00ff88; font-size: 1.3rem;">Modern Web</h3>
            <div style="font-size: 0.8rem; color: #8892b0;">2025+</div>
            <div style="margin-top: 1rem; text-align: left;">
                <ul style="list-style: none; padding: 0; color: #8892b0; font-size: 0.85rem;">
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">🧠 AI-First</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">⚡ Real-Time</li>
                    <li style="padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">☁️ Cloud-Native</li>
                    <li style="padding: 0.3rem 0;">🌐 Web3 Integration</li>
                </ul>
            </div>
            <div style="margin-top: 1rem; padding: 0.3rem 1rem; background: rgba(0,255,136,0.1); border-radius: 20px; font-size: 0.7rem; color: #00ff88;">
                The "Intelligent" Web
            </div>
        </div>
    </div>
</section>

<!-- Web Technologies -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">🔧 Key Web Technologies</h2>
        <p style="color: #8892b0;">Technologies that power the World Wide Web.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📄</div>
            <h4 style="color: #ff6b6b; font-size: 1rem;">HTML</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Structure & Content</p>
            <p style="color: #8892b0; font-size: 0.6rem; margin-top: 0.3rem;">1993</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🎨</div>
            <h4 style="color: #ffa500; font-size: 1rem;">CSS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Style & Design</p>
            <p style="color: #8892b0; font-size: 0.6rem; margin-top: 0.3rem;">1996</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">⚡</div>
            <h4 style="color: #ffd93d; font-size: 1rem;">JavaScript</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Interactivity</p>
            <p style="color: #8892b0; font-size: 0.6rem; margin-top: 0.3rem;">1995</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🌐</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">HTTP/HTTPS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Communication</p>
            <p style="color: #8892b0; font-size: 0.6rem; margin-top: 0.3rem;">1991</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📱</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">AJAX</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Asynchronous Updates</p>
            <p style="color: #8892b0; font-size: 0.6rem; margin-top: 0.3rem;">2005</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #00ff88; font-size: 1rem;">AI/ML</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Intelligent Web</p>
            <p style="color: #8892b0; font-size: 0.6rem; margin-top: 0.3rem;">2020</p>
        </div>
    </div>
</section>

<!-- Famous Websites -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">🏢 Famous Websites</h2>
        <p style="color: #8892b0;">Iconic websites that shaped the internet and changed the world.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.2rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem;">🔍</div>
            <h4 style="color: #00d4ff;">Google</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">1998</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Search Engine</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">📚</div>
            <h4 style="color: #7b2ffc;">Wikipedia</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2001</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Encyclopedia</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">🛒</div>
            <h4 style="color: #ffa500;">Amazon</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">1995</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">E-commerce</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">📺</div>
            <h4 style="color: #ff0064;">YouTube</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2005</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Video Sharing</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">👤</div>
            <h4 style="color: #00d4ff;">Facebook</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2004</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Social Media</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">🐦</div>
            <h4 style="color: #00ff88;">Twitter/X</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2006</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Microblogging</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">🔴</div>
            <h4 style="color: #ff6b6b;">Reddit</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2005</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Community</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">📱</div>
            <h4 style="color: #7b2ffc;">Instagram</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2010</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Visual Social</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">🎵</div>
            <h4 style="color: #ffa500;">TikTok</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2016</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Short Video</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">🤖</div>
            <h4 style="color: #00d4ff;">ChatGPT</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2022</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">AI Chatbot</div>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem;">🌐</div>
            <h4 style="color: #00ff88;">GitHub</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">2008</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Development</div>
        </div>
    </div>
</section>

<!-- Web Statistics -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📊 Web Statistics</h2>
        <p style="color: #8892b0;">Fascinating statistics about the World Wide Web.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; max-width: 900px; margin: 0 auto;">
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00d4ff;">1.9B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Websites</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Active sites</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #7b2ffc;">250M</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Blog Posts</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Live content</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00ff88;">4.5B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Social Media Users</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Global reach</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ffa500;">2.5M</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Pages Per Day</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">New content daily</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ff0064;">95%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Mobile Web Usage</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Mobile-first</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00d4ff;">53%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Web Traffic Mobile</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Mobile dominates</div>
        </div>
    </div>
</section>

<!-- Web Pioneers -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">👨‍💻 Web Pioneers</h2>
        <p style="color: #8892b0;">The visionaries who built the World Wide Web.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <?php
        $webPioneers = [
            ['name' => 'Tim Berners-Lee', 'role' => 'Web Inventor', 'icon' => '🌍', 'color' => '#00d4ff', 'contribution' => 'World Wide Web'],
            ['name' => 'Marc Andreessen', 'role' => 'Mosaic Creator', 'icon' => '🌐', 'color' => '#ffa500', 'contribution' => 'Web Browser'],
            ['name' => 'Brendan Eich', 'role' => 'JavaScript Creator', 'icon' => '⚡', 'color' => '#ffd93d', 'contribution' => 'JavaScript'],
            ['name' => 'Larry Page', 'role' => 'Google Co-Founder', 'icon' => '🔍', 'color' => '#00d4ff', 'contribution' => 'Search'],
            ['name' => 'Sergey Brin', 'role' => 'Google Co-Founder', 'icon' => '🔍', 'color' => '#7b2ffc', 'contribution' => 'Search'],
            ['name' => 'Mark Zuckerberg', 'role' => 'Facebook Founder', 'icon' => '👤', 'color' => '#00ff88', 'contribution' => 'Social Media'],
            ['name' => 'Jack Dorsey', 'role' => 'Twitter Founder', 'icon' => '🐦', 'color' => '#00d4ff', 'contribution' => 'Microblogging'],
            ['name' => 'Chad Hurley', 'role' => 'YouTube Co-Founder', 'icon' => '📺', 'color' => '#ff0064', 'contribution' => 'Video Sharing'],
            ['name' => 'Steve Chen', 'role' => 'YouTube Co-Founder', 'icon' => '📺', 'color' => '#ff6b6b', 'contribution' => 'Video Sharing'],
            ['name' => 'Sam Altman', 'role' => 'OpenAI CEO', 'icon' => '🤖', 'color' => '#7b2ffc', 'contribution' => 'AI Web']
        ];
        
        foreach ($webPioneers as $pioneer):
        ?>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 3rem; margin-bottom: 0.3rem;"><?php echo $pioneer['icon']; ?></div>
            <h4 style="color: <?php echo $pioneer['color']; ?>; font-size: 0.95rem; margin-bottom: 0.2rem;"><?php echo $pioneer['name']; ?></h4>
            <p style="color: #8892b0; font-size: 0.75rem;"><?php echo $pioneer['role']; ?></p>
            <span style="font-size: 0.6rem; background: <?php echo $pioneer['color']; ?>20; color: <?php echo $pioneer['color']; ?>; padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">
                <?php echo $pioneer['contribution']; ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Interactive Quiz -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff0064;">🧠 Web Quiz</h2>
        <p style="color: #8892b0;">Test your knowledge about the World Wide Web.</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <div id="webQuiz">
            <div id="webQuizQuestion" style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem;">
                Who invented the World Wide Web?
            </div>
            <div id="webQuizOptions" style="display: grid; gap: 0.5rem;">
                <button onclick="checkWebQuiz('Tim Berners-Lee')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    A. Tim Berners-Lee
                </button>
                <button onclick="checkWebQuiz('Vint Cerf')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    B. Vint Cerf
                </button>
                <button onclick="checkWebQuiz('Bill Gates')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    C. Bill Gates
                </button>
                <button onclick="checkWebQuiz('Steve Jobs')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    D. Steve Jobs
                </button>
            </div>
            <div id="webQuizFeedback" style="margin-top: 1rem; padding: 0.8rem; border-radius: 8px; display: none;"></div>
            <button onclick="nextWebQuizQuestion()" id="webQuizNextBtn" style="margin-top: 1rem; padding: 0.5rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600; display: none;">
                Next Question →
            </button>
        </div>
    </div>
</section>

<!-- Web Details Modal -->
<div id="webModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeWebModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="webModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Timeline scrolling
function scrollWebTimeline(direction) {
    const timeline = document.getElementById("webTimeline");
    const scrollAmount = 300;
    if (direction === "left") {
        timeline.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
        timeline.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
}

// Web details modal
function showWebDetails(title, description, year, icon, category, company) {
    const modal = document.getElementById("webModal");
    const content = document.getElementById("webModalContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 0.5rem;">${icon}</div>
            <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 0.5rem;">${title}</h2>
            <div style="font-family: 'Orbitron', monospace; color: #7b2ffc; font-size: 1.2rem; margin-bottom: 0.3rem;">${year}</div>
            <div style="color: #8892b0; margin-bottom: 0.3rem;">Company: ${company}</div>
            <div style="color: #8892b0; margin-bottom: 1rem;">Category: ${category}</div>
            <p style="color: #ccd6f6; line-height: 1.8;">${description}</p>
            <div style="margin-top: 1rem; padding: 0.8rem; background: rgba(0,212,255,0.05); border-radius: 8px; border: 1px solid rgba(0,212,255,0.1);">
                <span style="color: #00d4ff;">💡</span>
                <span style="color: #8892b0;">${title} was a ${category} milestone in web history that ${description.toLowerCase().includes("revolution") ? "revolutionized" : "transformed"} the digital world.</span>
            </div>
            <button onclick="closeWebModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Close
            </button>
        </div>
    `;
}

function closeWebModal() {
    document.getElementById("webModal").style.display = "none";
    document.body.style.overflow = "";
}

// Web Quiz functionality
let webQuizQuestions = [
    { question: "Who invented the World Wide Web?", options: ["Tim Berners-Lee", "Vint Cerf", "Bill Gates", "Steve Jobs"], correct: 0 },
    { question: "What year was the first website created?", options: ["1989", "1990", "1991", "1992"], correct: 2 },
    { question: "What does HTML stand for?", options: ["Hyper Text Markup Language", "High Tech Modern Language", "Hyper Transfer Markup Language", "Human Text Markup Language"], correct: 0 },
    { question: "What is Web 2.0 known for?", options: ["Static Pages", "User-Generated Content", "Decentralization", "AI Integration"], correct: 1 },
    { question: "Which browser was the first popular web browser?", options: ["Internet Explorer", "Chrome", "Mosaic", "Firefox"], correct: 2 },
    { question: "What year was Google founded?", options: ["1996", "1997", "1998", "1999"], correct: 2 },
    { question: "What is the most popular web browser today?", options: ["Chrome", "Safari", "Firefox", "Edge"], correct: 0 },
    { question: "What does CSS stand for?", options: ["Cascading Style Sheets", "Creative Style System", "Computer Style Sheets", "Coded Style Sheets"], correct: 0 }
];

let currentWebQuiz = 0;
let webQuizScore = 0;

function checkWebQuiz(selected) {
    const correct = webQuizQuestions[currentWebQuiz].options[webQuizQuestions[currentWebQuiz].correct];
    const feedback = document.getElementById("webQuizFeedback");
    const options = document.querySelectorAll("#webQuizOptions button");
    
    options.forEach(btn => btn.style.pointerEvents = "none");
    
    options.forEach((btn, index) => {
        if (webQuizQuestions[currentWebQuiz].options[index] === correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (btn.textContent.includes(selected) && selected !== correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    if (selected === correct) {
        webQuizScore++;
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
    
    document.getElementById("webQuizNextBtn").style.display = "inline-block";
}

function nextWebQuizQuestion() {
    currentWebQuiz++;
    
    if (currentWebQuiz >= webQuizQuestions.length) {
        const container = document.getElementById("webQuiz");
        const percentage = Math.round((webQuizScore / webQuizQuestions.length) * 100);
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
                    You got ${webQuizScore} out of ${webQuizQuestions.length} questions correct.
                </p>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Try Again
                </button>
            </div>
        `;
        return;
    }
    
    const q = webQuizQuestions[currentWebQuiz];
    document.getElementById("webQuizQuestion").textContent = q.question;
    const optionsContainer = document.getElementById("webQuizOptions");
    optionsContainer.innerHTML = "";
    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.textContent = String.fromCharCode(65 + index) + ". " + option;
        btn.style.cssText = "padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;";
        btn.onclick = function() { checkWebQuiz(option); };
        optionsContainer.appendChild(btn);
    });
    
    document.getElementById("webQuizFeedback").style.display = "none";
    document.getElementById("webQuizNextBtn").style.display = "none";
}

// Keyboard shortcuts
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeWebModal();
});

// Close modal on overlay click
document.getElementById("webModal").addEventListener("click", function(e) {
    if (e.target === this) closeWebModal();
});

// Intersection Observer for timeline items
document.querySelectorAll(".web-item").forEach((item, index) => {
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

document.querySelectorAll(".web-item").forEach(item => observer.observe(item));

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
.web-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
    max-height: 800px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.web-timeline::-webkit-scrollbar {
    width: 6px;
}

.web-timeline::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

.web-timeline::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

.web-timeline::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

.web-timeline::before {
    content: "";
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #00d4ff, #7b2ffc, transparent);
    transform: translateX(-50%);
}

.web-item {
    display: flex;
    padding: 1.5rem 0;
    position: relative;
    width: 50%;
}

.web-item.left {
    padding-right: 3rem;
    justify-content: flex-end;
}

.web-item.right {
    padding-left: 3rem;
    margin-left: 50%;
}

.web-dot {
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

.web-item:hover .web-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.web-content {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
    width: 100%;
}

.web-content:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

.web-year {
    font-family: "Orbitron", monospace;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.web-icon {
    font-size: 2.5rem;
    margin-bottom: 0.3rem;
}

.web-content h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.3rem;
}

.web-content p {
    color: #8892b0;
    font-size: 0.9rem;
    margin: 0;
}

#webQuizOptions button:hover {
    border-color: #00d4ff !important;
    background: rgba(0, 212, 255, 0.05) !important;
}

#webModal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .web-timeline {
        max-height: 600px;
        padding: 1rem 0;
    }
    
    .web-timeline::before {
        left: 20px;
    }
    
    .web-item {
        width: 100%;
        padding: 1rem 0;
    }
    
    .web-item.left {
        padding-right: 0;
    }
    
    .web-item.right {
        padding-left: 0;
        margin-left: 0;
    }
    
    .web-dot {
        left: 20px;
    }
    
    .web-content {
        margin-left: 40px;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>