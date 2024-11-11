<?php
session_start();
require_once 'mysqlConnect.php';
$pdo = connect_db();

$response = [
    "result" => "error",
    "message" => ""
];

// Check to see if the user have logged in or not
if(!isset($_SESSION['userId'])){
    $response["message"] = "User not logged in.";
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if demainId is in the input data
    if (isset($data['demainId'])) {
        $demainId = $data['demainId'];

        // Execute a query to add demainId to the user_demain table
        $sql = "INSERT INTO user_demain (userId, demainId) VALUES (:userId, :demainId)";
        $stmt = $pdo -> prepare($sql);

        try {
            $stmt -> execute([':userId' => $userId, ':demainId' => $demainId]);
            $response["result"] = "success";
            $response["message"] = "Added demainId to user_demain successfully.";
        } catch (PDOException $e) {
            $response["message"] = "Database error: " . $e->getMessage();
        }
    } else {
        $response["message"] = "Missing demainId in request.";
    }
} else {
    $response["message"] = "Invalid request method.";
}

header('Content-Type: application/json');
echo json_encode($response);