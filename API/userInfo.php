<?php

// Kết nối đến database, sử dụng file mysqlConnect.php
include("mysqlConnect.php");

// Đóng kết nối đến database, sử dụng file mysqlClose.php
include("mysqlClose.php");

// liên kết function.php
include ("function.php");

// Gọi hàm để kết nối đến database
$pdo = connect_db();

// Khởi tạo mảng response để lưu trữ kết quả trả về
$response = [
    "result" => "success",  // Mặc định là thành công, nếu có lỗi sẽ thay đổi sau
    "message" => ""         // Lưu trữ thông điệp cho người dùng (ví dụ lỗi hoặc thành công)
];

// Kiểm tra xem phương thức HTTP được sử dụng có phải là POST hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu JSON từ body của yêu cầu và chuyển đổi thành mảng PHP
    $userData = json_decode(file_get_contents('php://input'), true);

    if(!isUserIdExist($pdo, $userData)){
        $response["result"] = "error";
        $response["message"] = "USERIDが見つかりません";
    }
    if($response["message"] === null){
        try {
            $userData = getUserInfo($pdo, $userData);
            $response["result"] = "success";
            $response['userData'] = $userData;
        } catch (PDOException $e) {
            echo "Error: " . $e -> getMessage();
        }
    } else{
        $response["result"] = "error";
    }
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

require_once 'mysqlClose.php';
disconnect_db($pdo);
?>