<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';
include 'C:/Users/Anbewwwwwwwwwwwwww/Desktop/api_tmdb.php';

session_start();

// Kết nối đến cơ sở dữ liệu

// Kiểm tra xem user_id đã tồn tại trong $_SESSION hay chưa
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


// Kiểm tra xem id phim đã được truyền vào hay chưa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn thông tin phim dựa trên id
    $sql = "SELECT * FROM tmdb_5000_movies WHERE id = $id";
    $result = $connection->query($sql);

    // Kiểm tra xem có kết quả trả về hay không
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Kiểm tra yêu cầu thêm vào danh sách yêu thích
        if (isset($_POST['add_to_favorites'])) {
            // Kiểm tra xem user đã đăng nhập hay chưa
            if ($user_id !== null) {
                // Kiểm tra xem bộ phim đã có trong danh sách yêu thích của user chưa
                $checkSql = "SELECT * FROM favorites WHERE user_id = '$user_id' AND movie_id = '$id'";
                $checkResult = $connection->query($checkSql);

                if ($checkResult->num_rows > 0) {
                    echo "Bộ phim đã có trong danh sách yêu thích của bạn.";
                } else {
                    // Thêm bộ phim vào danh sách yêu thích
                    $insertSql = "INSERT INTO favorites (user_id, movie_id) VALUES ('$user_id', '$id')";
                    $insertResult = $connection->query($insertSql);

                    if ($insertResult === true) {
                        echo "Bộ phim đã được thêm vào danh sách yêu thích của bạn.";
                    } else {
                        echo "Đã xảy ra lỗi. Vui lòng thử lại sau.";
                    }
                }
            } else {
                echo "Bạn phải đăng nhập để thực hiện thao tác này.";
            }
        }
    }
}





// Kiểm tra xem id phim đã được truyền vào hay chưa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn thông tin phim dựa trên id
    $sql = "SELECT * FROM tmdb_5000_movies WHERE id = $id";
    $result = $connection->query($sql);

    // Kiểm tra xem có kết quả trả về hay không
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Hiển thị thông tin phim
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Chi tiết phim - <?php echo $row['title']; ?></title>
            <link rel="stylesheet" href="css/style.css">
            <link rel="shortcut icon" href="img/fav-icon.png" type="image/x-icon">
            <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
            <link rel="stylesheet" type="text/css" href="dropdown.css">
            <style>

.add-to-favorites {
            background-color: #E91A46;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
        }

        .add-to-favorites:hover {
            background-color: #c60738;
        }

                .movie-details.container {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                   //align-items: flex-start;
                    margin-top: 60px;

                }

                .movie-poster {
                    width: 35%;
                    position: relative;
                    cursor: pointer;
                    border-radius: 20px;
                }

                .movie-poster img {
                    transition: filter 0.3s ease;
                }

                .movie-poster:hover img {
                    filter: brightness(0.7);
                }

                .play-icon {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    display: none;
                    color: white;
                    font-size: 48px;
                    opacity: 0.8;
                }

                .movie-poster:hover .play-icon {
                    display: block;
                }

                .play-button {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    display: none;
                    background-color: #e80535;
                    color: white;
                    padding: 10px 20px;
                    font-size: 18px;
                    opacity: 1;
                    z-index: 2;
                    text-align: center;
                    font-weight: bold;

                }

                .movie-poster:hover .play-button {
                    display: block;
                }

                .movie-info {
                    width: 65%;
                    padding-left: 32px;
                    font-size: 20px;
                    line-height: 2;
                }

                .movie-description {
                    width: 100%;
                    padding-top: 16px;
                    font-size: 22px;
                    line-height: 2;
                    padding-bottom: 50px;
                }

                .poster-info-container {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    background-color: #22222E;
                    padding: 40px;
                    border-radius: 10px;
                    width: 100%;
                }

                .movie-title {
                    color: #E91A46;
                    font-weight: 550;
                }

                .movie-description h2 {
                    color: #E91A46;
                    font-size: 30px;
                }

                .movie-info strong {
                    color: #B8B8B8
                }
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
        <button class="search-button" type="submit" name="btn">
            <i class="bx bx-search"></i>
        </button>
    </form>
</div>

<a href="<?php echo isset($_SESSION['user_id']) ? 'UserInfo.php?user_id=' . $_SESSION['user_id'] : 'Dangnhap.php'; ?>" class="user">
                <img src="<?php echo isset($user['avatar_link']) ? $user['avatar_link'] : 'img/images.png'; ?>" alt="" class="user-img">
            </a>

                    <div class="navbar">

                    <a href="Trangchu.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="nav-link">
                            <i class="bx bx-home"></i>
                            <span class="nav-link-title">Trang chủ</span>
                        </a>

                        <a href="Trangchu.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="nav-link">
                            <i class="bx bxs-hot"></i>
                            <span class="nav-link-title">Thịnh hành</span>
                        </a>

                        <a href="PhimBo.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="nav-link">
                            <i class="bx bxs-movie"></i>
                            <span class="nav-link-title">Phim bộ</span>
                        </a>

                        <a href="PhimLe.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="nav-link">
                            <i class="bx bxs-film"></i>
                            <span class="nav-link-title">Phim lẻ</span>
                        </a>

                        <div class="dropdown-toggle-container" id="genre-dropdown-toggle">
                <a href="#" class="nav-link dropdown ">
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
                 <!-- Thêm các thể loại khác tương ứng với các option -->
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

                <div class="poster-info-container">
                    <div class="movie-poster">
                    <a href="XemTrailer.php?id=<?php echo $row['id']; ?>&user_id=<?php echo $_SESSION['user_id']; ?>">
                        <?php
                        $movie_id = $row['id'];
                        $poster_url = get_movie_image_url($movie_id);
                        ?>
                        <img src="<?php echo $poster_url; ?>" alt="Movie Poster">
                        <i class="play-icon bx bx-play-circle"></i>
                        <div class="play-button">Xem phim</div>
                    </a>

                    </div>
                    <div class="movie-info">
                        <h2 class="movie-title"><?php echo $row['title']; ?></h2>
                        <p><strong>Tên khác:</strong> <?php echo $row['original_title']; ?></p>
                        <p><strong>Năm sản xuất:</strong> <?php echo $row['release_date']; ?></p>
                        <p><strong>Thể loại:</strong> <?php 
                        $movie_id = $row['id'];
                        $sql = "SELECT GROUP_CONCAT(JSON_EXTRACT(genres, '$[*].name')) as genre FROM tmdb_5000_movies WHERE id = $movie_id";
                        $result = $connection->query($sql);
                        $genres = $result->fetch_assoc();
                        $genre_string = $genres['genre'];
                        $genres = explode(',', $genre_string);
                        foreach ($genres as $item) {
                            echo $item;
                        }
                    ?></p>
                        <p><strong>Trạng thái:</strong> <?php echo $row['status']; ?></p>

                        <form method="POST" action="AddFavorite.php?id=<?php echo $row['id']; ?>&user_id=<?php echo $_SESSION['user_id']; ?>">
    <button type="submit" name="add_to_favorites" class="add-to-favorites">Thêm vào danh sách yêu thích</button>
</form>

                    </div>
                </div>
                <div class="movie-description">
                    <h2>Nội dung chi tiết</h2>
                    <p><?php echo $row['overview']; ?></p>
                </div>
               

               <?php
        // Gọi file comment.php
        include 'comment.php';
        ?>

            </section>
            <script src="js/main.js"></script>
            <script src="dropdown.js"></script>
            
        </body>

        </html>
<?php
    } else {
        echo "Không tìm thấy phim.";
    }
} else {
    echo "Không có id phim được truyền vào.";
}

// Đóng kết nối cơ sở dữ liệu
$connection->close();
?>
