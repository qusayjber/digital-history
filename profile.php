<?php
// profile.php
// Digital History - User Profile

require_once 'includes/config.php';

$pageTitle = 'My Profile';

// Require login
requireLogin();

$user = getCurrentUser();
if (!$user) {
    redirect('login.php');
}

$userId = $user['id'];
$tab = $_GET['tab'] ?? 'profile';
$message = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tab === 'profile') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
        ];
        
        if (!empty($_POST['password']) || !empty($_POST['password_confirm'])) {
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $error = 'Passwords do not match.';
            } else {
                $data['password'] = $_POST['password'];
            }
        }
        
        if (empty($error)) {
            $result = updateUser($userId, $data);
            if ($result['success']) {
                $message = 'Profile updated successfully!';
                // Refresh user data
                $user = getCurrentUser();
            } else {
                $error = $result['error'];
            }
        }
    }
}

// Get user achievements
$achievements = getUserAchievements($userId);
$allAchievements = getAchievements();

// Get user favorites
$favorites = db()->fetchAll(
    "SELECT item_type, item_id, created_at FROM user_favorites WHERE user_id = ? ORDER BY created_at DESC",
    [$userId]
);

// Get quiz attempts
$quizAttempts = db()->fetchAll(
    "SELECT q.title, uqa.score, uqa.total_possible, uqa.completed_at 
     FROM user_quiz_attempts uqa 
     JOIN quizzes q ON uqa.quiz_id = q.id 
     WHERE uqa.user_id = ? 
     ORDER BY uqa.completed_at DESC LIMIT 10",
    [$userId]
);

trackPageView('profile');

ob_start();
?>

<!-- Page Header -->
<section class="section" style="padding-top: 3rem; padding-bottom: 0;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2>👤 My Profile</h2>
                <p style="color: #8892b0;">Welcome back, <?php echo escape($user['full_name'] ?? $user['username']); ?></p>
            </div>
            <a href="logout.php" style="color: #ff6b6b; text-decoration: none; font-size: 0.9rem;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</section>

<!-- Profile Tabs -->
<section class="section" style="padding-top: 1rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <!-- Tabs Navigation -->
        <div style="display: flex; gap: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); margin-bottom: 2rem; flex-wrap: wrap;">
            <a href="?tab=profile" style="padding: 0.8rem 1.5rem; color: <?php echo $tab === 'profile' ? '#00d4ff' : '#8892b0'; ?>; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'profile' ? '#00d4ff' : 'transparent'; ?>; transition: all 0.3s;">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="?tab=achievements" style="padding: 0.8rem 1.5rem; color: <?php echo $tab === 'achievements' ? '#00d4ff' : '#8892b0'; ?>; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'achievements' ? '#00d4ff' : 'transparent'; ?>; transition: all 0.3s;">
                <i class="fas fa-trophy"></i> Achievements <?php echo count($achievements) > 0 ? '<span style="background: #00d4ff; color: #000; padding: 0.1rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">' . count($achievements) . '</span>' : ''; ?>
            </a>
            <a href="?tab=favorites" style="padding: 0.8rem 1.5rem; color: <?php echo $tab === 'favorites' ? '#00d4ff' : '#8892b0'; ?>; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'favorites' ? '#00d4ff' : 'transparent'; ?>; transition: all 0.3s;">
                <i class="fas fa-star"></i> Favorites
            </a>
            <a href="?tab=quizzes" style="padding: 0.8rem 1.5rem; color: <?php echo $tab === 'quizzes' ? '#00d4ff' : '#8892b0'; ?>; text-decoration: none; border-bottom: 2px solid <?php echo $tab === 'quizzes' ? '#00d4ff' : 'transparent'; ?>; transition: all 0.3s;">
                <i class="fas fa-question-circle"></i> Quiz History
            </a>
        </div>
        
        <!-- Tab Content -->
        <?php if ($tab === 'profile'): ?>
        <!-- Profile Tab -->
        <div>
            <?php if ($message): ?>
            <div style="background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); color: #00d4ff; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.2); color: #ff6b6b; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="?tab=profile" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 2rem;">
                <?php echo getCSRFField(); ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Username</label>
                        <input type="text" value="<?php echo escape($user['username']); ?>" disabled style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; color: #8892b0; font-size: 1rem; cursor: not-allowed;">
                        <div style="font-size: 0.75rem; color: #8892b0; margin-top: 0.3rem;">Username cannot be changed</div>
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Email</label>
                        <input type="email" value="<?php echo escape($user['email']); ?>" disabled style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; color: #8892b0; font-size: 1rem; cursor: not-allowed;">
                        <div style="font-size: 0.75rem; color: #8892b0; margin-top: 0.3rem;">Email cannot be changed</div>
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Full Name</label>
                        <input type="text" name="full_name" value="<?php echo escape($user['full_name'] ?? ''); ?>" placeholder="Your full name" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Member Since</label>
                        <input type="text" value="<?php echo formatDate($user['created_at']); ?>" disabled style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; color: #8892b0; font-size: 1rem; cursor: not-allowed;">
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Bio</label>
                    <textarea name="bio" rows="3" placeholder="Tell us about yourself..." style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s; resize: vertical; font-family: inherit;"><?php echo escape($user['bio'] ?? ''); ?></textarea>
                </div>
                
                <div style="margin-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem;">
                    <h4 style="color: #ffa500; margin-bottom: 1rem;">Change Password (Optional)</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">New Password</label>
                            <input type="password" name="password" placeholder="Enter new password" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #ccd6f6; margin-bottom: 0.3rem;">Confirm Password</label>
                            <input type="password" name="password_confirm" placeholder="Confirm new password" style="width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #fff; font-size: 1rem; transition: border-color 0.3s;">
                        </div>
                    </div>
                    <div style="font-size: 0.75rem; color: #8892b0; margin-top: 0.5rem;">Leave blank to keep current password. Must be at least 8 characters with uppercase, lowercase, and numbers.</div>
                </div>
                
                <button type="submit" style="margin-top: 1.5rem; padding: 0.8rem 2rem; background: linear-gradient(135deg, #00d4ff, #7b2ffc); border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
        <?php endif; ?>
        
        <?php if ($tab === 'achievements'): ?>
        <!-- Achievements Tab -->
        <div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                <?php
                $unlockedIds = array_column($achievements, 'id');
                foreach ($allAchievements as $achievement):
                    $isUnlocked = in_array($achievement['id'], $unlockedIds);
                ?>
                <div style="background: <?php echo $isUnlocked ? 'rgba(0,212,255,0.05)' : 'rgba(255,255,255,0.02)'; ?>; border: 1px solid <?php echo $isUnlocked ? 'rgba(0,212,255,0.2)' : 'rgba(255,255,255,0.05)'; ?>; border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s; <?php echo $isUnlocked ? '' : 'opacity: 0.5;'; ?>">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;"><?php echo $achievement['icon'] ?? '🏆'; ?></div>
                    <h4 style="color: <?php echo $isUnlocked ? '#fff' : '#8892b0'; ?>; font-size: 0.95rem;"><?php echo escape($achievement['name']); ?></h4>
                    <p style="color: #8892b0; font-size: 0.8rem;"><?php echo escape($achievement['description']); ?></p>
                    <?php if ($isUnlocked): ?>
                    <div style="margin-top: 0.5rem; font-size: 0.7rem; color: #00d4ff;">
                        <i class="fas fa-check-circle"></i> Unlocked
                        <?php 
                        $unlockDate = array_filter($achievements, function($a) use ($achievement) {
                            return $a['id'] === $achievement['id'];
                        });
                        if (!empty($unlockDate)) {
                            $first = reset($unlockDate);
                            echo '· ' . formatDate($first['unlocked_at']);
                        }
                        ?>
                    </div>
                    <?php else: ?>
                    <div style="margin-top: 0.5rem; font-size: 0.7rem; color: #8892b0;">
                        <i class="fas fa-lock"></i> Locked
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($tab === 'favorites'): ?>
        <!-- Favorites Tab -->
        <div>
            <?php if (empty($favorites)): ?>
            <div style="text-align: center; padding: 3rem 0; background: rgba(255,255,255,0.02); border-radius: 12px;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">⭐</div>
                <p style="color: #8892b0;">You haven't saved any favorites yet.</p>
                <p style="color: #8892b0; font-size: 0.9rem;">Explore the site and click the <i class="fas fa-star" style="color: #ffa500;"></i> icon to save content.</p>
                <a href="timeline.php" style="display: inline-block; margin-top: 1rem; color: #00d4ff; text-decoration: none;">Start Exploring →</a>
            </div>
            <?php else: ?>
            <div style="display: grid; gap: 0.8rem;">
                <?php foreach ($favorites as $fav): ?>
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="color: #8892b0; font-size: 0.8rem;"><?php echo ucfirst($fav['item_type']); ?></span>
                        <span style="color: #fff; margin-left: 0.5rem;">#<?php echo $fav['item_id']; ?></span>
                    </div>
                    <div style="color: #8892b0; font-size: 0.8rem;">
                        <?php echo formatDate($fav['created_at']); ?>
                        <span style="margin-left: 0.5rem; color: #ffa500;"><i class="fas fa-star"></i></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($tab === 'quizzes'): ?>
        <!-- Quiz History Tab -->
        <div>
            <?php if (empty($quizAttempts)): ?>
            <div style="text-align: center; padding: 3rem 0; background: rgba(255,255,255,0.02); border-radius: 12px;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">📝</div>
                <p style="color: #8892b0;">You haven't taken any quizzes yet.</p>
                <a href="games.php" style="display: inline-block; margin-top: 1rem; color: #00d4ff; text-decoration: none;">Play a Quiz →</a>
            </div>
            <?php else: ?>
            <div style="display: grid; gap: 0.8rem;">
                <?php foreach ($quizAttempts as $attempt): ?>
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h4 style="color: #fff; font-size: 1rem;"><?php echo escape($attempt['title']); ?></h4>
                        <span style="color: #8892b0; font-size: 0.8rem;"><?php echo formatDate($attempt['completed_at']); ?></span>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-family: 'Orbitron', monospace; font-size: 1.2rem; color: <?php echo ($attempt['score'] / max(1, $attempt['total_possible'])) * 100 >= 70 ? '#00ff88' : '#ff6b6b'; ?>;">
                            <?php echo $attempt['score']; ?>/<?php echo $attempt['total_possible']; ?>
                        </span>
                        <div style="font-size: 0.8rem; color: #8892b0;">
                            <?php echo round(($attempt['score'] / max(1, $attempt['total_possible'])) * 100); ?>%
                            <?php if (($attempt['score'] / max(1, $attempt['total_possible'])) * 100 >= 70): ?>
                            <span style="color: #00ff88;">✅ Passed</span>
                            <?php else: ?>
                            <span style="color: #ff6b6b;">❌ Failed</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
$pageContent = ob_get_clean();
$pageCSS = '
input:focus, textarea:focus {
    outline: none;
    border-color: #00d4ff !important;
}
';

require_once 'includes/header.php';
echo $pageContent;
require_once 'includes/footer.php';
?>