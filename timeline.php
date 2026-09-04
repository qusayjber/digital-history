<?php
// timeline.php
// Digital History - Interactive Timeline (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'Timeline';
$pageDescription = 'Explore the complete history of computing, programming, and the internet through our interactive timeline.';

// Get all eras and events
$eras = getEras();
$events = getTimelineEvents();

// Group events by era
$eventsByEra = [];
foreach ($events as $event) {
    if ($event['era_id']) {
        $eventsByEra[$event['era_id']][] = $event;
    }
}

// Get statistics
$totalEvents = count($events);
$totalEras = count($eras);
$oldestEvent = !empty($events) ? min(array_column($events, 'year')) : 0;
$newestEvent = !empty($events) ? max(array_column($events, 'year')) : 0;

trackPageView('timeline');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            <?php echo t('timeline.title'); ?>
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;"><?php echo t('timeline.subtitle'); ?></p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo $totalEras; ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Eras</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo $totalEvents; ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Events</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">
                <?php 
                if ($oldestEvent > 0) {
                    echo $oldestEvent;
                } else {
                    echo '1800';
                }
                ?>
            </div>
            <div style="color: #8892b0; font-size: 0.85rem;">Earliest Event</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">
                <?php 
                if ($newestEvent > 0) {
                    echo $newestEvent;
                } else {
                    echo '2024';
                }
                ?>
            </div>
            <div style="color: #8892b0; font-size: 0.85rem;">Latest Event</div>
        </div>
    </div>
</section>

<!-- Timeline Controls -->
<section class="section" style="padding-top: 0; padding-bottom: 1rem;">
    <div style="max-width: 900px; margin: 0 auto; display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; align-items: center;">
        <!-- Era Navigation -->
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; flex: 1;">
            <?php foreach ($eras as $era): ?>
            <a href="#era-<?php echo $era['id']; ?>" class="era-nav-btn" data-era="<?php echo $era['id']; ?>" style="padding: 0.3rem 0.8rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; color: <?php echo $era['color'] ?? '#8892b0'; ?>; text-decoration: none; font-size: 0.75rem; transition: all 0.3s;">
                <?php echo escape($era['name']); ?>
            </a>
            <?php endforeach; ?>
            <a href="#future" style="padding: 0.3rem 0.8rem; background: rgba(123,47,252,0.1); border: 1px solid rgba(123,47,252,0.2); border-radius: 20px; color: #7b2ffc; text-decoration: none; font-size: 0.75rem; transition: all 0.3s;">
                🚀 Future
            </a>
        </div>
        
        <!-- View Options -->
        <div style="display: flex; gap: 0.5rem;">
            <button id="viewAllBtn" style="padding: 0.3rem 1rem; background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); border-radius: 6px; color: #00d4ff; cursor: pointer; font-size: 0.75rem; transition: all 0.3s;">
                <i class="fas fa-expand"></i> View All
            </button>
            <button id="collapseAllBtn" style="padding: 0.3rem 1rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 6px; color: #8892b0; cursor: pointer; font-size: 0.75rem; transition: all 0.3s;">
                <i class="fas fa-compress"></i> Collapse
            </button>
        </div>
    </div>
</section>

<!-- Interactive Timeline -->
<section class="section" style="padding-top: 0; padding-bottom: 2rem;">
    <div class="timeline-container" id="timelineContainer">
        <div class="timeline-line"></div>
        
        <?php 
        $itemIndex = 0;
        foreach ($eras as $era): 
            $eraEvents = $eventsByEra[$era['id']] ?? [];
            $itemIndex++;
            $eraColor = $era['color'] ?? '#00d4ff';
        ?>
        <div class="timeline-item <?php echo $itemIndex % 2 === 0 ? 'even' : 'odd'; ?>" data-era="<?php echo $era['id']; ?>" id="era-<?php echo $era['id']; ?>">
            <div class="timeline-dot" style="background: <?php echo $eraColor; ?>; box-shadow: 0 0 20px <?php echo $eraColor; ?>40;"></div>
            <div class="timeline-content" style="border-color: <?php echo $eraColor; ?>20;">
                <div class="timeline-era-header" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <?php if ($era['icon']): ?>
                    <span style="font-size: 1.5rem;"><?php echo $era['icon']; ?></span>
                    <?php endif; ?>
                    <div>
                        <div class="timeline-year" style="color: <?php echo $eraColor; ?>;"><?php echo $era['start_year'] . ' - ' . ($era['end_year'] ?: 'Present'); ?></div>
                        <h3 class="timeline-title" style="font-size: 1.2rem;"><?php echo escape($era['name']); ?></h3>
                    </div>
                </div>
                <p class="timeline-description"><?php echo escape($era['description']); ?></p>
                
                <?php if (!empty($eraEvents)): ?>
                <div style="margin-top: 1rem;">
                    <button class="btn-toggle-events" data-era="<?php echo $era['id']; ?>" style="background: none; border: none; color: <?php echo $eraColor; ?>; cursor: pointer; font-size: 0.85rem; display: flex; align-items: center; gap: 0.3rem;">
                        <i class="fas fa-chevron-down"></i> View Events (<?php echo count($eraEvents); ?>)
                    </button>
                    <div class="era-events" id="eraEvents_<?php echo $era['id']; ?>" style="display: none; margin-top: 0.8rem; padding-left: 1rem; border-left: 2px solid <?php echo $eraColor; ?>40;">
                        <?php 
                        $sortedEvents = $eraEvents;
                        usort($sortedEvents, function($a, $b) {
                            return $a['year'] - $b['year'];
                        });
                        foreach ($sortedEvents as $event): 
                        ?>
                        <div style="margin-bottom: 0.8rem; padding: 0.5rem 0.8rem; background: rgba(255,255,255,0.03); border-radius: 6px; transition: all 0.3s; hover:background: rgba(255,255,255,0.06);">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                                <span style="font-weight: 500; color: #fff;"><?php echo escape($event['title']); ?></span>
                                <span style="font-family: 'Orbitron', monospace; color: <?php echo $eraColor; ?>; font-size: 0.85rem;"><?php echo $event['year']; ?></span>
                            </div>
                            <p style="font-size: 0.85rem; color: #8892b0; margin-top: 0.3rem;"><?php echo escape(truncate($event['description'], 120)); ?></p>
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.3rem; flex-wrap: wrap;">
                                <?php if ($event['is_featured']): ?>
                                <span style="font-size: 0.6rem; background: rgba(255,215,0,0.1); color: #ffd700; padding: 0.1rem 0.5rem; border-radius: 12px; border: 1px solid rgba(255,215,0,0.2);">⭐ Featured</span>
                                <?php endif; ?>
                                <?php if ($event['event_type']): ?>
                                <span style="font-size: 0.6rem; background: rgba(255,255,255,0.05); color: #8892b0; padding: 0.1rem 0.5rem; border-radius: 12px;"><?php echo ucfirst($event['event_type']); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="event.php?slug=<?php echo $event['slug']; ?>" style="font-size: 0.8rem; color: <?php echo $eraColor; ?>; text-decoration: none; display: inline-block; margin-top: 0.3rem;">
                                Learn more →
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Future Era -->
        <div class="timeline-item even" data-era="future" id="future">
            <div class="timeline-dot" style="background: #7b2ffc; box-shadow: 0 0 20px rgba(123, 47, 252, 0.4);"></div>
            <div class="timeline-content" style="border-color: #7b2ffc40;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span style="font-size: 1.5rem;">🚀</span>
                    <div>
                        <div class="timeline-year" style="color: #7b2ffc;">2030+</div>
                        <h3 class="timeline-title" style="font-size: 1.2rem;">The Future</h3>
                    </div>
                </div>
                <p class="timeline-description">Quantum computing, AI agents, brain-computer interfaces, and beyond.</p>
                <div style="margin-top: 1rem; padding: 0.8rem; background: rgba(123,47,252,0.05); border-radius: 8px; border: 1px solid rgba(123,47,252,0.1);">
                    <p style="color: #8892b0; font-size: 0.85rem;">💡 Explore predictions for technology in the coming decades.</p>
                </div>
                <a href="future.php" style="display: inline-block; margin-top: 1rem; padding: 0.4rem 1.5rem; background: linear-gradient(135deg, #7b2ffc, #00d4ff); border: none; border-radius: 6px; color: #fff; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    Explore the Future →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Info -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 3rem 2rem;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h3 style="color: #00d4ff; margin-bottom: 1rem; font-size: 1.5rem;">📅 About the Timeline</h3>
        <p style="color: #8892b0; line-height: 1.8; font-size: 1.05rem;">
            The Digital History Timeline takes you on a journey through the most important moments in 
            technology history. From the first mechanical calculators to the dawn of artificial intelligence, 
            explore the events that shaped our digital world.
        </p>
        <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap;">
            <div style="background: rgba(255,255,255,0.03); padding: 0.8rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 1.2rem; font-weight: 700; color: #00d4ff;"><?php echo $totalEras; ?></div>
                <div style="color: #8892b0; font-size: 0.8rem;">Eras</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 0.8rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 1.2rem; font-weight: 700; color: #7b2ffc;"><?php echo $totalEvents; ?></div>
                <div style="color: #8892b0; font-size: 0.8rem;">Events</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 0.8rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 1.2rem; font-weight: 700; color: #00ff88;"><?php echo ($newestEvent - $oldestEvent); ?>+</div>
                <div style="color: #8892b0; font-size: 0.8rem;">Years of History</div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Guide -->
<section class="section" style="padding: 3rem 2rem;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h3 style="color: #ffa500; margin-bottom: 1rem; font-size: 1.5rem;">🎯 How to Use</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; margin-bottom: 0.3rem;">👆</div>
                <div style="color: #fff; font-weight: 500; font-size: 0.9rem;">Click</div>
                <div style="color: #8892b0; font-size: 0.75rem;">Expand events</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; margin-bottom: 0.3rem;">📜</div>
                <div style="color: #fff; font-weight: 500; font-size: 0.9rem;">Scroll</div>
                <div style="color: #8892b0; font-size: 0.75rem;">Explore eras</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; margin-bottom: 0.3rem;">🔍</div>
                <div style="color: #fff; font-weight: 500; font-size: 0.9rem;">Navigate</div>
                <div style="color: #8892b0; font-size: 0.75rem;">Jump to eras</div>
            </div>
            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 2rem; margin-bottom: 0.3rem;">📖</div>
                <div style="color: #fff; font-weight: 500; font-size: 0.9rem;">Learn</div>
                <div style="color: #8892b0; font-size: 0.75rem;">Read details</div>
            </div>
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// ======================================================
// TIMELINE INTERACTIVE JAVASCRIPT
// ======================================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ---- Toggle Events ----
    document.querySelectorAll('.btn-toggle-events').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const eraId = this.dataset.era;
            const eventsDiv = document.getElementById('eraEvents_' + eraId);
            const icon = this.querySelector('i');
            
            if (eventsDiv) {
                if (eventsDiv.style.display === 'none' || eventsDiv.style.display === '') {
                    eventsDiv.style.display = 'block';
                    this.innerHTML = '<i class="fas fa-chevron-up"></i> Hide Events (' + eventsDiv.querySelectorAll('div').length + ')';
                    // Add animation
                    eventsDiv.style.animation = 'slideDown 0.3s ease';
                } else {
                    eventsDiv.style.display = 'none';
                    const count = eventsDiv.querySelectorAll('div').length;
                    this.innerHTML = '<i class="fas fa-chevron-down"></i> View Events (' + count + ')';
                }
            }
        });
    });
    
    // ---- Era Navigation ----
    document.querySelectorAll('.era-nav-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const eraId = this.dataset.era;
            const target = document.querySelector('.timeline-item[data-era="' + eraId + '"]');
            
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Highlight effect
                target.style.background = 'rgba(0, 212, 255, 0.05)';
                target.style.borderRadius = '8px';
                target.style.transition = 'all 0.3s ease';
                
                // Auto-expand events
                const eventsDiv = document.getElementById('eraEvents_' + eraId);
                const toggleBtn = target.querySelector('.btn-toggle-events');
                if (eventsDiv && eventsDiv.style.display === 'none' && toggleBtn) {
                    eventsDiv.style.display = 'block';
                    toggleBtn.innerHTML = '<i class="fas fa-chevron-up"></i> Hide Events (' + eventsDiv.querySelectorAll('div').length + ')';
                }
                
                setTimeout(function() {
                    target.style.background = 'transparent';
                }, 2000);
            }
        });
    });
    
    // ---- View All ----
    document.getElementById('viewAllBtn').addEventListener('click', function() {
        document.querySelectorAll('.era-events').forEach(function(div) {
            div.style.display = 'block';
        });
        document.querySelectorAll('.btn-toggle-events').forEach(function(btn) {
            const count = btn.parentElement.querySelector('.era-events').querySelectorAll('div').length;
            btn.innerHTML = '<i class="fas fa-chevron-up"></i> Hide Events (' + count + ')';
        });
    });
    
    // ---- Collapse All ----
    document.getElementById('collapseAllBtn').addEventListener('click', function() {
        document.querySelectorAll('.era-events').forEach(function(div) {
            div.style.display = 'none';
        });
        document.querySelectorAll('.btn-toggle-events').forEach(function(btn) {
            const count = btn.parentElement.querySelector('.era-events').querySelectorAll('div').length;
            btn.innerHTML = '<i class="fas fa-chevron-down"></i> View Events (' + count + ')';
        });
    });
    
    // ---- Highlight timeline items on scroll ----
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateX(0)';
                }
            });
        }, { threshold: 0.2 });
        
        timelineItems.forEach(function(item, index) {
            const isEven = index % 2 === 0;
            item.style.opacity = '0';
            item.style.transform = isEven ? 'translateX(-30px)' : 'translateX(30px)';
            item.style.transition = 'all 0.6s ease ' + (index * 0.1) + 's';
            observer.observe(item);
        });
    } else {
        // Fallback for older browsers
        timelineItems.forEach(function(item) {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        });
    }
    
    // ---- Keyboard Navigation ----
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
            const current = document.querySelector('.timeline-item[style*="opacity: 1"]') || document.querySelector('.timeline-item');
            if (current) {
                const next = current.nextElementSibling;
                if (next && next.classList.contains('timeline-item')) {
                    next.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    e.preventDefault();
                }
            }
        } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
            const current = document.querySelector('.timeline-item[style*="opacity: 1"]') || document.querySelector('.timeline-item');
            if (current) {
                const prev = current.previousElementSibling;
                if (prev && prev.classList.contains('timeline-item')) {
                    prev.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    e.preventDefault();
                }
            }
        }
    });
    
    // ---- Smooth scroll to future ----
    document.querySelector('a[href="#future"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.getElementById('future');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            target.style.background = 'rgba(123, 47, 252, 0.05)';
            target.style.borderRadius = '8px';
            setTimeout(function() {
                target.style.background = 'transparent';
            }, 2000);
        }
    });
    
    // ---- Add slideDown animation ----
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .era-events {
            animation: slideDown 0.3s ease;
        }
    `;
    document.head.appendChild(style);
});

// Add hover effects to timeline items
document.querySelectorAll('.timeline-content').forEach(function(content) {
    content.addEventListener('mouseenter', function() {
        this.style.borderColor = 'rgba(0, 212, 255, 0.3)';
        this.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.3)';
    });
    content.addEventListener('mouseleave', function() {
        this.style.borderColor = '';
        this.style.boxShadow = '';
    });
});

// Add hover effects to era nav buttons
document.querySelectorAll('.era-nav-btn').forEach(function(btn) {
    btn.addEventListener('mouseenter', function() {
        this.style.background = 'rgba(255, 255, 255, 0.05)';
        this.style.transform = 'translateY(-2px)';
    });
    btn.addEventListener('mouseleave', function() {
        this.style.background = '';
        this.style.transform = '';
    });
});
JS;

$pageCSS = '
.timeline-container {
    position: relative;
    padding: 2rem 0;
}

.timeline-line {
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    transform: translateX(-50%);
    background: linear-gradient(to bottom, transparent, #00d4ff, #7b2ffc, transparent);
}

.timeline-item {
    display: flex;
    justify-content: flex-end;
    padding: 2rem 0;
    position: relative;
    width: 50%;
}

.timeline-item:nth-child(odd) {
    padding-right: 3rem;
    padding-left: 0;
}

.timeline-item:nth-child(even) {
    padding-left: 3rem;
    padding-right: 0;
    align-self: flex-end;
    margin-left: 50%;
}

.timeline-dot {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #0a0a0f;
    z-index: 2;
    transition: all 0.3s;
}

.timeline-item:hover .timeline-dot {
    transform: translate(-50%, -50%) scale(1.5);
}

.timeline-content {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s;
    width: 100%;
}

.timeline-content:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.timeline-year {
    font-family: "Orbitron", monospace;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.2rem;
}

.timeline-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
    color: #fff;
}

.timeline-description {
    font-size: 0.95rem;
    color: #8892b0;
    line-height: 1.6;
}

.era-events {
    animation: slideDown 0.3s ease;
}

.era-events > div:hover {
    background: rgba(255, 255, 255, 0.06) !important;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.era-nav-btn {
    transition: all 0.3s ease;
}

.era-nav-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

@media (max-width: 992px) {
    .timeline-item {
        width: 100%;
        padding: 1.5rem 0;
    }
    
    .timeline-item:nth-child(odd) {
        padding-right: 0;
    }
    
    .timeline-item:nth-child(even) {
        padding-left: 0;
        margin-left: 0;
    }
    
    .timeline-line {
        left: 20px;
    }
    
    .timeline-dot {
        left: 20px;
    }
    
    .timeline-content {
        margin-left: 40px;
    }
}

@media (max-width: 768px) {
    .timeline-content {
        padding: 1rem;
    }
    
    .timeline-year {
        font-size: 0.9rem;
    }
    
    .timeline-title {
        font-size: 1rem;
    }
    
    .timeline-description {
        font-size: 0.85rem;
    }
    
    .era-nav-btn {
        font-size: 0.7rem !important;
        padding: 0.2rem 0.6rem !important;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>