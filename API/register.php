<?php

// Kết nối đến database, sử dụng file mysqlConnect.php
include("mysqlConnect.php");

// Đóng kết nối đến database, sử dụng file mysqlClose.php
include("mysqlClose.php");

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

    // Kiểm tra nếu bất kỳ trường dữ liệu nào bị thiếu
    if (empty($userData['username']) || empty($userData['email']) || empty($userData['password'])) {
        // Nếu thiếu thông tin, thay đổi kết quả trả về là lỗi và đặt thông báo cho người dùng
        $response["result"] = "error";
        $response["message"] = "Thiếu thông tin cần thiết.";
    } else {
        // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu để đảm bảo an toàn
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

        // Câu lệnh SQL để thêm người dùng mới vào bảng `users`
        // Sử dụng tham số động (:username, :email, :password) để tránh SQL injection
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

        try {
            // Chuẩn bị câu lệnh SQL với các tham số động
            $stmt = $pdo->prepare($sql);
            // Gán giá trị cho các tham số động
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':password', $userData['password']);  // Lưu mật khẩu đã được mã hóa

            // Thực thi câu lệnh SQL
            $stmt->execute();

            // Sau khi thực hiện thành công, trả về thông báo thành công
            $response["message"] = "Đăng ký thành công!";
        } catch (PDOException $e) {
            // Nếu có lỗi trong quá trình thực thi SQL, thay đổi kết quả trả về là lỗi
            $response["result"] = "error";
            // Đặt thông báo lỗi vào message để gửi lại cho frontend
            $response["message"] = "Lỗi: " . $e->getMessage();
        }
    }
}

// Thiết lập kiểu dữ liệu trả về là JSON để phù hợp với frontend
header('Content-Type: application/json');
// Chuyển mảng response thành chuỗi JSON và trả về cho frontend
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Đóng kết nối tới cơ sở dữ liệu
disconnect_db($pdo);

?>