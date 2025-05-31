<?php

class Customer {
    private $conn;
    private $tableName = "customers";

    private $id;
    private $name;
    private $phone;
    private $password;
    private $address;
    private $status;
    private $createdAt;
    private $updatedAt;

    public function __construct($db) {
        $this->conn = $db;
    }

    //Getter and Setter
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }
    
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; return $this; }
    
    public function getPhone() { return $this->phone; }
    public function setPhone($phone) { $this->phone = $phone; return $this; }
    
    public function getAddress() { return $this->address; }
    public function setAddress($address) { $this->address = $address; return $this; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; return $this; }
    
    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; return $this; }
    
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }

    /**
     * Lấy tất cả khách hàng
     * @return PDOStatement
     */
    public function getAllCustomers() {
        $query = "SELECT * FROM " . $this->tableName;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Lấy thông tin một khách hàng theo ID
     * @param int $id ID của khách hàng
     * @return array Thông tin khách hàng
     */
    public function getCustomerById($id) {
        $query = "SELECT * FROM " . $this->tableName . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm kiếm khách hàng theo tên hoặc số điện thoại
     * @param string $keyword Từ khóa tìm kiếm
     * @return PDOStatement
     */
    public function getCustomerByNameOrPhoneNumber($keyword) {
        $query = "SELECT * FROM " . $this->tableName . " WHERE name LIKE ? OR phone LIKE ?";
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Tìm kiếm khách hàng theo số điện thoại
     * @param string $phone Số điện thoại cần tìm
     * @return array|false Thông tin khách hàng hoặc false nếu không tìm thấy
     */
    public function getCustomerByPhone($phone) {
        $query = "SELECT * FROM " . $this->tableName . " WHERE phone = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $phone);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo khách hàng mới
     * @return int|false ID của khách hàng mới hoặc false nếu thất bại
     */
    public function createCustomer() {
        $query = "INSERT INTO " . $this->tableName . 
                " (name, phone, password, address, status) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Data cleaning
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->address = htmlspecialchars(strip_tags($this->address));

        // Encode password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->phone);
        $stmt->bindParam(3, $this->password);
        $stmt->bindParam(4, $this->address);
        $stmt->bindParam(5, $this->status);

        // Execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Cập nhật thông tin khách hàng
     * @return boolean
     */
    public function updateCustomer() {
        $query = "UPDATE " . $this->tableName . " 
                SET name = ?, phone = ?";
        
        $params = [
            $this->name,
            $this->phone
        ];

        if(!empty($this->password)) {
            $query .= ", password = ?";
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $params[] = $this->password;
        }
        $query .= " WHERE id = ?";
        $params[] = $this->id;

        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->id = htmlspecialchars(strip_tags($this->id));

        for($i = 0; $i < count($params); $i++) {
            $stmt->bindParam($i + 1, $params[$i]);
        }
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Xóa khách hàng
     * @return boolean
     */
    public function deleteCustomer() {
        $query = "DELETE FROM " . $this->tableName . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Tinh tong so khach hang
    public function getTotalCustomers() {
        $query = "SELECT COUNT(*) as total_customers FROM {$this->tableName}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total_customers'];
    }

    public function getTotalCustomersInChainDay($startDate, $endDate) {
        $query = "SELECT 
                    count(*) as total_customer
                    from {$this->tableName}
                    where created_at between :start_date and :end_date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total_customer'];
    }

    // Tinh tong so khach hang moi ngay
    public function getDailyCustomerGrowth($startDate, $endDate) {
        $query = "SELECT DATE(created_at) as date,
                    COUNT(*) as daily_new,
                    SUM(CASE WHEN created_at <= t.created_at AND status = 1 THEN 1 ELSE 0 END) AS total_customers
                  FROM {$this->tableName} as t
                  WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                  GROUP BY DATE(created_at)
                  ORDER BY date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute(); 
        
        $res = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = [
                'date' => $row['date'],
                'total_customers' => $row['total_customers']
            ];
        }
        return $res;
    }

    public function getCustomerTrendLastYear() {
        $query = "SELECT 
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                COUNT(*) as customer_count
            FROM customers 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 MONTH)
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY YEAR(created_at), MONTH(created_at)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = array_fill(0, 7, 0);
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');

        $months = [];
        for ($i = 6; $i >= 0; $i--) {
            $time = strtotime("-$i month");
            $year = (int)date('Y', $time);
            $month = (int)date('m', $time);
            $months[] = [
                'year' => $year,
                'month' => $month
            ];
        }
        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $key = $row['year'] . '-' . $row['month'];
            $data[$key] = (int)$row['customer_count'];
        }

        // Map dữ liệu vào mảng kết quả
        foreach ($months as $index => $month) {
            $key = $month['year'] . '-' . $month['month'];
            if (isset($data[$key])) {
                $result[$index] = $data[$key];
            }
        }

        return $result;
    }

    // Get customer analysis
    public function getCustomerAnalysis() {
        $totalCustomers = $this->getTotalCustomers();

        $activeCustomers = $this->getActiveCustomerRatio();

        $newCustomers = $this->getNewCustomers();

        $loyalCustomers = $this->getLoyalCustomers();

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'new_customers' => $newCustomers,
            'loyal_customers' => $loyalCustomers
        ];
    }

    private function getActiveCustomerRatio() {
        $query = "
            select count(c.id) as active_customers from customers c
            where c.status = 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($row['active_customers'] / $this->getTotalCustomers(), 2) * 100;
    }

    private function getNewCustomers() {
        $query = "
            select count(c.id) as new_customers from customers c
            where c.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) and c.status = 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['new_customers'];
    }

    private function getLoyalCustomers() {
        $query = "SELECT c.id, c.name, COUNT(o.id) as total_orders, SUM(o.total_amount) as total_spent,
            CASE 
                WHEN COUNT(o.id) >= 10 THEN 'VIP'
                WHEN COUNT(o.id) >= 5 THEN 'Thân thiết'
                ELSE 'Mới'
            END as customer_level
            FROM customers c
            LEFT JOIN orders o ON c.id = o.customer_id
            WHERE c.status = 1
            GROUP BY c.id, c.name
            HAVING total_orders > 0
            ORDER BY total_spent DESC
            LIMIT 3";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lay khach hang co gia tri cao nhat
    public function getCustomerValue($limit = 5, $month = null, $year = null) {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        $preMonth = $month == 1 ? 12 : $month - 1;
        $preYear = $month == 1 ? $year - 1 : $year;

        $query = "
            WITH current_month AS (
                SELECT 
                    c.id,
                    c.name as customer_name, 
                    COUNT(o.id) as total_orders, 
                    COALESCE(SUM(o.total_amount), 0) as total_spent
                FROM customers c 
                LEFT JOIN orders o ON c.id = o.customer_id 
                    AND o.status = 1
                    AND MONTH(o.created_at) = :month
                    AND YEAR(o.created_at) = :year
                WHERE c.status = 1
                GROUP BY c.id, c.name
            ),
            previous_month AS (
                SELECT 
                    c.id,
                    c.name as customer_name, 
                    COUNT(o.id) as prev_orders, 
                    COALESCE(SUM(o.total_amount), 0) as prev_spent
                FROM customers c 
                LEFT JOIN orders o ON c.id = o.customer_id 
                    AND o.status = 1
                    AND MONTH(o.created_at) = :prev_month
                    AND YEAR(o.created_at) = :prev_year
                WHERE c.status = 1
                GROUP BY c.id, c.name
            )
            SELECT 
                cm.*,
                COALESCE(pm.prev_spent, 0) as prev_spent,
                CASE 
                    WHEN COALESCE(pm.prev_spent, 0) = 0 AND cm.total_spent > 0 THEN 100
                    WHEN COALESCE(pm.prev_spent, 0) = 0 AND cm.total_spent = 0 THEN 0
                    ELSE ((cm.total_spent - COALESCE(pm.prev_spent, 0)) / COALESCE(pm.prev_spent, 0)) * 100
                END as trend_percentage
            FROM current_month cm
            LEFT JOIN previous_month pm ON cm.id = pm.id
            ORDER BY cm.total_spent DESC
            LIMIT :limit
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':month', $month, PDO::PARAM_INT);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->bindValue(':prev_month', $preMonth, PDO::PARAM_INT);
        $stmt->bindValue(':prev_year', $preYear, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as &$item) {
            $item['trend'] = $this->getTrendIcon($item['trend_percentage']);
        }
        return $result;
    }

    private function getTrendIcon($percentage) {
        if ($percentage > 0) {
            return 'up'; 
        } elseif ($percentage < 0) {
            return 'down'; 
        } else {
            return 'stable'; 
        }
    }   
}