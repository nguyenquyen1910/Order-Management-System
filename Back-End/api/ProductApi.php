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

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';

class ProductApi {
    private $db;
    private $productController;
    private $requestMethod;
    private $productId;
    private $categoryController;
    private $categoryId;
    private $keyword;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productController = new ProductController($this->db);
        $this->categoryController = new CategoryController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        $this->productId = null;
        if(isset($_GET['id'])) {
            $this->productId = $_GET['id'];
        }

        $this->categoryId = null;
        if(isset($_GET['categoryId'])) {
            $this->categoryId = $_GET['categoryId'];
        }

        $this->keyword = null;
        if(isset($_GET['keyword'])) {
            $this->keyword = $_GET['keyword'];
        }
    }
    
    public function processRequest() {
        $response = [];
        switch ($this->requestMethod) {
            case "GET":
                $response = $this->handleGetRequest();
                break;
        }
        echo json_encode($response);
    }

    private function handleGetRequest() {
        if(isset($_GET['topSelling'])) {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $month = isset($_GET['month']) ? (int)$_GET['month'] : null;
            $year = isset($_GET['year']) ? (int)$_GET['year'] : null;
            return $this->productController->getTopSellingProducts($limit, $month, $year);
        }
        if($this->productId) {
            return $this->productController->getProductById($this->productId);
        } elseif($this->categoryId) {
            return $this->productController->getProductByCategoryId($this->categoryId);
        } elseif($this->keyword) {
            return $this->productController->searchProduct($this->keyword);
        } else {
            return $this->productController->getAllProducts();
        }
    }
}

// Tạo đối tượng CustomerApi và xử lý request
try {
    $api = new ProductApi();
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