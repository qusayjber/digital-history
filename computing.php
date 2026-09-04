<?php
// computing.php
// Digital History - Computing History (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'History of Computing';
$pageDescription = 'Explore the complete history of computing from mechanical calculators to quantum computers - the complete story of computing evolution.';

$eras = getEras();
$technologies = getTechnologies('Computers');

// Get featured computing events
$computingEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE is_active = 1 AND event_type IN ('invention', 'milestone', 'technology') 
     AND (title LIKE '%computer%' OR title LIKE '%computing%' OR title LIKE '%machine%' OR title LIKE '%processor%' OR title LIKE '%chip%')
     ORDER BY year ASC LIMIT 20"
);

// Get computer-related people
$computerPeople = db()->fetchAll(
    "SELECT p.* FROM people p 
     WHERE p.is_active = 1 
     AND (p.known_for LIKE '%computer%' OR p.known_for LIKE '%computing%' OR p.known_for LIKE '%hardware%' OR p.known_for LIKE '%processor%')
     ORDER BY p.birth_year ASC LIMIT 10"
);

// Get computer-related technologies
$computerTechs = db()->fetchAll(
    "SELECT * FROM technologies 
     WHERE is_active = 1 
     AND (category = 'Hardware' OR category = 'Computers' OR name LIKE '%computer%' OR name LIKE '%processor%' OR name LIKE '%chip%')
     ORDER BY year_introduced ASC LIMIT 10"
);

trackPageView('computing');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            🖥️ History of Computing
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">From mechanical calculators to quantum computers - the complete story of computing.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($computingEvents); ?>+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Computing Events</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($computerPeople); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;"><?php echo count($computerTechs); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Technologies</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">5</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Generations</div>
        </div>
    </div>
</section>

<!-- Interactive Computing Timeline -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem; background: rgba(255,255,255,0.01); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h3 style="color: #00d4ff; margin: 0; font-size: 1.5rem;">
            <i class="fas fa-timeline"></i> Interactive Timeline
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
    
    <div class="computing-timeline" id="computingTimeline">
        <?php
        $computingEventsList = [
            [
                'year' => '1801',
                'title' => 'Jacquard Loom',
                'description' => 'The first programmable machine using punch cards, revolutionizing textile manufacturing.',
                'icon' => '🏗️',
                'color' => '#ff6b6b',
                'category' => 'Mechanical',
                'impact' => 'High'
            ],
            [
                'year' => '1822',
                'title' => 'Difference Engine',
                'description' => 'Charles Babbage\'s mechanical calculator designed to compute polynomial functions with high accuracy.',
                'icon' => '⚙️',
                'color' => '#ffa500',
                'category' => 'Mechanical',
                'impact' => 'High'
            ],
            [
                'year' => '1837',
                'title' => 'Analytical Engine',
                'description' => 'Babbage\'s revolutionary design for a general-purpose mechanical computer, the first concept of a programmable computer.',
                'icon' => '🔧',
                'color' => '#ffd93d',
                'category' => 'Mechanical',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1945',
                'title' => 'ENIAC',
                'description' => 'The first electronic general-purpose computer, weighing 30 tons and occupying 1800 square feet.',
                'icon' => '💻',
                'color' => '#00d4ff',
                'category' => 'Electronic',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1951',
                'title' => 'UNIVAC I',
                'description' => 'The first commercially produced computer in the US, used for business and government applications.',
                'icon' => '🏢',
                'color' => '#7b2ffc',
                'category' => 'Commercial',
                'impact' => 'High'
            ],
            [
                'year' => '1975',
                'title' => 'Altair 8800',
                'description' => 'The first commercially successful personal computer, sparking the home computer revolution.',
                'icon' => '🖥️',
                'color' => '#00ff88',
                'category' => 'Personal',
                'impact' => 'High'
            ],
            [
                'year' => '1977',
                'title' => 'Apple II',
                'description' => 'One of the first successful mass-produced microcomputers, bringing computing to the masses.',
                'icon' => '🍎',
                'color' => '#ff0064',
                'category' => 'Personal',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1981',
                'title' => 'IBM PC',
                'description' => 'Set the standard for personal computing architecture and established the PC as a business tool.',
                'icon' => '💼',
                'color' => '#00d4ff',
                'category' => 'Business',
                'impact' => 'High'
            ],
            [
                'year' => '1984',
                'title' => 'Macintosh',
                'description' => 'First mass-market computer with a graphical user interface, making computers accessible to everyone.',
                'icon' => '🖱️',
                'color' => '#7b2ffc',
                'category' => 'Personal',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '1991',
                'title' => 'Linux',
                'description' => 'Open-source operating system kernel created by Linus Torvalds, powering most of the internet.',
                'icon' => '🐧',
                'color' => '#ffa500',
                'category' => 'Software',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '2007',
                'title' => 'iPhone',
                'description' => 'Revolutionized mobile computing and smartphones, creating the modern smartphone era.',
                'icon' => '📱',
                'color' => '#00d4ff',
                'category' => 'Mobile',
                'impact' => 'Revolutionary'
            ],
            [
                'year' => '2020+',
                'title' => 'Quantum Computing',
                'description' => 'Computers using quantum mechanics for computation, promising exponential speed increases.',
                'icon' => '⚛️',
                'color' => '#7b2ffc',
                'category' => 'Future',
                'impact' => 'Revolutionary'
            ]
        ];
        
        foreach ($computingEventsList as $index => $event):
        ?>
        <div class="computing-item <?php echo $index % 2 === 0 ? 'left' : 'right'; ?> animate-on-scroll" data-index="<?php echo $index; ?>">
            <div class="computing-dot" style="background: <?php echo $event['color']; ?>; box-shadow: 0 0 20px <?php echo $event['color']; ?>40;"></div>
            <div class="computing-content" style="border-color: <?php echo $event['color']; ?>20;">
                <div class="computing-year" style="color: <?php echo $event['color']; ?>;"><?php echo $event['year']; ?></div>
                <div class="computing-icon"><?php echo $event['icon']; ?></div>
                <h3><?php echo $event['title']; ?></h3>
                <p><?php echo $event['description']; ?></p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.65rem; background: <?php echo $event['color']; ?>20; color: <?php echo $event['color']; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $event['category']; ?>
                    </span>
                    <span style="font-size: 0.65rem; background: <?php echo $event['impact'] === 'Revolutionary' ? 'rgba(255,0,100,0.1)' : 'rgba(255,165,0,0.1)'; ?>; color: <?php echo $event['impact'] === 'Revolutionary' ? '#ff0064' : '#ffa500'; ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                        <?php echo $event['impact']; ?>
                    </span>
                </div>
                <button onclick="showEventDetails('<?php echo $event['title']; ?>', '<?php echo addslashes($event['description']); ?>', '<?php echo $event['year']; ?>', '<?php echo $event['icon']; ?>')" style="margin-top: 0.8rem; padding: 0.3rem 1rem; background: <?php echo $event['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; font-size: 0.8rem; transition: all 0.3s;">
                    Learn More
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Then vs Now Comparison -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">⚡ Then vs Now</h2>
        <p style="color: #8892b0;">Compare the computing power of yesterday and today.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🕰️</div>
            <h3 style="color: #8892b0; font-size: 1.3rem;">ENIAC (1945)</h3>
            <ul style="list-style: none; padding: 0; margin-top: 1rem; text-align: left;">
                <li style="padding: 0.4rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Speed</span>
                    <span style="color: #fff; font-weight: 500;">5,000 ops/sec</span>
                </li>
                <li style="padding: 0.4rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Memory</span>
                    <span style="color: #fff; font-weight: 500;">20 registers</span>
                </li>
                <li style="padding: 0.4rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Size</span>
                    <span style="color: #fff; font-weight: 500;">1,800 sq ft</span>
                </li>
                <li style="padding: 0.4rem 0; display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Weight</span>
                    <span style="color: #fff; font-weight: 500;">30 tons</span>
                </li>
            </ul>
        </div>
        
        <div style="background: rgba(0,212,255,0.03); border: 1px solid rgba(0,212,255,0.1); border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🚀</div>
            <h3 style="color: #00d4ff; font-size: 1.3rem;">Modern Smartphone (2024)</h3>
            <ul style="list-style: none; padding: 0; margin-top: 1rem; text-align: left;">
                <li style="padding: 0.4rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Speed</span>
                    <span style="color: #fff; font-weight: 500;">~3 trillion ops/sec</span>
                </li>
                <li style="padding: 0.4rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Memory</span>
                    <span style="color: #fff; font-weight: 500;">~128 GB storage</span>
                </li>
                <li style="padding: 0.4rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Size</span>
                    <span style="color: #fff; font-weight: 500;">~6 inches</span>
                </li>
                <li style="padding: 0.4rem 0; display: flex; justify-content: space-between;">
                    <span style="color: #8892b0;">Weight</span>
                    <span style="color: #fff; font-weight: 500;">~200 grams</span>
                </li>
            </ul>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 2rem; padding: 1.5rem; background: rgba(0,212,255,0.05); border-radius: 12px; border: 1px solid rgba(0,212,255,0.1);">
        <div style="font-size: 2.5rem; font-weight: 700; color: #00d4ff; animation: pulse 2s ease-in-out infinite;">
            600,000,000× more powerful
        </div>
        <p style="color: #8892b0; margin-top: 0.5rem;">A modern smartphone is hundreds of millions of times more powerful than ENIAC</p>
        <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1rem; flex-wrap: wrap;">
            <div style="text-align: center;">
                <div style="font-size: 0.8rem; color: #8892b0;">ENIAC vs Smartphone</div>
                <div style="width: 200px; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; margin-top: 0.3rem;">
                    <div style="width: 0.0000001%; height: 100%; background: #00d4ff; border-radius: 4px;"></div>
                </div>
                <div style="font-size: 0.7rem; color: #00d4ff; margin-top: 0.2rem;">ENIAC (almost invisible)</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 0.8rem; color: #8892b0;">Smartphone Performance</div>
                <div style="width: 200px; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; margin-top: 0.3rem;">
                    <div style="width: 100%; height: 100%; background: linear-gradient(90deg, #00d4ff, #7b2ffc); border-radius: 4px;"></div>
                </div>
                <div style="font-size: 0.7rem; color: #7b2ffc; margin-top: 0.2rem;">600 million times faster</div>
            </div>
        </div>
    </div>
</section>

<!-- Computer Generations -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📊 Generations of Computers</h2>
        <p style="color: #8892b0;">Five generations of computing technology evolution - from vacuum tubes to AI.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">💡</div>
            <h4 style="color: #ff6b6b; font-size: 1rem;">First Generation</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">1940-1956</p>
            <p style="color: #8892b0; font-size: 0.75rem;">Vacuum tubes, magnetic drums</p>
            <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <span style="font-size: 0.6rem; color: #ff6b6b;">⚡ ENIAC, UNIVAC</span>
            </div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔌</div>
            <h4 style="color: #ffa500; font-size: 1rem;">Second Generation</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">1956-1963</p>
            <p style="color: #8892b0; font-size: 0.75rem;">Transistors, magnetic core</p>
            <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <span style="font-size: 0.6rem; color: #ffa500;">🔬 IBM 1401</span>
            </div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔬</div>
            <h4 style="color: #ffd93d; font-size: 1rem;">Third Generation</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">1964-1971</p>
            <p style="color: #8892b0; font-size: 0.75rem;">Integrated circuits, keyboards</p>
            <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <span style="font-size: 0.6rem; color: #ffd93d;">💻 IBM System/360</span>
            </div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🖥️</div>
            <h4 style="color: #00ff88; font-size: 1rem;">Fourth Generation</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">1971-1990</p>
            <p style="color: #8892b0; font-size: 0.75rem;">Microprocessors, personal computers</p>
            <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <span style="font-size: 0.6rem; color: #00ff88;">🍎 Apple, IBM PC</span>
            </div>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; grid-column: <?php echo count($computingEventsList) % 2 === 0 ? 'span 1' : 'span 1'; ?>;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">Fifth Generation</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">1990-Present</p>
            <p style="color: #8892b0; font-size: 0.75rem;">AI, cloud, quantum computing</p>
            <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <span style="font-size: 0.6rem; color: #7b2ffc;">🤖 AI, Quantum</span>
            </div>
        </div>
        
        <!-- Future Generation -->
        <div style="background: rgba(123,47,252,0.03); border: 1px solid rgba(123,47,252,0.1); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; grid-column: span 1;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🚀</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Sixth Generation</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Future</p>
            <p style="color: #8892b0; font-size: 0.75rem;">AGI, Bio-computing, Neural interfaces</p>
            <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <span style="font-size: 0.6rem; color: #00d4ff;">🧠 AGI, BCI</span>
            </div>
        </div>
    </div>
</section>

<!-- Computing Pioneers -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">👨‍💻 Computing Pioneers</h2>
        <p style="color: #8892b0;">The brilliant minds who shaped the computing revolution.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <?php
        $pioneers = [
            ['name' => 'Ada Lovelace', 'role' => 'First Programmer', 'icon' => '👩‍💻', 'color' => '#ff6b6b'],
            ['name' => 'Alan Turing', 'role' => 'AI Pioneer', 'icon' => '🧠', 'color' => '#00d4ff'],
            ['name' => 'Grace Hopper', 'role' => 'Compiler Inventor', 'icon' => '👩‍🔬', 'color' => '#7b2ffc'],
            ['name' => 'Charles Babbage', 'role' => 'Computer Designer', 'icon' => '🔧', 'color' => '#ffa500'],
            ['name' => 'John von Neumann', 'role' => 'Computer Architecture', 'icon' => '📐', 'color' => '#00ff88'],
            ['name' => 'Steve Jobs', 'role' => 'Apple Founder', 'icon' => '🍎', 'color' => '#ff0064'],
            ['name' => 'Bill Gates', 'role' => 'Microsoft Founder', 'icon' => '💻', 'color' => '#00d4ff'],
            ['name' => 'Linus Torvalds', 'role' => 'Linux Creator', 'icon' => '🐧', 'color' => '#ffa500']
        ];
        
        foreach ($pioneers as $pioneer):
        ?>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 3rem; margin-bottom: 0.3rem;"><?php echo $pioneer['icon']; ?></div>
            <h4 style="color: <?php echo $pioneer['color']; ?>; font-size: 0.95rem; margin-bottom: 0.2rem;"><?php echo $pioneer['name']; ?></h4>
            <p style="color: #8892b0; font-size: 0.75rem;"><?php echo $pioneer['role']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Key Technologies -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">🔧 Key Computing Technologies</h2>
        <p style="color: #8892b0;">The technologies that powered the computing revolution.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem;">💾</div>
            <h4 style="color: #00d4ff;">Vacuum Tubes</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">First generation computing, enabled ENIAC and early computers.</p>
            <span style="font-size: 0.6rem; color: #8892b0;">1940s</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem;">🔌</div>
            <h4 style="color: #ffa500;">Transistors</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Replaced vacuum tubes, smaller and more reliable.</p>
            <span style="font-size: 0.6rem; color: #8892b0;">1950s</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem;">🔬</div>
            <h4 style="color: #7b2ffc;">Integrated Circuits</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Multiple transistors on a single chip, enabling modern computing.</p>
            <span style="font-size: 0.6rem; color: #8892b0;">1960s</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem;">⚡</div>
            <h4 style="color: #00ff88;">Microprocessors</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">The CPU on a single chip, powering personal computers.</p>
            <span style="font-size: 0.6rem; color: #8892b0;">1970s</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem;">🧠</div>
            <h4 style="color: #ff0064;">AI Accelerators</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Specialized chips for AI and machine learning workloads.</p>
            <span style="font-size: 0.6rem; color: #8892b0;">2010s</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem;">⚛️</div>
            <h4 style="color: #7b2ffc;">Quantum Processors</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Computing using quantum mechanics for exponential speed.</p>
            <span style="font-size: 0.6rem; color: #8892b0;">2020s</span>
        </div>
    </div>
</section>

<!-- Interactive Quiz Section -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff0064;">🧠 Test Your Knowledge</h2>
        <p style="color: #8892b0;">How well do you know the history of computing?</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <div id="computingQuiz">
            <div id="quizQuestion" style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem;">
                What was the first electronic general-purpose computer?
            </div>
            <div id="quizOptions" style="display: grid; gap: 0.5rem;">
                <button onclick="checkQuizAnswer('UNIVAC')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    A. UNIVAC
                </button>
                <button onclick="checkQuizAnswer('ENIAC')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    B. ENIAC
                </button>
                <button onclick="checkQuizAnswer('IBM PC')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    C. IBM PC
                </button>
                <button onclick="checkQuizAnswer('Macintosh')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    D. Macintosh
                </button>
            </div>
            <div id="quizFeedback" style="margin-top: 1rem; padding: 0.8rem; border-radius: 8px; display: none;"></div>
            <button onclick="nextQuizQuestion()" id="quizNextBtn" style="margin-top: 1rem; padding: 0.5rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600; display: none;">
                Next Question →
            </button>
        </div>
    </div>
</section>

<!-- Event Details Modal -->
<div id="eventModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeEventModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="eventModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = '
// Timeline scrolling
function scrollTimeline(direction) {
    const timeline = document.getElementById("computingTimeline");
    const scrollAmount = 300;
    if (direction === "left") {
        timeline.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
        timeline.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
}

// Event details modal
function showEventDetails(title, description, year, icon) {
    const modal = document.getElementById("eventModal");
    const content = document.getElementById("eventModalContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 0.5rem;">${icon}</div>
            <h2 style="font-family: \'Orbitron\', monospace; color: #00d4ff; margin-bottom: 0.5rem;">${title}</h2>
            <div style="font-family: \'Orbitron\', monospace; color: #7b2ffc; font-size: 1.2rem; margin-bottom: 1rem;">${year}</div>
            <p style="color: #ccd6f6; line-height: 1.8;">${description}</p>
            <button onclick="closeEventModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Close
            </button>
        </div>
    `;
}

function closeEventModal() {
    document.getElementById("eventModal").style.display = "none";
    document.body.style.overflow = "";
}

// Quiz functionality
let quizQuestions = [
    { question: "What was the first electronic general-purpose computer?", options: ["UNIVAC", "ENIAC", "IBM PC", "Macintosh"], correct: 1 },
    { question: "Who is considered the first computer programmer?", options: ["Alan Turing", "Grace Hopper", "Ada Lovelace", "Steve Jobs"], correct: 2 },
    { question: "What year was the IBM PC released?", options: ["1975", "1981", "1984", "1991"], correct: 1 },
    { question: "Which generation of computers introduced integrated circuits?", options: ["First", "Second", "Third", "Fourth"], correct: 2 },
    { question: "What company created the first commercially successful personal computer?", options: ["IBM", "Apple", "Microsoft", "Altair"], correct: 3 }
];

let currentQuizQuestion = 0;
let quizScore = 0;

function checkQuizAnswer(selected) {
    const correct = quizQuestions[currentQuizQuestion].options[quizQuestions[currentQuizQuestion].correct];
    const feedback = document.getElementById("quizFeedback");
    const options = document.querySelectorAll("#quizOptions button");
    
    // Disable all options
    options.forEach(btn => btn.style.pointerEvents = "none");
    
    // Highlight correct/incorrect
    options.forEach((btn, index) => {
        if (quizQuestions[currentQuizQuestion].options[index] === correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (btn.textContent.includes(selected) && selected !== correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    if (selected === correct) {
        quizScore++;
        feedback.innerHTML = `<span style="color: #00ff88;">✅ Correct! Great job!</span>`;
        feedback.style.background = "rgba(0, 255, 136, 0.05)";
    } else {
        feedback.innerHTML = `<span style="color: #ff6b6b;">❌ The correct answer was: ${correct}</span>`;
        feedback.style.background = "rgba(255, 0, 0, 0.05)";
    }
    
    feedback.style.display = "block";
    feedback.style.border = "1px solid rgba(255,255,255,0.05)";
    feedback.style.padding = "0.8rem";
    feedback.style.borderRadius = "8px";
    
    document.getElementById("quizNextBtn").style.display = "inline-block";
}

function nextQuizQuestion() {
    currentQuizQuestion++;
    
    if (currentQuizQuestion >= quizQuestions.length) {
        const container = document.getElementById("computingQuiz");
        const percentage = Math.round((quizScore / quizQuestions.length) * 100);
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
                    You got ${quizScore} out of ${quizQuestions.length} questions correct.
                </p>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Try Again
                </button>
            </div>
        `;
        return;
    }
    
    const q = quizQuestions[currentQuizQuestion];
    document.getElementById("quizQuestion").textContent = q.question;
    const optionsContainer = document.getElementById("quizOptions");
    optionsContainer.innerHTML = "";
    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.textContent = `${String.fromCharCode(65 + index)}. ${option}`;
        btn.style.cssText = "padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;";
        btn.onclick = function() { checkQuizAnswer(option); };
        optionsContainer.appendChild(btn);
    });
    
    document.getElementById("quizFeedback").style.display = "none";
    document.getElementById("quizNextBtn").style.display = "none";
}

// Add hover effects to buttons
document.querySelectorAll(".computing-content button").forEach(btn => {
    btn.addEventListener("mouseenter", function() {
        this.style.transform = "scale(1.05)";
    });
    btn.addEventListener("mouseleave", function() {
        this.style.transform = "scale(1)";
    });
});

// Intersection Observer for timeline items
document.querySelectorAll(".computing-item").forEach((item, index) => {
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

document.querySelectorAll(".computing-item").forEach(item => observer.observe(item));

// Keyboard shortcut: Escape to close modal
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeEventModal();
});

// Close modal on overlay click
document.getElementById("eventModal").addEventListener("click", function(e) {
    if (e.target === this) closeEventModal();
});

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
';

$pageCSS = '
.computing-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
    max-height: 800px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.computing-timeline::-webkit-scrollbar {
    width: 6px;
}

.computing-timeline::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

.computing-timeline::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

.computing-timeline::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

.computing-timeline::before {
    content: "";
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #00d4ff, #7b2ffc, transparent);
    transform: translateX(-50%);
}

.computing-item {
    display: flex;
    padding: 1.5rem 0;
    position: relative;
    width: 50%;
}

.computing-item.left {
    padding-right: 3rem;
    justify-content: flex-end;
}

.computing-item.right {
    padding-left: 3rem;
    margin-left: 50%;
}

.computing-dot {
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

.computing-item:hover .computing-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.computing-content {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
    width: 100%;
}

.computing-content:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

.computing-year {
    font-family: "Orbitron", monospace;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.computing-icon {
    font-size: 2.5rem;
    margin-bottom: 0.3rem;
}

.computing-content h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.3rem;
}

.computing-content p {
    color: #8892b0;
    font-size: 0.9rem;
    margin: 0;
}

@media (max-width: 768px) {
    .computing-timeline {
        max-height: 600px;
        padding: 1rem 0;
    }
    
    .computing-timeline::before {
        left: 20px;
    }
    
    .computing-item {
        width: 100%;
        padding: 1rem 0;
    }
    
    .computing-item.left {
        padding-right: 0;
    }
    
    .computing-item.right {
        padding-left: 0;
        margin-left: 0;
    }
    
    .computing-dot {
        left: 20px;
    }
    
    .computing-content {
        margin-left: 40px;
    }
}

#quizOptions button:hover {
    border-color: #00d4ff !important;
    background: rgba(0, 212, 255, 0.05) !important;
}

#eventModal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>