<?php
// people.php
// Digital History - Famous People (Fully Developed)

require_once 'includes/config.php';

$pageTitle = 'Famous People';
$pageDescription = 'Meet the pioneers and visionaries who shaped the world of technology and computing.';

$people = getPeople();
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'name';

// Filter by search
if ($search) {
    $people = array_filter($people, function($p) use ($search) {
        return stripos($p['full_name'], $search) !== false || 
               stripos($p['biography'], $search) !== false ||
               stripos($p['known_for'], $search) !== false ||
               stripos($p['nationality'], $search) !== false;
    });
}

// Filter by category (known_for contains keyword)
if ($category) {
    $people = array_filter($people, function($p) use ($category) {
        return stripos($p['known_for'], $category) !== false;
    });
}

// Sort people
if ($sort === 'name') {
    usort($people, function($a, $b) {
        return strcmp($a['full_name'], $b['full_name']);
    });
} elseif ($sort === 'birth') {
    usort($people, function($a, $b) {
        return ($a['birth_year'] ?? 9999) - ($b['birth_year'] ?? 9999);
    });
} elseif ($sort === 'recent') {
    usort($people, function($a, $b) {
        return ($b['birth_year'] ?? 0) - ($a['birth_year'] ?? 0);
    });
}

// Get categories from known_for
$allCategories = [];
foreach ($people as $p) {
    if (!empty($p['known_for'])) {
        $terms = explode(',', $p['known_for']);
        foreach ($terms as $term) {
            $term = trim($term);
            if (!empty($term) && !in_array($term, $allCategories)) {
                $allCategories[] = $term;
            }
        }
    }
}
sort($allCategories);

trackPageView('people');

ob_start();
?>

<!-- Page Header with Animation -->
<section class="section" style="padding-top: 3rem; padding-bottom: 1rem; position: relative; overflow: hidden;">
    <div class="section-header">
        <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); background: linear-gradient(135deg, #00d4ff, #7b2ffc, #ff0064); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: glowPulse 3s ease-in-out infinite;">
            <?php echo t('people.title'); ?>
        </h2>
        <p style="font-size: 1.2rem; color: #8892b0;"><?php echo t('people.subtitle'); ?></p>
    </div>
    
    <!-- Quick Stats Bar -->
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00d4ff;"><?php echo count($people); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Total People</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #7b2ffc;"><?php echo count($allCategories); ?></div>
            <div style="color: #8892b0; font-size: 0.85rem;">Categories</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #00ff88;">
                <?php 
                $living = count(array_filter($people, function($p) { return empty($p['death_year']); }));
                echo $living;
                ?>
            </div>
            <div style="color: #8892b0; font-size: 0.85rem;">Living Pioneers</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ffa500;">
                <?php 
                $nationalities = array_unique(array_filter(array_column($people, 'nationality')));
                echo count($nationalities);
                ?>
            </div>
            <div style="color: #8892b0; font-size: 0.85rem;">Nationalities</div>
        </div>
    </div>
</section>

<!-- Search and Filters -->
<section class="section" style="padding-top: 0;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Search Form -->
        <form method="GET" action="" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" placeholder="Search people by name, biography, or contribution..." value="<?php echo escape($search); ?>" style="width: 100%; padding: 0.6rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 0.95rem; transition: border-color 0.3s;">
            </div>
            <select name="category" style="padding: 0.6rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #8892b0; font-size: 0.95rem; cursor: pointer; min-width: 150px;">
                <option value="">All Categories</option>
                <?php foreach ($allCategories as $cat): ?>
                <option value="<?php echo escape($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                    <?php echo escape($cat); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select name="sort" style="padding: 0.6rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #8892b0; font-size: 0.95rem; cursor: pointer; min-width: 150px;">
                <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Sort by Name</option>
                <option value="birth" <?php echo $sort === 'birth' ? 'selected' : ''; ?>>Sort by Birth (Oldest)</option>
                <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>>Sort by Birth (Recent)</option>
            </select>
            <button type="submit" style="padding: 0.6rem 1.2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 600;">
                <i class="fas fa-search"></i> Filter
            </button>
            <?php if ($search || $category || $sort !== 'name'): ?>
            <a href="people.php" style="padding: 0.6rem 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #8892b0; text-decoration: none; display: flex; align-items: center;">
                <i class="fas fa-times"></i> Clear
            </a>
            <?php endif; ?>
        </form>
        
        <!-- Results count -->
        <div style="color: #8892b0; font-size: 0.9rem; text-align: center; margin-top: 0.5rem;">
            Showing <?php echo count($people); ?> people
            <?php if ($search): ?>
            matching "<strong style="color: #00d4ff;"><?php echo escape($search); ?></strong>"
            <?php endif; ?>
            <?php if ($category): ?>
            in category "<strong style="color: #7b2ffc;"><?php echo escape($category); ?></strong>"
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- People Grid -->
<section class="section" style="padding-top: 0;">
    <?php if (empty($people)): ?>
    <div style="text-align: center; padding: 4rem 0; background: rgba(255,255,255,0.02); border-radius: 12px;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
        <p style="color: #8892b0; font-size: 1.1rem;">No people found matching your search criteria.</p>
        <p style="color: #8892b0; font-size: 0.9rem;">Try adjusting your filters or search terms.</p>
        <a href="people.php" style="display: inline-block; margin-top: 1rem; color: #00d4ff; text-decoration: none;">View all people →</a>
    </div>
    <?php else: ?>
    <div class="card-grid">
        <?php foreach ($people as $person): ?>
        <div class="card animate-on-scroll" style="text-align: center; transition: all 0.3s;">
            <div style="font-size: 4rem; margin-bottom: 0.5rem; position: relative;">
                <?php 
                $portrait = $person['portrait'] ?? '';
                if ($portrait && file_exists(UPLOADS_PATH . $portrait)) {
                    echo '<img src="' . UPLOADS_URL . $portrait . '" alt="' . escape($person['full_name']) . '" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #00d4ff;">';
                } else {
                    echo '👤';
                }
                ?>
                <?php if (empty($person['death_year'])): ?>
                <span style="position: absolute; bottom: 0; right: 5px; font-size: 0.8rem; background: #00ff88; color: #000; padding: 0.1rem 0.4rem; border-radius: 12px; font-weight: 600;">Living</span>
                <?php endif; ?>
            </div>
            <div class="card-title" style="font-size: 1.1rem;"><?php echo escape($person['full_name']); ?></div>
            <div style="color: #00d4ff; font-size: 0.85rem; margin-bottom: 0.3rem;">
                <?php echo escape($person['known_for']); ?>
            </div>
            <div style="color: #8892b0; font-size: 0.8rem; margin-bottom: 0.5rem;">
                <?php 
                $years = [];
                if ($person['birth_year']) $years[] = $person['birth_year'];
                if ($person['death_year']) $years[] = $person['death_year'];
                echo implode(' - ', $years);
                if ($person['nationality']) echo ' · ' . escape($person['nationality']);
                ?>
            </div>
            <div class="card-description" style="font-size: 0.85rem;"><?php echo escape(truncate($person['biography'], 100)); ?></div>
            <div style="margin-top: 0.5rem; display: flex; gap: 0.3rem; justify-content: center; flex-wrap: wrap;">
                <?php 
                $tags = explode(',', $person['known_for']);
                foreach (array_slice($tags, 0, 3) as $tag):
                    $tag = trim($tag);
                    if (!empty($tag)):
                ?>
                <span style="font-size: 0.6rem; background: rgba(255,255,255,0.05); padding: 0.1rem 0.5rem; border-radius: 4px; color: #8892b0;"><?php echo escape($tag); ?></span>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            <a href="person.php?slug=<?php echo $person['slug']; ?>" style="display: inline-block; margin-top: 0.8rem; padding: 0.3rem 1.5rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border-radius: 6px; color: #fff; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                View Profile →
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<!-- Letters Navigation -->
<?php if (empty($search) && empty($category)): ?>
<section class="section" style="padding-top: 0; padding-bottom: 2rem;">
    <div style="display: flex; flex-wrap: wrap; gap: 0.3rem; justify-content: center; max-width: 800px; margin: 0 auto;">
        <?php
        $letters = range('A', 'Z');
        foreach ($letters as $letter):
            $count = count(array_filter($people, function($p) use ($letter) {
                return stripos($p['full_name'], $letter) === 0;
            }));
        ?>
        <a href="#letter-<?php echo $letter; ?>" style="padding: 0.3rem 0.6rem; background: <?php echo $count > 0 ? 'rgba(255,255,255,0.03)' : 'rgba(255,255,255,0.01)'; ?>; border: 1px solid <?php echo $count > 0 ? 'rgba(255,255,255,0.06)' : 'rgba(255,255,255,0.02)'; ?>; border-radius: 4px; color: <?php echo $count > 0 ? '#8892b0' : 'rgba(136, 146, 176, 0.3)'; ?>; text-decoration: none; font-size: 0.8rem; transition: all 0.3s; <?php echo $count === 0 ? 'pointer-events: none;' : ''; ?>">
            <?php echo $letter; ?>
            <?php if ($count > 0): ?>
            <span style="font-size: 0.6rem; color: #00d4ff;">(<?php echo $count; ?>)</span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Categories Cloud -->
<section class="section" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 3rem 2rem;">
    <div class="section-header">
        <h2 style="color: #7b2ffc;">🏷️ Explore by Category</h2>
        <p style="color: #8892b0;">Browse people by their contributions and fields of expertise.</p>
    </div>
    
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; max-width: 900px; margin: 0 auto;">
        <?php 
        $categoryCounts = [];
        foreach ($people as $p) {
            if (!empty($p['known_for'])) {
                $terms = explode(',', $p['known_for']);
                foreach ($terms as $term) {
                    $term = trim($term);
                    if (!empty($term)) {
                        $categoryCounts[$term] = ($categoryCounts[$term] ?? 0) + 1;
                    }
                }
            }
        }
        arsort($categoryCounts);
        $topCategories = array_slice($categoryCounts, 0, 20);
        ?>
        
        <a href="people.php" style="padding: 0.4rem 1.2rem; background: <?php echo !$category ? 'rgba(0,212,255,0.1)' : 'rgba(255,255,255,0.03)'; ?>; border: 1px solid <?php echo !$category ? 'rgba(0,212,255,0.2)' : 'rgba(255,255,255,0.06)'; ?>; border-radius: 20px; color: <?php echo !$category ? '#00d4ff' : '#8892b0'; ?>; text-decoration: none; font-size: 0.85rem; transition: all 0.3s;">
            All (<?php echo count($people); ?>)
        </a>
        
        <?php foreach ($topCategories as $cat => $count): ?>
        <a href="people.php?category=<?php echo urlencode($cat); ?>" style="padding: 0.4rem 1.2rem; background: <?php echo $category === $cat ? 'rgba(123,47,252,0.1)' : 'rgba(255,255,255,0.03)'; ?>; border: 1px solid <?php echo $category === $cat ? 'rgba(123,47,252,0.2)' : 'rgba(255,255,255,0.06)'; ?>; border-radius: 20px; color: <?php echo $category === $cat ? '#7b2ffc' : '#8892b0'; ?>; text-decoration: none; font-size: 0.85rem; transition: all 0.3s;">
            <?php echo escape($cat); ?> (<?php echo $count; ?>)
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Timeline of Pioneers -->
<section class="section" style="padding: 3rem 2rem;">
    <div class="section-header">
        <h2 style="color: #00ff88;">📅 Pioneers Timeline</h2>
        <p style="color: #8892b0;">Key figures in technology history chronologically.</p>
    </div>
    
    <div style="max-width: 900px; margin: 0 auto; position: relative; padding-left: 2rem; border-left: 2px solid rgba(0,255,136,0.2);">
        <?php 
        $timelinePeople = array_filter($people, function($p) { return !empty($p['birth_year']); });
        usort($timelinePeople, function($a, $b) {
            return ($a['birth_year'] ?? 9999) - ($b['birth_year'] ?? 9999);
        });
        $timelinePeople = array_slice($timelinePeople, 0, 15);
        ?>
        
        <?php foreach ($timelinePeople as $p): ?>
        <div style="position: relative; padding-bottom: 1.5rem; padding-left: 1.5rem;">
            <div style="position: absolute; left: -2rem; top: 0.5rem; width: 14px; height: 14px; border-radius: 50%; background: <?php echo empty($p['death_year']) ? '#00ff88' : '#7b2ffc'; ?>; border: 3px solid #0a0a0f; box-shadow: 0 0 20px <?php echo empty($p['death_year']) ? 'rgba(0,255,136,0.3)' : 'rgba(123,47,252,0.3)'; ?>;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <a href="person.php?slug=<?php echo $p['slug']; ?>" style="color: #fff; font-weight: 500; text-decoration: none; font-size: 1.05rem;">
                        <?php echo escape($p['full_name']); ?>
                    </a>
                    <div style="color: #8892b0; font-size: 0.85rem;"><?php echo escape($p['known_for']); ?></div>
                </div>
                <div style="font-family: 'Orbitron', monospace; color: #00d4ff; font-size: 0.9rem;">
                    <?php echo $p['birth_year']; ?>
                    <?php if ($p['death_year']): ?> - <?php echo $p['death_year']; ?><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- People Details Modal -->
<div id="peopleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 6000; overflow-y: auto; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
        <button onclick="closePeopleModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
        <div id="peopleModalContent">
            <!-- Dynamically loaded -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

$pageJS = <<<'JS'
// Smooth scroll to person cards
document.querySelectorAll("[href^=\"#letter-\"]").forEach(link => {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        const letter = this.textContent.trim();
        const cards = document.querySelectorAll(".card");
        for (const card of cards) {
            const name = card.querySelector(".card-title");
            if (name && name.textContent.toUpperCase().startsWith(letter)) {
                card.scrollIntoView({ behavior: "smooth", block: "center" });
                card.style.borderColor = "#00d4ff";
                card.style.boxShadow = "0 0 30px rgba(0, 212, 255, 0.2)";
                setTimeout(() => {
                    card.style.borderColor = "";
                    card.style.boxShadow = "";
                }, 2000);
                break;
            }
        }
    });
});

// Add hover effects to cards
document.querySelectorAll(".card").forEach(card => {
    card.addEventListener("mouseenter", function() {
        this.style.transform = "translateY(-6px)";
        this.style.borderColor = "rgba(0, 212, 255, 0.3)";
        this.style.boxShadow = "0 8px 30px rgba(0, 0, 0, 0.3)";
    });
    card.addEventListener("mouseleave", function() {
        this.style.transform = "translateY(0)";
        this.style.borderColor = "rgba(255, 255, 255, 0.06)";
        this.style.boxShadow = "none";
    });
});

// Search input auto-focus
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.querySelector("input[name='search']");
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});

// Category filter highlighting
document.querySelectorAll("[href^=\"people.php?category=\"]").forEach(link => {
    link.addEventListener("click", function(e) {
        // The page will reload with the filter
    });
});

// Add glow animation
const style = document.createElement("style");
style.textContent = `
    @keyframes glowPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
`;
document.head.appendChild(style);
JS;

$pageCSS = '
input:focus, select:focus {
    outline: none;
    border-color: #00d4ff !important;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-6px);
    border-color: rgba(0, 212, 255, 0.3);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.letter-nav a {
    transition: all 0.3s ease;
}

.letter-nav a:hover:not([style*="pointer-events: none"]) {
    background: rgba(0, 212, 255, 0.1) !important;
    color: #00d4ff !important;
}

@media (max-width: 768px) {
    form[method="GET"] {
        flex-direction: column;
    }
    
    form[method="GET"] > * {
        width: 100%;
    }
    
    form[method="GET"] select {
        min-width: auto;
    }
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>