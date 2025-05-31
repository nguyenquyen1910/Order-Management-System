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
require_once __DIR__ . '/../models/Delivery.php';
require_once __DIR__ . '/../controllers/DeliveryController.php';

class DeliveryApi {
    private $db;
    private $deliveryController;
    private $requestMethod;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->deliveryController = new DeliveryController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function processRequest() {
        $response = [];
        switch ($this->requestMethod) {
            case "GET":
                $response = $this->handleGetRequest();
                break;
            case "POST":
                $response = $this->handlePostRequest();
                break;
            default:
                $response = [
                    'status' => 405,
                    'message' => "Có lỗi xảy ra",
                    'data' => null
                ];        
        }
        echo json_encode($response);
    }

    private function handleGetRequest() {
        if(isset($_GET['status'])) {
            return $this->deliveryController->getDeliveries($_GET['status']);
        } elseif(isset($_GET['staff_name'])) {
            return $this->deliveryController->getAllDeliveryStaffName();
        } elseif(isset($_GET['staff_name_processing'])) {
            return $this->deliveryController->getStaffNameProcessing();
        } else {
            return $this->deliveryController->getDeliveries();
        }
    }
    private function handlePostRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['order_id'])) {
            return $this->deliveryController->insertDelivery($data);
        } else {
            return [
                'status' => 400,
                'message' => 'Không có dữ liệu được gửi lên',
                'data' => null
            ];
        }
    }
}

// Tạo đối tượng OrderApi và xử lý request
try {
    $api = new DeliveryApi();
    $api->processRequest();
} catch (Throwable $e) {
    // Xử lý lỗi chung
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
        'data' => null
    ]);
}
