<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../controllers/DashboardController.php';
require_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();
$dashboardController = new DashboardController($db);

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        $period = isset($_GET['period']) ? $_GET['period'] : 'month';
    
        $dashboardData = $dashboardController->getDashboardData($period);
        
        echo json_encode([
            'status' => 200,
            'data' => $dashboardData
        ]);
    } else {
        echo json_encode([
            'status' => 405,
            'message' => 'Phương thức không được hỗ trợ'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 500,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ]);
}