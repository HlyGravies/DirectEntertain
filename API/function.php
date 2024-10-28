<?php
// kiểm tra xem ID người dùng có tồn tại hay không
function isUserIdExist($pdo, $userId)
{
    $getUserSql = "SELECT userId FROM users WHERE userId = :userId";
    $getUserStmt = $pdo->prepare($getUserSql);
    $getUserStmt->bindParam(':userId', $userId);
    $getUserStmt->execute();
    return $getUserStmt->fetch(PDO::FETCH_ASSOC);
}

// Kiểm tra xem tên của nguười đùng có tồn tại hay không
function isUserNameExist($pdo, $username)
{
    $getUserSql = "SELECT username FROM users WHERE username = :username";
    $getUserStmt = $pdo->prepare($getUserSql);
    $getUserStmt->bindParam(':username', $username);
    $getUserStmt->execute();
    return $getUserStmt->fetch(PDO::FETCH_ASSOC);
}

function userAuthentication($pdo, $loginData)
{
    $getUserSql = "SELECT password FROM users WHERE username = :username";
    $getUserStmt = $pdo->prepare($getUserSql);
    $getUserStmt->bindParam(':username', $loginData["username"]);
    $getUserStmt->execute();
    $password = $getUserStmt->fetchColumn();
    if ($loginData["password"] === $password) {
        return true;
    } else {
        return false;
    }
}

// Lấy thông tin của user
function getUserInfo($pdo, $username)
{
    $getUserSql = "SELECT userId, userName, email, iconPath FROM users WHERE username = :username";
    $getUserStmt = $pdo->prepare($getUserSql);
    $getUserStmt->bindParam(':username', $username);
    $getUserStmt->execute();
    return $getUserStmt->fetch(PDO::FETCH_ASSOC);
}



?>