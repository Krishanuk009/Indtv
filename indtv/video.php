<?php
include 'includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM contents WHERE id = $id";
$result = mysqli_query($conn, $query);
$video = mysqli_fetch_assoc($result);

if(!$video) {
    header('Location: index.php');
    exit();
}

$update_views = "UPDATE contents SET views = views + 1 WHERE id = $id";
mysqli_query($conn, $update_views);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($video['title']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/4.8.8/controls.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/4.8.8/shaka-player.ui.min.js"></script>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0b0b0e;
            color: white;
            margin: 0;
            line-height: 1.6;
        }

        /* Sticky Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: #0b0b0e;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            padding: 0 15px;
            z-index: 9999;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        /* Main Container */
        .container {
            padding: 70px 0 80px 0;
            position: relative;
        }

        .video-container {
            position: relative;
            width: 100%;
            background: #000;
            margin-bottom: 20px;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .video-wrapper video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }

        .video-info {
            padding: 15px;
        }

        .video-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #fff;
            line-height: 1.4;
        }

        .video-meta {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .video-description {
            font-size: 14px;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 20px;
        }

        /* Bottom Shadow Overlay */
        .bottom-shadow-overlay {
            position: fixed;
            bottom: 60px;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to bottom, rgba(11, 11, 14, 0), rgba(11, 11, 14, 1));
            pointer-events: none;
            z-index: 999;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #0b0b0e;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            height: 60px;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 10px;
            font-weight: 500;
        }

        .nav-item.active {
            color: #fff;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            margin-bottom: 4px;
            opacity: 0.6;
        }

        .nav-item.active .nav-icon {
            opacity: 1;
        }

        .nav-text {
            font-size: 10px;
            letter-spacing: -0.2px;
        }

        .nav-icon.home {
            background-image: url('assets/home.png');
        }

        .nav-icon.search {
            background-image: url('assets/search.png');
        }

        /* Shaka Player Custom Styles */
        .shaka-video-container {
            width: 100% !important;
            height: 100% !important;
            position: absolute !important;
            top: 0;
            left: 0;
        }

        .shaka-controls-container {
            width: 100% !important;
        }

        .shaka-current-time {
            color: #fff !important;
        }

        .shaka-seek-bar-container {
            background: rgba(255, 255, 255, 0.3) !important;
        }

        .shaka-seek-bar-played {
            background: #fff !important;
        }

        .shaka-seek-bar-buffered {
            background: rgba(255, 255, 255, 0.4) !important;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .video-title {
                font-size: 16px;
            }
            .video-meta {
                font-size: 12px;
            }
            .video-description {
                font-size: 14px;
            }
        }

        @media (min-width: 769px) {
            .container {
                max-width: 1200px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <!-- Sticky Header -->
    <header>
        <div class="logo-container">
            <a href="index.php">
                <img src="assets/logo.png" alt="Logo" class="logo">
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <div class="video-container">
            <div class="video-wrapper">
                <video 
                    autoplay 
                    data-shaka-player 
                    id="video" 
                    class="shaka-video"
                    poster="<?php echo $video['thumbnail']; ?>">
                </video>
            </div>
        </div>
        <div class="video-info">
            <h1 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h1>
            <div class="video-meta">
                <span><?php echo htmlspecialchars($video['category']); ?></span>
                <span>•</span>
                <span><?php echo number_format($video['views']); ?> views</span>
            </div>
            <div class="video-description"><?php echo htmlspecialchars($video['description']); ?></div>
        </div>
    </div>

    <!-- Bottom Shadow Overlay -->
    <div class="bottom-shadow-overlay"></div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="index.php" class="nav-item">
            <div class="nav-icon home"></div>
            <span class="nav-text">Home</span>
        </a>
        <a href="search.php" class="nav-item">
            <div class="nav-icon search"></div>
            <span class="nav-text">Search</span>
        </a>
    </nav>

    <script>
        const manifestUri = "<?php echo $video['stream_url']; ?>";

        async function init() {
            const video = document.getElementById('video');
            const ui = video['ui'];
            const controls = ui.getControls();
            const player = controls.getPlayer();

            // Configure UI
            const config = {
                'controlPanelElements': ['play_pause', 'time_and_duration', 'spacer', 'mute', 'volume', 'quality', 'fullscreen'],
                'addSeekBar': true,
                'addBigPlayButton': false,
                'seekBarColors': {
                    base: 'rgba(255, 255, 255, 0.3)',
                    buffered: 'rgba(255, 255, 255, 0.54)',
                    played: 'rgb(255, 255, 255)',
                }
            };
            ui.configure(config);

            // Configure player
            player.configure({
                drm: {
                    clearKeys: {
                        "<?php echo $video['drm_key_id']; ?>": "<?php echo $video['drm_key']; ?>"
                    }
                },
            });

            // Error handling
            player.addEventListener('error', onPlayerErrorEvent);
            controls.addEventListener('error', onUIErrorEvent);

            // Load content
            try {
                await player.load(manifestUri);
                video.play();
            } catch (error) {
                onPlayerError(error);
            }
        }

        function onPlayerErrorEvent(errorEvent) {
            onPlayerError(event.detail);
        }

        function onPlayerError(error) {
            console.error('Error code', error.code, 'object', error);
        }

        function onUIErrorEvent(errorEvent) {
            onPlayerError(event.detail);
        }

        function initFailed(errorEvent) {
            console.error('Unable to load the UI library!');
        }

        document.addEventListener('shaka-ui-loaded', init);
        document.addEventListener('shaka-ui-load-failed', initFailed);
    </script>
</body>
</html>