<?php
// ai.php
// Digital History - Artificial Intelligence History (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'History of Artificial Intelligence';
$pageDescription = 'Explore the complete history of AI from early concepts to generative AI - the story of machine intelligence.';

// Get AI-related events
$aiEvents = db()->fetchAll(
    "SELECT * FROM timeline_events 
     WHERE is_active = 1 
     AND (title LIKE '%AI%' OR title LIKE '%artificial intelligence%' OR title LIKE '%machine learning%' OR title LIKE '%deep learning%' OR title LIKE '%neural%' OR title LIKE '%Turing%' OR title LIKE '%chatbot%' OR title LIKE '%generative%')
     ORDER BY year ASC LIMIT 15"
);

// Get AI-related people
$aiPeople = db()->fetchAll(
    "SELECT p.* FROM people p 
     WHERE p.is_active = 1 
     AND (p.known_for LIKE '%AI%' OR p.known_for LIKE '%intelligence%' OR p.known_for LIKE '%neural%' OR p.known_for LIKE '%learning%' OR p.known_for LIKE '%Turing%' OR p.known_for LIKE '%chatbot%')
     ORDER BY p.birth_year ASC LIMIT 10"
);

trackPageView('ai');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            🧠 History of Artificial Intelligence
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;">From Turing\'s vision to generative AI - the story of machine intelligence.</p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($aiEvents); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">AI Milestones</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($aiPeople); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">AI Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">$200B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">AI Market by 2025</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">70+</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Years of Evolution</div>
        </div>
    </div>
</section>

<!-- AI Timeline -->
<section class="section" style="padding-top: 1rem; padding-bottom: 2rem; background: rgba(255,255,255,0.01); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <h3 style="color: #00d4ff; margin: 0; font-size: 1.5rem;">
            <i class="fas fa-timeline"></i> AI Evolution Timeline
        </h3>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="scrollAITimeline('left')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollAITimeline('right')" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #8892b0; cursor: pointer;">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <div class="ai-timeline" id="aiTimeline">
        <?php
        $aiMilestones = [
            [
                'year' => '1950',
                'title' => 'Turing Test',
                'description' => 'Alan Turing proposes the Turing Test as a measure of machine intelligence, asking "Can machines think?"',
                'icon' => '🧪',
                'color' => '#ff6b6b',
                'category' => 'Theory',
                'impact' => 'Foundational',
                'company' => 'Turing'
            ],
            [
                'year' => '1956',
                'title' => 'Dartmouth Conference',
                'description' => 'The term "Artificial Intelligence" is officially coined by John McCarthy at the Dartmouth Summer Research Project.',
                'icon' => '🏛️',
                'color' => '#ffa500',
                'category' => 'Foundation',
                'impact' => 'Revolutionary',
                'company' => 'Dartmouth'
            ],
            [
                'year' => '1966',
                'title' => 'ELIZA Chatbot',
                'description' => 'Joseph Weizenbaum creates ELIZA, the first chatbot that mimics a psychotherapist using pattern matching.',
                'icon' => '💬',
                'color' => '#ffd93d',
                'category' => 'Chatbot',
                'impact' => 'High',
                'company' => 'MIT'
            ],
            [
                'year' => '1973',
                'title' => 'AI Winter Begins',
                'description' => 'The Lighthill Report criticizes AI research, leading to reduced funding and the first AI winter.',
                'icon' => '❄️',
                'color' => '#8892b0',
                'category' => 'Setback',
                'impact' => 'High',
                'company' => 'UK Government'
            ],
            [
                'year' => '1980',
                'title' => 'Expert Systems Emerge',
                'description' => 'Expert systems like MYCIN and XCON demonstrate practical AI applications in medicine and business.',
                'icon' => '🧠',
                'color' => '#00d4ff',
                'category' => 'Application',
                'impact' => 'High',
                'company' => 'Stanford'
            ],
            [
                'year' => '1986',
                'title' => 'Backpropagation',
                'description' => 'The backpropagation algorithm revolutionizes neural network training, enabling deep learning.',
                'icon' => '🔄',
                'color' => '#7b2ffc',
                'category' => 'Algorithm',
                'impact' => 'Revolutionary',
                'company' => 'Multiple'
            ],
            [
                'year' => '1997',
                'title' => 'Deep Blue Wins',
                'description' => 'IBM\'s Deep Blue defeats world chess champion Garry Kasparov, marking AI\'s triumph in strategic games.',
                'icon' => '♟️',
                'color' => '#00d4ff',
                'category' => 'Milestone',
                'impact' => 'Revolutionary',
                'company' => 'IBM'
            ],
            [
                'year' => '2006',
                'title' => 'Deep Learning Revival',
                'description' => 'Geoffrey Hinton coins "deep learning" as neural networks with many layers show remarkable performance.',
                'icon' => '🧬',
                'color' => '#00ff88',
                'category' => 'Breakthrough',
                'impact' => 'Revolutionary',
                'company' => 'University of Toronto'
            ],
            [
                'year' => '2011',
                'title' => 'Watson Wins Jeopardy!',
                'description' => 'IBM Watson defeats human champions in Jeopardy!, demonstrating natural language understanding.',
                'icon' => '📺',
                'color' => '#7b2ffc',
                'category' => 'Milestone',
                'impact' => 'High',
                'company' => 'IBM'
            ],
            [
                'year' => '2012',
                'title' => 'AlexNet Revolution',
                'description' => 'AlexNet wins ImageNet with deep neural networks, sparking the deep learning revolution.',
                'icon' => '🏆',
                'color' => '#ff0064',
                'category' => 'Breakthrough',
                'impact' => 'Revolutionary',
                'company' => 'University of Toronto'
            ],
            [
                'year' => '2014',
                'title' => 'GANs Invented',
                'description' => 'Ian Goodfellow invents Generative Adversarial Networks (GANs), enabling AI to generate realistic content.',
                'icon' => '🎨',
                'color' => '#ffa500',
                'category' => 'Innovation',
                'impact' => 'High',
                'company' => 'University of Montreal'
            ],
            [
                'year' => '2016',
                'title' => 'AlphaGo Wins',
                'description' => 'DeepMind\'s AlphaGo defeats Go champion Lee Sedol, mastering the most complex board game.',
                'icon' => '🎯',
                'color' => '#ff0064',
                'category' => 'Milestone',
                'impact' => 'Revolutionary',
                'company' => 'DeepMind'
            ],
            [
                'year' => '2017',
                'title' => 'Transformer Architecture',
                'description' => 'The Transformer architecture revolutionizes NLP, enabling models like BERT and GPT.',
                'icon' => '📊',
                'color' => '#00d4ff',
                'category' => 'Architecture',
                'impact' => 'Revolutionary',
                'company' => 'Google'
            ],
            [
                'year' => '2018',
                'title' => 'BERT Released',
                'description' => 'Google releases BERT, a bidirectional transformer that achieves state-of-the-art NLP performance.',
                'icon' => '📚',
                'color' => '#7b2ffc',
                'category' => 'Breakthrough',
                'impact' => 'High',
                'company' => 'Google'
            ],
            [
                'year' => '2020',
                'title' => 'GPT-3 Released',
                'description' => 'OpenAI releases GPT-3 with 175 billion parameters, demonstrating few-shot learning capabilities.',
                'icon' => '🚀',
                'color' => '#00ff88',
                'category' => 'Breakthrough',
                'impact' => 'Revolutionary',
                'company' => 'OpenAI'
            ],
            [
                'year' => '2022',
                'title' => 'ChatGPT Released',
                'description' => 'OpenAI releases ChatGPT, a conversational AI that brings generative AI to the mainstream.',
                'icon' => '🤖',
                'color' => '#00d4ff',
                'category' => 'Breakthrough',
                'impact' => 'Revolutionary',
                'company' => 'OpenAI'
            ],
            [
                'year' => '2023',
                'title' => 'GPT-4 Released',
                'description' => 'OpenAI releases GPT-4 with multimodal capabilities, passing the bar exam and advanced reasoning.',
                'icon' => '🚀',
                'color' => '#7b2ffc',
                'category' => 'Breakthrough',
                'impact' => 'Revolutionary',
                'company' => 'OpenAI'
            ],
            [
                'year' => '2024',
                'title' => 'AI Agents Emerge',
                'description' => 'Autonomous AI agents capable of complex reasoning, planning, and task execution appear.',
                'icon' => '🧠',
                'color' => '#ff0064',
                'category' => 'Emerging',
                'impact' => 'High',
                'company' => 'Multiple'
            ]
        ];
        
        foreach ($aiMilestones as $index => $event):
        ?>
        <div class="ai-item <?php echo $index % 2 === 0 ? 'left' : 'right'; ?> animate-on-scroll" data-index="<?php echo $index; ?>">
            <div class="ai-dot" style="background: <?php echo $event['color']; ?>; box-shadow: 0 0 20px <?php echo $event['color']; ?>40;"></div>
            <div class="ai-content" style="border-color: <?php echo $event['color']; ?>20;">
                <div class="ai-year" style="color: <?php echo $event['color']; ?>;"><?php echo $event['year']; ?></div>
                <div class="ai-icon"><?php echo $event['icon']; ?></div>
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
                <button onclick="showAIDetails('<?php echo addslashes($event['title']); ?>', '<?php echo addslashes($event['description']); ?>', '<?php echo $event['year']; ?>', '<?php echo $event['icon']; ?>', '<?php echo $event['category']; ?>', '<?php echo $event['company']; ?>')" style="margin-top: 0.8rem; padding: 0.3rem 1rem; background: <?php echo $event['color']; ?>; border: none; border-radius: 6px; color: #000; font-weight: 600; cursor: pointer; font-size: 0.8rem; transition: all 0.3s;">
                    Learn More
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- AI Categories -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">📚 AI Technologies</h2>
        <p style="color: #8892b0;">Key technologies that power artificial intelligence.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧠</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Neural Networks</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Inspired by brain</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">1940s</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">📊</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">Machine Learning</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Learning from data</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">1950s</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🧬</div>
            <h4 style="color: #00ff88; font-size: 1rem;">Deep Learning</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Multi-layer networks</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">2006</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🔤</div>
            <h4 style="color: #ffa500; font-size: 1rem;">NLP</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Language understanding</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">1960s</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🎨</div>
            <h4 style="color: #ff0064; font-size: 1rem;">Generative AI</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Creating content</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">2014</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🤖</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">AI Agents</h4>
            <p style="color: #8892b0; font-size: 0.75rem;">Autonomous systems</p>
            <p style="color: #8892b0; font-size: 0.7rem; margin-top: 0.3rem;">2024</p>
        </div>
    </div>
</section>

<!-- AI Statistics -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00d4ff;">📊 AI by the Numbers</h2>
        <p style="color: #8892b0;">Amazing statistics about artificial intelligence.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; max-width: 900px; margin: 0 auto;">
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00d4ff;">$200B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">AI Market by 2025</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Rapid growth</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #7b2ffc;">77%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Devices using AI</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Everyday tech</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00ff88;">85%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">Customer interactions with AI</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Chatbots & support</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ffa500;">97M</div>
            <div style="color: #8892b0; font-size: 0.85rem;">AI jobs by 2025</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">New careers</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #ff0064;">1.8B</div>
            <div style="color: #8892b0; font-size: 0.85rem;">AI Users Worldwide</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Growing rapidly</div>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #00d4ff;">38%</div>
            <div style="color: #8892b0; font-size: 0.85rem;">AI Adoption Rate</div>
            <div style="font-size: 0.6rem; color: #8892b0; margin-top: 0.3rem;">Enterprise usage</div>
        </div>
    </div>
</section>

<!-- AI Pioneers -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">👨‍💻 AI Pioneers</h2>
        <p style="color: #8892b0;">The brilliant minds who built the field of artificial intelligence.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <?php
        $aiPioneers = [
            ['name' => 'Alan Turing', 'role' => 'AI Pioneer', 'icon' => '🧪', 'color' => '#ff6b6b', 'contribution' => 'Turing Test'],
            ['name' => 'John McCarthy', 'role' => 'AI Father', 'icon' => '🏛️', 'color' => '#ffa500', 'contribution' => 'AI Coined'],
            ['name' => 'Marvin Minsky', 'role' => 'AI Visionary', 'icon' => '🧠', 'color' => '#00d4ff', 'contribution' => 'MIT AI Lab'],
            ['name' => 'Geoffrey Hinton', 'role' => 'Deep Learning Godfather', 'icon' => '🧬', 'color' => '#00ff88', 'contribution' => 'Deep Learning'],
            ['name' => 'Yann LeCun', 'role' => 'CNN Pioneer', 'icon' => '👁️', 'color' => '#7b2ffc', 'contribution' => 'Convolutional Nets'],
            ['name' => 'Yoshua Bengio', 'role' => 'Deep Learning Pioneer', 'icon' => '📚', 'color' => '#ff0064', 'contribution' => 'Deep Learning'],
            ['name' => 'Andrew Ng', 'role' => 'AI Education Leader', 'icon' => '📖', 'color' => '#00d4ff', 'contribution' => 'Coursera AI'],
            ['name' => 'Demis Hassabis', 'role' => 'DeepMind Founder', 'icon' => '🎯', 'color' => '#7b2ffc', 'contribution' => 'AlphaGo'],
            ['name' => 'Sam Altman', 'role' => 'OpenAI CEO', 'icon' => '🤖', 'color' => '#00ff88', 'contribution' => 'ChatGPT'],
            ['name' => 'Elon Musk', 'role' => 'AI Visionary', 'icon' => '🚀', 'color' => '#ffa500', 'contribution' => 'Tesla AI']
        ];
        
        foreach ($aiPioneers as $pioneer):
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

<!-- AI Applications -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">🚀 AI Applications</h2>
        <p style="color: #8892b0;">How artificial intelligence is transforming industries.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🏥</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Healthcare</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Medical diagnosis, drug discovery, personalized medicine</p>
            <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">Revolutionizing</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🚗</div>
            <h4 style="color: #7b2ffc; font-size: 1rem;">Autonomous Vehicles</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Self-driving cars, traffic prediction, safety systems</p>
            <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">Emerging</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">💬</div>
            <h4 style="color: #00ff88; font-size: 1rem;">Chatbots & Support</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Customer service, virtual assistants, help desks</p>
            <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">Widely used</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🎨</div>
            <h4 style="color: #ffa500; font-size: 1rem;">Content Creation</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Art generation, writing, music composition, video</p>
            <span style="font-size: 0.6rem; color: #ffa500; background: rgba(255,165,0,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">Growing</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">💼</div>
            <h4 style="color: #ff0064; font-size: 1rem;">Business Analytics</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Data analysis, prediction, decision making</p>
            <span style="font-size: 0.6rem; color: #ff0064; background: rgba(255,0,100,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">Essential</span>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.3rem;">🎮</div>
            <h4 style="color: #00d4ff; font-size: 1rem;">Gaming</h4>
            <p style="color: #8892b0; font-size: 0.85rem;">Game AI, procedural generation, NPC behavior</p>
            <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">Advanced</span>
        </div>
    </div>
</section>

<!-- Future of AI -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ffa500;">🚀 The Future of AI</h2>
        <p style="color: #8892b0;">What\'s next for artificial intelligence?</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; max-width: 1000px; margin: 0 auto;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-4px);">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🧠</div>
            <h4 style="color: #00d4ff;">AGI</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Artificial General Intelligence - AI that can reason and learn like humans.</p>
            <span style="font-size: 0.6rem; color: #00d4ff; background: rgba(0,212,255,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2030+</span>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🤝</div>
            <h4 style="color: #7b2ffc;">Human-AI Collaboration</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">AI working alongside humans to solve complex problems.</p>
            <span style="font-size: 0.6rem; color: #7b2ffc; background: rgba(123,47,252,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">Near future</span>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚡</div>
            <h4 style="color: #00ff88;">Quantum AI</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">Quantum computing combined with AI for unprecedented processing power.</p>
            <span style="font-size: 0.6rem; color: #00ff88; background: rgba(0,255,136,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2035+</span>
        </div>
        
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎨</div>
            <h4 style="color: #ffa500;">Creative AI</h4>
            <p style="color: #8892b0; font-size: 0.9rem;">AI that creates art, music, and literature indistinguishable from human work.</p>
            <span style="font-size: 0.6rem; color: #ffa500; background: rgba(255,165,0,0.1); padding: 0.2rem 0.6rem; border-radius: 12px; display: inline-block; margin-top: 0.3rem;">2025+</span>
        </div>
    </div>
</section>

<!-- Interactive Quiz -->
<section class="section" style="padding: 4rem 2rem;">
    <div class="section-header">
        <h2 style="color: #ff0064;">🧠 AI Quiz</h2>
        <p style="color: #8892b0;">Test your knowledge about artificial intelligence.</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
        <div id="aiQuiz">
            <div id="aiQuizQuestion" style="color: #fff; font-size: 1.1rem; margin-bottom: 1rem;">
                Who proposed the Turing Test?
            </div>
            <div id="aiQuizOptions" style="display: grid; gap: 0.5rem;">
                <button onclick="checkAIQuiz('Alan Turing')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    A. Alan Turing
                </button>
                <button onclick="checkAIQuiz('John McCarthy')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    B. John McCarthy
                </button>
                <button onclick="checkAIQuiz('Geoffrey Hinton')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    C. Geoffrey Hinton
                </button>
                <button onclick="checkAIQuiz('Marvin Minsky')" style="padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;">
                    D. Marvin Minsky
                </button>
            </div>
            <div id="aiQuizFeedback" style="margin-top: 1rem; padding: 0.8rem; border-radius: 8px; display: none;"></div>
            <button onclick="nextAIQuizQuestion()" id="aiQuizNextBtn" style="margin-top: 1rem; padding: 0.5rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600; display: none;">
                Next Question →
            </button>
        </div>
    </div>
</section>

<!-- AI Details Modal -->
<div id="aiModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closeAIModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="aiModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Timeline scrolling
function scrollAITimeline(direction) {
    const timeline = document.getElementById("aiTimeline");
    const scrollAmount = 300;
    if (direction === "left") {
        timeline.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    } else {
        timeline.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
}

// AI details modal
function showAIDetails(title, description, year, icon, category, company) {
    const modal = document.getElementById("aiModal");
    const content = document.getElementById("aiModalContent");
    
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
                <span style="color: #8892b0;">${title} was a ${category} milestone in AI history that ${description.toLowerCase().includes("revolution") ? "revolutionized" : "transformed"} the field of artificial intelligence.</span>
            </div>
            <button onclick="closeAIModal()" style="margin-top: 1.5rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                Close
            </button>
        </div>
    `;
}

function closeAIModal() {
    document.getElementById("aiModal").style.display = "none";
    document.body.style.overflow = "";
}

// AI Quiz functionality
let aiQuizQuestions = [
    { question: "Who proposed the Turing Test?", options: ["Alan Turing", "John McCarthy", "Geoffrey Hinton", "Marvin Minsky"], correct: 0 },
    { question: "What year was the term 'Artificial Intelligence' coined?", options: ["1950", "1956", "1966", "1973"], correct: 1 },
    { question: "What was the first chatbot called?", options: ["ELIZA", "Siri", "ChatGPT", "Alexa"], correct: 0 },
    { question: "Which AI defeated Garry Kasparov in chess?", options: ["Deep Blue", "AlphaGo", "Watson", "GPT-3"], correct: 0 },
    { question: "What did AlphaGo defeat Lee Sedol in?", options: ["Chess", "Go", "Jeopardy!", "Poker"], correct: 1 },
    { question: "Which company created ChatGPT?", options: ["Google", "Microsoft", "OpenAI", "DeepMind"], correct: 2 },
    { question: "What is the name of Google's AI language model?", options: ["BERT", "GPT-3", "GPT-4", "Claude"], correct: 0 },
    { question: "What is the projected AI market size by 2025?", options: ["$100B", "$150B", "$200B", "$250B"], correct: 2 }
];

let currentAIQuiz = 0;
let aiQuizScore = 0;

function checkAIQuiz(selected) {
    const correct = aiQuizQuestions[currentAIQuiz].options[aiQuizQuestions[currentAIQuiz].correct];
    const feedback = document.getElementById("aiQuizFeedback");
    const options = document.querySelectorAll("#aiQuizOptions button");
    
    options.forEach(btn => btn.style.pointerEvents = "none");
    
    options.forEach((btn, index) => {
        if (aiQuizQuestions[currentAIQuiz].options[index] === correct) {
            btn.style.borderColor = "#00ff88";
            btn.style.background = "rgba(0, 255, 136, 0.1)";
        } else if (btn.textContent.includes(selected) && selected !== correct) {
            btn.style.borderColor = "#ff6b6b";
            btn.style.background = "rgba(255, 0, 0, 0.1)";
        }
    });
    
    if (selected === correct) {
        aiQuizScore++;
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
    
    document.getElementById("aiQuizNextBtn").style.display = "inline-block";
}

function nextAIQuizQuestion() {
    currentAIQuiz++;
    
    if (currentAIQuiz >= aiQuizQuestions.length) {
        const container = document.getElementById("aiQuiz");
        const percentage = Math.round((aiQuizScore / aiQuizQuestions.length) * 100);
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
                    You got ${aiQuizScore} out of ${aiQuizQuestions.length} questions correct.
                </p>
                <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.6rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                    Try Again
                </button>
            </div>
        `;
        return;
    }
    
    const q = aiQuizQuestions[currentAIQuiz];
    document.getElementById("aiQuizQuestion").textContent = q.question;
    const optionsContainer = document.getElementById("aiQuizOptions");
    optionsContainer.innerHTML = "";
    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.textContent = String.fromCharCode(65 + index) + ". " + option;
        btn.style.cssText = "padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #ccd6f6; cursor: pointer; text-align: left; transition: all 0.3s;";
        btn.onclick = function() { checkAIQuiz(option); };
        optionsContainer.appendChild(btn);
    });
    
    document.getElementById("aiQuizFeedback").style.display = "none";
    document.getElementById("aiQuizNextBtn").style.display = "none";
}

// Keyboard shortcuts
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") closeAIModal();
});

// Close modal on overlay click
document.getElementById("aiModal").addEventListener("click", function(e) {
    if (e.target === this) closeAIModal();
});

// Intersection Observer for timeline items
document.querySelectorAll(".ai-item").forEach((item, index) => {
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

document.querySelectorAll(".ai-item").forEach(item => observer.observe(item));

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
.ai-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
    max-height: 800px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.ai-timeline::-webkit-scrollbar {
    width: 6px;
}

.ai-timeline::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.02);
    border-radius: 3px;
}

.ai-timeline::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 255, 0.3);
    border-radius: 3px;
}

.ai-timeline::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 212, 255, 0.5);
}

.ai-timeline::before {
    content: "";
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, #00d4ff, #7b2ffc, transparent);
    transform: translateX(-50%);
}

.ai-item {
    display: flex;
    padding: 1.5rem 0;
    position: relative;
    width: 50%;
}

.ai-item.left {
    padding-right: 3rem;
    justify-content: flex-end;
}

.ai-item.right {
    padding-left: 3rem;
    margin-left: 50%;
}

.ai-dot {
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

.ai-item:hover .ai-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.ai-content {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    text-align: center;
    width: 100%;
}

.ai-content:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 212, 255, 0.1);
}

.ai-year {
    font-family: "Orbitron", monospace;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.ai-icon {
    font-size: 2.5rem;
    margin-bottom: 0.3rem;
}

.ai-content h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 0.3rem;
}

.ai-content p {
    color: #8892b0;
    font-size: 0.9rem;
    margin: 0;
}

#aiQuizOptions button:hover {
    border-color: #00d4ff !important;
    background: rgba(0, 212, 255, 0.05) !important;
}

#aiModal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .ai-timeline {
        max-height: 600px;
        padding: 1rem 0;
    }
    
    .ai-timeline::before {
        left: 20px;
    }
    
    .ai-item {
        width: 100%;
        padding: 1rem 0;
    }
    
    .ai-item.left {
        padding-right: 0;
    }
    
    .ai-item.right {
        padding-left: 0;
        margin-left: 0;
    }
    
    .ai-dot {
        left: 20px;
    }
    
    .ai-content {
        margin-left: 40px;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>