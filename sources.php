<?php
// sources.php
// Digital History - Sources & References

require_once 'includes/config.php';

$pageTitle = 'Sources & References';
$pageDescription = 'Sources and references used in Digital History.';

trackPageView('sources');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="section-header">
        <h2>📚 Sources & References</h2>
        <p>Academic and historical sources used in Digital History.</p>
    </div>
</section>

<!-- Sources Content -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Introduction -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <p style="color: #ccd6f6; line-height: 1.8;">
                Digital History is built on a foundation of rigorous research and verified sources. 
                Below are the primary sources and references used in creating our content. 
                We strive for accuracy and transparency in all our historical information.
            </p>
        </div>
        
        <!-- Books -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: #00d4ff; margin-bottom: 1rem;">📖 Books</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 1rem;">
                    <span style="color: #00d4ff; font-size: 1.2rem;">•</span>
                    <div>
                        <div style="color: #fff; font-weight: 500;">The Innovators</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">Walter Isaacson · 2014</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">The story of the digital revolution and the people who made it.</div>
                    </div>
                </li>
                <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 1rem;">
                    <span style="color: #7b2ffc; font-size: 1.2rem;">•</span>
                    <div>
                        <div style="color: #fff; font-weight: 500;">The Cuckoo's Egg</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">Cliff Stoll · 1989</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">A classic cybersecurity story about tracking a hacker.</div>
                    </div>
                </li>
                <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 1rem;">
                    <span style="color: #00ff88; font-size: 1.2rem;">•</span>
                    <div>
                        <div style="color: #fff; font-weight: 500;">The Dream Machine</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">M. Mitchell Waldrop · 2001</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">The story of J.C.R. Licklider and the birth of the internet.</div>
                    </div>
                </li>
                <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 1rem;">
                    <span style="color: #ffa500; font-size: 1.2rem;">•</span>
                    <div>
                        <div style="color: #fff; font-weight: 500;">Code: The Hidden Language of Computer Hardware and Software</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">Charles Petzold · 1999</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">An accessible introduction to how computers work.</div>
                    </div>
                </li>
                <li style="padding: 0.8rem 0; display: flex; gap: 1rem;">
                    <span style="color: #ff0064; font-size: 1.2rem;">•</span>
                    <div>
                        <div style="color: #fff; font-weight: 500;">The Universal Computer</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">Martin Davis · 2000</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">The story of computing from Leibniz to Turing.</div>
                    </div>
                </li>
            </ul>
        </div>
        
        <!-- Websites -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: #7b2ffc; margin-bottom: 1rem;">🌐 Websites</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #00d4ff;">🔗</span>
                    <a href="https://www.computerhistory.org/" target="_blank" style="color: #ccd6f6; text-decoration: none;">Computer History Museum</a>
                </li>
                <li style="padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #7b2ffc;">🔗</span>
                    <a href="https://www.internethistory.org/" target="_blank" style="color: #ccd6f6; text-decoration: none;">Internet History Archive</a>
                </li>
                <li style="padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #00ff88;">🔗</span>
                    <a href="https://www.acm.org/" target="_blank" style="color: #ccd6f6; text-decoration: none;">ACM Digital Library</a>
                </li>
                <li style="padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #ffa500;">🔗</span>
                    <a href="https://ieeexplore.ieee.org/" target="_blank" style="color: #ccd6f6; text-decoration: none;">IEEE Xplore</a>
                </li>
                <li style="padding: 0.6rem 0; display: flex; align-items: center; gap: 0.8rem;">
                    <span style="color: #ff0064;">🔗</span>
                    <a href="https://en.wikipedia.org/wiki/History_of_computing" target="_blank" style="color: #ccd6f6; text-decoration: none;">Wikipedia - History of Computing</a>
                </li>
            </ul>
        </div>
        
        <!-- Academic Papers -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: #00ff88; margin-bottom: 1rem;">📄 Academic Papers</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 1rem;">
                    <span style="color: #00d4ff;">📄</span>
                    <div>
                        <div style="color: #fff;">"Computing Machinery and Intelligence"</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">Alan Turing · 1950</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">The paper that introduced the Turing Test.</div>
                    </div>
                </li>
                <li style="padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 1rem;">
                    <span style="color: #7b2ffc;">📄</span>
                    <div>
                        <div style="color: #fff;">"A Mathematical Theory of Communication"</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">Claude Shannon · 1948</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">Foundation of information theory.</div>
                    </div>
                </li>
                <li style="padding: 0.6rem 0; display: flex; gap: 1rem;">
                    <span style="color: #00ff88;">📄</span>
                    <div>
                        <div style="color: #fff;">"The Architecture of Complexity"</div>
                        <div style="color: #8892b0; font-size: 0.85rem;">Herbert A. Simon · 1962</div>
                        <div style="color: #8892b0; font-size: 0.8rem;">Influential work on complex systems.</div>
                    </div>
                </li>
            </ul>
        </div>
        
        <!-- Note -->
        <div style="background: rgba(255,165,0,0.05); border: 1px solid rgba(255,165,0,0.1); border-radius: 12px; padding: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="font-size: 2rem;">📌</span>
                <div>
                    <h4 style="color: #ffa500; margin: 0;">Note on Sources</h4>
                    <p style="color: #8892b0; font-size: 0.9rem; margin: 0.5rem 0 0;">
                        This list represents the primary sources used in creating Digital History. 
                        Additional sources are cited directly in individual articles and entries. 
                        We continuously update our references as new research becomes available.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>