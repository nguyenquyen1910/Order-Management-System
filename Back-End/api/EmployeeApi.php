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
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../controllers/EmployeeController.php';

class EmployeeApi {
    private $db;
    private $employeeController;
    private $requestMethod;
    private $employeeId;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->employeeController = new EmployeeController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        $this->employeeId = null;
        if (isset($_GET['id'])) {
            $this->employeeId = $_GET['id'];
        } elseif (preg_match('/\/api\/EmployeeApi\.php\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $this->employeeId = $matches[1];
        }
    }

    public function processRequest() {
        $response = [];
        switch ($this->requestMethod) {
            case "GET":
                $response = $this->handleGetRequest();
                break;
            default:
                $response = [
                    'status' => 405,
                    'message' => "Phương thức không hợp lệ",
                    'data' => null
                ];
        }
        
        echo json_encode($response);
    }

    public function handleGetRequest() {
        return $this->employeeController->getAllEmployees();
    }
    
}


try {
    $api = new EmployeeApi();
    $api->processRequest();
} catch (Throwable $e) {

    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
        'data' => null
    ]);
}