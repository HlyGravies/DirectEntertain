<?php

require_once 'mysqlConnect.php';
$pdo = connect_db();

// Create a response to return the result
$response = [
    "result" => "error", // Default is error; it will change upon success.
    "data" => []
];

// Check to see if HTTP methods is GET or not
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    // Get userId from session
    session_start();
    if (!isset($_SESSION['userId'])) {
        $response['message'] = "User not logged in.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    $userId = $_SESSION['userId'];

    // Get the type parameter if available (e.g., Video, Music, Picture, Sub)
    $type = isset($_GET['type']) ? $_GET['type'] : null;

    // // SQL statement based on type
    if ($type) {
        $sql = "SELECT d.demainId, d.title, d.type, d.description, d.content_url, d.iconPath
                FROM demain d
                JOIN user_demain ud on d.demainID = ud.demainID
                WHERE ud.userID = :userId AND d.type = :type";
    } else {
        // If type is equal to null, get everything
        $sql = "SELECT d.demainId, d.title, d.description, d.content_url, d.iconPath, d.type
                FROM demain d
                JOIN user_demain ud ON d.demainId = ud.demainId
                WHERE ud.userId = :userId";
    }
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':userId', $userId, PDO::PARAM_INT);
    if($type) {
        $stmt -> bindParam(':type', $type, PDO::PARAM_INT);
    }
    try {
        $stmt -> execute();
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        if ($data) {
            $response["result"] = "success";
            $response["data"] = $data;
        } else {
            $response["message"] = "No content";
        }
    } catch (PDOException $e) {
        $response["message"] = "Database error: " . $e -> getMessage();
    }
} else {
    $response["message"] = "Invalid request method";
}

// Set the header to JSON and output the response
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>