<?php
// about.php
// Digital History - About Page

require_once 'includes/config.php';

$pageTitle = 'About Digital History';
$pageDescription = 'Learn about Digital History - the interactive museum of computing, programming, and the internet.';

trackPageView('about');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>📖 About Digital History</h2>
        <p>The interactive museum of computing, programming, and the internet.</p>
    </div>
</section>

<!-- Main Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Mission -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 1rem;">🎯 Our Mission</h3>
            <p style="color: #ccd6f6; line-height: 1.8;">
                Digital History was created to preserve and share the incredible story of how computing, programming, 
                and the internet transformed human civilization. We believe that understanding our technological past 
                is essential for building a better future.
            </p>
        </div>
        
        <!-- What We Do -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: #7b2ffc; margin-bottom: 1rem;">🌐 What We Do</h3>
            <div style="display: grid; gap: 1rem;">
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <span style="font-size: 1.5rem;">📚</span>
                    <div>
                        <h4 style="color: #fff; font-size: 1rem;">Comprehensive History</h4>
                        <p style="color: #8892b0; font-size: 0.9rem;">We cover the entire history of computing, from mechanical calculators to quantum computers.</p>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <span style="font-size: 1.5rem;">🎮</span>
                    <div>
                        <h4 style="color: #fff; font-size: 1rem;">Interactive Learning</h4>
                        <p style="color: #8892b0; font-size: 0.9rem;">Learn through interactive timelines, simulators, and educational games.</p>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <span style="font-size: 1.5rem;">🌍</span>
                    <div>
                        <h4 style="color: #fff; font-size: 1rem;">Global Accessibility</h4>
                        <p style="color: #8892b0; font-size: 0.9rem;">Available in multiple languages with RTL support for Arabic speakers.</p>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <span style="font-size: 1.5rem;">🔬</span>
                    <div>
                        <h4 style="color: #fff; font-size: 1rem;">Educational Tools</h4>
                        <p style="color: #8892b0; font-size: 0.9rem;">Simulators and visualizations that make complex concepts easy to understand.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Methodology -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: #00ff88; margin-bottom: 1rem;">📋 Methodology</h3>
            <p style="color: #ccd6f6; line-height: 1.8;">
                Our content is carefully researched using authoritative sources including academic publications, 
                historical documents, and expert interviews. We strive for accuracy while making the content 
                accessible and engaging for all audiences.
            </p>
            <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <span style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 0.2rem 0.8rem; border-radius: 12px; color: #8892b0;">📖 Academic Sources</span>
                <span style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 0.2rem 0.8rem; border-radius: 12px; color: #8892b0;">🏛️ Historical Archives</span>
                <span style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 0.2rem 0.8rem; border-radius: 12px; color: #8892b0;">👨‍💻 Expert Review</span>
                <span style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 0.2rem 0.8rem; border-radius: 12px; color: #8892b0;">📊 Data Verification</span>
            </div>
        </div>
        
        <!-- Team -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: #ffa500; margin-bottom: 1rem;">👥 Our Team</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="text-align: center;">
                    <div style="font-size: 3rem;">👨‍💻</div>
                    <h4 style="color: #fff; font-size: 0.95rem;">Digital Historian</h4>
                    <p style="color: #8892b0; font-size: 0.8rem;">Research & Content</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 3rem;">🎨</div>
                    <h4 style="color: #fff; font-size: 0.95rem;">UI/UX Designer</h4>
                    <p style="color: #8892b0; font-size: 0.8rem;">Design & Experience</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 3rem;">⚡</div>
                    <h4 style="color: #fff; font-size: 0.95rem;">Developer</h4>
                    <p style="color: #8892b0; font-size: 0.8rem;">Implementation & Architecture</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 3rem;">📚</div>
                    <h4 style="color: #fff; font-size: 0.95rem;">Content Curator</h4>
                    <p style="color: #8892b0; font-size: 0.8rem;">Verification & Quality</p>
                </div>
            </div>
        </div>
        
        <!-- Sources -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
            <h3 style="color: #ff0064; margin-bottom: 1rem;">📚 Sources & References</h3>
            <p style="color: #8892b0; margin-bottom: 1rem;">
                We rely on these and other authoritative sources for our historical content:
            </p>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #00d4ff;">📖</span>
                    <span style="color: #ccd6f6;">Computer History Museum</span>
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #7b2ffc;">📖</span>
                    <span style="color: #ccd6f6;">Internet History Archive</span>
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #00ff88;">📖</span>
                    <span style="color: #ccd6f6;">ACM Digital Library</span>
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #ffa500;">📖</span>
                    <span style="color: #ccd6f6;">IEEE Xplore</span>
                </li>
                <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #ff0064;">📖</span>
                    <span style="color: #ccd6f6;">Wikipedia (for general reference)</span>
                </li>
            </ul>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>