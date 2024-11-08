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
    <link rel="manifest" href="../manifest.json">
    <link rel="stylesheet" href="../CSS/home.css">
    <title>Music DEC</title>
</head>

<body>
<!-- Header -->
<div class="header">
    <!-- Logo -->
    <div class="logo">
        <img src="../Assets/Icon/Logo1.png" alt="Drive Logo">
        <h1>Direct Entertain</h1>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <img src="https://www.gstatic.com/images/icons/material/system/1x/search_black_24dp.png" alt="Search Icon">
        <input type="text" placeholder="Search your DEC">
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
            <li><a href="home.php">Main DEC</a></li>
            <li><a href="videoDec.php">Video DEC</a></li>
            <li><a href="musicDec.php">Music DEC</a></li>
            <li><a href="pictureDec.php">Picture DEC</a></li>
            <li><a href="subDec.php">Sub DEC</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome back, <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></h1>
        <br> <br>

        <!-- My DEC Section -->
        <h2>Music DEC</h2>
        <div class="DEContainer" id="contentContainer">
            <!-- Nội dung sẽ được tạo động từ API -->
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

        // Fetch data from the API with the condition to only get type "music"
        fetch("http://localhost/DEproject/API/getDec.php?type=music")
            .then(response => response.json())
            .then(data => {
                if (data.result === "success") {
                    displayContent(data.data);
                } else {
                    console.error("Error fetching content:", data.message);
                }
            })
            .catch(error => console.error("Error:", error));

        function displayContent(contentArray) {
            const container = document.getElementById("contentContainer");
            container.innerHTML = "";

            contentArray.forEach(content => {
                const contentDiv = document.createElement("div");
                contentDiv.className = "file-item";

                contentDiv.innerHTML = `
                    <div class="file-icon">
                        <img src="${content.iconPath}" alt="${content.title}" width="100" height="100">
                    </div>
                    <div class="file-name">${content.title}</div>
                    <div class="file-description">${content.description}</div>
                `;

                contentDiv.onclick = () => window.location.href = content.content_url;
                container.appendChild(contentDiv);
            });
        }
    });
</script>
</body>

</html>
