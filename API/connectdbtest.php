<?php
// Bao gồm file kết nối database
include("mysqlConnect.php");

try {
    // Gọi hàm kết nối
    $pdo = connect_db();
    echo "Kết nối thành công đến cơ sở dữ liệu!";
} catch (PDOException $e) {
    // Hiển thị lỗi nếu kết nối thất bại
    echo "Kết nối thất bại: " . $e->getMessage();
}
?>
