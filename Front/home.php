<?php
session_start(); // Bắt đầu session

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Chuyển hướng đến trang đăng nhập
    exit;
}

require_once '../API/mysqlConnect.php'; // Kết nối đến cơ sở dữ liệu
$pdo = connect_db();

// Get userID from sesion
$userId = $_SESSION['userId'];

// Thực hiện truy vấn để lấy các mục trong bảng demain mà user có quyền truy cập
$sql = "SELECT d.demainId, d.title, d.description, d.content_url, d.iconPath
        FROM demain d
        JOIN user_demain ud ON d.demainId = ud.demainId
        WHERE ud.userId = :userId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();

// Store everything into $demainContent
$demainContent = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="manifest" href="../manifest.json">
    <link rel="stylesheet" href="./login.css">
    <title>Home</title>
    <style>
        /* Reset margin và padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #f1f3f4;
            border-bottom: 1px solid #e0e0e0;
        }

        .header .logo {
            display: flex;
            align-items: center;
        }

        .header .logo img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .header .search-bar {
            flex: 1;
            max-width: 600px;
            position: relative;
        }

        .header .search-bar input {
            width: 100%;
            padding: 8px 12px;
            padding-left: 35px;
            border-radius: 20px;
            border: 1px solid #e0e0e0;
            outline: none;
        }

        .header .search-bar img {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            opacity: 0.5;
        }

        .header .user-options {
            display: flex;
            align-items: center;
        }

        .header .user-options .user-icon {
            margin-left: 20px;
            border-radius: 50%;
            overflow: hidden;
            width: 32px;
            height: 32px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #f1f3f4;
            padding-top: 20px;
            height: 100vh;
            border-right: 1px solid #e0e0e0;
        }

        .sidebar ul {
            list-style-type: none;
        }

        .sidebar ul li {
            padding: 15px 20px;
            color: #5f6368;
            cursor: pointer;
        }

        .sidebar ul li:hover {
            background-color: #e8f0fe;
            color: #1967d2;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .container {
            display: flex;
        }

        /* CSS cho bố cục lưới */
        .DEContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); /* Tăng kích thước tối thiểu của ô */
            gap: 25px; /* Tăng khoảng cách giữa các ô */
            padding: 20px;
        }

        .file-item {
            background-color: #f1f3f4;
            border-radius: 8px;
            padding: 20px 20px 10px 20px; /* Điều chỉnh padding để giảm khoảng cách phía dưới */
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease;
            font-size: 20px;
        }

        .file-item:hover {
            transform: scale(1.05);
        }

        .file-icon {
            width: 100%;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Di chuyển ảnh lên trên */
            overflow: hidden;
        }

        .file-icon img {
            width: 90%;
            height: auto;
            margin-bottom: 5px; /* Thêm khoảng cách giữa ảnh và chữ */
            border-radius: 8px;
        }

        .file-name {
            margin-top: 20px;
            font-size: 20px; /* Tăng kích thước chữ cho tên file */
            color: #333;
        }

        .file-preview {
            display: none;
            padding: 20px;
        }

    </style>
</head>

<body>
<!-- Header -->
<div class="header">
    <!-- Logo -->
    <div class="logo">
        <img src="../Assets/E.png" alt="Drive Logo">
        <h1>Direct Entertain</h1>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <img src="https://www.gstatic.com/images/icons/material/system/1x/search_black_24dp.png" alt="Search Icon">
        <input type="text" placeholder="Search in DE">
    </div>

    <!-- User Options -->
    <div class="user-options">
        <div class="add-button">
            <button style="padding: 6px 10px; border: none; background-color: #1a73e8; color: white; border-radius: 4px;">
                + New
            </button>
        </div>
        <div class="user-icon">
            <a href="userProfile.php"><img src="" alt="User Icon" id="userIcon" width="32" height="32"></a>
        </div>
    </div>
</div>

<!-- Container for Sidebar and Main Content -->
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li>My DEC</li>
            <li>Video DEC</li>
            <li>Music DEC</li>
            <li>Picture DEC</li>
            <li>Favorite DEC</li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome back, <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></h1>
        <br> <br>

        <!-- My DEC Section -->
        <h2>My DEC</h2>
        <div class="DEContainer">
            <!-- Các ô nội dung được tạo động từ cơ sở dữ liệu -->
            <?php foreach ($demainContent as $content): ?>
                <div class="file-item" onclick="window.location.href ='<?= htmlspecialchars($content['content_url']) ?>'">
                    <div class="file-icon">
                        <img src="<?= htmlspecialchars($content['iconPath']) ?>" alt="Icon" width="100" height="222">
                    </div>
                    <div class="file-name"><?= htmlspecialchars($content['title']) ?></div>
                    <div class="file-description"><?= htmlspecialchars($content['description']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get userId from session
        const userId = <?php echo json_encode($_SESSION["userId"]); ?>

            // Send POST request to userInfo.php in order to get the user information
            fetch("http://localhost/DEproject/API/userInfo.php", {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({userId: userId})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.result === "success") {
                        document.getElementById("userIcon").src = data.userData.iconPath;
                    } else {
                        console.error("Error:", data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching user data:', error)
                });

        // Hàm JavaScript để hiển thị nội dung chi tiết khi nhấp vào file
        function showPreview(fileId) {
            // Ẩn tất cả các nội dung chi tiết
            document.querySelectorAll('.file-preview').forEach(preview => {
                preview.style.display = 'none';
            });

            // Hiển thị nội dung chi tiết của file được nhấp vào
            document.getElementById(fileId).style.display = 'block';
        }
    });
</script>
</body>

</html>
