<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search_term']);
    $query = "SELECT * FROM contents WHERE title LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%' OR category LIKE '%$searchTerm%'";
    $searchResults = mysqli_query($conn, $query);
} else {
    $searchResults = null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Video App - Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
            padding: 85px 15px 80px 15px;
        }

        /* Search Container */
        .search-container {
            margin-bottom: 20px;
            position: relative;
        }

        .search-form {
            display: flex;
            align-items: center;
            background: #15181f;
            border-radius: 8px;
            padding: 4px;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            background: transparent;
            border: none;
            color: white;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Content List */
        .content-list {
            margin-top: 20px;
        }

        .content-item {
            display: flex;
            margin-bottom: 15px;
            background: #15181f;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .content-item:active {
            transform: scale(0.98);
        }

        .content-thumbnail {
            width: 120px;
            height: 80px;
            object-fit: cover;
        }

        .content-details {
            padding: 12px;
            flex: 1;
        }

        .content-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #fff;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }

        .content-category {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        .no-results {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 30px 0;
            font-size: 14px;
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

        /* Media Queries */
        @media (min-width: 768px) {
            .container {
                max-width: 768px;
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
        <div class="search-container">
            <form method="POST" action="" class="search-form">
                <input 
                    type="text" 
                    name="search_term" 
                    class="search-input" 
                    placeholder="Search videos..." 
                    value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>"
                    required
                    autocomplete="off"
                >
            </form>
        </div>

        <div class="content-list">
            <?php
            if ($searchResults !== null) {
                if (mysqli_num_rows($searchResults) > 0) {
                    while ($content = mysqli_fetch_assoc($searchResults)) {
            ?>
                        <div class="content-item" onclick="window.location.href='video.php?id=<?php echo $content['id']; ?>'">
                            <img src="<?php echo htmlspecialchars($content['thumbnail']); ?>" alt="" class="content-thumbnail" loading="lazy">
                            <div class="content-details">
                                <div class="content-title"><?php echo htmlspecialchars($content['title']); ?></div>
                                <div class="content-category"><?php echo htmlspecialchars($content['category']); ?></div>
                            </div>
                        </div>
            <?php
                    }
                } else {
            ?>
                    <div class="no-results">No results found</div>
            <?php
                }
            }
            ?>
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
        <a href="#" class="nav-item active">
            <div class="nav-icon search"></div>
            <span class="nav-text">Search</span>
        </a>
    </nav>
</body>
</html>