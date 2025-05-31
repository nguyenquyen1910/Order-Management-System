<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Xử lý OPTIONS request (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include các file cần thiết
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../controllers/CustomerController.php';


class CustomerApi {
    private $db;
    private $customerController;
    private $requestMethod;
    private $customerId;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->customerController = new CustomerController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        // Lấy ID từ URL (nếu có)
        $this->customerId = null;
        if (isset($_GET['id'])) {
            $this->customerId = $_GET['id'];
        } elseif (preg_match('/\/api\/CustomerApi\.php\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $this->customerId = $matches[1];
        }
    }

    // Xử lý các request
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
                    'message' => 'Phương thức không được hỗ trợ',
                    'data' => null
                ];
        }

        // Trả về response dưới dạng JSON
        echo json_encode($response);
    }

    // Xử lý GET request
    private function handleGetRequest() {
        if(isset($_GET['type']) && $_GET['type'] == 'analysis') {
            return $this->customerController->getCustomerAnalysis();
        } elseif($this->customerId) {
            return $this->customerController->getCustomerById($this->customerId);
        } elseif(isset($_GET['search'])) {
            return $this->customerController->searchCustomer($_GET['search']);
        } elseif(isset($_GET['type']) && $_GET['type'] == 'top') {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $month = isset($_GET['month']) ? (int)$_GET['month'] : null;
            $year = isset($_GET['year']) ? (int)$_GET['year'] : null;
            return $this->customerController->getTopCustomers($limit, $month, $year);
        } else {
            return $this->customerController->getAllCustomers();
        }
    }

    // Xử lý POST request
    private function handlePostRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        return $this->customerController->createCustomer($data);
    }

    // Xử lý PUT request
    private function handlePutRequest() {
        // Kiểm tra ID
        if (!$this->customerId) {
            return [
                'status' => 400,
                'message' => 'Thiếu ID khách hàng',
                'data' => null
            ];
        }
        $data = json_decode(file_get_contents('php://input'), true);
        return $this->customerController->updateCustomer($this->customerId, $data);
    }

    // Xử lý DELETE request
    private function handleDeleteRequest() {
        // Kiểm tra ID
        if (!$this->customerId) {
            return [
                'status' => 400,
                'message' => 'Thiếu ID khách hàng',
                'data' => null
            ];
        }
        return $this->customerController->deleteCustomer($this->customerId);
    }
}

// Tạo đối tượng CustomerApi và xử lý request
try {
    $api = new CustomerApi();
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
