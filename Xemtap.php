<?php
include 'db_connection.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null;
}


if ($_SESSION['user_id'] !== null) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
}


if (isset($_GET['id']) && isset($_GET['episode'])) {
    $id = $_GET['id'];
    $episode = $_GET['episode'];

    $sql_movie = "SELECT title FROM movies WHERE id = $id";
    $result_movie = $connection->query($sql_movie);

    if ($result_movie->num_rows > 0) {
        $row_movie = $result_movie->fetch_assoc();
        $title = $row_movie['title'];

        $sql_episodes = "SELECT episode_number, video_link FROM episodes WHERE movie_id = $id";
        $result_episodes = $connection->query($sql_episodes);

        if ($result_episodes->num_rows > 0) {
            $episodes = array();

            while ($row_episodes = $result_episodes->fetch_assoc()) {
                $episodeNumber = $row_episodes['episode_number'];
                $videoLink = $row_episodes['video_link'];
                $episodes[] = array('number' => $episodeNumber, 'link' => $videoLink);
            }

            if (isset($_GET['episode']) && is_numeric($_GET['episode']) && $_GET['episode'] >= 1 && $_GET['episode'] <= count($episodes)) {
                $episode = $_GET['episode'];
            } else {
                $episode = 1;
            }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem Tập Phim - <?php echo $title; ?> - Tập <?php echo $episode; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/fav-icon.png" type="image/x-icon">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="dropdown.css">
    <style>
    /* CSS cho phần khung xem phim */
    .video-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* Tỷ lệ 16:9 */
    }
    
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .movie-details.container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 60px;
    }
    
    .episode-list {
        margin-top: 15px;
        padding: 15px 20px;
        background-color: #272735;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 40px;
    }
    
    .container h3 {
                font-size: 30px;
                margin-bottom: 5px;
                color: #E91A46;
                margin-top: 40px;
            }
    
    .episode-list ul {
        padding: 0;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .episode-list li {
        padding: 10px 15px;
        border-radius: 10px;
        margin-right: 10px;
        background-color: #272735;
        color: white;
    }
    
    .episode-list li:hover {
        background-color: #E91A46;
        color: white;
        cursor: pointer;
    }
    
    .episode-list li.current-episode {
        background-color: #E91A46;
        color: white;
    }
 
    .episode-list li a {
        color: white; /* Màu chữ trắng */
    }
    
    .current-episode {
        color: white; /* Màu chữ trắng */
    }
    
    .comment-section {
        margin-top: 20px;
        padding: 10px;
        background-color: #272735;
    }
    .current-episode-title {
        margin-top: 20px;
        font-weight: bold;
        font-size: 28px;
        margin-bottom: 20px;
    }
    </style>
</head>
<body>
    <header>
        <div class="nav container">
            <a href="TrangChu.html" class="logo">
                Movie<span>Manhwa</span>
            </a>
            <div class="search-box">
    <form method="post" action="search.php" style="display: flex;">
        <input type="text" name="noidung" autocomplete="off" id="search-input" placeholder="Search Movies">
        <button class="search-button" type="submit" name="btn">
            <i class="bx bx-search"></i>
        </button>
    </form>
</div>

<a href="<?php echo isset($_SESSION['user_id']) ? 'UserInfo.php?user_id=' . $_SESSION['user_id'] : 'Dangnhap.php'; ?>" class="user">
                <img src="<?php echo isset($user['avatar_link']) ? $user['avatar_link'] : 'img/images.png'; ?>" alt="" class="user-img">
            </a>

            <div class="navbar">
                <a href="TrangChu.html" class="nav-link">
                    <i class="bx bx-home"></i>
                    <span class="nav-link-title">Trang chủ</span>
                </a>
                <a href="#home" class="nav-link">
                    <i class="bx bxs-hot"></i>
                    <span class="nav-link-title">Thịnh hành</span>
                </a>
                <a href="PhimBo.php" class="nav-link">
                    <i class="bx bxs-movie"></i>
                    <span class="nav-link-title">Phim bộ</span>
                </a>
                <a href="PhimLe.php" class="nav-link">
                    <i class="bx bxs-film"></i>
                    <span class="nav-link-title">Phim lẻ</span>
                </a>

                <div class="dropdown-toggle-container" id="genre-dropdown-toggle">
                    <a href="#" class="nav-link dropdown">
                        <i class="bx bx-category nav-link-icon"></i>
                        <span class="nav-link-title">Thể loại</span>
                    </a>
                    <div class="dropdown-content">
                        <div class="column">
                            <a href="Theloai.php?genre=Hài hước">Hài hước</a>
                            <a href="Theloai.php?genre=Hành động">Hành động</a>
                            <a href="Theloai.php?genre=Phiêu lưu">Phiêu lưu</a>
                            <a href="Theloai.php?genre=Tình cảm">Tình cảm</a>
                            <a href="Theloai.php?genre=Học đường">Học đường</a>
                            <a href="Theloai.php?genre=Võ thuật">Võ thuật</a>
                            <a href="Theloai.php?genre=Tài liệu">Tài liệu</a>
                        </div>
                        <div class="column">
                            <a href="Theloai.php?genre=Viễn tưởng">Viễn tưởng</a>
                            <a href="Theloai.php?genre=Hoạt hình">Hoạt hình</a>
                            <a href="Theloai.php?genre=Thể thao">Thể thao</a>
                            <a href="Theloai.php?genre=Âm nhạc">Âm nhạc</a>
                            <a href="Theloai.php?genre=Gia đình">Gia đình</a>
                            <a href="Theloai.php?genre=Kinh dị">Kinh dị</a>
                            <a href="Theloai.php?genre=Tâm lý">Tâm lý</a>
                        </div>
                    </div>
                </div>
             
                <a href="Yeuthich.php?user_id=<?php echo  $_SESSION['user_id']; ?>" class="nav-link">
                    <i class='bx bx-heart'></i>
                    <span class="nav-link-title">Yêu thích</span>
                </a>
            </div>
        </div>
    </header>
    <section class="movie-details container">
        <div class="container">
            <div class="current-episode-title">
                <p>Phim '<?php echo $title; ?>' - Tập <?php echo $episode; ?></p>
            </div>
            <div class="video-container">
                <iframe src="<?php echo $episodes[$episode-1]['link']; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            
            <h3>Danh sách tập phim</h3>
            <div class="episode-list">
             
                <ul>
                <li><a href="XemTrailer.php?id=<?php echo $id; ?>&user_id=<?php echo $_SESSION['user_id']; ?>">Trailer</a></li>
                    <?php
                    foreach ($episodes as $episodeItem) {
                        $currentClass = ($episodeItem['number'] == $episode) ? 'current-episode' : '';
                        echo '<li class="' . $currentClass . '"><a href="XemTap.php?id=' . $id . '&episode=' . $episodeItem['number'] .  '&user_id=' . $_SESSION['user_id'] . '">Tập ' . $episodeItem['number'] . '</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <?php
        // Gọi file comment.php
        include 'comment.php';
        ?>

        </div>
    </section>
    <script src="js/main.js"></script>
    <script src="dropdown.js"></script>
</body>
</html>

<?php
        } else {
            echo "Không tìm thấy tập phim.";
        }
    } else {
        echo "Không tìm thấy thông tin phim.";
    }
} else {
    echo "Không có đủ thông tin để xem tập phim.";
}

$connection->close();
?>
