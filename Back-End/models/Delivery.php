<?php


class Delivery {
    private $db;
    private $tableName = "deliveries";

    public function __construct($db) {
        $this->db = $db;
    }

    // Lay danh sach don hang dang giao
    public function getDeliveries($status = null) {
        $query = "SELECT d.*, c.name, o.receiver_address, o.receiver_phone, o.created_at as order_created_at
                  FROM {$this->tableName} as d
                  JOIN orders as o ON d.order_id = o.id
                  JOIN customers as c ON o.customer_id = c.id ";
        $params = [];
        if($status) {
            $statusArr = explode(',', $status);
            $in = str_repeat('?,', count($statusArr) - 1) . '?';
            $query .= " WHERE d.status IN ($in)";
            $params = $statusArr;
        }
        $stmt = $this->db->prepare($query);     
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lay danh sach nhan vien giao hang
    public function getAllDeliveryStaffName() {
        $query = "SELECT DISTINCT d.delivery_staff_name as name FROM deliveries as d";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lay danh sach nhan vien dang giao hang
    public function getStaffNameProcessing() {
        $query = "SELECT COUNT(d.order_id) as total_order, d.delivery_staff_name as name 
        FROM deliveries as d WHERE d.status = 'in_progress' 
        GROUP BY d.delivery_staff_name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Phan cong don hang cho nhan vien giao hang
    public function insertDelivery($data) {
        $query = "INSERT INTO deliveries (order_id, delivery_staff_name, status, current_latitude, current_longitude, destination_latitude, destination_longitude, estimated_delivery_time)
        VALUES (:order_id, :delivery_staff_name, :status, :current_latitude, :current_longitude, :destination_latitude, :destination_longitude, :estimated_delivery_time)";
        $stmt = $this->db->prepare($query);
        $orderId = $data['order_id'];
        $deliveryStaffName = $data['delivery_staff_name'];
        $status = "in_progress";
        $currentLatitude = "20.9808774";
        $currentLongitude = "105.7874327";
        $destinationLatitude = $data['destination_latitude'];
        $destinationLongitude = $data['destination_longitude'];
        $estimatedDeliveryTime = $data['estimated_delivery_time'];

        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':delivery_staff_name', $deliveryStaffName);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':current_latitude', $currentLatitude);
        $stmt->bindParam(':current_longitude', $currentLongitude);
        $stmt->bindParam(':destination_latitude', $destinationLatitude);
        $stmt->bindParam(':destination_longitude', $destinationLongitude);
        $stmt->bindParam(':estimated_delivery_time', $estimatedDeliveryTime);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
}
