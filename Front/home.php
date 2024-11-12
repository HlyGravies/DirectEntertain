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
    <title>Home</title>
    <style>
        /* CSS cho pop-up */
        .popup-overlay {
            display: none; /* Ẩn mặc định */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .popup-window {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            width: 300px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .popup-window h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .popup-window .option-button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            color: white;
        }

        .insert-button {
            background-color: #4CAF50; /* Màu xanh lá cho Insert Manually */
        }

        .dec-button {
            background-color: #1a73e8; /* Màu xanh dương cho Using DEC */
        }

        .close-popup {
            margin-top: 15px;
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
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
        <!-- Add button -->
        <div class="add-button">
            <button style="padding: 6px 10px; border: none; background-color: #1a73e8; color: white; border-radius: 4px;"
                    onclick="openPopup()">+ New</button>
        </div>　

        <!-- Pop-up Overlay & Pop-up Window -->
        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-window" id="popupWindow">
                <h3>Select an Option</h3>
                <button class="option-button insert-button" onclick="insertManually()">Insert manually</button>
                <button class="option-button dec-button" onclick="useDEC()">Using DEC</button>
                <button class="close-popup" onclick="closePopup()">Close</button>
            </div>
        </div>

        <!-- Pop-up nhập ID cho "Insert manually" -->
        <div class="popup-overlay" id="insertBoxOverlay">
            <div class="popup-window" id="insertBox">
                <h3>Enter Demain ID</h3>
                <input type="number" id="demainIdInput" placeholder="Enter demain ID">
                <button class="confirm-button" onclick="submitDemainId()">Confirm</button>
                <button class="close-popup" onclick="closeInsertBox()">Close</button>
            </div>
        </div>

            <div class="user-icon">
                <a href="userProfile.php"><img src="" alt="User Icon" id="userIcon" width="50" height="50"></a>
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
            <h1>Hello <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?>, What would you like to do today?</h1>
            <br><br>

            <!-- My DEC Section -->
            <h2>Main DEC</h2>
            <div class="DEContainer" id="contentContainer">
                <!-- Content will be generated automatically from API -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchContent();
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

            // Fetch data from API and display it in the contentContainer
            fetch("http://localhost/DEproject/API/getDec.php")
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

        // Open pop-up
        function openPopup() {
            document.getElementById('popupOverlay').style.display = 'block';
        }

        // Close pop-up
        function closePopup() {
            document.getElementById('popupOverlay').style.display = 'none';
        }

        // "Insert manually" function
        function insertManually() {
            document.getElementById('popupOverlay').style.display = 'none';
            document.getElementById('insertBoxOverlay').style.display = 'block';
        }

        // Close the insert box
        function closeInsertBox() {
            document.getElementById('insertBoxOverlay').style.display = 'none';
        }

        // "Using DEC" function
        function useDEC() {
            closePopup();
            alert("Using DEC selected.");
            // Thực hiện các hành động cần thiết khi chọn "Using DEC"
        }

        // Return button for insert box

        // Send demainID to API to add into database
        function submitDemainId() {
            const demainId = document.getElementById('demainIdInput').value;

            if (!demainId) {
                alert("Please enter a valid Demain ID");
                return;
            }

            fetch("http://localhost/DEproject/API/addDEC.php", {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({demainId: parseInt(demainId)})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.result === "success") {
                        alert("Demain ID added successfully");
                        closePopup();

                        // Reload content after successful addition
                        fetchContent();
                    } else {
                        alert("Error:" + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error connecting to server.");
                });
        }
        // Fetch data from API and display it in the contentContainer
        function fetchContent() {
            fetch("http://localhost/DEproject/API/getDec.php")
                .then(response => response.json())
                .then(data => {
                    if (data.result === "success") {
                        displayContent(data.data);
                    } else {
                        console.error("Error fetching content:", data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        }


    </script>
</body>
</html>