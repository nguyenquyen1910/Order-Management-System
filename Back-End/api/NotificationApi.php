<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include các file cần thiết
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../controllers/NotificationController.php';

class NotificationApi {
    private $db;
    private $notificationController;
    private $requestMethod;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->notificationController = new NotificationController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function processRequest() {
        $response = [];
        switch ($this->requestMethod) {
            case "GET":
                $response = $this->handleGetRequest();
                break;
            case "PUT":
                $response = $this->handlePutRequest();
                break;
            default:
                $response = $this->jsonResponse(405, "Phương thức không hợp lệ", null);
        }
        echo json_encode($response);
    }

    public function handleGetRequest() {
        return $this->notificationController->getRecentNotifications();
    }

    public function handlePutRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        $notificationId = $data['id'] ?? null;
        if(!$notificationId) {
            return $this->jsonResponse(400, "Thiếu ID thông báo", null);
        }
        return $this->notificationController->markAsRead($notificationId);
    }
    
    private function jsonResponse($status, $message, $data) {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        return $response;
    }
}


try {
    $api = new NotificationApi();
    $api->processRequest();
} catch (Throwable $e) {

    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
        'data' => null
    ]);
}