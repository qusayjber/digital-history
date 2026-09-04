<?php
// cybersecurity.php
// Digital History - Cybersecurity History (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'History of Cybersecurity';
$pageDescription = 'Explore the complete history of cybersecurity from early viruses to modern AI security - the story of digital protection.';

// Get cybersecurity-related events
$cyberEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE is_active = 1 
     AND (title LIKE '%virus%' OR title LIKE '%worm%' OR title LIKE '%malware%' OR title LIKE '%ransomware%' OR title LIKE '%security%' OR title LIKE '%hack%' OR title LIKE '%cyber%' OR title LIKE '%encryption%')
     ORDER BY year ASC LIMIT 15"
);

// Get cybersecurity-related people
$cyberPeople = db()->fetchAll(
    "SELECT p.* FROM people p 
     WHERE p.is_active = 1 
     AND (p.known_for LIKE '%security%' OR p.known_for LIKE '%crypt%' OR p.known_for LIKE '%hack%' OR p.known_for LIKE '%cyber%' OR p.known_for LIKE '%encrypt%')
     ORDER BY p.birth_year ASC LIMIT 10"
);

trackPageView('cybersecurity');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #ff6b6b, #ffa500, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            🛡️ History of Cybersecurity
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">From the first viruses to modern AI security - the story of digital protection.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ff6b6b;"><?php echo count($cyberEvents); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Security Events</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($cyberPeople); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Security Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">$10.5T</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Cybercrime Cost by 2025</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;">50+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Years of Evolution</div>
        </div>
    </div>
</section>

<!-- Cybersecurity Timeline -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem; background: rgba(255,255,255,0.01); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h3 style="color: #ff6b6b; margin: 0; font-size: 1.5rem;">
            <i class="fas fa-timeline"></i> Cybersecurity Timeline
        </h3>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="scrollCyberTimeline('left')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollCyberTimeline('right')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="cyber-timeline" id="cyberTimeline">
        <?php
        $cyberMilestones = [
            [
                'year' => '1971',
                'title' => 'Creeper Virus',
                'description' => 'The first computer virus, created as an experiment. It displayed the message "I\'m the creeper, catch me if you can!"',
                'icon' => '🦠',
                'color' => '#ff6b6b',
                'category' => 'Malware',
                'impact' => 'Foundational',
                'company' => 'BBN Technologies'
            ],
            [
                'year' => '1986',
                'title' => 'Brain Virus',
                'description' => 'The first PC virus, spread via floppy disks. Created by two Pakistani brothers, it was a boot sector virus.',
                'icon' => '🧠',
                'color' => '#ffa500',
                'category' => 'Malware',
                'impact' => 'High',
                'company' => 'Brain'
            ],
            [
                'year' => '1988',
                'title' => 'Morris Worm',
                'description' => 'The first major internet worm, affecting approximately 6,000 computers and causing significant damage.',
                'icon' => '🐛',
                'color' => '#ffd93d',
                'category' => 'Malware',
                'impact' => 'Revolutionary',
                'company' => 'MIT'
            ],
            [
                'year' => '1990',
                'title' => 'First Antivirus',
                'description' => 'Commercial antivirus software becomes available, with companies like McAfee and Norton leading the way.',
                'icon' => '🛡️',
                'color' => '#00d4ff',
                'category' => 'Defense',
                'impact' => 'High',
                'company' => 'McAfee'
            ],
            [
                'year' => '1995',
                'title' => 'SSL/TLS Introduced',
                'description' => 'Secure Sockets Layer (SSL) and Transport Layer Security (TLS) protocols introduced for web security.',
                'icon' => '🔐',
                'color' => '#7b2ffc',
                'category' => 'Protocol',
                'impact' => 'Revolutionary',
                'company' => 'Netscape'
            ],
            [
                'year' => '2000',
                'title' => 'First DDoS Attack',
                'description' => 'Major Distributed Denial of Service attacks demonstrate internet vulnerability and the power of botnets.',
                'icon' => '💥',
                'color' => '#ff0064',
                'category' => 'Attack',
                'impact' => 'High',
                'company' => 'Multiple'
            ],
            [
                'year' => '2003',
                'title' => 'Slammer Worm',
                'description' => 'The Slammer worm attacks SQL servers, causing internet slowdowns and demonstrating the speed of modern worms.',
                'icon' => '⚡',
                'color' => '#ffa500',
                'category' => 'Malware',
                'impact' => 'High',
                'company' => 'Multiple'
            ],
            [
                'year' => '2007',
                'title' => 'APT Attacks',
                'description' => 'Advanced Persistent Threats become a major concern, with state-sponsored cyber attacks increasing.',
                'icon' => '🎯',
                'color' => '#ffa500',
                'category' => 'Threat',
                'impact' => 'High',
                'company' => 'Various'
            ],
            [
                'year' => '2010',
                'title' => 'Stuxnet Virus',
                'description' => 'Stuxnet, a sophisticated computer worm, targets Iranian nuclear facilities, marking the first cyber weapon.',
                'icon' => '💣',
                'color' => '#ff6b6b',
                'category' => 'Malware',
                'impact' => 'Revolutionary',
                'company' => 'Various'
            ],
            [
                'year' => '2013',
                'title' => 'Edward Snowden Leaks',
                'description' => 'Edward Snowden reveals extensive global surveillance programs, raising awareness about privacy and security.',
                'icon' => '📡',
                'color' => '#00d4ff',
                'category' => 'Privacy',
                'impact' => 'High',
                'company' => 'NSA'
            ],
            [
                'year' => '2017',
                'title' => 'WannaCry Ransomware',
                'description' => 'Global ransomware attack affecting 150+ countries, exploiting a vulnerability in Windows systems.',
                'icon' => '💰',
                'color' => '#ff6b6b',
                'category' => 'Malware',
                'impact' => 'High',
                'company' => 'Shadow Brokers'
            ],
            [
                'year' => '2017',
                'title' => 'NotPetya Malware',
                'description' => 'A destructive malware attack causing billions in damages, targeting Ukrainian infrastructure.',
                'icon' => '💥',
                'color' => '#ff0064',
                'category' => 'Malware',
                'impact' => 'High',
                'company' => 'Various'
            ],
            [
                'year' => '2018',
                'title' => 'GDPR Enforcement',
                'description' => 'The General Data Protection Regulation is enforced, setting new standards for data privacy and security.',
                'icon' => '📋',
                'color' => '#7b2ffc',
                'category' => 'Regulation',
                'impact' => 'High',
                'company' => 'EU'
            ],
            [
                'year' => '2020',
                'title' => 'Zero-day Exploits',
                'description' => 'Increasing sophistication of zero-day vulnerabilities, with hackers exploiting unknown security flaws.',
                'icon' => '🎯',
                'color' => '#00d4ff',
                'category' => 'Threat',
                'impact' => 'High',
                'company' => 'Multiple'
            ],
            [
                'year' => '2021',
                'title' => 'Colonial Pipeline Attack',
                'description' => 'A ransomware attack on Colonial Pipeline disrupts fuel supply across the US East Coast.',
                'icon' => '⛽',
                'color' => '#ffa500',
                'category' => 'Attack',
                'impact' => 'High',
                'company' => 'DarkSide'
            ],
            [
                'year' => '2022',
                'title' => 'AI-Powered Security',
                'description' => 'AI and machine learning become essential for threat detection, prevention, and response in cybersecurity.',
                'icon' => '🧠',
                'color' => '#7b2ffc',
                'category' => 'Defense',
                'impact' => 'Revolutionary',
                'company' => 'Multiple'
            ],
            [
                'year' => '2023',
                'title' => 'Zero-Trust Architecture',
                'description' => 'Zero-trust security models become mainstream, assuming no user or device is automatically trusted.',
                'icon' => '🔒',
                'color' => '#00d4ff',
                'category' => 'Strategy',
                'impact' => 'High',
                'company' => 'Various'
            ],
            [
                'year' => '2024',
                'title' => 'Quantum-Safe Cryptography',
                'description' => 'Development of cryptographic algorithms resistant to quantum computing attacks begins.',
                'icon' => '⚛️',
                'color' => '#7b2ffc',
                'category' => 'Cryptography',
                'impact' => 'Emerging',
                'company' => 'NIST'
            ]
        ];
        
        foreach ($cyberMilestones as $index => $event):
        ?>
        <div class="cyber-item <?php echo $index % 2 === 0 ? 'left' : 'right'; ?> animate-on-scroll" data-index="<?php echo $index; ?>">
            <div class="cyber-dot" style="background: <?php echo $event['color']; ?>; box-shadow: 0 0 20px <?php echo $event['color']; ?>40;"></div>
            <div class="cyber-content" style="border-color: <?php echo $event['color']; ?>20;">
                <div class="cyber-year" style="color: <?php echo $event['color']; ?>;"><?php echo $event['year']; ?></div>
                <div class="cyber-icon"><?php echo $event['icon']; ?></div>
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
                        $impactBg = 'rgba(255,165,0,0.1)';
                        $impactColor = '#ffa500';
                    } elseif ($event['impact'] === 'Foundational') {
                        $impactBg = 'rgba(0,212,255,0.1)';
                        $impactColor = '#00d4ff';
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
                <button onclick="showCyberDetails('<?php echo addslashes($event['title']); ?>', '<?php echo addslashes($event['description']); ?>', '<?php echo $event['year']; ?>', '<?php echo $event['icon']; ?>', '<?php echo $event['category']; ?>', '<?php echo $event['company']; ?>')" style="margin-top: 0.8rem; padding: 0.3rem 1rem; background: <?php echo $event['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; font-size: 0.8rem; transition: all 0.3s;">
                    Learn More
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Security Categories -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📚 Cybersecurity Categories</h2>
        <p style="color: #8892b0;">Different areas of cybersecurity and digital protection.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🛡️</div>
            <h4 style="color: #ff6b6b; font-size: 1rem;">Network Security</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Protecting networks</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Firewalls, VPN</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔐</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">Application Security</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Secure software</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">Encryption, SSL</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">👤</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Identity Security</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">User authentication</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">2FA, Biometrics</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">☁️</div>
            <h4 style="color: #ffa500; font-size: 1rem;">Cloud Security</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Cloud protection</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">SaaS, IaaS security</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #00ff88; font-size: 1rem;">AI Security</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">AI protection</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">ML threat detection</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📱</div>
            <h4 style="color: #ff0064; font-size: 1rem;">Mobile Security</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Mobile protection</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">App security</p>
        </div>
    </div>
</section>

<!-- Security Stats -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">📊 Cybersecurity by the Numbers</h2>
        <p style="color: #8892b0;">Critical statistics about the state of cybersecurity.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; max-width: 900px; margin: 0 auto;">
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ff6b6b;">$10.5T</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Cybercrime Cost by 2025</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Global damage</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ffa500;">2,200</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Attacks Per Day</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Global average</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00d4ff;">94%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Organizations Affected</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Security incidents</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #7b2ffc;">30,000</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Websites Hacked Daily</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Every single day</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00ff88;">95%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Breaches Due to Human Error</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Social engineering</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ff0064;">60%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">SMBs Close Within 6 Months</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">After a cyber attack</div>
        </div>
    </div>
</section>

<!-- Security Pioneers -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">👨‍💻 Cybersecurity Pioneers</h2>
        <p style="color: #8892b0;">The visionaries who built the field of cybersecurity.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <?php
        $cyberPioneers = [
            ['name' => 'John McAfee', 'role' => 'Antivirus Pioneer', 'icon' => '🛡️', 'color' => '#ff6b6b', 'contribution' => 'McAfee Antivirus'],
            ['name' => 'Peter Norton', 'role' => 'Antivirus Pioneer', 'icon' => '🛡️', 'color' => '#ffa500', 'contribution' => 'Norton Antivirus'],
            ['name' => 'Bruce Schneier', 'role' => 'Cryptography Expert', 'icon' => '🔐', 'color' => '#00d4ff', 'contribution' => 'Cryptography'],
            ['name' => 'Ron Rivest', 'role' => 'RSA Inventor', 'icon' => '🔑', 'color' => '#7b2ffc', 'contribution' => 'RSA Encryption'],
            ['name' => 'Adi Shamir', 'role' => 'RSA Inventor', 'icon' => '🔑', 'color' => '#00ff88', 'contribution' => 'RSA Encryption'],
            ['name' => 'Leonard Adleman', 'role' => 'RSA Inventor', 'icon' => '🔑', 'color' => '#ffa500', 'contribution' => 'RSA Encryption'],
            ['name' => 'Kevin Mitnick', 'role' => 'Hacker Turned Expert', 'icon' => '💻', 'color' => '#ff0064', 'contribution' => 'Security Consulting'],
            ['name' => 'Whitfield Diffie', 'role' => 'Public Key Crypto', 'icon' => '🔑', 'color' => '#00d4ff', 'contribution' => 'Diffie-Hellman'],
            ['name' => 'Martin Hellman', 'role' => 'Public Key Crypto', 'icon' => '🔑', 'color' => '#7b2ffc', 'contribution' => 'Diffie-Hellman'],
            ['name' => 'Eugene Kaspersky', 'role' => 'Antivirus Pioneer', 'icon' => '🛡️', 'color' => '#00ff88', 'contribution' => 'Kaspersky Lab']
        ];
        
        foreach ($cyberPioneers as $pioneer):
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

<!-- Security Tips -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">🔐 Cybersecurity Best Practices</h2>
        <p style="color: #8892b0;">Essential tips to stay safe online and protect your digital life.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; max-width: 1000px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔑</div>
            <h4 style="color: #00d4ff;">Strong Passwords</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Use unique, complex passwords for each account.</p>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Use a password manager</div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔐</div>
            <h4 style="color: #7b2ffc;">Two-Factor Authentication</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Enable 2FA on all important accounts.</p>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">App or SMS based</div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📦</div>
            <h4 style="color: #00ff88;">Update Software</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Keep all software and systems up to date.</p>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Enable auto-updates</div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚠️</div>
            <h4 style="color: #ffa500;">Be Cautious</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Don't click suspicious links or download unknown attachments.</p>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Verify sender identity</div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">💾</div>
            <h4 style="color: #ff0064;">Backup Data</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Regularly backup important files and data.</p>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">3-2-1 backup strategy</div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🛡️</div>
            <h4 style="color: #00d4ff;">Use Antivirus</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Install and maintain reliable antivirus software.</p>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Run regular scans</div>
        </div>
    </div>
</section>

<!-- Security Threats Timeline -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff6b6b;">⚠️ Major Security Threats</h2>
        <p style="color: #8892b0;">The most significant cyber threats in history and their impact.</p>
    </div>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="display: grid; gap: 0.8rem;">
            <div style="background: rgba(255,0,0,0.05); border-left: 4px solid #ff6b6b; padding: 1rem 1.5rem; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="color: #ff6b6b; font-weight: 700;">🦠 Creeper Virus</span>
                        <span style="color: #8892b0; font-size: 0.85rem; margin-left: 0.5rem;">1971</span>
                    </div>
                    <span style="font-size: 0.7rem; background: rgba(255,0,0,0.1); padding: 0.2rem 0.8rem; border-radius: 12px; color: #ff6b6b;">First Virus</span>
                </div>
            </div>
            <div style="background: rgba(255,165,0,0.05); border-left: 4px solid #ffa500; padding: 1rem 1.5rem; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="color: #ffa500; font-weight: 700;">🐛 Morris Worm</span>
                        <span style="color: #8892b0; font-size: 0.85rem; margin-left: 0.5rem;">1988</span>
                    </div>
                    <span style="font-size: 0.7rem; background: rgba(255,165,0,0.1); padding: 0.2rem 0.8rem; border-radius: 12px; color: #ffa500;">First Internet Worm</span>
                </div>
            </div>
            <div style="background: rgba(255,0,100,0.05); border-left: 4px solid #ff0064; padding: 1rem 1.5rem; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="color: #ff0064; font-weight: 700;">💣 Stuxnet</span>
                        <span style="color: #8892b0; font-size: 0.85rem; margin-left: 0.5rem;">2010</span>
                    </div>
                    <span style="font-size: 0.7rem; background: rgba(255,0,100,0.1); padding: 0.2rem 0.8rem; border-radius: 12px; color: #ff0064;">First Cyber Weapon</span>
                </div>
            </div>
            <div style="background: rgba(255,107,107,0.05); border-left: 4px solid #ff6b6b; padding: 1rem 1.5rem; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="color: #ff6b6b; font-weight: 700;">💰 WannaCry Ransomware</span>
                        <span style="color: #8892b0; font-size: 0.85rem; margin-left: 0.5rem;">2017</span>
                    </div>
                    <span style="font-size: 0.7rem; background: rgba(255,107,107,0.1); padding: 0.2rem 0.8rem; border-radius: 12px; color: #ff6b6b;">Global Ransomware</span>
                </div>
            </div>
            <div style="background: rgba(0,212,255,0.05); border-left: 4px solid #00d4ff; padding: 1rem 1.5rem; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="color: #00d4ff; font-weight: 700;">📡 Snowden Leaks</span>
                        <span style="color: #8892b0; font-size: 0.85rem; margin-left: 0.5rem;">2013</span>
                    </div>
                    <span style="font-size: 0.7rem; background: rgba(0,212,255,0.1); padding: 0.2rem 0.8rem; border-radius: 12px; color: #00d4ff;">Privacy Revelation</span>
                </div>
            </div>
            <div style="background: rgba(123,47,252,0.05); border-left: 4px solid #7b2ffc; padding: 1rem 1.5rem; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="color: #7b2ffc; font-weight: 700;">🧠 AI-Powered Attacks</span>
                        <span style="color: #8892b0; font-size: 0.85rem; margin-left: 0.5rem;">2024</span>
                    </div>
                    <span style="font-size: 0.7rem; background: rgba(123,47,252,0.1); padding: 0.2rem 0.8rem; border-radius: 12px; color: #7b2ffc;">Emerging Threat</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Quiz -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff0064;">🧠 Cybersecurity Quiz</h2>
        <p style="color: #8892b0;">Test your knowledge about cybersecurity and digital protection.</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <div id="cyberQuiz">
            <div id="cyberQuizQuestion" style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem;">
                What was the first computer virus?
            </div>
            <div id="cyberQuizOptions" style="display: grid; gap: 0.5rem;">
                <button onclick="checkCyberQuiz('Creeper Virus')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    A. Creeper Virus
                </button>
                <button onclick="checkCyberQuiz('Brain Virus')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    B. Brain Virus
                </button>
                <button onclick="checkCyberQuiz('Morris Worm')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    C. Morris Worm
                </button>
                <button onclick="checkCyberQuiz('Stuxnet')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    D. Stuxnet
                </button>
            </div>
            <div id="cyberQuizFeedback" style="margin-top: 1rem; padding: 0.8rem; border-radius: 8px; display: none;"></div>
            <button onclick="nextCyberQuizQuestion()" id="cyberQuizNextBtn" style="margin-top: 1rem; padding: 0.5rem 2rem; background: linear-gradient(135deg, #ff6b6b, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600; display: none;">
                Next Question →
            </button>
        </div>
    </div>
</section>

<!-- Cybersecurity Details Modal -->
<div id="cyberModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeCyberModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="cyberModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Timeline scrolling
function scrollCyberTimeline(direction) {
    const timeline = document.getElementById("cyberTimeline");
    const scrollAmount = 300;
    if (direction === "left") {
        timeline.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
        timeline.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
}

// Cyber details modal
function showCyberDetails(title, description, year, icon, category, company) {
    const modal = document.getElementById("cyberModal");
    const content = document.getElementById("cyberModalContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 0.5rem;">${icon}</div>
            <h2 style="font-family: 'Orbitron', monospace; color: #ff6b6b; margin-bottom: 0.5rem;">${title}</h2>
            <div style="font-family: 'Orbitron', monospace; color: #7b2ffc; font-size: 1.2rem; margin-bottom: 0.3rem;">${year}</div>
            <div style="color: #8892b0; margin-bottom: 0.3rem;">Company: ${company}</div>
            <div style="color: #8892b0; margin-bottom: 1rem;">Category: ${category}</div>
            <p style="color: #ccd6f6; line-height: 1.8;">${description}</p>
            <div style="margin-top: 1rem; padding: 0.8rem; background: rgba(255,107,107,0.05); border-radius: 8px; border: 1px solid rgba(255,107,107,0.1);">
                <span style="color: #ff6b6b;">💡</span>
                <span style="color: #8892b0;">${title} was a ${category} event in cybersecurity history that ${description.toLowerCase().includes("first") ? "pioneered" : "transformed"} the field of digital protection.</span>
            </div>
            <button onclick="closeCyberModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #ff6b6b, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Close
            </button>
        </div>
    `;
}

function closeCyberModal() {
    document.getElementById("cyberModal").style.display = "none";
    document.body.style.overflow = "";
}

// Cyber Quiz functionality
let cyberQuizQuestions = [
    { question: "What was the first computer virus?", options: ["Creeper Virus", "Brain Virus", "Morris Worm", "Stuxnet"], correct: 0 },
    { question: "What year was the Morris Worm released?", options: ["1986", "1988", "1990", "1995"], correct: 1 },
    { question: "What does SSL stand for?", options: ["Secure Sockets Layer", "Safe System Layer", "Secure Server Layer", "System Security Layer"], correct: 0 },
    { question: "Which was the first cyber weapon?", options: ["Morris Worm", "Stuxnet", "WannaCry", "NotPetya"], correct: 1 },
    { question: "What is the most common cause of data breaches?", options: ["Hacking", "Human Error", "Malware", "Ransomware"], correct: 1 },
    { question: "What year was GDPR enforced?", options: ["2016", "2017", "2018", "2019"], correct: 2 },
    { question: "What does 2FA stand for?", options: ["Two-Factor Authentication", "Two-File Access", "Temporal File Access", "Terminal File Authentication"], correct: 0 },
    { question: "What is the projected cost of cybercrime by 2025?", options: ["$5T", "$7.5T", "$10.5T", "$15T"], correct: 2 }
];

let currentCyberQuiz = 0;
let cyberQuizScore = 0;

function checkCyberQuiz(selected) {
    const correct = cyberQuizQuestions[currentCyberQuiz].options[cyberQuizQuestions[currentCyberQuiz].correct];
    const feedback = document.getElementById("cyberQuizFeedback");
    const options = document.querySelectorAll("#cyberQuizOptions button");
    
    options.forEach(btn => btn.style.pointerEvents = "none");
    
    options.forEach((btn, index) => {
        if (cyberQuizQuestions[currentCyberQuiz].options[index] === correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (btn.textContent.includes(selected) && selected !== correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    if (selected === correct) {
        cyberQuizScore++;
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
    
    document.getElementById("cyberQuizNextBtn").style.display = "inline-block";
}

function nextCyberQuizQuestion() {
    currentCyberQuiz++;
    
    if (currentCyberQuiz >= cyberQuizQuestions.length) {
        const container = document.getElementById("cyberQuiz");
        const percentage = Math.round((cyberQuizScore / cyberQuizQuestions.length) * 100);
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
                    You got ${cyberQuizScore} out of ${cyberQuizQuestions.length} questions correct.
                </p>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #ff6b6b, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Try Again
                </button>
            </div>
        `;
        return;
    }
    
    const q = cyberQuizQuestions[currentCyberQuiz];
    document.getElementById("cyberQuizQuestion").textContent = q.question;
    const optionsContainer = document.getElementById("cyberQuizOptions");
    optionsContainer.innerHTML = "";
    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.textContent = String.fromCharCode(65 + index) + ". " + option;
        btn.style.cssText = "padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;";
        btn.onclick = function() { checkCyberQuiz(option); };
        optionsContainer.appendChild(btn);
    });
    
    document.getElementById("cyberQuizFeedback").style.display = "none";
    document.getElementById("cyberQuizNextBtn").style.display = "none";
}

// Keyboard shortcuts
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeCyberModal();
});

// Close modal on overlay click
document.getElementById("cyberModal").addEventListener("click", function(e) {
    if (e.target === this) closeCyberModal();
});

// Intersection Observer for timeline items
document.querySelectorAll(".cyber-item").forEach((item, index) => {
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

document.querySelectorAll(".cyber-item").forEach(item => observer.observe(item));

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
.cyber-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
    max-height: 800px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.cyber-timeline::-webkit-scrollbar {
    width: 6px;
}

.cyber-timeline::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

.cyber-timeline::-webkit-scrollbar-thumb {
    background: rgba(255,107,107,0.3);
    border-radius: 3px;
}

.cyber-timeline::-webkit-scrollbar-thumb:hover {
    background: rgba(255,107,107,0.5);
}

.cyber-timeline::before {
    content: "";
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #ff6b6b, #7b2ffc, transparent);
    transform: translateX(-50%);
}

.cyber-item {
    display: flex;
    padding: 1.5rem 0;
    position: relative;
    width: 50%;
}

.cyber-item.left {
    padding-right: 3rem;
    justify-content: flex-end;
}

.cyber-item.right {
    padding-left: 3rem;
    margin-left: 50%;
}

.cyber-dot {
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

.cyber-item:hover .cyber-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.cyber-content {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
    width: 100%;
}

.cyber-content:hover {
    transform: translateY(-4px);
    border-color: #ff6b6b;
    box-shadow: 0 8px 30px rgba(255,107,107,0.1);
}

.cyber-year {
    font-family: "Orbitron", monospace;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.cyber-icon {
    font-size: 2.5rem;
    margin-bottom: 0.3rem;
}

.cyber-content h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.3rem;
}

.cyber-content p {
    color: #8892b0;
    font-size: 0.9rem;
    margin: 0;
}

#cyberQuizOptions button:hover {
    border-color: #ff6b6b !important;
    background: rgba(255,107,107,0.05) !important;
}

#cyberModal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .cyber-timeline {
        max-height: 600px;
        padding: 1rem 0;
    }
    
    .cyber-timeline::before {
        left: 20px;
    }
    
    .cyber-item {
        width: 100%;
        padding: 1rem 0;
    }
    
    .cyber-item.left {
        padding-right: 0;
    }
    
    .cyber-item.right {
        padding-left: 0;
        margin-left: 0;
    }
    
    .cyber-dot {
        left: 20px;
    }
    
    .cyber-content {
        margin-left: 40px;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>