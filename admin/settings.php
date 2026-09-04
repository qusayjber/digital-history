<?php
// admin/settings.php
// Digital History Admin - Settings

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Settings';

$message = '';
$error = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $settings = $_POST['settings'] ?? [];
        
        foreach ($settings as $key => $value) {
            $exists = db()->fetch("SELECT id FROM settings WHERE key_name = ?", [$key]);
            if ($exists) {
                db()->update('settings', ['value' => $value], 'key_name = ?', [$key]);
            } else {
                db()->insert('settings', [
                    'key_name' => $key,
                    'value' => $value,
                    'group_name' => 'general',
                    'is_public' => 1
                ]);
            }
        }
        
        logActivity('admin_update_settings');
        $message = 'Settings updated successfully!';
    }
}

// Get current settings
$settings = [];
$settingsData = db()->fetchAll("SELECT * FROM settings");
foreach ($settingsData as $row) {
    $settings[$row['key_name']] = $row['value'];
}

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-cog"></i> Settings</h2>
    </div>
    
    <?php if ($message): ?>
    <div class="admin-message success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="admin-message error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="settings.php">
        <?php echo getCSRFField(); ?>
        
        <div class="settings-grid">
            <!-- General Settings -->
            <div class="settings-card">
                <h3><i class="fas fa-globe"></i> General Settings</h3>
                <div class="form-group">
                    <label>Site Name</label>
                    <input type="text" name="settings[site_name]" value="<?php echo escape($settings['site_name'] ?? 'Digital History'); ?>">
                </div>
                <div class="form-group">
                    <label>Site Description</label>
                    <textarea name="settings[site_description]" rows="3"><?php echo escape($settings['site_description'] ?? 'The interactive museum of computing, programming, the internet and the future.'); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Default Language</label>
                    <select name="settings[default_language]">
                        <option value="en" <?php echo ($settings['default_language'] ?? 'en') === 'en' ? 'selected' : ''; ?>>English</option>
                        <option value="ar" <?php echo ($settings['default_language'] ?? 'en') === 'ar' ? 'selected' : ''; ?>>العربية</option>
                        <option value="fr" <?php echo ($settings['default_language'] ?? 'en') === 'fr' ? 'selected' : ''; ?>>Français</option>
                        <option value="es" <?php echo ($settings['default_language'] ?? 'en') === 'es' ? 'selected' : ''; ?>>Español</option>
                        <option value="de" <?php echo ($settings['default_language'] ?? 'en') === 'de' ? 'selected' : ''; ?>>Deutsch</option>
                        <option value="zh" <?php echo ($settings['default_language'] ?? 'en') === 'zh' ? 'selected' : ''; ?>>中文</option>
                        <option value="ja" <?php echo ($settings['default_language'] ?? 'en') === 'ja' ? 'selected' : ''; ?>>日本語</option>
                        <option value="ru" <?php echo ($settings['default_language'] ?? 'en') === 'ru' ? 'selected' : ''; ?>>Русский</option>
                        <option value="pt" <?php echo ($settings['default_language'] ?? 'en') === 'pt' ? 'selected' : ''; ?>>Português</option>
                        <option value="hi" <?php echo ($settings['default_language'] ?? 'en') === 'hi' ? 'selected' : ''; ?>>हिन्दी</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Time Zone</label>
                    <select name="settings[timezone]">
                        <option value="UTC" <?php echo ($settings['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                        <option value="America/New_York" <?php echo ($settings['timezone'] ?? 'UTC') === 'America/New_York' ? 'selected' : ''; ?>>Eastern Time (ET)</option>
                        <option value="America/Chicago" <?php echo ($settings['timezone'] ?? 'UTC') === 'America/Chicago' ? 'selected' : ''; ?>>Central Time (CT)</option>
                        <option value="America/Denver" <?php echo ($settings['timezone'] ?? 'UTC') === 'America/Denver' ? 'selected' : ''; ?>>Mountain Time (MT)</option>
                        <option value="America/Los_Angeles" <?php echo ($settings['timezone'] ?? 'UTC') === 'America/Los_Angeles' ? 'selected' : ''; ?>>Pacific Time (PT)</option>
                        <option value="Europe/London" <?php echo ($settings['timezone'] ?? 'UTC') === 'Europe/London' ? 'selected' : ''; ?>>London (GMT/BST)</option>
                        <option value="Europe/Paris" <?php echo ($settings['timezone'] ?? 'UTC') === 'Europe/Paris' ? 'selected' : ''; ?>>Paris (CET/CEST)</option>
                        <option value="Asia/Dubai" <?php echo ($settings['timezone'] ?? 'UTC') === 'Asia/Dubai' ? 'selected' : ''; ?>>Dubai (GST)</option>
                        <option value="Asia/Tokyo" <?php echo ($settings['timezone'] ?? 'UTC') === 'Asia/Tokyo' ? 'selected' : ''; ?>>Tokyo (JST)</option>
                        <option value="Australia/Sydney" <?php echo ($settings['timezone'] ?? 'UTC') === 'Australia/Sydney' ? 'selected' : ''; ?>>Sydney (AEST)</option>
                    </select>
                </div>
            </div>
            
            <!-- Contact Settings -->
            <div class="settings-card">
                <h3><i class="fas fa-envelope"></i> Contact Settings</h3>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="settings[contact_email]" value="<?php echo escape($settings['contact_email'] ?? 'contact@digitalhistory.com'); ?>">
                </div>
                <div class="form-group">
                    <label>Contact Phone (optional)</label>
                    <input type="text" name="settings[contact_phone]" value="<?php echo escape($settings['contact_phone'] ?? ''); ?>" placeholder="+1 234 567 8900">
                </div>
                <div class="form-group">
                    <label>Address (optional)</label>
                    <input type="text" name="settings[address]" value="<?php echo escape($settings['address'] ?? ''); ?>" placeholder="123 Tech Street, Silicon Valley, CA">
                </div>
            </div>
            
            <!-- User Settings -->
            <div class="settings-card">
                <h3><i class="fas fa-users"></i> User Settings</h3>
                <div class="form-group">
                    <label>Enable Registration</label>
                    <select name="settings[enable_registration]">
                        <option value="true" <?php echo ($settings['enable_registration'] ?? 'true') === 'true' ? 'selected' : ''; ?>>Yes</option>
                        <option value="false" <?php echo ($settings['enable_registration'] ?? 'true') === 'false' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Require Email Verification</label>
                    <select name="settings[require_email_verification]">
                        <option value="true" <?php echo ($settings['require_email_verification'] ?? 'false') === 'true' ? 'selected' : ''; ?>>Yes</option>
                        <option value="false" <?php echo ($settings['require_email_verification'] ?? 'false') === 'false' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Default User Role</label>
                    <select name="settings[default_role]">
                        <option value="user" <?php echo ($settings['default_role'] ?? 'user') === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="editor" <?php echo ($settings['default_role'] ?? 'user') === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    </select>
                </div>
            </div>
            
            <!-- Maintenance -->
            <div class="settings-card">
                <h3><i class="fas fa-tools"></i> Maintenance</h3>
                <div class="form-group">
                    <label>Maintenance Mode</label>
                    <select name="settings[maintenance_mode]">
                        <option value="false" <?php echo ($settings['maintenance_mode'] ?? 'false') === 'false' ? 'selected' : ''; ?>>Off</option>
                        <option value="true" <?php echo ($settings['maintenance_mode'] ?? 'false') === 'true' ? 'selected' : ''; ?>>On</option>
                    </select>
                    <small>When enabled, only admins can access the site</small>
                </div>
                <div class="form-group">
                    <label>Maintenance Message</label>
                    <textarea name="settings[maintenance_message]" rows="2" placeholder="We're currently performing maintenance. Please check back soon."><?php echo escape($settings['maintenance_message'] ?? 'We\'re currently performing maintenance. Please check back soon.'); ?></textarea>
                </div>
            </div>
            
            <!-- SEO Settings -->
            <div class="settings-card full-width">
                <h3><i class="fas fa-search"></i> SEO Settings</h3>
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <input type="text" name="settings[meta_keywords]" value="<?php echo escape($settings['meta_keywords'] ?? 'digital history, computing, programming, internet, technology, museum, timeline'); ?>">
                    <small>Comma-separated keywords</small>
                </div>
                <div class="form-group">
                    <label>Google Analytics ID</label>
                    <input type="text" name="settings[google_analytics_id]" value="<?php echo escape($settings['google_analytics_id'] ?? ''); ?>" placeholder="G-XXXXXXXXXX">
                </div>
                <div class="form-group">
                    <label>Facebook Pixel ID</label>
                    <input type="text" name="settings[facebook_pixel_id]" value="<?php echo escape($settings['facebook_pixel_id'] ?? ''); ?>" placeholder="123456789012345">
                </div>
            </div>
            
            <!-- Security Settings -->
            <div class="settings-card full-width">
                <h3><i class="fas fa-shield-alt"></i> Security Settings</h3>
                <div class="form-group">
                    <label>Session Lifetime (seconds)</label>
                    <input type="number" name="settings[session_lifetime]" value="<?php echo escape($settings['session_lifetime'] ?? '86400'); ?>" min="3600" max="604800">
                    <small>Default: 86400 (24 hours)</small>
                </div>
                <div class="form-group">
                    <label>Login Attempts Limit</label>
                    <input type="number" name="settings[login_attempts]" value="<?php echo escape($settings['login_attempts'] ?? '5'); ?>" min="3" max="10">
                    <small>Number of failed login attempts before temporary lockout</small>
                </div>
                <div class="form-group">
                    <label>Lockout Duration (minutes)</label>
                    <input type="number" name="settings[lockout_duration]" value="<?php echo escape($settings['lockout_duration'] ?? '15'); ?>" min="5" max="60">
                    <small>Minutes to lock out after max login attempts</small>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
        </div>
    </form>
</div>

<style>
.admin-page {
    padding: 1rem 0;
}

.admin-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.admin-page-header h2 {
    color: #fff;
    font-size: 1.5rem;
    margin: 0;
}

.admin-message {
    padding: 0.8rem 1.2rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.admin-message.success {
    background: rgba(0, 212, 255, 0.1);
    border: 1px solid rgba(0, 212, 255, 0.2);
    color: #00d4ff;
}

.admin-message.error {
    background: rgba(255, 0, 0, 0.1);
    border: 1px solid rgba(255, 0, 0, 0.2);
    color: #ff6b6b;
}

.settings-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.settings-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    padding: 1.5rem;
}

.settings-card.full-width {
    grid-column: 1 / -1;
}

.settings-card h3 {
    color: #00d4ff;
    margin-top: 0;
    margin-bottom: 1.2rem;
    font-size: 1.1rem;
}

.settings-card h3 i {
    margin-right: 0.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    color: #ccd6f6;
    margin-bottom: 0.3rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.6rem 0.8rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    color: #fff;
    font-size: 0.95rem;
    transition: border-color 0.3s;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #00d4ff;
}

.form-group textarea {
    resize: vertical;
}

.form-group small {
    color: #8892b0;
    font-size: 0.75rem;
    display: block;
    margin-top: 0.2rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.btn {
    padding: 0.5rem 1.2rem;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #00d4ff, #7b2ffc);
    color: #fff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(0, 212, 255, 0.2);
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
$pageContent = ob_get_clean();

// Instead of using the main header, we'll use a simplified admin header
// since we're in the admin directory and need to avoid path issues
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Digital History Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Admin Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            color: #ccd6f6;
            min-height: 100vh;
        }
        
        <?php echo $pageCSS ?? ''; ?>
    </style>
</head>
<body>
    <?php echo $pageContent; ?>
    
    <script src="../assets/js/main.js"></script>
    <?php if (isset($pageJS)): ?>
    <script><?php echo $pageJS; ?></script>
    <?php endif; ?>
</body>
</html>