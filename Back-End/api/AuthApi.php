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
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class AuthApi {
    private $db;
    private $authController;
    private $requestMethod;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->authController = new AuthController($this->db);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function processRequest() {
        $response = [];
        switch ($this->requestMethod) {
            case "POST":
                $response = $this->handlePostRequest();
                break;
            default:
                $response = $this->jsonResponse(405, false, "Phương thức không hợp lệ", null);
                break;
        }
        echo json_encode($response);
    }

    private function handlePostRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = [];
        switch ($data['action']) {
            case "login":
                $response = $this->authController->login($data['usernameOrEmail'], $data['password']);
                break;
            case "register":
                $response = $this->authController->register($data);
                break;
            default:
                $response = $this->jsonResponse(400, false, "Phương thức không hợp lệ", null);
                break;
        }
        return $response;
    }

    private function jsonResponse($status, $success, $message, $data) {
        $response = [
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];
        return $response;
    }
}

try {
    $api = new AuthApi();
    $api->processRequest();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
        'data' => null
    ]);
}