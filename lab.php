<?php
// lab.php
// Digital History - Interactive Lab (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'Digital Lab';
$pageDescription = 'Experiment with interactive technology simulations and learn how things work.';

$experiments = [
    [
        'id' => 'dns-simulator',
        'title' => 'DNS Simulator',
        'icon' => '🌐',
        'description' => 'See how domain names are resolved to IP addresses step by step.',
        'color' => '#00d4ff',
        'category' => 'Networking',
        'difficulty' => 'Beginner',
        'popular' => true
    ],
    [
        'id' => 'http-simulator',
        'title' => 'HTTP Simulator',
        'icon' => '📡',
        'description' => 'Watch HTTP requests and responses in action.',
        'color' => '#7b2ffc',
        'category' => 'Web',
        'difficulty' => 'Beginner',
        'popular' => true
    ],
    [
        'id' => 'binary-converter',
        'title' => 'Binary Converter',
        'icon' => '💻',
        'description' => 'Convert text to binary, decimal, and hexadecimal.',
        'color' => '#00ff88',
        'category' => 'Programming',
        'difficulty' => 'Beginner',
        'popular' => true
    ],
    [
        'id' => 'ascii-explorer',
        'title' => 'ASCII Explorer',
        'icon' => '🔤',
        'description' => 'Explore the ASCII character set and their codes.',
        'color' => '#ff6b6b',
        'category' => 'Programming',
        'difficulty' => 'Beginner',
        'popular' => false
    ],
    [
        'id' => 'encryption-visualizer',
        'title' => 'Encryption Visualizer',
        'icon' => '🔐',
        'description' => 'See how encryption transforms data step by step.',
        'color' => '#ffa500',
        'category' => 'Security',
        'difficulty' => 'Intermediate',
        'popular' => false
    ],
    [
        'id' => 'cpu-simulator',
        'title' => 'CPU Simulator',
        'icon' => '⚡',
        'description' => 'Visualize how a CPU executes instructions.',
        'color' => '#ff0064',
        'category' => 'Hardware',
        'difficulty' => 'Advanced',
        'popular' => false
    ],
    [
        'id' => 'packet-simulator',
        'title' => 'Packet Simulator',
        'icon' => '📦',
        'description' => 'Watch data packets travel across a network.',
        'color' => '#00d4ff',
        'category' => 'Networking',
        'difficulty' => 'Intermediate',
        'popular' => false
    ],
    [
        'id' => 'ip-calculator',
        'title' => 'IP Calculator',
        'icon' => '📊',
        'description' => 'Calculate IP ranges, subnets, and network information.',
        'color' => '#7b2ffc',
        'category' => 'Networking',
        'difficulty' => 'Intermediate',
        'popular' => false
    ],
    [
        'id' => 'subnet-calculator',
        'title' => 'Subnet Calculator',
        'icon' => '🔢',
        'description' => 'Calculate subnet masks and network ranges.',
        'color' => '#00ff88',
        'category' => 'Networking',
        'difficulty' => 'Advanced',
        'popular' => false
    ],
    [
        'id' => 'base64-encoder',
        'title' => 'Base64 Encoder',
        'icon' => '🔤',
        'description' => 'Encode and decode text using Base64.',
        'color' => '#ffa500',
        'category' => 'Programming',
        'difficulty' => 'Beginner',
        'popular' => false
    ]
];

trackPageView('lab');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            <?php echo t('lab.title'); ?>
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;"><?php echo t('lab.subtitle'); ?></p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($experiments); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Experiments</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;">
                <?php 
                $categories = array_unique(array_column($experiments, 'category'));
                echo count($categories);
                ?>
            </div>
            <div style="color: #8892b0; font-size: 0.85rem;">Categories</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">
                <?php 
                $popular = array_filter($experiments, function($e) { return $e['popular'] ?? false; });
                echo count($popular);
                ?>
            </div>
            <div style="color: #8892b0; font-size: 0.85rem;">Popular Labs</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">
                <?php 
                $levels = array_unique(array_column($experiments, 'difficulty'));
                echo count($levels);
                ?>
            </div>
            <div style="color: #8892b0; font-size: 0.85rem;">Difficulty Levels</div>
        </div>
    </div>
</section>

<!-- Filter Bar -->
<section class="section" style="padding-top: 0;">
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; max-width: 900px; margin: 0 auto;">
        <button onclick="filterExperiments('all')" class="filter-btn active" data-filter="all" style="padding: 0.4rem 1.2rem; background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); border-radius: 20px; color: #00d4ff; cursor: pointer; transition: all 0.3s; font-size: 0.85rem;">
            All
        </button>
        <?php foreach ($categories as $cat): ?>
        <button onclick="filterExperiments('<?php echo $cat; ?>')" class="filter-btn" data-filter="<?php echo $cat; ?>" style="padding: 0.4rem 1.2rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; color: #8892b0; cursor: pointer; transition: all 0.3s; font-size: 0.85rem;">
            <?php echo $cat; ?>
        </button>
        <?php endforeach; ?>
    </div>
</section>

<!-- Experiment Cards -->
<section class="section" style="padding-top: 1rem;">
    <div class="card-grid" id="experimentGrid">
        <?php foreach ($experiments as $exp): ?>
        <div class="card animate-on-scroll experiment-card" style="cursor: pointer; text-align: center;" onclick="openExperiment('<?php echo $exp['id']; ?>')" data-category="<?php echo $exp['category']; ?>">
            <?php if ($exp['popular'] ?? false): ?>
            <div style="position: absolute; top: 0.5rem; right: 0.5rem; font-size: 0.6rem; background: rgba(255,215,0,0.2); color: #ffd700; padding: 0.15rem 0.6rem; border-radius: 12px; border: 1px solid rgba(255,215,0,0.2);">
                ⭐ Popular
            </div>
            <?php endif; ?>
            <div style="font-size: 3rem; margin-bottom: 0.5rem; margin-top: 0.5rem;"><?php echo $exp['icon']; ?></div>
            <div class="card-title"><?php echo escape($exp['title']); ?></div>
            <div class="card-description"><?php echo escape($exp['description']); ?></div>
            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.6rem; background: rgba(255,255,255,0.05); padding: 0.15rem 0.6rem; border-radius: 12px; color: #8892b0;"><?php echo $exp['category']; ?></span>
                <span style="font-size: 0.6rem; background: <?php 
                    if ($exp['difficulty'] === 'Beginner') echo 'rgba(0,255,136,0.1)';
                    elseif ($exp['difficulty'] === 'Intermediate') echo 'rgba(255,165,0,0.1)';
                    else echo 'rgba(255,0,100,0.1)';
                ?>; color: <?php 
                    if ($exp['difficulty'] === 'Beginner') echo '#00ff88';
                    elseif ($exp['difficulty'] === 'Intermediate') echo '#ffa500';
                    else echo '#ff0064';
                ?>; padding: 0.15rem 0.6rem; border-radius: 12px;">
                    <?php echo $exp['difficulty']; ?>
                </span>
            </div>
            <button style="margin-top: 0.8rem; padding: 0.4rem 1.2rem; background: <?php echo $exp['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Launch Experiment
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Lab Statistics -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 3rem 2rem;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h3 style="color: #00d4ff; margin-bottom: 1rem; font-size: 1.5rem;">🧪 About the Digital Lab</h3>
        <p style="color: #8892b0; line-height: 1.8; font-size: 1.05rem;">
            The Digital Lab is an interactive learning environment where you can experiment with 
            technology simulations. Each experiment is designed to help you understand complex 
            concepts through hands-on interaction and visual feedback.
        </p>
        <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap;">
            <div style="background: rgba(255,255,255,0.03); padding: 0.8rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 1.2rem; font-weight: 700; color: #00d4ff;">📚</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Learn by Doing</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 0.8rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 1.2rem; font-weight: 700; color: #7b2ffc;">🎯</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Interactive Simulations</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 0.8rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 1.2rem; font-weight: 700; color: #00ff88;">🧠</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Visual Learning</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 0.8rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 1.2rem; font-weight: 700; color: #ffa500;">⚡</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Real-time Feedback</div>
            </div>
        </div>
    </div>
</section>

<!-- Experiment Modal -->
<div id="experimentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 5000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 900px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative; max-height: 90vh; overflow-y: auto;">
        <button onclick="closeExperiment()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer; z-index: 10;">
            <i class="fas fa-times"></i>
        </button>
        <div id="experimentContent">
            <div style="text-align: center; padding: 2rem 0;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🧪</div>
                <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 1rem;">Digital Lab</h2>
                <p style="color: #8892b0;">Select an experiment from the cards above to launch it.</p>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Filter experiments
function filterExperiments(category) {
    const cards = document.querySelectorAll('.experiment-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    buttons.forEach(btn => {
        btn.classList.remove('active');
        btn.style.background = 'rgba(255,255,255,0.03)';
        btn.style.borderColor = 'rgba(255,255,255,0.06)';
        btn.style.color = '#8892b0';
    });
    
    const activeBtn = document.querySelector(`.filter-btn[data-filter="${category}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.style.background = 'rgba(0,212,255,0.1)';
        activeBtn.style.borderColor = 'rgba(0,212,255,0.2)';
        activeBtn.style.color = '#00d4ff';
    }
    
    cards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
}

// Open experiment
function openExperiment(id) {
    const modal = document.getElementById("experimentModal");
    const content = document.getElementById("experimentContent");
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
    
    // Load experiment content based on ID
    let html = "";
    
    switch(id) {
        case "dns-simulator":
            html = getDNSSimulator();
            break;
        case "http-simulator":
            html = getHTTPSimulator();
            break;
        case "binary-converter":
            html = getBinaryConverter();
            break;
        case "ascii-explorer":
            html = getASCIIExplorer();
            break;
        case "encryption-visualizer":
            html = getEncryptionVisualizer();
            break;
        case "cpu-simulator":
            html = getCPUSimulator();
            break;
        case "packet-simulator":
            html = getPacketSimulator();
            break;
        case "ip-calculator":
            html = getIPCalculator();
            break;
        case "subnet-calculator":
            html = getSubnetCalculator();
            break;
        case "base64-encoder":
            html = getBase64Encoder();
            break;
        default:
            html = `<h2 style="font-family: 'Orbitron', monospace; color: #00d4ff;">Experiment: ${id}</h2>
                    <p style="color: #8892b0;">This experiment is coming soon. Check back later!</p>`;
    }
    
    content.innerHTML = html;
}

function closeExperiment() {
    document.getElementById("experimentModal").style.display = "none";
    document.body.style.overflow = "";
}

// Close modal on escape key
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeExperiment();
});

// Close modal on overlay click
document.getElementById("experimentModal").addEventListener("click", function(e) {
    if (e.target === this) closeExperiment();
});

// Get DNS Simulator
function getDNSSimulator() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 0.5rem;">🌐 DNS Simulator</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">See how domain names are resolved to IP addresses step by step.</p>
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <input type="text" id="dnsDomain" value="google.com" style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="runDNS()" style="padding: 0.8rem 1.5rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Resolve
            </button>
        </div>
        <div id="dnsResult" style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); min-height: 100px;">
            <p style="color: #8892b0;">Enter a domain name and click Resolve to see the DNS lookup process.</p>
        </div>
    `;
}

// Get HTTP Simulator
function getHTTPSimulator() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #7b2ffc; margin-bottom: 0.5rem;">📡 HTTP Simulator</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Watch HTTP requests and responses in action.</p>
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <input type="text" id="httpUrl" value="https://example.com/api/data" style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <select id="httpMethod" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </select>
            <button onclick="runHTTP()" style="padding: 0.8rem 1.5rem; background: linear-gradient(135deg, #7b2ffc, #00d4ff); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Send
            </button>
        </div>
        <div id="httpResult" style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); min-height: 100px;">
            <p style="color: #8892b0;">Enter a URL and method to simulate an HTTP request.</p>
        </div>
    `;
}

// Get Binary Converter
function getBinaryConverter() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #00ff88; margin-bottom: 0.5rem;">💻 Binary Converter</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Convert text to binary, decimal, and hexadecimal.</p>
        <div style="margin-bottom: 1.5rem;">
            <input type="text" id="binaryInput" placeholder="Enter text to convert..." style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;" oninput="convertBinary()">
        </div>
        <div id="binaryResult" style="display: grid; gap: 1rem;">
            <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <span style="color: #8892b0; font-size: 0.85rem;">ASCII</span>
                <div id="binaryASCII" style="font-size: 1.2rem; font-weight: 500; color: #fff; word-break: break-all;">Enter text to see conversion</div>
            </div>
            <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <span style="color: #8892b0; font-size: 0.85rem;">Binary</span>
                <div id="binaryBinary" style="font-size: 1.2rem; font-weight: 500; color: #00ff88; word-break: break-all;">Enter text to see conversion</div>
            </div>
            <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <span style="color: #8892b0; font-size: 0.85rem;">Hexadecimal</span>
                <div id="binaryHex" style="font-size: 1.2rem; font-weight: 500; color: #00d4ff; word-break: break-all;">Enter text to see conversion</div>
            </div>
            <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <span style="color: #8892b0; font-size: 0.85rem;">Decimal</span>
                <div id="binaryDecimal" style="font-size: 1.2rem; font-weight: 500; color: #7b2ffc; word-break: break-all;">Enter text to see conversion</div>
            </div>
        </div>
    `;
}

// Get ASCII Explorer
function getASCIIExplorer() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #ff6b6b; margin-bottom: 0.5rem;">🔤 ASCII Explorer</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Explore the ASCII character set and their codes.</p>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem; max-height: 500px; overflow-y: auto; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
            ${Array.from({length: 128}, (_, i) => {
                const char = String.fromCharCode(i);
                const isPrintable = i >= 32 && i <= 126;
                return `
                    <div style="text-align: center; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 4px; ${isPrintable ? '' : 'opacity: 0.3;'}">
                        <div style="font-size: 1.5rem; color: ${isPrintable ? '#fff' : '#8892b0'};">${isPrintable ? char : '·'}</div>
                        <div style="font-size: 0.6rem; color: #8892b0;">${i}</div>
                    </div>
                `;
            }).join('')}
        </div>
    `;
}

// Get Encryption Visualizer
function getEncryptionVisualizer() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #ffa500; margin-bottom: 0.5rem;">🔐 Encryption Visualizer</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">See how encryption transforms data step by step.</p>
        <div style="margin-bottom: 1.5rem;">
            <input type="text" id="encryptInput" placeholder="Enter text to encrypt..." style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;" oninput="runEncryption()">
            <button onclick="runEncryption()" style="margin-top: 0.5rem; padding: 0.6rem 1.5rem; background: #ffa500; border: none; border-radius: 8px; color: #000; cursor: pointer; font-weight: 600;">
                Encrypt
            </button>
        </div>
        <div id="encryptResult" style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); min-height: 100px;">
            <p style="color: #8892b0;">Enter text and click Encrypt to see the encryption process.</p>
        </div>
    `;
}

// Get CPU Simulator
function getCPUSimulator() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #ff0064; margin-bottom: 0.5rem;">⚡ CPU Simulator</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Visualize how a CPU executes instructions.</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <h4 style="color: #ff0064; margin: 0 0 0.5rem 0;">Registers</h4>
                <div id="registersDisplay" style="font-family: monospace; color: #00ff88; font-size: 0.9rem;">
                    AX: 0000<br>
                    BX: 0000<br>
                    CX: 0000<br>
                    DX: 0000
                </div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                <h4 style="color: #ff0064; margin: 0 0 0.5rem 0;">Program Counter</h4>
                <div id="pcDisplay" style="font-family: monospace; color: #00d4ff; font-size: 1.5rem; text-align: center;">0000</div>
            </div>
        </div>
        <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
            <h4 style="color: #ff0064; margin: 0 0 0.5rem 0;">Memory</h4>
            <div id="memoryDisplay" style="font-family: monospace; color: #ccd6f6; font-size: 0.8rem; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.3rem;">
                ${Array.from({length: 16}, (_, i) => `
                    <div style="background: rgba(255,255,255,0.03); padding: 0.3rem; border-radius: 4px; text-align: center;">
                        <span style="color: #8892b0; font-size: 0.6rem;">${i.toString(16).toUpperCase()}</span><br>
                        <span id="mem-${i}">00</span>
                    </div>
                `).join('')}
            </div>
        </div>
        <div style="margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button onclick="stepCPU()" style="padding: 0.6rem 1.5rem; background: #ff0064; border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Step
            </button>
            <button onclick="resetCPU()" style="padding: 0.6rem 1.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                Reset
            </button>
        </div>
    `;
}

// Get Packet Simulator
function getPacketSimulator() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #00d4ff; margin-bottom: 0.5rem;">📦 Packet Simulator</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Watch data packets travel across a network.</p>
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <input type="text" id="packetData" value="Hello, World!" style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="sendPacket()" style="padding: 0.8rem 1.5rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Send Packet
            </button>
        </div>
        <div id="packetResult" style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); min-height: 150px;">
            <p style="color: #8892b0;">Enter data and click Send Packet to simulate network transmission.</p>
        </div>
    `;
}

// Get IP Calculator
function getIPCalculator() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #7b2ffc; margin-bottom: 0.5rem;">📊 IP Calculator</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Calculate IP ranges, subnets, and network information.</p>
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <input type="text" id="ipInput" value="192.168.1.0/24" style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;">
            <button onclick="calculateIP()" style="padding: 0.8rem 1.5rem; background: linear-gradient(135deg, #7b2ffc, #00d4ff); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Calculate
            </button>
        </div>
        <div id="ipResult" style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); min-height: 100px;">
            <p style="color: #8892b0;">Enter an IP address with CIDR notation (e.g., 192.168.1.0/24) and click Calculate.</p>
        </div>
    `;
}

// Get Subnet Calculator
function getSubnetCalculator() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #00ff88; margin-bottom: 0.5rem;">🔢 Subnet Calculator</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Calculate subnet masks and network ranges.</p>
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <input type="text" id="subnetInput" value="24" style="flex: 1; min-width: 200px; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem;" placeholder="Enter CIDR (e.g., 24)">
            <button onclick="calculateSubnet()" style="padding: 0.8rem 1.5rem; background: linear-gradient(135deg, #00ff88, #00d4ff); border: none; border-radius: 8px; color: #000; cursor: pointer; font-weight: 600;">
                Calculate
            </button>
        </div>
        <div id="subnetResult" style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); min-height: 100px;">
            <p style="color: #8892b0;">Enter a CIDR number (e.g., 24) and click Calculate.</p>
        </div>
    `;
}

// Get Base64 Encoder
function getBase64Encoder() {
    return `
        <h2 style="font-family: 'Orbitron', monospace; color: #ffa500; margin-bottom: 0.5rem;">🔤 Base64 Encoder</h2>
        <p style="color: #8892b0; margin-bottom: 1.5rem;">Encode and decode text using Base64.</p>
        <div style="margin-bottom: 1.5rem;">
            <textarea id="base64Input" rows="3" placeholder="Enter text to encode..." style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 1rem; resize: vertical; font-family: inherit;">Hello, World!</textarea>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
            <button onclick="encodeBase64()" style="padding: 0.6rem 1.5rem; background: #ffa500; border: none; border-radius: 8px; color: #000; cursor: pointer; font-weight: 600;">
                Encode
            </button>
            <button onclick="decodeBase64()" style="padding: 0.6rem 1.5rem; background: #00d4ff; border: none; border-radius: 8px; color: #000; cursor: pointer; font-weight: 600;">
                Decode
            </button>
            <button onclick="clearBase64()" style="padding: 0.6rem 1.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                Clear
            </button>
        </div>
        <div id="base64Result" style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); min-height: 100px;">
            <p style="color: #8892b0;">Enter text and click Encode or Decode.</p>
        </div>
    `;
}

// DNS Simulator function
function runDNS() {
    const domain = document.getElementById("dnsDomain").value || "google.com";
    const resultDiv = document.getElementById("dnsResult");
    
    const steps = [
        "Browser checks DNS cache...",
        "Query sent to DNS resolver...",
        "Resolver queries Root Server...",
        "Root Server redirects to TLD Server...",
        "TLD Server redirects to Authoritative DNS...",
        "Authoritative DNS returns IP address...",
        "DNS lookup complete!"
    ];
    
    const ips = ["142.250.185.78", "172.217.164.78", "216.58.194.78", "142.250.186.78"];
    const randomIP = ips[Math.floor(Math.random() * ips.length)];
    
    let currentStep = 0;
    resultDiv.innerHTML = "";
    
    const interval = setInterval(() => {
        if (currentStep < steps.length) {
            const step = document.createElement("div");
            step.style.cssText = "padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;";
            step.innerHTML = `
                <span style="color: #00d4ff; font-size: 0.8rem;">${currentStep + 1}.</span>
                <span style="color: #ccd6f6;">${steps[currentStep]}</span>
                ${currentStep === steps.length - 1 ? '<span style="margin-left: auto; color: #00ff88; font-weight: 600;">✓</span>' : ''}
            `;
            resultDiv.appendChild(step);
            currentStep++;
        } else {
            clearInterval(interval);
            const result = document.createElement("div");
            result.style.cssText = "margin-top: 1rem; padding: 1rem; background: rgba(0, 212, 255, 0.05); border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 8px;";
            result.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="color: #8892b0;">Resolved IP for <strong style="color: #fff;">${domain}</strong></span>
                    <span style="font-family: 'Orbitron', monospace; color: #00d4ff; font-size: 1.2rem;">${randomIP}</span>
                </div>
            `;
            resultDiv.appendChild(result);
        }
    }, 400);
}

// HTTP Simulator function
function runHTTP() {
    const url = document.getElementById("httpUrl").value || "https://example.com/api/data";
    const method = document.getElementById("httpMethod").value || "GET";
    const resultDiv = document.getElementById("httpResult");
    
    resultDiv.innerHTML = `
        <div style="margin-bottom: 1rem;">
            <div style="color: #00d4ff; font-weight: 600; margin-bottom: 0.3rem;">📤 Request</div>
            <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 6px; font-family: monospace; font-size: 0.9rem; color: #ccd6f6;">
                ${method} ${url} HTTP/1.1<br>
                Host: ${new URL(url).hostname}<br>
                User-Agent: Digital-History-Simulator/1.0<br>
                Accept: */*<br>
                Connection: keep-alive
            </div>
        </div>
        <div>
            <div style="color: #00ff88; font-weight: 600; margin-bottom: 0.3rem;">📥 Response</div>
            <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 6px; font-family: monospace; font-size: 0.9rem; color: #ccd6f6;">
                HTTP/1.1 200 OK<br>
                Content-Type: application/json<br>
                Content-Length: 256<br>
                <br>
                {<br>
                &nbsp;&nbsp;"status": "success",<br>
                &nbsp;&nbsp;"data": {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;"message": "Hello from Digital History!",<br>
                &nbsp;&nbsp;&nbsp;&nbsp;"timestamp": "${new Date().toISOString()}"<br>
                &nbsp;&nbsp;}<br>
                }
            </div>
        </div>
    `;
}

// Binary Converter function
function convertBinary() {
    const input = document.getElementById("binaryInput").value || "";
    
    if (!input) {
        document.getElementById("binaryASCII").textContent = "Enter text to see conversion";
        document.getElementById("binaryBinary").textContent = "Enter text to see conversion";
        document.getElementById("binaryHex").textContent = "Enter text to see conversion";
        document.getElementById("binaryDecimal").textContent = "Enter text to see conversion";
        return;
    }
    
    let ascii = "";
    let binary = "";
    let hex = "";
    let decimal = "";
    
    for (let i = 0; i < input.length; i++) {
        const code = input.charCodeAt(i);
        ascii += code + " ";
        binary += code.toString(2).padStart(8, "0") + " ";
        hex += code.toString(16).toUpperCase().padStart(2, "0") + " ";
        decimal += code + " ";
    }
    
    document.getElementById("binaryASCII").textContent = ascii.trim();
    document.getElementById("binaryBinary").textContent = binary.trim();
    document.getElementById("binaryHex").textContent = hex.trim();
    document.getElementById("binaryDecimal").textContent = decimal.trim();
}

// Encryption Visualizer function
function runEncryption() {
    const input = document.getElementById("encryptInput").value || "";
    const resultDiv = document.getElementById("encryptResult");
    
    if (!input) {
        resultDiv.innerHTML = '<p style="color: #8892b0;">Enter text to encrypt.</p>';
        return;
    }
    
    // Simple Caesar cipher simulation
    const shift = 3;
    let encrypted = "";
    for (let i = 0; i < input.length; i++) {
        const char = input[i];
        const code = char.charCodeAt(0);
        if (code >= 65 && code <= 90) {
            encrypted += String.fromCharCode(((code - 65 + shift) % 26) + 65);
        } else if (code >= 97 && code <= 122) {
            encrypted += String.fromCharCode(((code - 97 + shift) % 26) + 97);
        } else {
            encrypted += char;
        }
    }
    
    resultDiv.innerHTML = `
        <div style="margin-bottom: 0.8rem;">
            <span style="color: #8892b0; font-size: 0.85rem;">Original:</span>
            <span style="color: #fff; font-family: monospace;">${input}</span>
        </div>
        <div style="margin-bottom: 0.8rem;">
            <span style="color: #8892b0; font-size: 0.85rem;">Shift:</span>
            <span style="color: #ffa500;">${shift}</span>
        </div>
        <div>
            <span style="color: #8892b0; font-size: 0.85rem;">Encrypted:</span>
            <span style="color: #00ff88; font-family: monospace; font-size: 1.1rem;">${encrypted}</span>
        </div>
    `;
}

// CPU Simulator functions
let cpuState = {
    registers: { AX: 0, BX: 0, CX: 0, DX: 0 },
    pc: 0,
    memory: Array(16).fill(0),
    program: [0x01, 0x0A, 0x02, 0x05, 0x03, 0x00, 0x04, 0x00]
};

function resetCPU() {
    cpuState.registers = { AX: 0, BX: 0, CX: 0, DX: 0 };
    cpuState.pc = 0;
    cpuState.memory = Array(16).fill(0);
    updateCPUIDisplay();
}

function stepCPU() {
    const pc = cpuState.pc;
    if (pc >= cpuState.program.length) {
        alert("Program execution complete!");
        return;
    }
    
    const instruction = cpuState.program[pc];
    cpuState.pc++;
    
    // Simple instruction simulation
    if (instruction === 0x01) {
        // LOAD AX, value
        const value = cpuState.program[cpuState.pc];
        cpuState.pc++;
        cpuState.registers.AX = value;
        cpuState.memory[0] = value;
    } else if (instruction === 0x02) {
        // LOAD BX, value
        const value = cpuState.program[cpuState.pc];
        cpuState.pc++;
        cpuState.registers.BX = value;
        cpuState.memory[1] = value;
    } else if (instruction === 0x03) {
        // ADD AX, BX
        cpuState.registers.AX += cpuState.registers.BX;
        cpuState.memory[2] = cpuState.registers.AX;
    } else if (instruction === 0x04) {
        // STORE AX, address
        const address = cpuState.program[cpuState.pc];
        cpuState.pc++;
        cpuState.memory[address] = cpuState.registers.AX;
    }
    
    updateCPUIDisplay();
}

function updateCPUIDisplay() {
    document.getElementById("registersDisplay").innerHTML = `
        AX: ${cpuState.registers.AX.toString(16).toUpperCase().padStart(4, '0')}<br>
        BX: ${cpuState.registers.BX.toString(16).toUpperCase().padStart(4, '0')}<br>
        CX: ${cpuState.registers.CX.toString(16).toUpperCase().padStart(4, '0')}<br>
        DX: ${cpuState.registers.DX.toString(16).toUpperCase().padStart(4, '0')}
    `;
    
    document.getElementById("pcDisplay").textContent = cpuState.pc.toString(16).toUpperCase().padStart(4, '0');
    
    for (let i = 0; i < 16; i++) {
        const memElement = document.getElementById(`mem-${i}`);
        if (memElement) {
            memElement.textContent = cpuState.memory[i].toString(16).toUpperCase().padStart(2, '0');
        }
    }
}

// Packet Simulator function
function sendPacket() {
    const data = document.getElementById("packetData").value || "Hello, World!";
    const resultDiv = document.getElementById("packetResult");
    
    const nodes = ["Computer", "Router", "Switch", "Server"];
    let currentStep = 0;
    resultDiv.innerHTML = "";
    
    const interval = setInterval(() => {
        if (currentStep < nodes.length) {
            const step = document.createElement("div");
            step.style.cssText = "padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;";
            const icon = ["🖥️", "📡", "🔀", "🗄️"][currentStep];
            step.innerHTML = `
                <span style="font-size: 1.2rem;">${icon}</span>
                <span style="color: #ccd6f6;">Packet arriving at <strong style="color: #00d4ff;">${nodes[currentStep]}</strong></span>
                <span style="margin-left: auto; color: #8892b0; font-size: 0.7rem;">→</span>
            `;
            resultDiv.appendChild(step);
            currentStep++;
        } else {
            clearInterval(interval);
            const result = document.createElement("div");
            result.style.cssText = "margin-top: 1rem; padding: 1rem; background: rgba(0, 212, 255, 0.05); border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 8px;";
            result.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="color: #8892b0;">📦 Packet Delivered</span>
                    <span style="color: #00ff88; font-family: monospace;">${data}</span>
                </div>
            `;
            resultDiv.appendChild(result);
        }
    }, 500);
}

// IP Calculator function
function calculateIP() {
    const input = document.getElementById("ipInput").value || "192.168.1.0/24";
    const resultDiv = document.getElementById("ipResult");
    
    try {
        const [ip, cidr] = input.split('/');
        const cidrNum = parseInt(cidr);
        if (isNaN(cidrNum) || cidrNum < 0 || cidrNum > 32) {
            throw new Error("Invalid CIDR");
        }
        
        const octets = ip.split('.').map(Number);
        if (octets.length !== 4 || octets.some(isNaN)) {
            throw new Error("Invalid IP");
        }
        
        const ipInt = (octets[0] << 24) + (octets[1] << 16) + (octets[2] << 8) + octets[3];
        const mask = ~((1 << (32 - cidrNum)) - 1);
        const network = ipInt & mask;
        const broadcast = network | ~mask;
        
        const firstHost = network + 1;
        const lastHost = broadcast - 1;
        const totalHosts = Math.pow(2, 32 - cidrNum) - 2;
        
        resultDiv.innerHTML = `
            <div style="display: grid; gap: 0.5rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                    <span style="color: #8892b0;">Network Address</span>
                    <span style="color: #00d4ff; font-family: monospace;">${((network >> 24) & 255)}.${((network >> 16) & 255)}.${((network >> 8) & 255)}.${(network & 255)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                    <span style="color: #8892b0;">Broadcast Address</span>
                    <span style="color: #ff6b6b; font-family: monospace;">${((broadcast >> 24) & 255)}.${((broadcast >> 16) & 255)}.${((broadcast >> 8) & 255)}.${(broadcast & 255)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                    <span style="color: #8892b0;">First Host</span>
                    <span style="color: #00ff88; font-family: monospace;">${((firstHost >> 24) & 255)}.${((firstHost >> 16) & 255)}.${((firstHost >> 8) & 255)}.${(firstHost & 255)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                    <span style="color: #8892b0;">Last Host</span>
                    <span style="color: #00ff88; font-family: monospace;">${((lastHost >> 24) & 255)}.${((lastHost >> 16) & 255)}.${((lastHost >> 8) & 255)}.${(lastHost & 255)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                    <span style="color: #8892b0;">Total Hosts</span>
                    <span style="color: #7b2ffc; font-weight: 600;">${totalHosts.toLocaleString()}</span>
                </div>
            </div>
        `;
    } catch (e) {
        resultDiv.innerHTML = `<p style="color: #ff6b6b;">❌ Error: ${e.message}</p>`;
    }
}

// Subnet Calculator function
function calculateSubnet() {
    const input = document.getElementById("subnetInput").value || "24";
    const resultDiv = document.getElementById("subnetResult");
    
    try {
        const cidr = parseInt(input);
        if (isNaN(cidr) || cidr < 0 || cidr > 32) {
            throw new Error("Invalid CIDR (must be 0-32)");
        }
        
        const mask = ~((1 << (32 - cidr)) - 1);
        const maskParts = [
            (mask >> 24) & 255,
            (mask >> 16) & 255,
            (mask >> 8) & 255,
            mask & 255
        ];
        
        resultDiv.innerHTML = `
            <div style="display: grid; gap: 0.5rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                    <span style="color: #8892b0;">CIDR</span>
                    <span style="color: #00d4ff; font-family: monospace;">/${cidr}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                    <span style="color: #8892b0;">Subnet Mask</span>
                    <span style="color: #00ff88; font-family: monospace;">${maskParts.join('.')}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid rgba(255,255,255,0.03);">
                    <span style="color: #8892b0;">Binary Mask</span>
                    <span style="color: #7b2ffc; font-family: monospace;">${'1'.repeat(cidr)}${'0'.repeat(32 - cidr)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                    <span style="color: #8892b0;">Total Hosts</span>
                    <span style="color: #ffa500; font-weight: 600;">${(Math.pow(2, 32 - cidr) - 2).toLocaleString()}</span>
                </div>
            </div>
        `;
    } catch (e) {
        resultDiv.innerHTML = `<p style="color: #ff6b6b;">❌ Error: ${e.message}</p>`;
    }
}

// Base64 Encoder functions
function encodeBase64() {
    const input = document.getElementById("base64Input").value || "";
    const resultDiv = document.getElementById("base64Result");
    
    if (!input) {
        resultDiv.innerHTML = '<p style="color: #8892b0;">Please enter text to encode.</p>';
        return;
    }
    
    const encoded = btoa(unescape(encodeURIComponent(input)));
    resultDiv.innerHTML = `
        <div style="margin-bottom: 0.5rem;">
            <span style="color: #8892b0; font-size: 0.85rem;">Encoded (Base64):</span>
        </div>
        <div style="background: rgba(0,0,0,0.2); padding: 0.8rem; border-radius: 6px; font-family: monospace; color: #00ff88; word-break: break-all;">
            ${encoded}
        </div>
    `;
}

function decodeBase64() {
    const input = document.getElementById("base64Input").value || "";
    const resultDiv = document.getElementById("base64Result");
    
    if (!input) {
        resultDiv.innerHTML = '<p style="color: #8892b0;">Please enter Base64 text to decode.</p>';
        return;
    }
    
    try {
        const decoded = decodeURIComponent(escape(atob(input)));
        resultDiv.innerHTML = `
            <div style="margin-bottom: 0.5rem;">
                <span style="color: #8892b0; font-size: 0.85rem;">Decoded:</span>
            </div>
            <div style="background: rgba(0,0,0,0.2); padding: 0.8rem; border-radius: 6px; font-family: monospace; color: #00d4ff; word-break: break-all;">
                ${decoded}
            </div>
        `;
    } catch (e) {
        resultDiv.innerHTML = `<p style="color: #ff6b6b;">❌ Error: Invalid Base64 string</p>`;
    }
}

function clearBase64() {
    document.getElementById("base64Input").value = "";
    document.getElementById("base64Result").innerHTML = '<p style="color: #8892b0;">Enter text and click Encode or Decode.</p>';
}

// Make functions globally accessible
window.runDNS = runDNS;
window.runHTTP = runHTTP;
window.convertBinary = convertBinary;
window.runEncryption = runEncryption;
window.stepCPU = stepCPU;
window.resetCPU = resetCPU;
window.sendPacket = sendPacket;
window.calculateIP = calculateIP;
window.calculateSubnet = calculateSubnet;
window.encodeBase64 = encodeBase64;
window.decodeBase64 = decodeBase64;
window.clearBase64 = clearBase64;
window.openExperiment = openExperiment;
window.closeExperiment = closeExperiment;
window.filterExperiments = filterExperiments;
JS;

$pageCSS = '
.experiment-card {
    transition: all 0.3s ease;
    position: relative;
}

.experiment-card:hover {
    transform: translateY(-6px);
    border-color: rgba(0, 212, 255, 0.3);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.filter-btn {
    transition: all 0.3s ease;
}

.filter-btn:hover:not(.active) {
    background: rgba(255,255,255,0.05) !important;
    color: #ccd6f6 !important;
}

.filter-btn.active {
    background: rgba(0,212,255,0.1) !important;
    border-color: rgba(0,212,255,0.2) !important;
    color: #00d4ff !important;
}

#experimentModal {
    animation: fadeIn 0.3s ease;
}

#experimentModal > div::-webkit-scrollbar {
    width: 6px;
}

#experimentModal > div::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

#experimentModal > div::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

#experimentModal > div::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes glowPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@media (max-width: 768px) {
    #experimentModal {
        padding: 1rem !important;
    }
    
    #experimentModal > div {
        padding: 1.5rem !important;
        max-height: 95vh;
    }
    
    .experiment-card {
        padding: 1rem !important;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>