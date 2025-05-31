<?php
class CategoryController {
    private $db;
    private $category;

    public function __construct($db) {
        $this->db = $db;
        $this->category = new Category($db);
    }

    public function getAllCategories() {
        $categories = $this->category->getAllCategories();
        return $this->jsonResponse(200, "Thành công", $categories);
    }

    public function getCategoryById($id) {
        $result = $this->category->getCategoryById($id);
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(404, "Không tìm thấy danh mục", null);
        }
    }

    private function jsonResponse($status, $message, $data) {
        $response = [
            'status' => $status,
            "message" => $message,
            "data" => $data
        ];
        return $response;
    }
}
