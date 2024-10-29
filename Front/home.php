<?php
session_start(); // Bắt đầu session

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Chuyển hướng đến trang đăng nhập
    exit;
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
            <button style="padding: 6px 10px; border: none; background-color: #1a73e8; color: white; border-radius: 4px;">+ New</button>
        </div>
        <div class="user-icon">
            <a href="./userInfo.html"><img src="https://www.w3schools.com/howto/img_avatar.png" alt="User Icon" width="32" height="32"></a>
        </div>
    </div>
</div>

<!-- Container for Sidebar and Main Content -->
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li>My Collection</li>
            <li>Video</li>
            <li>Music</li>
            <li>Picture</li>
            <li>Favorite</li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>My Drive</h2>
        <!-- Content sẽ hiển thị tên người dùng dưới dạng in hoa -->
        <p>Welcome back, <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></p>
    </div>
</div>
</body>

</html>
