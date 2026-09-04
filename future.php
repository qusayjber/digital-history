<?php
// future.php
// Digital History - Future Technology (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'Future of Technology';
$pageDescription = 'Explore predictions for technology in 2030, 2040, 2050, and beyond.';

trackPageView('future');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064, #00ff88); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            🚀 The Future of Technology
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">Explore predictions for technology in 2030, 2040, 2050, and beyond.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;">4</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Future Eras</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;">12</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Future Technologies</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">100+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Years of Predictions</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">100%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Speculative</div>
        </div>
    </div>
</section>

<!-- Future Timeline Navigation -->
<section class="section" style="padding-top: 0;">
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; max-width: 800px; margin: 0 auto;">
        <?php
        $years = [
            ['year' => 2030, 'color' => '#00d4ff', 'icon' => '📱'],
            ['year' => 2040, 'color' => '#7b2ffc', 'icon' => '🧠'],
            ['year' => 2050, 'color' => '#00ff88', 'icon' => '🚀'],
            ['year' => 2100, 'color' => '#ff0064', 'icon' => '🌌']
        ];
        foreach ($years as $y):
        ?>
        <a href="#year-<?php echo $y['year']; ?>" style="padding: 0.8rem 2rem; background: rgba(255,255,255,0.03); border: 1px solid <?php echo $y['color']; ?>40; border-radius: 12px; color: <?php echo $y['color']; ?>; text-decoration: none; font-family: 'Orbitron', monospace; font-size: 1.2rem; transition: all 0.3s; display: flex; align-items: center; gap: 0.5rem;">
            <span><?php echo $y['icon']; ?></span>
            <span><?php echo $y['year']; ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ====================================================== -->
<!-- 2030 SECTION -->
<!-- ====================================================== -->
<section class="section" id="year-2030" style="background: rgba(0,212,255,0.02); border-top: 1px solid rgba(0,212,255,0.1); border-bottom: 1px solid rgba(0,212,255,0.05); padding: 3rem 2rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="font-family: 'Orbitron', monospace; font-size: 2.5rem; color: #00d4ff; margin-bottom: 1.5rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <span>📱</span>
            <span>2030</span>
            <span style="font-size: 0.8rem; color: #8892b0; font-family: 'Inter', sans-serif;">The Near Future</span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
                <div style="font-size: 2.5rem;">📱</div>
                <h4 style="color: #00d4ff;">6G Networks</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Ultra-fast wireless networks enabling real-time holographic communication and immersive experiences.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">5+ Gbps</span>
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">1ms Latency</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🤖</div>
                <h4 style="color: #00d4ff;">AI Assistants</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Advanced AI assistants capable of autonomous task completion, scheduling, and decision-making.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Autonomous</span>
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Proactive</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🚗</div>
                <h4 style="color: #00d4ff;">Autonomous Vehicles</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Mainstream self-driving cars, autonomous transport systems, and smart traffic management.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Level 5 Autonomy</span>
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">EV Dominance</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🕶️</div>
                <h4 style="color: #00d4ff;">AR/VR Integration</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Augmented and virtual reality seamlessly integrated into daily life and work.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Mainstream</span>
                    <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Mixed Reality</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================== -->
<!-- 2040 SECTION -->
<!-- ====================================================== -->
<section class="section" id="year-2040" style="background: rgba(123,47,252,0.02); border-top: 1px solid rgba(123,47,252,0.1); border-bottom: 1px solid rgba(123,47,252,0.05); padding: 3rem 2rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="font-family: 'Orbitron', monospace; font-size: 2.5rem; color: #7b2ffc; margin-bottom: 1.5rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <span>🧠</span>
            <span>2040</span>
            <span style="font-size: 0.8rem; color: #8892b0; font-family: 'Inter', sans-serif;">The AI Era</span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(123,47,252,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
                <div style="font-size: 2.5rem;">🧠</div>
                <h4 style="color: #7b2ffc;">BCI Technology</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Brain-Computer Interfaces enabling direct neural communication with devices and the internet.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Neural Control</span>
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Thought-to-Text</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(123,47,252,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🌍</div>
                <h4 style="color: #7b2ffc;">Quantum Internet</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Quantum communication networks with unhackable encryption and instantaneous data transfer.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Unhackable</span>
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Quantum Encryption</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(123,47,252,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🩺</div>
                <h4 style="color: #7b2ffc;">AI Medicine</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">AI-powered personalized medicine, predictive healthcare, and robotic surgeries.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Personalized</span>
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Predictive</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(123,47,252,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🔋</div>
                <h4 style="color: #7b2ffc;">Fusion Energy</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Commercial nuclear fusion providing unlimited clean energy for the world.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Clean Energy</span>
                    <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Unlimited</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================== -->
<!-- 2050 SECTION -->
<!-- ====================================================== -->
<section class="section" id="year-2050" style="background: rgba(0,255,136,0.02); border-top: 1px solid rgba(0,255,136,0.1); border-bottom: 1px solid rgba(0,255,136,0.05); padding: 3rem 2rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="font-family: 'Orbitron', monospace; font-size: 2.5rem; color: #00ff88; margin-bottom: 1.5rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <span>🚀</span>
            <span>2050</span>
            <span style="font-size: 0.8rem; color: #8892b0; font-family: 'Inter', sans-serif;">The Transformation</span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,255,136,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
                <div style="font-size: 2.5rem;">🧬</div>
                <h4 style="color: #00ff88;">Human Enhancement</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Biotechnology and AI integration for enhanced human capabilities, longevity, and health.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Gene Editing</span>
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Longevity</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,255,136,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🏠</div>
                <h4 style="color: #00ff88;">Smart Environments</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Fully autonomous smart cities, homes, and living environments with AI integration.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Autonomous</span>
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Sustainable</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,255,136,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🌌</div>
                <h4 style="color: #00ff88;">Space Internet</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Global satellite internet providing connectivity everywhere on Earth and beyond.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Global Coverage</span>
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Space-Based</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,255,136,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🤖</div>
                <h4 style="color: #00ff88;">AGI Development</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Artificial General Intelligence approaching human-level reasoning and understanding.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Human-Level</span>
                    <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Reasoning</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================== -->
<!-- 2100 SECTION -->
<!-- ====================================================== -->
<section class="section" id="year-2100" style="background: rgba(255,0,100,0.02); border-top: 1px solid rgba(255,0,100,0.1); border-bottom: 1px solid rgba(255,0,100,0.05); padding: 3rem 2rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="font-family: 'Orbitron', monospace; font-size: 2.5rem; color: #ff0064; margin-bottom: 1.5rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <span>🌌</span>
            <span>2100</span>
            <span style="font-size: 0.8rem; color: #8892b0; font-family: 'Inter', sans-serif;">The New Frontier</span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,0,100,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
                <div style="font-size: 2.5rem;">🧠</div>
                <h4 style="color: #ff0064;">AGI Achieved</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Artificial General Intelligence matching or exceeding human intelligence in all domains.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Superintelligent</span>
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">All Domains</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,0,100,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🌍</div>
                <h4 style="color: #ff0064;">Global AI Governance</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">AI-assisted global governance, policy-making, and conflict resolution systems.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">AI-Assisted</span>
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Global</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,0,100,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🚀</div>
                <h4 style="color: #ff0064;">Interstellar Computing</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Computing systems enabling interstellar travel, communication, and exploration.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Interstellar</span>
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Exploration</span>
                </div>
            </div>
            
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,0,100,0.2); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
                <div style="font-size: 2.5rem;">🧬</div>
                <h4 style="color: #ff0064;">Immortality Tech</h4>
                <p style="color: #8892b0; font-size: 0.9rem;">Advanced biotechnology and digital consciousness allowing extended human lifespan.</p>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Extended Life</span>
                    <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px;">Digital Consciousness</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================== -->
<!-- ADDITIONAL SECTIONS -->
<!-- ====================================================== -->

<!-- Timeline of Future Predictions -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">📅 Future Timeline</h2>
        <p style="color: #8892b0;">Key milestones predicted for the coming decades.</p>
    </div>
    
    <div style="max-width: 900px; margin: 0 auto; position: relative; padding-left: 2rem; border-left: 2px solid rgba(255,165,0,0.2);">
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #00d4ff; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(0,212,255,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #00d4ff; font-weight: 500; font-size: 1.1rem;">2025</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">AI becomes mainstream in business and everyday life</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #7b2ffc; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(123,47,252,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #7b2ffc; font-weight: 500; font-size: 1.1rem;">2027</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">First commercial quantum computers available</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #00ff88; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(0,255,136,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #00ff88; font-weight: 500; font-size: 1.1rem;">2030</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">6G networks deployed globally</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #ffa500; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(255,165,0,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #ffa500; font-weight: 500; font-size: 1.1rem;">2035</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">First human brain-computer interface implants</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #ff0064; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(255,0,100,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #ff0064; font-weight: 500; font-size: 1.1rem;">2040</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">Quantum internet becomes operational</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #00d4ff; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(0,212,255,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #00d4ff; font-weight: 500; font-size: 1.1rem;">2045</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">Commercial nuclear fusion achieved</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #7b2ffc; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(123,47,252,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #7b2ffc; font-weight: 500; font-size: 1.1rem;">2050</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">AGI approaches human-level intelligence</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #ffa500; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(255,165,0,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #ffa500; font-weight: 500; font-size: 1.1rem;">2070</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">First human colonies on Mars</div>
                </div>
            </div>
        </div>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: #ff0064; border: 3px solid #0a0a0f; box-shadow: 0 0 20px rgba(255,0,100,0.3);"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div style="color: #ff0064; font-weight: 500; font-size: 1.1rem;">2100</div>
                    <div style="color: #8892b0; font-size: 0.9rem;">Human lifespan extended beyond 150 years</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technologies to Watch -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">🔬 Technologies to Watch</h2>
        <p style="color: #8892b0;">Emerging technologies that will shape the future.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">⚛️</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Quantum Computing</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">Revolutionizing computation with quantum mechanics</p>
            <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2030</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">BCI</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">Brain-Computer Interfaces for direct neural control</p>
            <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2035</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔋</div>
            <h4 style="color: #00ff88; font-size: 1rem;">Fusion Energy</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">Unlimited clean energy from nuclear fusion</p>
            <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2040</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧬</div>
            <h4 style="color: #ffa500; font-size: 1rem;">Gene Editing</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">CRISPR and advanced genetic modification</p>
            <span style="font-size: 0.6rem; color: #ffa500; background: rgba(255,165,0,0.1); padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2025</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🚀</div>
            <h4 style="color: #ff0064; font-size: 1rem;">Space Exploration</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">Mars colonization and interstellar travel</p>
            <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2050</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🤖</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">AGI</h4>
            <p style="color: #8892b0; font-size: 0.8rem;">Artificial General Intelligence</p>
            <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.15rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2050</span>
        </div>
    </div>
</section>

<!-- Disclaimer -->
<section class="section" style="padding-top: 1rem; padding-bottom: 3rem;">
    <div style="max-width: 700px; margin: 0 auto; text-align: center; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1.5rem;">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚠️</div>
        <h4 style="color: #ffa500;">Disclaimer</h4>
        <p style="color: #8892b0; font-size: 0.9rem; line-height: 1.8;">
            These are predictions based on current trends and expert opinions.
            The future is uncertain and may unfold differently than predicted.
            This is for educational and entertainment purposes.
        </p>
    </div>
</section>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Smooth scroll to year sections
document.querySelectorAll("[href^=\"#year-\"]").forEach(link => {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "start" });
            target.style.background = "rgba(0, 212, 255, 0.05)";
            target.style.borderRadius = "8px";
            setTimeout(() => {
                target.style.background = "";
            }, 2000);
        }
    });
});

// Add hover effects to cards
document.querySelectorAll(".section[id^=\"year-\"] .transition-all").forEach(card => {
    card.addEventListener("mouseenter", function() {
        this.style.transform = "translateY(-4px)";
        this.style.borderColor = "rgba(0, 212, 255, 0.3)";
        this.style.boxShadow = "0 8px 30px rgba(0, 0, 0, 0.3)";
    });
    card.addEventListener("mouseleave", function() {
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
`;
document.head.appendChild(style);

// Intersection Observer for cards
document.querySelectorAll(".section[id^=\"year-\"] > div > div > div").forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";
    card.style.transition = "all 0.5s ease " + (index * 0.1) + "s";
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll(".section[id^=\"year-\"] > div > div > div").forEach(card => observer.observe(card));
JS;

$pageCSS = '
.section[id^="year-"] {
    scroll-margin-top: 80px;
}

.section[id^="year-"] > div > div > div {
    transition: all 0.3s ease;
}

.section[id^="year-"] > div > div > div:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 212, 255, 0.3);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

a[href^="#year-"] {
    transition: all 0.3s ease;
}

a[href^="#year-"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 212, 255, 0.1);
}

@media (max-width: 768px) {
    .section[id^="year-"] {
        padding: 2rem 1rem !important;
    }
    
    .section[id^="year-"] > div > div > div {
        padding: 1rem !important;
    }
    
    a[href^="#year-"] {
        padding: 0.5rem 1rem !important;
        font-size: 1rem !important;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>