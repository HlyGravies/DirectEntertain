<?php
include("mysqlConnect.php");
include("mysqlClose.php");

$pdo = connect_db();
session_start();

$response = [
    "result" => "success",
    "message" => null
];

if (!isset($_POST['userID']) || empty($_POST['userID'])) {
    $response['result'] = "error";
    $response['message'] = "UserID không được cung cấp.";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$userID = $_POST['userID'];



// Cập nhật username và avatar
if (!empty($userData["username"])) {
    $username = $userData["username"];

    $iconPath = '';
    if (isset($_FILES["iconPath"]) && $_FILES["iconPath"]["error"] === UPLOAD_ERR_OK) {
        $targetDir = "Assets/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = $userID . '_avatar.' . pathinfo($_FILES["iconPath"]["name"], PATHINFO_EXTENSION);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["iconPath"]["tmp_name"], $targetFilePath)) {
            $iconPath = $targetFilePath;
        } else {
            $response["result"] = "error";
            $response["message"] = "Failed to upload avatar.";
        }
    }
    if ($response["result"] === "success") {
        $sql = "UPDATE users SET userName = :userName, iconPath = :iconPath WHERE userId = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':iconPath', $iconPath);
        $stmt->execute();
    }
}

// Xử lý đổi mật khẩu
if (!empty($userData["currentPassword"]) && !empty($userData["newPassword"]) && !empty($userData['confirmPassword'])) {
    $currentPassword = $userData["currentPassword"];
    $newPassword = $userData["newPassword"];
    $confirmPassword = $userData["confirmPassword"];

    // Kiểm tra mật khẩu hiện tại
    $stmt = $pdo->prepare("SELECT password FROM users WHERE userID = :userID");
    $stmt->execute([":userID" => $userID]);
    $user = $stmt->fetch();

    if (password_verify($currentPassword, $user["password"])) {
        if ($newPassword === $confirmPassword) {
            // Cập nhật mật khẩu mới
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :newPassword WHERE userId = :userId");
            $stmt->execute([':newPassword' => $newHashedPassword, ':userId' => $userID]);
        } else {
            $response["result"] = "error";
            $response["errorDetails"] = "New passwords do not match.";
        }
    } else {
        $response["result"] = "error";
        $response["errorDetails"] = "Incorrect current password.";
    }
}

header('Content-Type: application/json');
echo json_encode($_POST);

?>