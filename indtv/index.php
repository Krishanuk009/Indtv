<?php
include 'includes/config.php';

// Get featured content
$featured = mysqli_query($conn, "SELECT * FROM contents WHERE featured = 1 ORDER BY created_at DESC") or die(mysqli_error($conn));

// Get all content for the content list below slider
$allContent = mysqli_query($conn, "SELECT * FROM contents ORDER BY created_at DESC") or die(mysqli_error($conn));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Video App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Watch the latest and trending videos on our Video App.">
    <meta property="og:title" content="Video App">
    <meta property="og:description" content="Browse and watch featured videos.">
    <meta property="og:image" content="assets/logo.png">
    <meta property="og:type" content="website">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
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
            height: 100px;
            display: flex;
            align-items: center;
            padding: 0 15px;
            z-index: 9999;
            background: linear-gradient(180deg, rgba(11, 11, 14, 0.8) 0%, rgba(11, 11, 14, 0.4) 40%, rgba(11, 11, 14, 0) 100%);
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

        .app-title {
            font-size: 18px;
            font-weight: 600;
        }

        /* Main Container */
        .container {
            padding: 0 0 120px 0;
            padding-top: 75px;
            position: relative;
        }

        /* Bottom Navigation Shadow Overlay */
        .bottom-shadow-overlay {
            position: fixed;
            bottom: 60px; /* Height of bottom nav */
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(to bottom, rgba(11, 11, 14, 0), rgba(11, 11, 14, 1));
            pointer-events: none;
            z-index: 999;
        }

        /* Slider Section */
        .slider-section {
            margin-bottom: 20px;
            margin-top: -75px;
            position: relative;
            z-index: 1;
        }

        .swiper {
            width: 100%;
            border-radius: 0;
        }

        .video-card {
            background: #0b0b0e;
            position: relative;
            border-radius: 0;
            overflow: hidden;
        }

        .thumbnail-container {
            position: relative;
            width: 100%;
            aspect-ratio: 30/25;
        }

        .thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                180deg,
                rgba(11, 11, 14, 0) 0%,
                rgba(11, 11, 14, 0.2) 20%,
                rgba(11, 11, 14, 0.4) 40%,
                rgba(11, 11, 14, 0.6) 60%,
                rgba(11, 11, 14, 0.8) 80%,
                #0b0b0e 100%
            );
        }

        .video-info {
            position: absolute;
            bottom: 25px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 0 15px;
            z-index: 2;
        }

        .video-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .video-meta {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Watch Button */
        .watch-button-container {
            padding: 0 15px 20px 15px;
            margin-top: 15px;
            display: flex;
            justify-content: center;
            position: relative;
            z-index: 2;
            background: linear-gradient(180deg, #0b0b0e 0%, #0b0b0e 100%);
        }

        .watch-now-button {
            background: linear-gradient(135deg, #00acff 0%, #4c57ee 50%, #fd0071 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            width: 60%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Content List Section */
        .content-list {
            margin-top: 20px;
            padding: 0 15px;
            position: relative;
            z-index: 2;
            background: #0b0b0e;
        }

        .content-item {
            display: flex;
            margin-bottom: 15px;
            background: #15181f;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
        }

        .content-thumbnail {
            width: 120px;
            height: 80px;
            object-fit: cover;
        }

        .content-details {
            padding: 10px;
            flex: 1;
        }

        .content-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .content-category {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
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

        /* Pagination Bullets */
        .swiper-pagination {
            bottom: 8px !important;
        }

        .swiper-pagination-bullet {
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.4);
            opacity: 0.6;
            margin: 0 3px !important;
        }

        .swiper-pagination-bullet-active {
            background: #fff;
            opacity: 1;
        }

        .nav-icon.home {
            background-image: url('assets/home.png');
        }

        .nav-icon.search {
            background-image: url('assets/search.png');
        }

        /* Media Queries for smaller devices */
        @media (max-width: 768px) {
            header {
                height: 70px;
            }
            .logo {
                width: 50px;
                height: 50px;
            }
            .app-title {
                font-size: 16px;
            }
            .watch-now-button {
                width: 60%;
            }
        }
    </style>
</head>
<body>
    <!-- Sticky Header -->
    <header>
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo">
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Slider Section -->
        <div class="slider-section">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php 
                    if($featured->num_rows > 0) {
                        while($video = mysqli_fetch_assoc($featured)): 
                    ?>
                        <div class="swiper-slide">
                            <div class="video-card" data-video-id="<?php echo $video['id']; ?>">
                                <div class="thumbnail-container">
                                    <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="" class="thumbnail" loading="lazy">
                                    <div class="gradient-overlay"></div>
                                    <div class="video-info">
                                        <div class="video-title"><?php echo htmlspecialchars($video['title']); ?></div>
                                        <div class="video-meta"><?php echo htmlspecialchars($video['category']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile;
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- Watch Button -->
        <div class="watch-button-container">
            <button class="watch-now-button" onclick="watchCurrentVideo()">
                <span class="play-icon">▶</span> Watch Now
            </button>
        </div>

        <!-- Content List -->
        <div class="content-list">
            <?php 
            if($allContent->num_rows > 0) {
                while($content = mysqli_fetch_assoc($allContent)): 
            ?>
                <div class="content-item" onclick="window.location.href='video.php?id=<?php echo $content['id']; ?>'">
                    <img src="<?php echo htmlspecialchars($content['thumbnail']); ?>" alt="" class="content-thumbnail" loading="lazy">
                    <div class="content-details">
                        <div class="content-title"><?php echo htmlspecialchars($content['title']); ?></div>
                        <div class="content-category"><?php echo htmlspecialchars($content['category']); ?></div>
                    </div>
                </div>
            <?php 
                endwhile;
            }
            ?>
        </div>
    </div>

    <!-- Bottom Shadow Overlay -->
    <div class="bottom-shadow-overlay"></div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="nav-item active">
            <div class="nav-icon home"></div>
            <span class="nav-text">Home</span>
        </a>
        <a href="search.php" class="nav-item">
            <div class="nav-icon search"></div>
            <span class="nav-text">Search</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true,
            },
            speed: 1500,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            }
        });

        function watchCurrentVideo() {
            const activeSlide = document.querySelector('.swiper-slide-active');
            if (activeSlide) {
                const videoId = activeSlide.querySelector('.video-card').dataset.videoId;
                window.location.href = 'video.php?id=' + videoId;
            }
        }
    </script>
</body>
</html>