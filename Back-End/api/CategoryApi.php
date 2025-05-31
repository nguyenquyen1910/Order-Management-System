<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../controllers/CategoryController.php';

class CategoryApi {
    private $db;
    private $categoryController;
    private $requestMethod;
    private $categoryId;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoryController = new CategoryController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        $this->categoryId = null;
        if (isset($_GET['id'])) {
            $this->categoryId = $_GET['id'];
        } elseif (preg_match('/\/api\/CategoryApi\.php\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $this->categoryId = $matches[1];
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
                    'message' => 'Phương thức không được hỗ trợ',
                    'data' => null
                ];
        }
        echo json_encode($response);
    }

    private function handleGetRequest() {
        if($this->categoryId) {
            return $this->categoryController->getCategoryById($this->categoryId);
        } else {
            return $this->categoryController->getAllCategories();
        }
    }
}

try {
    $api = new CategoryApi();
    $api->processRequest();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
        'data' => null
    ]);
}

