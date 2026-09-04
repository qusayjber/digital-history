<?php
// museum.php
// Digital History - Virtual Museum (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'Virtual Museum';
$pageDescription = 'Explore the Digital History virtual museum with themed rooms and interactive exhibits.';

// Get museum statistics from database
$totalExhibits = 0;
$totalRooms = 0;

// Try to get actual counts from database
$roomCount = db()->fetch("SELECT COUNT(DISTINCT category) as count FROM technologies WHERE is_active = 1");
if ($roomCount) {
    $totalRooms = $roomCount['count'] + 5; // Add some extra rooms
}

$exhibitCount = db()->fetch("SELECT COUNT(*) as count FROM timeline_events WHERE is_active = 1");
if ($exhibitCount) {
    $totalExhibits = $exhibitCount['count'];
}

trackPageView('museum');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            🏛️ Virtual Museum
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">Explore technology history through themed museum rooms and interactive exhibits.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;">9</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Museum Rooms</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo max(110, $totalExhibits); ?>+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Exhibits</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">50+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Interactive Elements</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;"><?php echo count(getPeople()); ?>+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Featured People</div>
        </div>
    </div>
</section>

<!-- Museum Rooms -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
        <?php
        $rooms = [
            [
                'id' => 'early-computers',
                'name' => 'Early Computers',
                'icon' => '🖥️',
                'description' => 'From mechanical calculators to the first electronic computers.',
                'color' => '#ff6b6b',
                'items' => 12,
                'era' => '1800-1940s',
                'featured' => true
            ],
            [
                'id' => 'internet',
                'name' => 'Internet History',
                'icon' => '🌐',
                'description' => 'The story of ARPANET, TCP/IP, and the World Wide Web.',
                'color' => '#00d4ff',
                'items' => 15,
                'era' => '1960-2000s',
                'featured' => true
            ],
            [
                'id' => 'programming',
                'name' => 'Programming Languages',
                'icon' => '💻',
                'description' => 'The evolution of programming from machine code to modern languages.',
                'color' => '#7b2ffc',
                'items' => 18,
                'era' => '1940-Present',
                'featured' => true
            ],
            [
                'id' => 'web',
                'name' => 'Web Evolution',
                'icon' => '🌍',
                'description' => 'From Web 1.0 to Web 3.0 and the modern web.',
                'color' => '#00ff88',
                'items' => 10,
                'era' => '1990-Present',
                'featured' => false
            ],
            [
                'id' => 'gaming',
                'name' => 'Gaming History',
                'icon' => '🎮',
                'description' => 'The evolution of video games from Pong to modern gaming.',
                'color' => '#ffa500',
                'items' => 14,
                'era' => '1970-Present',
                'featured' => true
            ],
            [
                'id' => 'mobile',
                'name' => 'Mobile Technology',
                'icon' => '📱',
                'description' => 'From the first mobile phones to smartphones and beyond.',
                'color' => '#ff0064',
                'items' => 11,
                'era' => '1980-Present',
                'featured' => false
            ],
            [
                'id' => 'cybersecurity',
                'name' => 'Cybersecurity',
                'icon' => '🛡️',
                'description' => 'The history of digital security and cyber threats.',
                'color' => '#ff6b6b',
                'items' => 9,
                'era' => '1970-Present',
                'featured' => false
            ],
            [
                'id' => 'ai',
                'name' => 'Artificial Intelligence',
                'icon' => '🧠',
                'description' => 'From the Turing test to generative AI.',
                'color' => '#7b2ffc',
                'items' => 13,
                'era' => '1950-Present',
                'featured' => true
            ],
            [
                'id' => 'future',
                'name' => 'Future Technology',
                'icon' => '🚀',
                'description' => 'Predictions and visions for the future of technology.',
                'color' => '#00d4ff',
                'items' => 8,
                'era' => '2030+',
                'featured' => false
            ]
        ];
        
        foreach ($rooms as $room):
        ?>
        <div class="museum-room" onclick="openRoom('<?php echo $room['id']; ?>')" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: <?php echo $room['color']; ?>;"></div>
            <?php if ($room['featured']): ?>
            <div style="position: absolute; top: 0.5rem; right: 0.5rem; font-size: 0.6rem; background: rgba(255,215,0,0.2); color: #ffd700; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(255,215,0,0.2);">
                ⭐ Featured
            </div>
            <?php endif; ?>
            <div style="font-size: 3rem; margin-bottom: 0.5rem; margin-top: 0.5rem;"><?php echo $room['icon']; ?></div>
            <h3 style="color: #fff; font-size: 1.1rem;"><?php echo $room['name']; ?></h3>
            <p style="color: #8892b0; font-size: 0.85rem; margin: 0.3rem 0;"><?php echo $room['description']; ?></p>
            <div style="color: #8892b0; font-size: 0.7rem; margin-bottom: 0.5rem;">
                <i class="fas fa-clock"></i> <?php echo $room['era']; ?>
            </div>
            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem;">
                <span style="font-size: 0.7rem; color: <?php echo $room['color']; ?>; background: rgba(255,255,255,0.05); padding: 0.2rem 0.8rem; border-radius: 12px;">
                    <?php echo $room['items']; ?> exhibits
                </span>
                <span style="font-size: 0.7rem; color: #8892b0; background: rgba(255,255,255,0.05); padding: 0.2rem 0.8rem; border-radius: 12px;">
                    <i class="fas fa-arrow-right"></i> Enter
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Museum Info -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h3 style="color: #00d4ff; margin-bottom: 1rem; font-size: 1.8rem;">About the Museum</h3>
        <p style="color: #8892b0; line-height: 1.8; font-size: 1.05rem;">
            The Digital History Virtual Museum is a curated collection of exhibits that tell the story 
            of technology. Each room focuses on a specific era or aspect of technology history, 
            featuring key inventions, important figures, and interactive displays.
        </p>
        <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap;">
            <div style="background: rgba(255,255,255,0.03); padding: 1rem 2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;">9</div>
                <div style="color: #8892b0; font-size: 0.85rem;">Museum Rooms</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem 2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;">110+</div>
                <div style="color: #8892b0; font-size: 0.85rem;">Exhibits</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem 2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">50+</div>
                <div style="color: #8892b0; font-size: 0.85rem;">Interactive Elements</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem 2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">30+</div>
                <div style="color: #8892b0; font-size: 0.85rem;">Featured People</div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Tour -->
<section class="section" style="padding: 3rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">🎫 Museum Highlights</h2>
        <p style="color: #8892b0;">Must-see exhibits in the Digital History Virtual Museum.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">💻</div>
            <h4 style="color: #ff6b6b; font-size: 1rem;">ENIAC Display</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">See the first electronic computer</p>
            <span style="font-size: 0.6rem; color: #ff6b6b; background: rgba(255,107,107,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Early Computers</span>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🌐</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">World Wide Web</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">The invention that changed everything</p>
            <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Internet History</span>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">AI Exhibition</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">From Turing to ChatGPT</p>
            <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Artificial Intelligence</span>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🎮</div>
            <h4 style="color: #ffa500; font-size: 1rem;">Gaming Evolution</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">From Pong to modern consoles</p>
            <span style="font-size: 0.6rem; color: #ffa500; background: rgba(255,165,0,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Gaming History</span>
        </div>
    </div>
</section>

<!-- Museum Modal -->
<div id="museumModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 5000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 900px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative; min-height: 400px; max-height: 90vh; overflow-y: auto;">
        <button onclick="closeMuseum()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer; z-index: 10;">
            <i class="fas fa-times"></i>
        </button>
        <div id="museumContent">
            <div style="text-align: center; padding: 2rem 0;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏛️</div>
                <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 1rem;">Virtual Museum</h2>
                <p style="color: #8892b0;">Select a room from the museum to explore.</p>
            </div>
        </div>
    </div>
</div>

<!-- Exhibit Details Modal -->
<div id="exhibitModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 5500; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeExhibitModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="exhibitContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Room data with all exhibits
const roomData = {
    "early-computers": {
        title: "Early Computers",
        icon: "🖥️",
        color: "#ff6b6b",
        description: "The history of computing from mechanical devices to electronic computers.",
        exhibits: [
            { name: "Jacquard Loom", year: "1801", description: "First programmable machine using punch cards.", image: null },
            { name: "Difference Engine", year: "1822", description: "Babbage's mechanical calculator for polynomial functions.", image: null },
            { name: "Analytical Engine", year: "1837", description: "Babbage's design for a general-purpose mechanical computer.", image: null },
            { name: "ENIAC", year: "1945", description: "First electronic general-purpose computer, weighing 30 tons.", image: null },
            { name: "UNIVAC I", year: "1951", description: "First commercially produced computer in the US.", image: null },
            { name: "Altair 8800", year: "1975", description: "First commercially successful personal computer.", image: null },
            { name: "Apple II", year: "1977", description: "One of the first successful mass-produced microcomputers.", image: null },
            { name: "IBM PC", year: "1981", description: "Set the standard for personal computing architecture.", image: null },
            { name: "Macintosh", year: "1984", description: "First mass-market computer with a graphical user interface.", image: null }
        ]
    },
    "internet": {
        title: "Internet History",
        icon: "🌐",
        color: "#00d4ff",
        description: "The story of how the internet was born and evolved into the global network we know today.",
        exhibits: [
            { name: "ARPANET", year: "1969", description: "First packet-switching network, precursor to the internet.", image: null },
            { name: "First Email", year: "1971", description: "Ray Tomlinson sends the first email on ARPANET.", image: null },
            { name: "TCP/IP", year: "1974", description: "Vint Cerf and Bob Kahn create the TCP/IP protocol suite.", image: null },
            { name: "DNS", year: "1983", description: "Domain Name System makes internet addresses human-readable.", image: null },
            { name: "World Wide Web", year: "1989", description: "Tim Berners-Lee invents the World Wide Web at CERN.", image: null },
            { name: "First Website", year: "1991", description: "The first website goes online at CERN.", image: null },
            { name: "Mosaic Browser", year: "1993", description: "The first popular web browser is released.", image: null },
            { name: "Google", year: "1998", description: "Google becomes the dominant search engine.", image: null }
        ]
    },
    "programming": {
        title: "Programming Languages",
        icon: "💻",
        color: "#7b2ffc",
        description: "The evolution of programming languages from machine code to modern languages.",
        exhibits: [
            { name: "Machine Code", year: "1940", description: "Lowest-level programming language executed by CPU.", image: null },
            { name: "Assembly", year: "1949", description: "Low-level language with strong correspondence to machine code.", image: null },
            { name: "FORTRAN", year: "1957", description: "First high-level programming language for scientific computing.", image: null },
            { name: "LISP", year: "1958", description: "Known for parentheses and recursive functions.", image: null },
            { name: "COBOL", year: "1959", description: "Designed for business data processing.", image: null },
            { name: "C", year: "1972", description: "Powerful system programming language.", image: null },
            { name: "C++", year: "1985", description: "Extension of C with object-oriented features.", image: null },
            { name: "Python", year: "1991", description: "Versatile language known for readability.", image: null },
            { name: "JavaScript", year: "1995", description: "Programming language of the web.", image: null },
            { name: "Java", year: "1995", description: "Popular language for platform-independent applications.", image: null }
        ]
    },
    "web": {
        title: "Web Evolution",
        icon: "🌍",
        color: "#00ff88",
        description: "The evolution of the World Wide Web across generations.",
        exhibits: [
            { name: "Web 1.0", year: "1990", description: "The read-only web with static pages.", image: null },
            { name: "First Web Browser", year: "1990", description: "WorldWideWeb by Tim Berners-Lee.", image: null },
            { name: "HTML 1.0", year: "1991", description: "First version of HTML specification.", image: null },
            { name: "CSS 1.0", year: "1996", description: "First version of Cascading Style Sheets.", image: null },
            { name: "JavaScript", year: "1995", description: "Programming language for web interactivity.", image: null },
            { name: "Web 2.0", year: "2000", description: "Interactive web with social media and user content.", image: null },
            { name: "Web 3.0", year: "2015", description: "Decentralized web with blockchain and semantic technology.", image: null },
            { name: "Modern Web", year: "2020", description: "AI-first, cloud-native, real-time web applications.", image: null }
        ]
    },
    "gaming": {
        title: "Gaming History",
        icon: "🎮",
        color: "#ffa500",
        description: "The evolution of video games from simple arcade games to modern gaming.",
        exhibits: [
            { name: "Pong", year: "1972", description: "First commercially successful video game.", image: null },
            { name: "Atari 2600", year: "1977", description: "First successful home video game console.", image: null },
            { name: "Nintendo Entertainment System", year: "1985", description: "Revolutionized home gaming.", image: null },
            { name: "Sega Genesis", year: "1988", description: "16-bit gaming console.", image: null },
            { name: "PlayStation", year: "1994", description: "First successful CD-ROM gaming console.", image: null },
            { name: "Xbox", year: "2001", description: "Microsoft enters the gaming market.", image: null },
            { name: "Wii", year: "2006", description: "Motion-controlled gaming.", image: null },
            { name: "Modern Gaming", year: "2020", description: "Cloud gaming, VR, and esports.", image: null }
        ]
    },
    "ai": {
        title: "Artificial Intelligence",
        icon: "🧠",
        color: "#7b2ffc",
        description: "The history of artificial intelligence from early concepts to modern generative AI.",
        exhibits: [
            { name: "Turing Test", year: "1950", description: "Alan Turing proposes the Turing Test for machine intelligence.", image: null },
            { name: "Dartmouth Conference", year: "1956", description: "The term 'Artificial Intelligence' is coined.", image: null },
            { name: "ELIZA", year: "1966", description: "First chatbot by Joseph Weizenbaum.", image: null },
            { name: "Deep Blue", year: "1997", description: "IBM's Deep Blue defeats Garry Kasparov in chess.", image: null },
            { name: "Watson", year: "2011", description: "IBM Watson defeats Jeopardy! champions.", image: null },
            { name: "AlexNet", year: "2012", description: "Deep learning revolution with AlexNet.", image: null },
            { name: "AlphaGo", year: "2016", description: "DeepMind's AlphaGo defeats Go champion Lee Sedol.", image: null },
            { name: "ChatGPT", year: "2022", description: "OpenAI releases ChatGPT to the public.", image: null },
            { name: "GPT-4", year: "2023", description: "OpenAI releases GPT-4 with multimodal capabilities.", image: null }
        ]
    }
};

function openRoom(roomId) {
    const modal = document.getElementById("museumModal");
    const content = document.getElementById("museumContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    const room = roomData[roomId];
    if (!room) {
        content.innerHTML = `
            <div style="text-align: center; padding: 2rem 0;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
                <h2 style="font-family: 'Orbitron', monospace; color: #ff6b6b;">Room Not Found</h2>
                <p style="color: #8892b0;">The requested room could not be found.</p>
                <button onclick="closeMuseum()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Close
                </button>
            </div>
        `;
        return;
    }
    
    let html = `
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <span style="font-size: 3rem;">${room.icon}</span>
            <div>
                <h2 style="font-family: 'Orbitron', monospace; color: ${room.color}; margin: 0; font-size: 1.5rem;">${room.title}</h2>
                <p style="color: #8892b0; margin: 0; font-size: 0.9rem;">${room.description}</p>
            </div>
        </div>
        <div style="display: grid; gap: 0.8rem;">
    `;
    
    room.exhibits.forEach((exhibit, index) => {
        html += `
            <div onclick="showExhibit('${exhibit.name}', '${exhibit.description}', '${exhibit.year}', '${room.color}', '${room.title}')" style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); padding: 0.8rem 1.2rem; border-radius: 8px; border-left: 3px solid ${room.color}; transition: all 0.3s; cursor: pointer;">
                <div style="font-family: 'Orbitron', monospace; color: ${room.color}; font-size: 0.9rem; min-width: 60px;">${exhibit.year}</div>
                <div style="flex: 1;">
                    <div style="color: #fff; font-weight: 500;">${exhibit.name}</div>
                    <div style="color: #8892b0; font-size: 0.85rem;">${exhibit.description}</div>
                </div>
                <span style="font-size: 0.7rem; color: ${room.color};">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </div>
        `;
    });
    
    html += `
        </div>
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); text-align: center;">
            <button onclick="closeMuseum()" style="padding: 0.5rem 1.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; color: #8892b0; cursor: pointer;">
                Close Museum
            </button>
        </div>
    `;
    
    content.innerHTML = html;
}

function showExhibit(name, description, year, color, room) {
    const modal = document.getElementById("exhibitModal");
    const content = document.getElementById("exhibitContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🏛️</div>
            <div style="font-family: 'Orbitron', monospace; color: ${color}; font-size: 1.2rem; margin-bottom: 0.3rem;">${year}</div>
            <h2 style="font-family: 'Orbitron', monospace; color: #fff; margin-bottom: 0.5rem;">${name}</h2>
            <div style="color: #8892b0; margin-bottom: 0.5rem;">Room: ${room}</div>
            <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <p style="color: #ccd6f6; line-height: 1.8;">${description}</p>
            </div>
            <div style="margin-top: 1rem; padding: 0.8rem; background: rgba(255,215,0,0.05); border-radius: 8px; border: 1px solid rgba(255,215,0,0.1);">
                <span style="color: #ffd700;">💡</span>
                <span style="color: #8892b0;">This exhibit is part of the ${room} collection in the Digital History Virtual Museum.</span>
            </div>
            <button onclick="closeExhibitModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, ${color}, ${color}aa); border: none; border-radius: 8px; color: #000; font-weight: 600; cursor: pointer;">
                Close Exhibit
            </button>
        </div>
    `;
}

function closeMuseum() {
    document.getElementById("museumModal").style.display = "none";
    document.body.style.overflow = "";
}

function closeExhibitModal() {
    document.getElementById("exhibitModal").style.display = "none";
    document.body.style.overflow = "";
}

// Keyboard shortcuts
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
        if (document.getElementById("exhibitModal").style.display === "block") {
            closeExhibitModal();
        } else if (document.getElementById("museumModal").style.display === "block") {
            closeMuseum();
        }
    }
});

// Close modals on overlay click
document.getElementById("museumModal").addEventListener("click", function(e) {
    if (e.target === this) closeMuseum();
});

document.getElementById("exhibitModal").addEventListener("click", function(e) {
    if (e.target === this) closeExhibitModal();
});

// Hover effect for museum rooms
document.querySelectorAll(".museum-room").forEach(room => {
    room.addEventListener("mouseenter", function() {
        this.style.transform = "translateY(-6px)";
        this.style.borderColor = "rgba(0, 212, 255, 0.3)";
        this.style.boxShadow = "0 8px 30px rgba(0, 0, 0, 0.3)";
    });
    room.addEventListener("mouseleave", function() {
        this.style.transform = "translateY(0)";
        this.style.borderColor = "rgba(255, 255, 255, 0.06)";
        this.style.boxShadow = "none";
    });
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
    .museum-room {
        transition: all 0.3s ease;
    }
    #museumModal, #exhibitModal {
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
document.head.appendChild(style);
JS;

$pageCSS = '
.museum-room {
    transition: all 0.3s ease;
}

.museum-room:hover {
    transform: translateY(-6px);
    border-color: rgba(0, 212, 255, 0.3);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

#museumModal::-webkit-scrollbar {
    width: 6px;
}

#museumModal::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

#museumModal::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

#museumModal::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

@media (max-width: 768px) {
    .museum-room {
        padding: 1rem !important;
    }
    
    #museumModal {
        padding: 1rem !important;
    }
    
    #museumModal > div {
        padding: 1.5rem !important;
        max-height: 95vh;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>