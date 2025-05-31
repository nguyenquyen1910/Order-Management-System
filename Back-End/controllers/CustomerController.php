<?php

class CustomerController {
    private $db;
    private $customer;
    
    public function __construct($db) {
        $this->db = $db;
        $this->customer = new Customer($db);
    }
    /**
     * Lấy tất cả khách hàng
    */
    public function getAllCustomers() {
        $result = $this->customer->getAllCustomers();
        $customers = [];
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $customers[] = $row;
        }
        return $this->jsonResponse(200, "Thành công", $customers);
    }

    /**
     * Lấy khách hàng theo ID
    */
    public function getCustomerById($id) {
        $customer = $this->customer->getCustomerById($id);
        if($customer) {
            return $this->jsonResponse(200, "Thành công", $customer);
        } else {
            return $this->jsonResponse(404, "Không tìm thấy khách hàng", null);
        }
    }

    /**
     * Tìm kiếm khách hàng
    */
    public function searchCustomer($keyword) {
        $result = $this->customer->getCustomerByNameOrPhoneNumber($keyword);
        $customers = [];
        while($row = $result -> fetch(PDO::FETCH_ASSOC)) {
            $customers[] = $row;
        }
        return $this->jsonResponse(200, "Thành công", $customers);
    }

    /**
     * Lấy khách hàng phân tích
    */
    public function getCustomerAnalysis() {
        $result = $this->customer->getCustomerAnalysis();
        return $this->jsonResponse(200, "Thành công", $result);
    }

    /**
     * Tạo khách hàng mới
    */
    public function createCustomer($data) {
        // Validate dữ liệu
        if (empty($data['name']) || empty($data['phone']) || empty($data['password'])) {
            return $this->jsonResponse(400, "Thiếu thông tin bắt buộc", null);
        }

        $this->customer->setName($data['name']);
        $this->customer->setPhone($data['phone']);
        $this->customer->setPassword($data['password']);
        $this->customer->setAddress($data['address']);
        $this->customer->setStatus(1);

        $id = $this->customer->createCustomer();
        if($id) {
            return $this->jsonResponse(201, "Thêm khách hàng thành công", $id);
        } else {
            return $this->jsonResponse(500, "Lỗi khi thêm khách hàng", null);
        }
    }

    /**
     * Cập nhật thông tin khách hàng
    */
    public function updateCustomer($id, $data) {
        // Kiểm tra khách hàng tồn tại
        if (!$this->customer->getCustomerById($id)) {
            return $this->jsonResponse(404, "Không tìm thấy khách hàng", null);
        }
        // Validate dữ liệu
        if (empty($data['name']) || empty($data['phone']) || empty($data['password'])) {
            return $this->jsonResponse(400, "Thiếu thông tin bắt buộc", null);
        }
        $this->customer->setName($data['name']);
        $this->customer->setPhone($data['phone']);
        $this->customer->setPassword($data['password']);
        $this->customer->setStatus(1);

        if ($this->customer->updateCustomer()) {
            return $this->jsonResponse(200, "Cập nhật khách hàng thành công", null);
        } else {
            return $this->jsonResponse(500, "Lỗi khi cập nhật khách hàng", null);
        }
    }

    /**
     * Xóa khách hàng
    */
    public function deleteCustomer($id) {
        if (!$this->customer->getCustomerById($id)) {
            return $this->jsonResponse(404, "Không tìm thấy khách hàng", null);
        }
        
        $this->customer->setId($id);
        
        if ($this->customer->deleteCustomer()) {
            return $this->jsonResponse(200, "Xóa khách hàng thành công", null);
        } else {
            return $this->jsonResponse(500, "Lỗi khi xóa khách hàng", null);
        }
    }

    /**
     * Lấy khách hàng có giá trị cao nhất
    */
    public function getTopCustomers($limit = 5, $month = null, $year = null) {
        $result = $this->customer->getCustomerValue($limit, $month, $year);
        return $this->jsonResponse(200, "Thành công", $result);
    }

    /**
     * Trả về JSON response
    */
    private function jsonResponse($status, $message, $data) {
        $response = [
            'status' => $status,
            "message" => $message,
            "data" => $data
        ];
        return $response;
    }
}
