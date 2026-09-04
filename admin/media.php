<?php
// admin/media.php
// Digital History Admin - Media Manager

require_once '../includes/config.php';
requireAdmin();

$pageTitle = 'Media Manager';

$message = '';
$error = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    $file = $_FILES['media_file'];
    $alt_text = $_POST['alt_text'] ?? '';
    $alt_text_ar = $_POST['alt_text_ar'] ?? '';
    $caption = $_POST['caption'] ?? '';
    
    $validation = validateFileUpload($file);
    
    if ($validation['valid']) {
        $filename = sanitizeFilename($file['name']);
        $destination = UPLOADS_PATH . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $mediaId = db()->insert('media', [
                'filename' => $filename,
                'original_name' => $file['name'],
                'file_type' => pathinfo($filename, PATHINFO_EXTENSION),
                'mime_type' => $file['type'],
                'file_size' => $file['size'],
                'file_path' => $filename,
                'alt_text' => $alt_text,
                'alt_text_ar' => $alt_text_ar,
                'caption' => $caption,
                'uploaded_by' => $_SESSION['user_id']
            ]);
            logActivity('admin_upload_media', ['media_id' => $mediaId, 'filename' => $filename]);
            $message = 'File uploaded successfully!';
        } else {
            $error = 'Failed to move uploaded file.';
        }
    } else {
        $error = $validation['error'];
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $media = db()->fetch("SELECT * FROM media WHERE id = ?", [$id]);
    
    if ($media) {
        $filePath = UPLOADS_PATH . $media['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        db()->delete('media', 'id = ?', [$id]);
        logActivity('admin_delete_media', ['media_id' => $id]);
        $message = 'File deleted successfully.';
    }
}

// Get media files
$mediaFiles = db()->fetchAll("SELECT * FROM media ORDER BY created_at DESC");
$totalSize = 0;
foreach ($mediaFiles as $media) {
    $totalSize += $media['file_size'];
}

// Get file type counts
$imageCount = count(array_filter($mediaFiles, function($m) { return strpos($m['mime_type'], 'image/') === 0; }));
$videoCount = count(array_filter($mediaFiles, function($m) { return strpos($m['mime_type'], 'video/') === 0; }));
$documentCount = count($mediaFiles) - $imageCount - $videoCount;

ob_start();
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h2><i class="fas fa-images"></i> Media Manager</h2>
        <button onclick="document.getElementById('uploadModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-upload"></i> Upload File
        </button>
    </div>
    
    <?php if ($message): ?>
    <div class="admin-message success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="admin-message error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Stats -->
    <div class="media-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(0,212,255,0.1); color: #00d4ff;">
                <i class="fas fa-file-image"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?php echo count($mediaFiles); ?></span>
                <span class="stat-label">Total Files</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(0,255,136,0.1); color: #00ff88;">
                <i class="fas fa-image"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?php echo $imageCount; ?></span>
                <span class="stat-label">Images</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(123,47,252,0.1); color: #7b2ffc;">
                <i class="fas fa-video"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?php echo $videoCount; ?></span>
                <span class="stat-label">Videos</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(255,165,0,0.1); color: #ffa500;">
                <i class="fas fa-file"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?php echo $documentCount; ?></span>
                <span class="stat-label">Documents</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(255,0,100,0.1); color: #ff0064;">
                <i class="fas fa-hdd"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?php echo number_format($totalSize / 1024 / 1024, 1); ?> MB</span>
                <span class="stat-label">Total Size</span>
            </div>
        </div>
    </div>
    
    <!-- Upload Modal -->
    <div id="uploadModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 5000; overflow-y: auto; padding: 2rem;">
        <div style="max-width: 600px; margin: 0 auto; background: #0d1117; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 2rem; position: relative;">
            <button onclick="document.getElementById('uploadModal').style.display='none'" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #8892b0; font-size: 1.5rem; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
            <h3 style="color: #00d4ff; margin-top: 0; margin-bottom: 1.5rem;"><i class="fas fa-upload"></i> Upload File</h3>
            
            <form method="POST" action="media.php" enctype="multipart/form-data">
                <?php echo getCSRFField(); ?>
                
                <div class="form-group">
                    <label>Select File *</label>
                    <input type="file" name="media_file" required accept="image/*,video/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <small>Allowed: images, videos, PDF, Word documents. Max size: 5MB</small>
                </div>
                
                <div class="form-group">
                    <label>Alt Text</label>
                    <input type="text" name="alt_text" placeholder="Description for accessibility">
                </div>
                
                <div class="form-group">
                    <label>Alt Text (Arabic)</label>
                    <input type="text" name="alt_text_ar" placeholder="وصف للوصولية">
                </div>
                
                <div class="form-group">
                    <label>Caption</label>
                    <input type="text" name="caption" placeholder="Image caption or description">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
                    <button type="button" onclick="document.getElementById('uploadModal').style.display='none'" class="btn btn-outline">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Media Grid -->
    <div class="media-grid">
        <?php if (empty($mediaFiles)): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 0; color: #8892b0;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📁</div>
            <p>No media files uploaded yet.</p>
            <p style="font-size: 0.9rem;">Click "Upload File" to add your first media file.</p>
        </div>
        <?php else: ?>
        <?php foreach ($mediaFiles as $media): ?>
        <div class="media-item" data-id="<?php echo $media['id']; ?>">
            <div class="media-preview">
                <?php if (strpos($media['mime_type'], 'image/') === 0): ?>
                <img src="<?php echo UPLOADS_URL . $media['filename']; ?>" alt="<?php echo escape($media['alt_text']); ?>" loading="lazy">
                <?php elseif (strpos($media['mime_type'], 'video/') === 0): ?>
                <video src="<?php echo UPLOADS_URL . $media['filename']; ?>" controls style="width: 100%; height: 100%; object-fit: cover;"></video>
                <?php else: ?>
                <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 3rem; color: #8892b0;">
                    <i class="fas fa-file"></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="media-info">
                <div class="media-name" title="<?php echo escape($media['original_name']); ?>">
                    <?php echo escape(truncate($media['original_name'], 25)); ?>
                </div>
                <div class="media-meta">
                    <span><?php echo number_format($media['file_size'] / 1024, 1); ?> KB</span>
                    <span>·</span>
                    <span><?php echo formatDate($media['created_at']); ?></span>
                </div>
                <div class="media-actions">
                    <a href="<?php echo UPLOADS_URL . $media['filename']; ?>" target="_blank" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                    <button onclick="copyURL('<?php echo UPLOADS_URL . $media['filename']; ?>')" class="btn-icon" title="Copy URL"><i class="fas fa-copy"></i></button>
                    <a href="media.php?delete=<?php echo $media['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this file?')"><i class="fas fa-trash"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
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

.btn-outline {
    background: transparent;
    color: #8892b0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-outline:hover {
    color: #fff;
    border-color: rgba(255, 255, 255, 0.3);
}

/* Media Stats */
.media-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
}

.stat-label {
    font-size: 0.7rem;
    color: #8892b0;
}

/* Form */
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

/* Media Grid */
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.media-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
}

.media-item:hover {
    transform: translateY(-4px);
    border-color: #00d4ff;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.media-preview {
    width: 100%;
    height: 150px;
    background: rgba(0, 0, 0, 0.3);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.media-preview img,
.media-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-info {
    padding: 0.8rem 1rem;
}

.media-name {
    color: #fff;
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 0.3rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.media-meta {
    color: #8892b0;
    font-size: 0.7rem;
    margin-bottom: 0.5rem;
}

.media-meta span {
    display: inline-block;
}

.media-actions {
    display: flex;
    gap: 0.3rem;
}

.btn-icon {
    padding: 0.2rem 0.4rem;
    background: rgba(255, 255, 255, 0.05);
    border: none;
    border-radius: 4px;
    color: #8892b0;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.8rem;
    cursor: pointer;
}

.btn-icon:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
}

.btn-icon.delete:hover {
    color: #ff6b6b;
    background: rgba(255, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .media-stats {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .media-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

@media (max-width: 480px) {
    .media-stats {
        grid-template-columns: 1fr 1fr;
    }
    
    .media-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<script>
function copyURL(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('URL copied to clipboard!');
    }).catch(() => {
        prompt('Copy this URL:', url);
    });
}

// Close modal on escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('uploadModal').style.display = 'none';
    }
});

// Close modal on overlay click
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});
</script>

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