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
    <title>User Profile</title>

</head>
<body>
<h2>Your Profile</h2>
<div id="profileInfo">
    <!-- Avatar -->
    <div>
        <img src="" alt="User Avatar" id="userAvatar" width="100" height="100">
    </div>

    <!-- Username -->
    <div>
        <strong>Username:</strong> <span id="userName">Current Username</span>
    </div>

    <!-- Email -->
    <div>
        <strong>Email:</strong> <span id="userEmail">user@example.com</span>
    </div>
</div>

<!-- Button to change information -->
<div>
    <button onclick="location.href='updateUser.html'">Thay đổi thông tin</button>
</div>
<script>
    // To get the information of user
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
                        document.getElementById("userAvatar").src = data.userData.iconPath;
                        document.getElementById("userName").textContent = data.userData.username;
                        document.getElementById("userEmail").textContent = data.userData.email;
                    } else {
                        console.error("Error:", data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching user data:', error)
                });
    });

</script>
</body>
</html>
