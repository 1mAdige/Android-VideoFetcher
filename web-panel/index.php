<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';

panel_init_lang();

$c = panel_config();
$video_path = panel_video_path();
$base_url = panel_base_url();
$lang = panel_lang();
$has_app_icon = file_exists(__DIR__ . '/assets/app_icon.png');

if (isset($_POST['login'])) {
    $user = isset($_POST['username']) ? trim($_POST['username']) : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';
    if ($user === $c['username'] && $pass === $c['password']) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $user;
    } else {
        $error = panel_t('wrong_credentials');
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php?lang=' . urlencode($lang));
    exit;
}

if (isset($_FILES['video']) && isset($_SESSION['logged_in'])) {
    panel_ensure_video_dir();
    $target_file = $video_path;

    if ($_FILES['video']['size'] > $c['max_upload_bytes']) {
        $error = panel_t('upload_big');
    } else if (!panel_is_valid_mp4_upload($_FILES['video'])) {
        $error = panel_t('upload_type');
    } else {
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        if (move_uploaded_file($_FILES['video']['tmp_name'], $target_file)) {
            $success = panel_t('upload_ok');
        } else {
            $error = panel_t('upload_fail');
        }
    }
}

$video_exists = file_exists($video_path);
$video_info = null;
if ($video_exists) {
    $video_info = [
        'date' => date('d.m.Y H:i', filemtime($video_path)),
        'size' => round(filesize($video_path) / 1048576, 2),
        'timestamp' => filemtime($video_path),
    ];
}

$devices = panel_get_devices_sorted();
$online_count = panel_count_online_devices();
$apk_panel_url = rtrim($base_url, '/');
$apk_check_url = $apk_panel_url . '/get_latest_video_info.php';
$apk_video_url = $apk_panel_url . '/videos/current.mp4';
$footer_year = date('Y');
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($c['app_name']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/panel.css">
    <?php if ($has_app_icon): ?>
    <link rel="icon" href="assets/app_icon.png" type="image/png">
    <?php endif; ?>
</head>
<body>
<div class="shell">
    <div class="topbar">
        <div class="brand">
            <?php if ($has_app_icon): ?>
                <img src="assets/app_icon.png" alt="<?php echo htmlspecialchars($c['app_name']); ?>" class="brand-badge">
            <?php else: ?>
                <div class="brand-badge-text">VF</div>
            <?php endif; ?>
            <div>
                <h1><?php echo htmlspecialchars($c['app_name']); ?></h1>
                <p><?php echo htmlspecialchars($c['app_tagline']); ?></p>
            </div>
        </div>
        <div class="topbar-actions">
            <div class="lang-pill">
                <span class="lang-pill-label"><?php echo panel_icon_globe(); ?> <?php echo htmlspecialchars(panel_t('lang_label')); ?></span>
                <div class="lang-pill-options">
                    <a href="?lang=tr" class="<?php echo $lang === 'tr' ? 'active' : ''; ?>">TR</a>
                    <a href="?lang=en" class="<?php echo $lang === 'en' ? 'active' : ''; ?>">EN</a>
                </div>
            </div>
            <?php if (isset($_SESSION['logged_in'])): ?>
            <form method="post" class="logout-form">
                <button type="submit" name="logout" class="btn-logout-top">
                    <?php echo panel_icon_logout(); ?> <?php echo htmlspecialchars(panel_t('logout_btn')); ?>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!isset($_SESSION['logged_in'])): ?>
        <div class="login-shell">
            <div class="login-card">
                <div class="login-brand">
                    <?php if ($has_app_icon): ?>
                        <img src="assets/app_icon.png" alt="VideoFetcher">
                    <?php endif; ?>
                    <h2><?php echo htmlspecialchars(panel_t('login_title')); ?></h2>
                    <p><?php echo htmlspecialchars(panel_t('login_desc')); ?></p>
                </div>
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="input-group">
                        <label for="username"><?php echo htmlspecialchars(panel_t('username')); ?></label>
                        <div class="input-field">
                            <span class="input-prefix" aria-hidden="true"><?php echo panel_icon_user(); ?></span>
                            <input type="text" name="username" id="username" placeholder="<?php echo htmlspecialchars(panel_t('username_ph')); ?>" required autofocus autocomplete="username">
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="password"><?php echo htmlspecialchars(panel_t('password')); ?></label>
                        <div class="input-field">
                            <span class="input-prefix" aria-hidden="true"><?php echo panel_icon_lock(); ?></span>
                            <input type="password" name="password" id="password" placeholder="<?php echo htmlspecialchars(panel_t('password_ph')); ?>" required autocomplete="current-password">
                        </div>
                    </div>
                    <button type="submit" name="login" class="btn-login">
                        <?php echo panel_icon_login(); ?> <?php echo htmlspecialchars(panel_t('login_btn')); ?>
                    </button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="grid">
            <div class="stat-card">
                <div class="stat-label"><?php echo htmlspecialchars(panel_t('stat_video')); ?></div>
                <div class="stat-value <?php echo $video_exists ? 'ok' : 'warn'; ?>">
                    <?php echo $video_exists ? htmlspecialchars(panel_t('stat_video_ok')) : htmlspecialchars(panel_t('stat_video_no')); ?>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><?php echo htmlspecialchars(panel_t('stat_devices')); ?></div>
                <div class="stat-value" id="statDevicesOnline"><?php echo (int) $online_count; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><?php echo htmlspecialchars(panel_t('timestamp')); ?></div>
                <div class="stat-value" style="font-size:1rem;">
                    <?php echo $video_exists ? (int) $video_info['timestamp'] : '-'; ?>
                </div>
            </div>
        </div>

        <div class="two-col">
            <div class="panel-card">
                <h2><?php echo htmlspecialchars(panel_t('upload_title')); ?></h2>
                <div class="desc"><?php echo htmlspecialchars(panel_t('upload_desc')); ?></div>
                <div class="info-box"><?php echo htmlspecialchars(panel_t('max_size')); ?></div>

                <?php if (isset($error)): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="success" id="successMsg"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($video_exists): ?>
                    <strong><?php echo htmlspecialchars(panel_t('current_video')); ?></strong>
                    <video controls preload="metadata" style="margin-top:10px;">
                        <source src="videos/current.mp4?<?php echo time(); ?>" type="video/mp4">
                    </video>
                    <div class="meta-box">
                        <div><strong><?php echo htmlspecialchars(panel_t('uploaded_at')); ?>:</strong> <?php echo htmlspecialchars($video_info['date']); ?></div>
                        <div><strong><?php echo htmlspecialchars(panel_t('size')); ?>:</strong> <?php echo htmlspecialchars($video_info['size']); ?> MB</div>
                    </div>
                <?php else: ?>
                    <div class="info-box"><?php echo htmlspecialchars(panel_t('no_video')); ?></div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" id="uploadForm" style="margin-top:16px;">
                    <label for="video"><?php echo htmlspecialchars(panel_t('select_video')); ?></label>
                    <input type="file" name="video" id="video" accept="video/mp4,.mp4" required>
                    <div class="progress-label" id="progressLabel"><?php echo htmlspecialchars(panel_t('uploading')); ?></div>
                    <div class="progress-bar-bg" id="progressBg"><div class="progress-bar" id="progressBar"></div></div>
                    <button type="submit"><?php echo htmlspecialchars(panel_t('upload_btn')); ?></button>
                </form>
            </div>

            <div>
                <div class="panel-card">
                    <h2><?php echo htmlspecialchars(panel_t('apk_settings')); ?></h2>
                    <div class="desc"><?php echo htmlspecialchars(panel_t('panel_url')); ?></div>
                    <code class="copy-line"><?php echo htmlspecialchars($apk_panel_url); ?></code>
                    <div class="desc" style="margin-top:12px;"><?php echo htmlspecialchars(panel_t('check_endpoint')); ?></div>
                    <code class="copy-line"><?php echo htmlspecialchars($apk_check_url); ?></code>
                    <div class="desc" style="margin-top:12px;"><?php echo htmlspecialchars(panel_t('video_file')); ?></div>
                    <code class="copy-line"><?php echo htmlspecialchars($apk_video_url); ?></code>
                </div>

                <div class="panel-card">
                    <div class="toolbar">
                        <div>
                            <h2 style="margin:0;"><?php echo htmlspecialchars(panel_t('connected_devices')); ?></h2>
                            <div class="desc" style="margin:6px 0 0;"><?php echo htmlspecialchars(panel_t('connected_devices_desc')); ?></div>
                        </div>
                        <button type="button" class="btn-small" id="refreshDevices"><?php echo htmlspecialchars(panel_t('refresh')); ?></button>
                    </div>

                    <div id="devicesWrap">
                        <?php if (empty($devices)): ?>
                            <div class="info-box"><?php echo htmlspecialchars(panel_t('no_devices')); ?></div>
                        <?php else: ?>
                            <table class="device-table">
                                <thead>
                                <tr>
                                    <th><?php echo htmlspecialchars(panel_t('device_id')); ?></th>
                                    <th><?php echo htmlspecialchars(panel_t('device_ip')); ?></th>
                                    <th><?php echo htmlspecialchars(panel_t('device_last_seen')); ?></th>
                                    <th><?php echo htmlspecialchars(panel_t('device_status')); ?></th>
                                </tr>
                                </thead>
                                <tbody id="devicesBody">
                                <?php foreach ($devices as $device): ?>
                                    <tr>
                                        <td class="device-id"><?php echo htmlspecialchars(substr($device['id'], 0, 12)); ?>...</td>
                                        <td><?php echo htmlspecialchars($device['ip']); ?></td>
                                        <td><?php echo htmlspecialchars($device['last_seen_human']); ?></td>
                                        <td>
                                            <span class="status-dot <?php echo !empty($device['online']) ? 'online' : ''; ?>">
                                                <?php echo !empty($device['online']) ? htmlspecialchars(panel_t('status_online')) : htmlspecialchars(panel_t('status_offline')); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <footer class="footer-card">
        <div class="footer-brand">VideoFetcher</div>
        <div class="footer-tagline"><?php echo htmlspecialchars(panel_t('footer_tagline')); ?></div>
        <div class="footer-links">
            <a href="<?php echo htmlspecialchars($c['github_url']); ?>" target="_blank" rel="noopener">GitHub</a>
            <a href="mailto:<?php echo htmlspecialchars($c['email']); ?>"><?php echo htmlspecialchars($c['email']); ?></a>
        </div>
        <div class="footer-copy">
            &copy; <?php echo (int) $footer_year; ?> <?php echo htmlspecialchars($c['developer']); ?> &middot; <?php echo htmlspecialchars(panel_t('footer_rights')); ?>
        </div>
    </footer>
</div>

<?php if (isset($_SESSION['logged_in'])): ?>
<script>
    const uploadForm = document.getElementById('uploadForm');
    const progressBg = document.getElementById('progressBg');
    const progressBar = document.getElementById('progressBar');
    const progressLabel = document.getElementById('progressLabel');
    const uploadingText = <?php echo json_encode(panel_t('uploading')); ?>;
    const uploadErrorText = <?php echo json_encode(panel_t('upload_error')); ?>;
    const statusOnline = <?php echo json_encode(panel_t('status_online')); ?>;
    const statusOffline = <?php echo json_encode(panel_t('status_offline')); ?>;
    const noDevicesText = <?php echo json_encode(panel_t('no_devices')); ?>;

    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(uploadForm);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            progressBg.style.display = 'block';
            progressLabel.style.display = 'block';
            progressBar.style.width = '0%';
            xhr.upload.onprogress = function(ev) {
                if (ev.lengthComputable) {
                    const percent = Math.round((ev.loaded / ev.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressLabel.textContent = uploadingText + ' %' + percent;
                }
            };
            xhr.onload = function() {
                progressBg.style.display = 'none';
                progressLabel.style.display = 'none';
                if (xhr.status === 200) location.reload();
            };
            xhr.onerror = function() {
                progressBg.style.display = 'none';
                progressLabel.style.display = 'none';
                alert(uploadErrorText);
            };
            xhr.send(formData);
        });
    }

    function renderDevices(data) {
        const stat = document.getElementById('statDevicesOnline');
        if (stat) stat.textContent = data.online;

        const wrap = document.getElementById('devicesWrap');
        if (!wrap) return;

        if (!data.devices || data.devices.length === 0) {
            wrap.innerHTML = '<div class="info-box">' + noDevicesText + '</div>';
            return;
        }

        let rows = '';
        data.devices.forEach(function(d) {
            const online = d.online ? 'online' : '';
            const status = d.online ? statusOnline : statusOffline;
            const shortId = (d.id || '').substring(0, 12) + '...';
            rows += '<tr>'
                + '<td class="device-id">' + shortId + '</td>'
                + '<td>' + (d.ip || '-') + '</td>'
                + '<td>' + (d.last_seen_human || '-') + '</td>'
                + '<td><span class="status-dot ' + online + '">' + status + '</span></td>'
                + '</tr>';
        });

        wrap.innerHTML = '<table class="device-table"><thead><tr>'
            + '<th><?php echo htmlspecialchars(panel_t('device_id')); ?></th>'
            + '<th><?php echo htmlspecialchars(panel_t('device_ip')); ?></th>'
            + '<th><?php echo htmlspecialchars(panel_t('device_last_seen')); ?></th>'
            + '<th><?php echo htmlspecialchars(panel_t('device_status')); ?></th>'
            + '</tr></thead><tbody>' + rows + '</tbody></table>';
    }

    function refreshDevices() {
        fetch('api/devices.php')
            .then(function(r) { return r.json(); })
            .then(renderDevices)
            .catch(function() {});
    }

    document.getElementById('refreshDevices').addEventListener('click', refreshDevices);
    setInterval(refreshDevices, 15000);

    setTimeout(function() {
        const msg = document.getElementById('successMsg');
        if (msg) msg.style.display = 'none';
    }, 4000);
</script>
<?php endif; ?>
</body>
</html>
