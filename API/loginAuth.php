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

    // Kiểm tra xem username và password có tồn tại trong dữ liệu đầu vào không
    if (isset($postData['username']) && isset($postData['password'])) {
        $username = $postData['username'];
        $password = $postData['password'];

        // Kiểm tra xem username có tồn tại trong cơ sở dữ liệu không và xác thực người dùng
        if (isUserNameExist($pdo, $username) && userAuthentication($pdo, ['username' => $username, 'password' => $password])) {
            // Lấy thông tin người dùng từ cơ sở dữ liệu
            $userInfo = getUserInfo($pdo, $username);

            // Lưu thông tin người dùng vào session
            $_SESSION['username'] = $userInfo['username'];
            $_SESSION['email'] = $userInfo['email'];
            $_SESSION['userId'] = $userInfo['userId'];
            $_SESSION['avatar'] = $userInfo['iconPath'];

            $response["result"] = "success";
            $response["userData"] = $userInfo;
        } else {
            $response["errMsg"] = "ユーザ名またはパスワードが違います"; // "Username or password is incorrect"
        }
    } else {
        $response["errMsg"] = "ユーザ名とパスワードは必須です"; // "Username and password are required"
    }
} else {
    $response["errMsg"] = "無効なリクエストです"; // "Invalid request."
}

// Đặt tiêu đề cho phản hồi
header('Content-Type: application/json');

// Gửi phản hồi về cho client
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Đóng kết nối cơ sở dữ liệu
disconnect_db($pdo);

?>
