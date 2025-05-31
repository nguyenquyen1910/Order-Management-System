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
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../models/Customer.php';

class OrderApi {
    private $db;
    private $orderController;
    private $requestMethod;
    private $orderId;
    private $customerId;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderController = new OrderController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        $this->orderId = null;
        if (isset($_GET['id'])) {
            $this->orderId = $_GET['id'];
        } elseif (preg_match('/\/api\/OrderApi\.php\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $this->orderId = $matches[1];
        }

        $this->customerId = null;
        if(isset($_GET['customerId'])) {
            $this->customerId = $_GET['customerId'];
        }
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
            case "PUT":
                $response = $this->handlePutRequest();
                break;
            case "DELETE":
                $response = $this->handleDeleteRequest();
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
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($_GET['statistic'])) {
            return $this->orderController->getStatistic();
        } elseif(isset($_GET['recent'])) {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            return $this->orderController->getRecentOrders();
        } elseif($this->orderId) {
            return $this->orderController->getOrder($this->orderId);
        } elseif($this->customerId) {
            return $this->orderController->getOrdersByCustomer($this->customerId);
        } elseif(isset($_GET['search'])) {
            return $this->orderController->searchOrder($_GET['search']);
        } elseif(isset($_GET['status']) || isset($_GET['employee']) || isset($_GET['fromDate']) || isset($_GET['toDate'])) {
            $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
            $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $employee = isset($_GET['employee']) ? $_GET['employee'] : null;
            return $this->orderController->getFilteredOrders($fromDate, $toDate, $status, $employee);
        } elseif(isset($_GET['orderDetails'])) {
            return $this->orderController->getOrderDetails();
        } elseif(isset($_GET['districts'])) {
            return $this->orderController->getDistricts();
        } elseif(isset($_GET['delivering'])) {
            return $this->orderController->getOrdersDelivering();
        } elseif(isset($_GET['delivery'])) {
            $timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : 'all';
            $district = isset($_GET['district']) ? $_GET['district'] : null;
            $staffName = isset($_GET['staffName']) ? $_GET['staffName'] : null;
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
            return $this->orderController->filterOrderByDelivery($timeRange, $district, $staffName, $keyword);
        } else {
            return $this->orderController->getAllOrder();
        }
    }
    
    private function handlePostRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        return $this->orderController->createOrder($data);
    }

    private function handlePutRequest() {
        if (!$this->orderId) {
            return [
                'status' => 400,
                'message' => "Thiếu ID đơn hàng",
                'data' => null
            ];
        } elseif(isset($_GET['status']) && isset($_GET['id'])) {
            return $this->orderController->handleOrder($_GET['id'], $_GET['status']);
        }
        $data = json_decode(file_get_contents('php://input'), true);

        return $this->orderController->updateOrder($this->orderId, $data);
    }

    private function handleDeleteRequest() {
        // Kiểm tra ID
        if (!$this->orderId) {
            return [
                'status' => 400,
                'message' => 'Thiếu ID đơn hàng',
                'data' => null
            ];
        }
        return $this->orderController->deleteOrder($this->orderId);
    }
}

// Tạo đối tượng OrderApi và xử lý request
try {
    $api = new OrderApi();
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