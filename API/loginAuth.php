<?php

/*
    Pending
*/

// Kết nối cơ sở dữ liệu
include("mysqlConnect.php"); // Giả sử mysqlConnect.php chứa hàm connect_db()

// Bắt đầu session để lưu trữ thông tin đăng nhập
session_start();

// // Nhận thông tin từ form đăng nhập
// $username = $_POST['username'];
// $pass = $_POST['password'];


if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
} else {
    die("Thiếu dữ liệu đăng nhập!");
}

// Kết nối tới cơ sở dữ liệu bằng PDO
$pdo = connect_db();

try {
    // Chuẩn bị truy vấn để kiểm tra người dùng với deuser
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Kiểm tra xem người dùng có tồn tại không
    if ($stmt->rowCount() > 0) {
        // Người dùng tồn tại, lấy dữ liệu người dùng
        $user = $stmt->fetch();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['username'] = $user['username']; // Lưu tên người dùng trong session
            echo "Đăng nhập thành công! Xin chào, " . $user['username'];

            // Chuyển hướng tới trang giải trí hoặc trang cá nhân
            header("Location: entertainment.php"); // Trang sau khi đăng nhập thành công
            exit();
        } else {
            // Mật khẩu không đúng
            echo "Sai mật khẩu!";
        }
    } else {
        // Không tìm thấy người dùng với deuser đã nhập
        echo "Không tìm thấy người dùng!";
    }
} catch (PDOException $e) {
    // Xử lý lỗi kết nối hoặc truy vấn
    echo "Lỗi: " . $e->getMessage();
}

// Đóng kết nối (không bắt buộc vì PDO tự động giải phóng tài nguyên khi script kết thúc)
?>