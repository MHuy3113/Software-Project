<?php
session_start();
include 'db_connection.php';
include 'C:/Users/Anbewwwwwwwwwwwwww/Desktop/api_tmdb.php';

if (isset($_POST['noidung'])) {
    $keyword = $_POST['noidung'];

    $sql = "SELECT * FROM tmdb_5000_movies WHERE (title LIKE '%$keyword%' OR original_title LIKE '%$keyword%')";
    $result = $connection->query($sql);

    if ($_SESSION['user_id'] !== null) {
        $user_id = $_SESSION['user_id'];
        $sqlUser = "SELECT * FROM users WHERE id = '$user_id'";
        $resultUser = $connection->query($sqlUser);

        if ($resultUser->num_rows > 0) {
            $user = $resultUser->fetch_assoc();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManhwaMovies - Tìm kiếm</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/fav-icon.png" type="image/x-icon">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="dropdown.css">
    <style>
   
    </style>
</head>
<body>
    <header>
        <div class="nav container">
            <a href="Trangchu.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="logo">
                Movie<span>Manhwa</span>
            </a>
            <div class="search-box">
                <form method="post" action="search.php" style="display: flex;">
                    <input type="text" name="noidung" autocomplete="off" id="search-input" placeholder="Search Movies">
                    <button class="search-button" type="submit" name="btn" >
                        <i class="bx bx-search"></i>
                    </button>
                </form>
            </div>

            <a href="<?php echo isset($_SESSION['user_id']) ? 'UserInfo.php?user_id=' . $_SESSION['user_id'] : 'Dangnhap.php'; ?>" class="user">
                <img src="<?php echo isset($user['avatar_link']) ? $user['avatar_link'] : 'img/images.png'; ?>" alt="" class="user-img">
            </a>
            <div class="navbar">
                <a href="Trangchu.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="nav-link">
                    <i class="bx bx-home nav-link-icon"></i>
                    <span class="nav-link-title">Trang chủ</span>
                </a>
                <a href="#home" class="nav-link">
                    <i class="bx bxs-hot nav-link-icon"></i>
                    <span class="nav-link-title">Thịnh hành</span>
                </a>
                <a href="PhimBo.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="nav-link nav-active">
                    <i class="bx bxs-movie nav-link-icon"></i>
                    <span class="nav-link-title">Phim bộ</span>
                </a>
                <a href="PhimLe.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="nav-link">
                    <i class="bx bxs-film nav-link-icon"></i>
                    <span class="nav-link-title">Phim lẻ</span>
                </a>
                <div class="dropdown-toggle-container" id="genre-dropdown-toggle">
                    <a href="#" class="nav-link dropdown">
                        <i class="bx bx-category nav-link-icon"></i>
                        <span class="nav-link-title">Thể loại</span>
                    </a>
                    <div class="dropdown-content">
                        <div class="column">
                            <a href="Theloai.php?genre=Hài hước&user_id=<?php echo $_SESSION['user_id']; ?>">Hài hước</a>
                            <a href="Theloai.php?genre=Hành động&user_id=<?php echo $_SESSION['user_id']; ?>">Hành động</a>
                            <a href="Theloai.php?genre=Phiêu lưu&user_id=<?php echo $_SESSION['user_id']; ?>">Phiêu lưu</a>
                            <a href="Theloai.php?genre=Tình cảm&user_id=<?php echo $_SESSION['user_id']; ?>">Tình cảm</a>
                            <a href="Theloai.php?genre=Học đường&user_id=<?php echo $_SESSION['user_id']; ?>">Học đường</a>
                            <a href="Theloai.php?genre=Võ thuật&user_id=<?php echo $_SESSION['user_id']; ?>">Võ thuật</a>
                            <a href="Theloai.php?genre=Tài liệu&user_id=<?php echo $_SESSION['user_id']; ?>">Tài liệu</a>
                        </div>
                        <div class="column">
                            <a href="Theloai.php?genre=Viễn tưởng&user_id=<?php echo $_SESSION['user_id']; ?>">Viễn tưởng</a>
                            <a href="Theloai.php?genre=Hoạt hình&user_id=<?php echo $_SESSION['user_id']; ?>">Hoạt hình</a>
                            <a href="Theloai.php?genre=Thể thao&user_id=<?php echo $_SESSION['user_id']; ?>">Thể thao</a>
                            <a href="Theloai.php?genre=Âm nhạc&user_id=<?php echo $_SESSION['user_id']; ?>">Âm nhạc</a>
                            <a href="Theloai.php?genre=Gia đình&user_id=<?php echo $_SESSION['user_id']; ?>">Gia đình</a>
                            <a href="Theloai.php?genre=Kinh dị&user_id=<?php echo $_SESSION['user_id']; ?>">Kinh dị</a>
                            <a href="Theloai.php?genre=Tâm lý&user_id=<?php echo $_SESSION['user_id']; ?>">Tâm lý</a>
                        </div>
                    </div>
                </div>
                <a href="#home" class="nav-link">
                    <i class="bx bx-heart nav-link-icon"></i>
                    <span class="nav-link-title">Yêu thích</span>
                </a>
            </div>
        </div>
    </header>

    <section class="popular container" id="popular" style="margin-top: 80px;">
        <div class="heading">
            <h2 class="heading-title">Kết quả tìm kiếm cho "<?php echo $keyword; ?>"</h2>
        </div>
        <div class="popular-content">
            <div class="movie-grid">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $movie_id = $row['id'];
                        $poster_url = get_movie_image_url($movie_id); // Gọi hàm từ file api_tmdb.php để lấy URL hình ảnh
                        echo '
                        <div class="movie-box">
                            <img src="' . $poster_url . '" alt="" class="movie-box-img">
                            <div class="box-text">
                                <h2 class="movie-title">' . $row['title'] . '</h2>
                                <a href="chitietphim.php?id=' . $row['id'] . '&user_id=' . $_SESSION['user_id'] . '" class="watch-btn play-btn">
                                    <i class="bx bx-right-arrow"></i>
                                </a>
                            </div>
                        </div>';
                    }
                } else {
                    echo "Không tìm thấy kết quả phù hợp.";
                }
                ?>
            </div>
        </div>
    </section>

    <section class="recommended container" id="recommended" style="margin-top: 40px;">
    <div class="heading">
        <h2 class="heading-title">Các bộ phim gợi ý</h2>
    </div>
    <div class="recommended-content">
        <div class="movie-grid">
            <?php
            $lst_title = @get_title($keyword);
            if ($lst_title == false){
                echo "Không có gợi ý bộ phim nào được tìm thấy.";
            } else {
                foreach ($lst_title as $item) {
                    $item = str_replace("'", "''", $item);
                    $sql = "SELECT * FROM tmdb_5000_movies WHERE (title = '$item' OR original_title = '$item')";
                    $result = $connection->query($sql);
                    if ($result === false) {
                        echo $connection->error;
                    }                
                    elseif ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $movie_id = $row['id'];
                            $poster_url = get_movie_image_url($movie_id); // Gọi hàm từ file api_tmdb.php để lấy URL hình ảnh
                            echo '
                            <div class="movie-box">
                                <img src="' . $poster_url . '" alt="" class="movie-box-img">
                                <div class="box-text">
                                    <h2 class="movie-title">' . $row['title'] . '</h2>
                                    <a href="chitietphim.php?id=' . $row['id'] . '&user_id=' . $_SESSION['user_id'] . '" class="watch-btn play-btn">
                                        <i class="bx bx-right-arrow"></i>
                                    </a>
                                </div>
                            </div>';
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</section>
    <script src="js/main.js"></script>
    <script src="dropdown.js"></script>

</body>
</html>

<?php
$connection->close();
?>
