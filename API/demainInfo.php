<?php

include("mysqlConnect.php");
include("mysqlClose.php");
include ("function.php");

$pdo = connect_db();

$response = [
    "result" => "success",
    "data" => null,
    "message" => ""
];

// Kiểm tra xem phương thức HTTP được sử dụng có phải là POST hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu JSON từ body của yêu cầu và chuyển đổi thành mảng PHP
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Kiểm tra xem demainID có tồn tại trong dữ liệu đầu vào không
    if (isset($inputData['demainID'])) {
        $demainID = $inputData['demainID'];

        // Truy vấn để lấy thông tin từ bảng demain
        $sql = "SELECT * FROM demain WHERE demainID = :demainID";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam('demainID', $demainID);
            $stmt->execute();

            // Lấy dữ liệu
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $response["data"] = $data;  // Gán dữ liệu vào mảng response
            } else {
                $response["result"] = "error";
                $response["message"] = "Demain ID not found.";
            }
        } catch (PDOException $e) {
            $response["result"] = "error";
            $response["message"] = "Error: " . $e->getMessage();
        }
    } else {
        $response["result"] = "error";
        $response["message"] = "Demain ID is required.";
    }
} else {
    $response["result"] = "error";
    $response["message"] = "Invalid request method.";
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

require_once 'mysqlClose.php';
disconnect_db($pdo);
?>
