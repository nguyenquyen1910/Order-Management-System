<?php

require_once __DIR__ . '/../models/Delivery.php';

class DeliveryController {
    private $db;
    private $delivery;

    public function __construct($db) {
        $this->db = $db;
        $this->delivery = new Delivery($db);
    }

    public function getDeliveries($status=null) {
        $result = $this->delivery->getDeliveries($status);
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(500, "Không có dữ liệu", []);
        }
    }

    public function getAllDeliveryStaffName() {
        $result = $this->delivery->getAllDeliveryStaffName();
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(500, "Không có dữ liệu", []);
        }
    }

    public function getStaffNameProcessing() {
        $result = $this->delivery->getStaffNameProcessing();
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(500, "Không có dữ liệu", []);
        }
    }

    public function insertDelivery($data) {
        $result = $this->delivery->insertDelivery($data);
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(500, "Lỗi khi phân công giao hàng", null);
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