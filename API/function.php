<?php
// kiểm tra xem người dùng có tồn tại hay không
function isUserIdExist($pdo, $userId){
    $getUserSql = "SELECT userId FROM users WHERE userId = :userId";
    $getUserStmt = $pdo -> prepare($getUserSql);
    $getUserStmt -> bindParam(':userId', $userId);
    $getUserStmt -> execute();
    return $getUserStmt -> fetch(PDO::FETCH_ASSOC);
}

// Lấy thông tin của user
function getUserInfo($pdo, $userId) {
    $getUserSql = "SELECT userId, userName, profile, iconPath FROM users WHERE userId = :userId";
    $getUserStmt = $pdo->prepare($getUserSql);
    $getUserStmt->bindParam(':userId', $userId);
    $getUserStmt->execute();
    return $getUserStmt->fetch(PDO::FETCH_ASSOC);
}


?>