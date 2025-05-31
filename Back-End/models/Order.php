<?php

require_once __DIR__ .'/Product.php';
require_once __DIR__ .'/Customer.php';
require_once __DIR__ .'/OrderItem.php';
require_once __DIR__ . '/../utils/DateChangeUtils.php';

class Order {
    private $db;
    private $tableName = "orders";

    private $id;
    private $customerId;
    private $employeeId;
    private $totalAmount;
    private $deliveryMethod;
    private $deliveryDate;
    private $receiverName;
    private $receiverPhone;
    private $receiverAddress;
    private $note;
    private $status;
    private $createdAt;
    private $updatedAt;

    private $items = [];

    public function __construct($db) {
        $this->db = $db;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getCustomerId() { return $this->customerId; }
    public function setCustomerId($customerId) { $this->customerId = $customerId; }

    public function getEmployeeId() { return $this->employeeId; }
    public function setEmployeeId($employeeId) { $this->employeeId = $employeeId; }

    public function getTotalAmount() { return $this->totalAmount; }
    public function setTotalAmount($totalAmount) { $this->totalAmount = $totalAmount; }

    public function getDeliveryMethod() { return $this->deliveryMethod; }
    public function setDeliveryMethod($deliveryMethod) { $this->deliveryMethod = $deliveryMethod; }

    public function getDeliveryDate() { return $this->deliveryDate; }
    public function setDeliveryDate($deliveryDate) { $this->deliveryDate = $deliveryDate; }

    public function getReceiverName() { return $this->receiverName; }
    public function setReceiverName($receiverName) { $this->receiverName = $receiverName; }

    public function getReceiverPhone() { return $this->receiverPhone; }
    public function setReceiverPhone($receiverPhone) { $this->receiverPhone = $receiverPhone; }

    public function getReceiverAddress() { return $this->receiverAddress; }
    public function setReceiverAddress($receiverAddress) { $this->receiverAddress = $receiverAddress; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; }

    public function getItems() { return $this->items; }
    public function setItems($items) { $this->items = $items; }

    public function addItem($item) {
        $this->items[] = $item;
        return $this;
    }

    public function createOrder() {
        try {
            $query = "INSERT INTO " . $this->tableName . "
                (customer_id, employee_id, total_amount, delivery_method, delivery_date, 
                 receiver_name, receiver_phone, receiver_address, note, status)
                VALUES
                (:customerId, :employeeId, :totalAmount, :deliveryMethod, :deliveryDate,
                 :receiverName, :receiverPhone, :receiverAddress, :note, :status)";

            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                $error = $this->db->errorInfo();
                error_log("Order.php: Lỗi prepare statement: " . json_encode($error));
                return false;
            }

            $employeeId = $this->employeeId === null ? null : $this->employeeId;
            $status = 0;
            $stmt->bindParam(':customerId', $this->customerId);
            $stmt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
            $stmt->bindParam(':totalAmount', $this->totalAmount);
            $stmt->bindParam(':deliveryMethod', $this->deliveryMethod);
            $stmt->bindParam(':deliveryDate', $this->deliveryDate);
            $stmt->bindParam(':receiverName', $this->receiverName);
            $stmt->bindParam(':receiverPhone', $this->receiverPhone);
            $stmt->bindParam(':receiverAddress', $this->receiverAddress);
            $stmt->bindParam(':note', $this->note);
            $stmt->bindParam(':status', $status);

            if (!$stmt->execute()) {
                return false;
            }

            $orderId = $this->db->lastInsertId();
            $this->id = $orderId;

            return $orderId;

        } catch(PDOException $e) {
            error_log("Error in createOrder: " . $e->getMessage());
            return false;
        }
    }

    public function getAllOrders() {
        try {
            $query = "SELECT * FROM " . $this->tableName;
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getOrders: " . $e->getMessage());
            return false;
        }
    }

    public function getOrder($id) {
        try {
            $query = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getOrder: " . $e->getMessage());
            return false;
        }
    }

    public function getOrdersByCustomer($customerId) {
        try {
            $query = "SELECT * FROM " . $this->tableName . " WHERE customer_id = :customerId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':customerId', $customerId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getOrdersByCustomer: " . $e->getMessage());
            return false;
        }
    }

    public function updateOrder($id) {
        try {
            $query = "UPDATE " . $this->tableName . 
            " SET total_amount = :totalAmount,
                delivery_method = :deliveryMethod,
                delivery_date = :deliveryDate,
                receiver_name = :receiverName,
                receiver_phone = :receiverPhone,
                receiver_address = :receiverAddress,
                note = :note
            WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':totalAmount', $this->totalAmount);
            $stmt->bindParam(':deliveryMethod', $this->deliveryMethod);
            $stmt->bindParam(':deliveryDate', $this->deliveryDate);
            $stmt->bindParam(':receiverName', $this->receiverName);
            $stmt->bindParam(':receiverPhone', $this->receiverPhone);
            $stmt->bindParam(':receiverAddress', $this->receiverAddress);
            $stmt->bindParam(':note', $this->note);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in updateOrder: " . $e->getMessage());
            return false;
        }
    }

    public function deleteOrder($id) {
        try {
            $query = "DELETE FROM " . $this->tableName . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in deleteOrder: " . $e->getMessage());
            return false;
        }
    }

    public function searchOrder($keyword) {
        try {
            $query = "SELECT * FROM " . $this->tableName . " 
                    WHERE customer_id LIKE :keyword 
                    OR receiver_name LIKE :keyword 
                    OR receiver_phone LIKE :keyword
                    ORDER BY created_at DESC";

            $stmt = $this->db->prepare($query);
            $keyword = "%{$keyword}%";
            $stmt->bindParam(':keyword', $keyword);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in searchOrders: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin đơn hàng kèm chi tiết
     * @param int $id ID của đơn hàng
     * @return array|false
     */
    public function getOrderWithDetails($id) {
        try {
            $orderQuery = "SELECT o.*, c.name as customer_name, c.phone as customer_phone 
                         FROM " . $this->tableName . " o
                         LEFT JOIN customers c ON o.customer_id = c.id
                         WHERE o.id = :id";
            
            $stmt = $this->db->prepare($orderQuery);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$order) {
                return false;
            }

            $orderItem = new OrderItem($this->db);
            $orderDetails = $orderItem->getByOrderId($id);

            // Kết hợp thông tin
            $order['items'] = $orderDetails ?: [];
            
            return $order;
        } catch(PDOException $e) {
            error_log("Error in getOrderWithDetails: " . $e->getMessage());
            return false;
        }
    }

    public function handleOrder($id, $status) {
        try {
            $query = "UPDATE " . $this->tableName . " SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in handleOrder: " . $e->getMessage());
            return false;
        }
    }

    public function handleDelivery($id, $data) {
        try {
            $shippingStatus = $data['shippingStatus'] ?? null;
            $delivery_expected_time = $data['delivery_expected_time'] ?? null;
            $delivery_actual_time = $data['delivery_actual_time'] ?? null;

            $query = "UPDATE " . $this->tableName . " SET 
                shipping_status = :shippingStatus,
                delivery_expected_time = :deliveryExpectedTime,
                delivery_actual_time = :deliveryActualTime
            WHERE id = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':shippingStatus', $shippingStatus);
            $stmt->bindParam(':deliveryExpectedTime', $delivery_expected_time);
            $stmt->bindParam(':deliveryActualTime', $delivery_actual_time);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in handleDelivery: " . $e->getMessage());
            return false;
        }
    }

    // Tinh tong doanh thu trong khoang thoi gian
    public function getTotalRevenueBetweenDates($startDate, $endDate) {
        $query = "SELECT SUM(total_amount) as total_revenue FROM {$this->tableName} 
                  WHERE created_at BETWEEN :start_date AND :end_date
                  AND status = 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_revenue'] ?? 0;
    }

    // Tinh tong so luong don hang trong khoang thoi gian
    public function getTotalOrdersBetweenDates($startDate, $endDate) {
        $query = "SELECT COUNT(*) as total_orders FROM {$this->tableName} 
                  WHERE created_at BETWEEN :start_date AND :end_date";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_orders'] ?? 0;
    }

    

    // Tinh gia tri trung binh don hang trong khoang thoi gian
    public function getAverageOrderValueBetweenDates($startDate, $endDate) {
        $query = "SELECT AVG(total_amount) as avg_value FROM {$this->tableName} 
                  WHERE created_at BETWEEN :start_date AND :end_date
                  AND status = 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['avg_value']/1e3 ?? 0;
    }

    // Tinh tong so luong don hang theo trang thai
    public function getOrderCountByStatus($startDate, $endDate) {
        $query = "SELECT 
                    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as processed,
                    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = -1 THEN 1 ELSE 0 END) as cancelled
                  FROM orders
                  WHERE created_at BETWEEN :start_date AND :end_date";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tinh tong so luong don hang theo gio
    public function getOrderCountByHour($startDate, $endDate) {
        $query = "SELECT 
                    FLOOR(HOUR(created_at)/2) as hour_group,
                    COUNT(*) as order_count
                  FROM {$this->tableName} 
                  WHERE created_at BETWEEN :start_date AND :end_date
                  GROUP BY hour_group
                  ORDER BY hour_group";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        $result = array_fill(0, 7, 0); 
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['hour_group'] < 7) {
                $result[$row['hour_group']] = (int)$row['order_count'];
            }
        }
        
        return $result;
    }

    // Tinh tong doanh thu theo tung thang thang
    public function getRevenueByMonths($month = 12) {
        $query = "SELECT 
                    YEAR(created_at) as year,
                    MONTH(created_at) as month,
                    SUM(total_amount) as revenue
                FROM orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH) AND status = 1
                GROUP BY YEAR(created_at), MONTH(created_at)
                ORDER BY year, month";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':months', $month, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = [];
        $data = array_fill(0, $month, 0);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $monthIndex = ($row['month'] - 1); 
            $data[$monthIndex] = (float)$row['revenue']/1e6;
        }

        return array_slice($data, 0, 5);
    }

    // Tinh tong so don hang thanh cong
    public function getTotalSuccessOrder() {
        $query = "SELECT COUNT(*) as total_success_orders FROM {$this->tableName} WHERE status = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_success_orders'] ?? 0;
    }

    // Tinh tong so don hang chua xu ly
    public function getTotalPendingOrder() {
        $query = "SELECT COUNT(*) as total_pending_orders FROM {$this->tableName} WHERE status = 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_pending_orders'] ?? 0;
    }

    // Tinh tong so don hang bi huy
    public function getTotalCanceledOrder() {
        $query = "SELECT COUNT(*) as total_canceled_orders FROM {$this->tableName} WHERE status = -1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_canceled_orders'] ?? 0;
    }
    

    // Tinh trend so don hang theo thang
    public function getOrdersTrendLastYear($month = 12) {
        $query = "SELECT 
                    YEAR(created_at) as year,
                    MONTH(created_at) as month,
                    COUNT(*) as order_count
                FROM orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                GROUP BY YEAR(created_at), MONTH(created_at)
                ORDER BY year, month";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':months', $month, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
    
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = (int)$row['order_count'];
        }

        $count = count($result);
        if ($count < $month) {
            $zeros = array_fill(0, $month - $count, 0);
            $result = array_merge($zeros, $result);
        }
        return $result;
    }

    // Lấy 5 đơn hàng gần nhất
    public function getRecentOrders($limit = 5) {
        $query = "
            SELECT o.id, c.name as customer_name, o.created_at, o.total_amount, o.status
              FROM orders o
              LEFT JOIN customers c ON o.customer_id = c.id
              ORDER BY o.created_at DESC
              LIMIT :limit
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy đơn hàng sau khi lọc
    public function getFilteredOrders($fromDate, $toDate, $status, $employee) {
        $sql = "SELECT o.*, c.name AS customer_name, e.name AS employee_name
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            LEFT JOIN employees e ON o.employee_id = e.id
            WHERE 1=1";
        $params = array();

        if ($fromDate !== null) {
            $sql .= " AND o.created_at >= ?";
            $params[] = $fromDate;
        }
        if ($toDate !== null) {
            $sql .= " AND o.created_at <= ?";
            $params[] = $toDate;
        }
        if ($status !== null) {
            $sql .= " AND o.status = ?";
            $params[] = $status;
        }
        if ($employee !== null) {
            $sql .= " AND o.employee_id = ?";
            $params[] = $employee;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lay danh sach don hang chi tiet
    public function getOrderDetails(){
        $query = "Select o.*, c.name as customer_name, o.created_at, o.total_amount, o.status, o.updated_at, o.delivery_method, e.name as employee_name
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            LEFT JOIN employees e ON o.employee_id = e.id
            ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lay cac quan cua dia chi don hang
    public function getDistricts() {
        $query = "SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(receiver_address, ',', -1), ',', 1) as district
                  FROM orders
                  WHERE receiver_address IS NOT NULL
                  AND receiver_address != ''";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Loc don hang co lien quan den van chuyen
    public function filterOrdersByDelivery($timeRange='today', $district=null, $staffName=null, $keyword=null) {
        list($fromDate, $toDate) = DateChangeUtils::getDateRangeOption($timeRange);
        $query = "SELECT o.id as order_id, o.created_at, c.name as customer_name, 
                    d.estimated_delivery_time as estimated_time, 
                    d.delivery_staff_name as staff_name, 
                    d.status,
                    o.receiver_address as address
                    FROM orders as o
                    LEFT JOIN customers as c ON o.customer_id = c.id
                    LEFT JOIN deliveries as d on d.order_id = o.id
                    WHERE o.status != -1 ";
        $params = [];

        // Loc theo khoang thoi gian
        if ($fromDate && $toDate) {
            $query .= " AND o.created_at BETWEEN ? AND ? ";
            $params[] = $fromDate;
            $params[] = $toDate;
        }

        // Loc theo quan
        if ($district && $district != "all") {
            $query .= " AND (o.receiver_address LIKE ? OR o.receiver_address LIKE ?)";
            $params[] = "%, $district";
            $params[] = "%, $district%";
        }

        // Loc theo nhan vien giao hang
        if ($staffName && $staffName !== "all") {
            $query .= " AND d.delivery_staff_name COLLATE utf8mb4_unicode_ci = ? ";
            $params[] = $staffName;
        }

        // Loc theo tu khoa
        if($keyword) {
            $query .= " AND (o.id LIKE ? OR c.name LIKE ?) ";
            $kw = "%{$keyword}%";
            $params[] = $kw;
            $params[] = $kw;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lay danh sach don hang dang giao
    public function getOrdersDelivering() {
        $query = "Select o.id, o.receiver_name, d.delivery_staff_name, o.receiver_address, o.created_at as order_created_at, d.estimated_delivery_time
            FROM orders as o
            LEFT JOIN deliveries as d on d.order_id = o.id
            WHERE d.status = 'in_progress'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}