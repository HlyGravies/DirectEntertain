<?php

// Kết nối cơ sở dữ liệu
include("mysqlConnect.php");
include("mysqlClose.php");
include("function.php");

// Bắt đầu session để lưu trữ thông tin đăng nhập
session_start();

$pdo = connect_db();

$response = [
    "result" => "error",
    "errMsg" => null,
];

// Kiểm tra xem phương thức HTTP có phải là POST hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu JSON từ yêu cầu
    $postData = json_decode(file_get_contents('php://input'), true);

    // Kiểm tra xem userId có tồn tại trong dữ liệu đầu vào không
    if (isset($postData['username']) && isset($postData['password'])) {
        // Kiểm tra xem userId có tồn tại trong cơ sở dữ liệu không và xác thực người dùng
        if (isUserNameExist($pdo, $postData['username']) && userAuthentication($pdo, $postData)) {
            // Lưu thông tin người dùng vào session (nếu cần)
            $_SESSION['username'] = $postData['username'];

            $response["result"] = "success";
            $response["userData"] = getUserInfo($pdo, $postData["username"]);
        } else {
            $response["errMsg"] = "ユーザ名またはパスワードが違います"; // "User ID or password is incorrect"
        }
    } else {
        $response["errMsg"] = "ユーザ名とパスワードは必須です"; // "User ID and password are required"
    }
} else {
    $response["errMsg"] = "無効なリクエストです"; // "Invalid request."
}

// Đặt tiêu đề cho phản hồi
header('Content-Type: application/json');

// Gửi phản hồi về cho client
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Đóng kết nối cơ sở dữ liệu
require_once 'mysqlClose.php';
disconnect_db($pdo);
?>
