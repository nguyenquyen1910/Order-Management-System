<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

class ProductController {

    private $db;
    private $product;

    public function __construct($db) {
        $this->db = $db;
        $this->product = new Product($this->db);
    }

    public function getAllProducts() {
        $products = $this->product->getAllProducts();
        return $this->jsonResponse(200, "Thành công", $products);
    }

    public function getProductById($id) {
        $result = $this->product->getProductById($id);
        if ($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(404, "Không tìm thấy sản phẩm", null);
        }
    }

    public function getProductByCategoryId($categoryId) {
        $result = $this->product->getProductByCategoryId($categoryId);
        if ($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(404, "Không tìm thấy sản phẩm", null);
        }
    }

    public function searchProduct($keyword) {
        $result = $this->product->searchProduct($keyword);
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(404, "Không tìm thấy sản phẩm", null);
        }
    }

    public function getTopSellingProducts($limit = 5, $month = null, $year = null) {
        $result = $this->product->getTopSellingProducts($limit, $month, $year);
        return $this->jsonResponse(200, "Thành công", $result);
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