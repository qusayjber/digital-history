<?php
// internet.php
// Digital History - Internet History (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'History of the Internet';
$pageDescription = 'Explore the complete history of the internet from ARPANET to the modern web - the story of global connectivity.';

// Get internet-related events
$internetEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE is_active = 1 
     AND (title LIKE '%internet%' OR title LIKE '%ARPANET%' OR title LIKE '%TCP%' OR title LIKE '%web%' OR title LIKE '%browser%' OR title LIKE '%email%' OR title LIKE '%DNS%' OR title LIKE '%search%')
     ORDER BY year ASC LIMIT 15"
);

// Get internet-related people
$internetPeople = db()->fetchAll(
    "SELECT p.* FROM people p 
     WHERE p.is_active = 1 
     AND (p.known_for LIKE '%internet%' OR p.known_for LIKE '%TCP%' OR p.known_for LIKE '%web%' OR p.known_for LIKE '%browser%' OR p.known_for LIKE '%email%' OR p.known_for LIKE '%search%')
     ORDER BY p.birth_year ASC LIMIT 10"
);

trackPageView('internet');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            🌐 History of the Internet
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">From ARPANET to the modern web - the story of global connectivity.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($internetEvents); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Historical Events</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($internetPeople); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Internet Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">5.4B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Internet Users</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">50+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Years of Evolution</div>
        </div>
    </div>
</section>

<!-- Internet Timeline -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem; background: rgba(255,255,255,0.01); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h3 style="color: #00d4ff; margin: 0; font-size: 1.5rem;">
            <i class="fas fa-timeline"></i> Internet Timeline
        </h3>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="scrollInternetTimeline('left')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollInternetTimeline('right')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="internet-timeline" id="internetTimeline">
        <?php
        $internetMilestones = [
            [
                'year' => '1969',
                'title' => 'ARPANET Created',
                'description' => 'The first packet-switching network, precursor to the internet, connecting UCLA and Stanford.',
                'icon' => '📡',
                'color' => '#ff6b6b',
                'category' => 'Infrastructure',
                'impact' => 'Revolutionary',
                'company' => 'DARPA'
            ],
            [
                'year' => '1971',
                'title' => 'First Email',
                'description' => 'Ray Tomlinson sends the first email on ARPANET, introducing the @ symbol.',
                'icon' => '✉️',
                'color' => '#ffa500',
                'category' => 'Communication',
                'impact' => 'Revolutionary',
                'company' => 'BBN Technologies'
            ],
            [
                'year' => '1974',
                'title' => 'TCP/IP Developed',
                'description' => 'Vint Cerf and Bob Kahn create the TCP/IP protocol suite, the foundation of the internet.',
                'icon' => '🔗',
                'color' => '#ffd93d',
                'category' => 'Protocol',
                'impact' => 'Revolutionary',
                'company' => 'DARPA'
            ],
            [
                'year' => '1983',
                'title' => 'DNS Introduced',
                'description' => 'The Domain Name System makes internet addresses human-readable with domain names like .com, .org.',
                'icon' => '📋',
                'color' => '#00d4ff',
                'category' => 'Infrastructure',
                'impact' => 'High',
                'company' => 'ISI'
            ],
            [
                'year' => '1989',
                'title' => 'World Wide Web',
                'description' => 'Tim Berners-Lee invents the World Wide Web at CERN, revolutionizing information sharing.',
                'icon' => '🌍',
                'color' => '#7b2ffc',
                'category' => 'Technology',
                'impact' => 'Revolutionary',
                'company' => 'CERN'
            ],
            [
                'year' => '1990',
                'title' => 'First Web Browser',
                'description' => 'Tim Berners-Lee creates the first web browser and web server, making the web accessible.',
                'icon' => '🖥️',
                'color' => '#00ff88',
                'category' => 'Software',
                'impact' => 'High',
                'company' => 'CERN'
            ],
            [
                'year' => '1991',
                'title' => 'First Website',
                'description' => 'The first website goes online at CERN, explaining the World Wide Web project.',
                'icon' => '💻',
                'color' => '#00ff88',
                'category' => 'Technology',
                'impact' => 'High',
                'company' => 'CERN'
            ],
            [
                'year' => '1993',
                'title' => 'Mosaic Browser',
                'description' => 'The first popular web browser with graphics, making the web accessible to everyone.',
                'icon' => '🌐',
                'color' => '#ff0064',
                'category' => 'Software',
                'impact' => 'Influential',
                'company' => 'NCSA'
            ],
            [
                'year' => '1994',
                'title' => 'Netscape Navigator',
                'description' => 'The first commercial web browser, dominating the early web market.',
                'icon' => '🚀',
                'color' => '#ffa500',
                'category' => 'Software',
                'impact' => 'High',
                'company' => 'Netscape'
            ],
            [
                'year' => '1995',
                'title' => 'Internet Explorer',
                'description' => 'Microsoft releases Internet Explorer, entering the browser wars.',
                'icon' => '🪟',
                'color' => '#00d4ff',
                'category' => 'Software',
                'impact' => 'High',
                'company' => 'Microsoft'
            ],
            [
                'year' => '1998',
                'title' => 'Google Founded',
                'description' => 'Larry Page and Sergey Brin found Google, revolutionizing search and information access.',
                'icon' => '🔍',
                'color' => '#00d4ff',
                'category' => 'Company',
                'impact' => 'Revolutionary',
                'company' => 'Google'
            ],
            [
                'year' => '2001',
                'title' => 'Wikipedia Launched',
                'description' => 'Wikipedia launches, becoming the largest free online encyclopedia.',
                'icon' => '📚',
                'color' => '#7b2ffc',
                'category' => 'Company',
                'impact' => 'High',
                'company' => 'Wikimedia'
            ],
            [
                'year' => '2004',
                'title' => 'Web 2.0 Emerges',
                'description' => 'The interactive web and social media emerge with user-generated content.',
                'icon' => '📱',
                'color' => '#7b2ffc',
                'category' => 'Technology',
                'impact' => 'Revolutionary',
                'company' => 'Multiple'
            ],
            [
                'year' => '2004',
                'title' => 'Facebook Launched',
                'description' => 'Mark Zuckerberg launches Facebook, revolutionizing social media.',
                'icon' => '👤',
                'color' => '#00d4ff',
                'category' => 'Company',
                'impact' => 'Revolutionary',
                'company' => 'Meta'
            ],
            [
                'year' => '2005',
                'title' => 'YouTube Founded',
                'description' => 'YouTube launches, transforming video sharing and content consumption.',
                'icon' => '📺',
                'color' => '#ff0064',
                'category' => 'Company',
                'impact' => 'Revolutionary',
                'company' => 'Google'
            ],
            [
                'year' => '2006',
                'title' => 'Twitter Launched',
                'description' => 'Twitter launches, introducing microblogging and real-time updates.',
                'icon' => '🐦',
                'color' => '#00d4ff',
                'category' => 'Company',
                'impact' => 'High',
                'company' => 'X Corp'
            ],
            [
                'year' => '2007',
                'title' => 'iPhone & Mobile Internet',
                'description' => 'The iPhone brings the internet to everyone\'s pocket, revolutionizing mobile access.',
                'icon' => '📱',
                'color' => '#00d4ff',
                'category' => 'Technology',
                'impact' => 'Revolutionary',
                'company' => 'Apple'
            ],
            [
                'year' => '2008',
                'title' => 'Chrome Browser',
                'description' => 'Google releases Chrome browser, becoming the most popular web browser.',
                'icon' => '🌐',
                'color' => '#00ff88',
                'category' => 'Software',
                'impact' => 'High',
                'company' => 'Google'
            ],
            [
                'year' => '2010',
                'title' => 'Instagram Launched',
                'description' => 'Instagram launches, revolutionizing visual content and social media.',
                'icon' => '📸',
                'color' => '#ff0064',
                'category' => 'Company',
                'impact' => 'High',
                'company' => 'Meta'
            ],
            [
                'year' => '2016',
                'title' => 'TikTok Launched',
                'description' => 'TikTok launches, transforming short-form video content.',
                'icon' => '🎵',
                'color' => '#ff6b6b',
                'category' => 'Company',
                'impact' => 'High',
                'company' => 'ByteDance'
            ],
            [
                'year' => '2020',
                'title' => 'COVID-19 Digital Shift',
                'description' => 'The pandemic accelerates digital transformation, remote work, and online services.',
                'icon' => '🏥',
                'color' => '#ffa500',
                'category' => 'Society',
                'impact' => 'High',
                'company' => 'Global'
            ],
            [
                'year' => '2022',
                'title' => 'AI Revolution',
                'description' => 'Generative AI like ChatGPT transforms how we interact with the internet.',
                'icon' => '🧠',
                'color' => '#7b2ffc',
                'category' => 'Technology',
                'impact' => 'Revolutionary',
                'company' => 'OpenAI'
            ],
            [
                'year' => '2023',
                'title' => 'Web3 & Decentralization',
                'description' => 'Web3 and blockchain technologies promise a more decentralized internet.',
                'icon' => '⛓️',
                'color' => '#00d4ff',
                'category' => 'Technology',
                'impact' => 'Emerging',
                'company' => 'Multiple'
            ]
        ];
        
        foreach ($internetMilestones as $index => $event):
        ?>
        <div class="internet-item <?php echo $index % 2 === 0 ? 'left' : 'right'; ?> animate-on-scroll" data-index="<?php echo $index; ?>">
            <div class="internet-dot" style="background: <?php echo $event['color']; ?>; box-shadow: 0 0 20px <?php echo $event['color']; ?>40;"></div>
            <div class="internet-content" style="border-color: <?php echo $event['color']; ?>20;">
                <div class="internet-year" style="color: <?php echo $event['color']; ?>;"><?php echo $event['year']; ?></div>
                <div class="internet-icon"><?php echo $event['icon']; ?></div>
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
                    } elseif ($event['impact'] === 'Influential') {
                        $impactBg = 'rgba(0,255,136,0.1)';
                        $impactColor = '#00ff88';
                    } else {
                        $impactBg = 'rgba(255,165,0,0.1)';
                        $impactColor = '#ffa500';
                    }
                    ?>
                    <span style="font-size: 0.6rem; background: <?php echo $impactBg; ?>; color: <?php echo $impactColor; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $event['impact']; ?>
                    </span>
                </div>
                <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">
                    <?php echo $event['company']; ?>
                </div>
                <button onclick="showInternetDetails('<?php echo addslashes($event['title']); ?>', '<?php echo addslashes($event['description']); ?>', '<?php echo $event['year']; ?>', '<?php echo $event['icon']; ?>', '<?php echo $event['category']; ?>', '<?php echo $event['company']; ?>')" style="margin-top: 0.8rem; padding: 0.3rem 1rem; background: <?php echo $event['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; font-size: 0.8rem; transition: all 0.3s;">
                    Learn More
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Internet Categories -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📚 Internet Technologies</h2>
        <p style="color: #8892b0;">Key technologies that power the internet.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🌐</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">TCP/IP</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Foundation protocol</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">1974</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📋</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">DNS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Domain resolution</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">1983</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔗</div>
            <h4 style="color: #00ff88; font-size: 1rem;">HTTP/HTTPS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Web communication</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">1991</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔐</div>
            <h4 style="color: #ffa500; font-size: 1rem;">SSL/TLS</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Security protocol</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">1995</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📱</div>
            <h4 style="color: #ff0064; font-size: 1rem;">Mobile Web</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Mobile access</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">2007</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">AI Web</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Intelligent web</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">2022</p>
        </div>
    </div>
</section>

<!-- Internet Statistics -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">📊 Internet by the Numbers</h2>
        <p style="color: #8892b0;">Amazing statistics about the modern internet.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; max-width: 900px; margin: 0 auto;">
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00d4ff;">5.4B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Internet Users</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">67% of global population</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #7b2ffc;">1.9B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Websites</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Growing daily</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00ff88;">347B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Emails Per Day</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Every single day</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ffa500;">8.5B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Google Searches Per Day</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Every day</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ff6b6b;">500M</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Tweets Per Day</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Real-time updates</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ff0064;">95%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Mobile Internet Usage</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Smartphones dominate</div>
        </div>
    </div>
</section>

<!-- How the Internet Works -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">🔧 How the Internet Works</h2>
        <p style="color: #8892b0;">Follow a request from your computer to the server and back.</p>
    </div>
    
    <div style="max-width: 700px; margin: 0 auto; position: relative;">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid #00d4ff; transition: all 0.3s; hover:border-color: #7b2ffc;">
                <span style="font-size: 1.2rem;">🖥️</span>
                <span style="color: #fff; font-weight: 500;">Your Computer</span>
                <span style="margin-left: auto; color: #8892b0; font-size: 0.7rem;">Sends request</span>
            </div>
            <div style="text-align: center; color: #00d4ff; font-size: 0.8rem;">⬇️</div>
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid #7b2ffc;">
                <span style="font-size: 1.2rem;">📶</span>
                <span style="color: #fff; font-weight: 500;">Router</span>
                <span style="margin-left: auto; color: #8892b0; font-size: 0.7rem;">Routes traffic</span>
            </div>
            <div style="text-align: center; color: #7b2ffc; font-size: 0.8rem;">⬇️</div>
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid #ffa500;">
                <span style="font-size: 1.2rem;">🌐</span>
                <span style="color: #fff; font-weight: 500;">DNS Server</span>
                <span style="margin-left: auto; color: #8892b0; font-size: 0.7rem;">Resolves domain</span>
            </div>
            <div style="text-align: center; color: #ffa500; font-size: 0.8rem;">⬇️</div>
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid #00ff88;">
                <span style="font-size: 1.2rem;">🏢</span>
                <span style="color: #fff; font-weight: 500;">ISP</span>
                <span style="margin-left: auto; color: #8892b0; font-size: 0.7rem;">Internet Provider</span>
            </div>
            <div style="text-align: center; color: #00ff88; font-size: 0.8rem;">⬇️</div>
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid #ff0064;">
                <span style="font-size: 1.2rem;">🌍</span>
                <span style="color: #fff; font-weight: 500;">Internet Backbone</span>
                <span style="margin-left: auto; color: #8892b0; font-size: 0.7rem;">Global network</span>
            </div>
            <div style="text-align: center; color: #ff0064; font-size: 0.8rem;">⬇️</div>
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid #00d4ff;">
                <span style="font-size: 1.2rem;">🗄️</span>
                <span style="color: #fff; font-weight: 500;">Server</span>
                <span style="margin-left: auto; color: #00d4ff; font-size: 0.7rem;">Processes request</span>
            </div>
            <div style="text-align: center; color: #00d4ff; font-size: 0.8rem;">⬆️</div>
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(0,212,255,0.05); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid #00d4ff; border: 1px solid rgba(0,212,255,0.1);">
                <span style="font-size: 1.2rem;">✅</span>
                <span style="color: #fff; font-weight: 500;">Response</span>
                <span style="margin-left: auto; color: #00d4ff; font-size: 0.7rem;">Data returned</span>
            </div>
        </div>
    </div>
</section>

<!-- Internet Pioneers -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">👨‍💻 Internet Pioneers</h2>
        <p style="color: #8892b0;">The brilliant minds who built the internet we use today.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <?php
        $internetPioneers = [
            ['name' => 'Vint Cerf', 'role' => 'TCP/IP Co-Creator', 'icon' => '🔗', 'color' => '#00d4ff', 'contribution' => 'TCP/IP'],
            ['name' => 'Bob Kahn', 'role' => 'TCP/IP Co-Creator', 'icon' => '🔗', 'color' => '#7b2ffc', 'contribution' => 'TCP/IP'],
            ['name' => 'Tim Berners-Lee', 'role' => 'World Wide Web Inventor', 'icon' => '🌍', 'color' => '#00ff88', 'contribution' => 'WWW'],
            ['name' => 'Ray Tomlinson', 'role' => 'Email Inventor', 'icon' => '✉️', 'color' => '#ffa500', 'contribution' => 'Email'],
            ['name' => 'Paul Mockapetris', 'role' => 'DNS Inventor', 'icon' => '📋', 'color' => '#00d4ff', 'contribution' => 'DNS'],
            ['name' => 'Larry Page', 'role' => 'Google Co-Founder', 'icon' => '🔍', 'color' => '#ff0064', 'contribution' => 'Search'],
            ['name' => 'Sergey Brin', 'role' => 'Google Co-Founder', 'icon' => '🔍', 'color' => '#7b2ffc', 'contribution' => 'Search'],
            ['name' => 'Mark Zuckerberg', 'role' => 'Facebook Founder', 'icon' => '👤', 'color' => '#00d4ff', 'contribution' => 'Social Media'],
            ['name' => 'Steve Jobs', 'role' => 'iPhone Creator', 'icon' => '📱', 'color' => '#ff0064', 'contribution' => 'Mobile Internet'],
            ['name' => 'Marc Andreessen', 'role' => 'Mosaic Creator', 'icon' => '🌐', 'color' => '#ffa500', 'contribution' => 'Web Browser']
        ];
        
        foreach ($internetPioneers as $pioneer):
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
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff0064;">🧠 Internet Quiz</h2>
        <p style="color: #8892b0;">Test your knowledge about the internet and its history.</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <div id="internetQuiz">
            <div id="internetQuizQuestion" style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem;">
                What was the first packet-switching network?
            </div>
            <div id="internetQuizOptions" style="display: grid; gap: 0.5rem;">
                <button onclick="checkInternetQuiz('ARPANET')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    A. ARPANET
                </button>
                <button onclick="checkInternetQuiz('NSFNET')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    B. NSFNET
                </button>
                <button onclick="checkInternetQuiz('Internet')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    C. Internet
                </button>
                <button onclick="checkInternetQuiz('World Wide Web')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    D. World Wide Web
                </button>
            </div>
            <div id="internetQuizFeedback" style="margin-top: 1rem; padding: 0.8rem; border-radius: 8px; display: none;"></div>
            <button onclick="nextInternetQuizQuestion()" id="internetQuizNextBtn" style="margin-top: 1rem; padding: 0.5rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600; display: none;">
                Next Question →
            </button>
        </div>
    </div>
</section>

<!-- Internet Details Modal -->
<div id="internetModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeInternetModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="internetModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Timeline scrolling
function scrollInternetTimeline(direction) {
    const timeline = document.getElementById("internetTimeline");
    const scrollAmount = 300;
    if (direction === "left") {
        timeline.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
        timeline.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
}

// Internet details modal
function showInternetDetails(title, description, year, icon, category, company) {
    const modal = document.getElementById("internetModal");
    const content = document.getElementById("internetModalContent");
    
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
                <span style="color: #8892b0;">${title} was a ${category} milestone in internet history that ${description.toLowerCase().includes("revolution") ? "revolutionized" : "transformed"} the digital world.</span>
            </div>
            <button onclick="closeInternetModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Close
            </button>
        </div>
    `;
}

function closeInternetModal() {
    document.getElementById("internetModal").style.display = "none";
    document.body.style.overflow = "";
}

// Internet Quiz functionality
let internetQuizQuestions = [
    { question: "What was the first packet-switching network?", options: ["ARPANET", "NSFNET", "Internet", "World Wide Web"], correct: 0 },
    { question: "Who invented the World Wide Web?", options: ["Vint Cerf", "Bob Kahn", "Tim Berners-Lee", "Ray Tomlinson"], correct: 2 },
    { question: "What year was the first email sent?", options: ["1969", "1971", "1974", "1983"], correct: 1 },
    { question: "What does DNS stand for?", options: ["Domain Name System", "Digital Network Service", "Data Node Server", "Dynamic Name Server"], correct: 0 },
    { question: "Which company created the first popular web browser?", options: ["Microsoft", "Google", "Netscape", "Apple"], correct: 2 },
    { question: "What year was Google founded?", options: ["1994", "1996", "1998", "2000"], correct: 2 },
    { question: "Which protocol is used for web communication?", options: ["TCP", "HTTP", "DNS", "FTP"], correct: 1 },
    { question: "What percentage of internet usage is mobile?", options: ["70%", "80%", "85%", "95%"], correct: 3 }
];

let currentInternetQuiz = 0;
let internetQuizScore = 0;

function checkInternetQuiz(selected) {
    const correct = internetQuizQuestions[currentInternetQuiz].options[internetQuizQuestions[currentInternetQuiz].correct];
    const feedback = document.getElementById("internetQuizFeedback");
    const options = document.querySelectorAll("#internetQuizOptions button");
    
    options.forEach(btn => btn.style.pointerEvents = "none");
    
    options.forEach((btn, index) => {
        if (internetQuizQuestions[currentInternetQuiz].options[index] === correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (btn.textContent.includes(selected) && selected !== correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    if (selected === correct) {
        internetQuizScore++;
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
    
    document.getElementById("internetQuizNextBtn").style.display = "inline-block";
}

function nextInternetQuizQuestion() {
    currentInternetQuiz++;
    
    if (currentInternetQuiz >= internetQuizQuestions.length) {
        const container = document.getElementById("internetQuiz");
        const percentage = Math.round((internetQuizScore / internetQuizQuestions.length) * 100);
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
                    You got ${internetQuizScore} out of ${internetQuizQuestions.length} questions correct.
                </p>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Try Again
                </button>
            </div>
        `;
        return;
    }
    
    const q = internetQuizQuestions[currentInternetQuiz];
    document.getElementById("internetQuizQuestion").textContent = q.question;
    const optionsContainer = document.getElementById("internetQuizOptions");
    optionsContainer.innerHTML = "";
    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.textContent = String.fromCharCode(65 + index) + ". " + option;
        btn.style.cssText = "padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;";
        btn.onclick = function() { checkInternetQuiz(option); };
        optionsContainer.appendChild(btn);
    });
    
    document.getElementById("internetQuizFeedback").style.display = "none";
    document.getElementById("internetQuizNextBtn").style.display = "none";
}

// Keyboard shortcuts
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeInternetModal();
});

// Close modal on overlay click
document.getElementById("internetModal").addEventListener("click", function(e) {
    if (e.target === this) closeInternetModal();
});

// Intersection Observer for timeline items
document.querySelectorAll(".internet-item").forEach((item, index) => {
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

document.querySelectorAll(".internet-item").forEach(item => observer.observe(item));

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
.internet-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
    max-height: 800px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.internet-timeline::-webkit-scrollbar {
    width: 6px;
}

.internet-timeline::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

.internet-timeline::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

.internet-timeline::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

.internet-timeline::before {
    content: "";
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #00d4ff, #7b2ffc, transparent);
    transform: translateX(-50%);
}

.internet-item {
    display: flex;
    padding: 1.5rem 0;
    position: relative;
    width: 50%;
}

.internet-item.left {
    padding-right: 3rem;
    justify-content: flex-end;
}

.internet-item.right {
    padding-left: 3rem;
    margin-left: 50%;
}

.internet-dot {
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

.internet-item:hover .internet-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.internet-content {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
    width: 100%;
}

.internet-content:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

.internet-year {
    font-family: "Orbitron", monospace;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.internet-icon {
    font-size: 2.5rem;
    margin-bottom: 0.3rem;
}

.internet-content h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.3rem;
}

.internet-content p {
    color: #8892b0;
    font-size: 0.9rem;
    margin: 0;
}

#internetQuizOptions button:hover {
    border-color: #00d4ff !important;
    background: rgba(0, 212, 255, 0.05) !important;
}

#internetModal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .internet-timeline {
        max-height: 600px;
        padding: 1rem 0;
    }
    
    .internet-timeline::before {
        left: 20px;
    }
    
    .internet-item {
        width: 100%;
        padding: 1rem 0;
    }
    
    .internet-item.left {
        padding-right: 0;
    }
    
    .internet-item.right {
        padding-left: 0;
        margin-left: 0;
    }
    
    .internet-dot {
        left: 20px;
    }
    
    .internet-content {
        margin-left: 40px;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>