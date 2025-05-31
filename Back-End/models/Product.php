<?php

require_once __DIR__ . '/Category.php';

class Product {
    private $conn;
    private $tableName = "products";

    private $id;
    private $name;
    private $description;
    private $price;
    private $image;
    private $category;
    private $categoryId;
    private $status;
    private $createdAt;
    private $updatedAt;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters và Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; return $this; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; return $this; }

    public function getPrice() { return $this->price; }
    public function setPrice($price) { $this->price = $price; return $this; }

    public function getImage() { return $this->image; }
    public function setImage($image) { $this->image = $image; return $this; }

    public function getCategory() { return $this->category; }
    public function setCategory($category) { 
        if ($category instanceof Category) {
            $this->categoryId = $category->getId();
            $this->category = $category->getName();
        }
        return $this;
    }

    public function getCategoryId() { return $this->categoryId; }
    public function setCategoryId($categoryId) { 
        $this->categoryId = $categoryId; 
        return $this; 
    }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; return $this; }

    /**
     * @param int ID của sản phẩm
     * @return array|false
     */
    public function getProductById($id) {
        try {
            $query = "SELECT p.*, c.name as category_name, c.id as category_id 
                     FROM " . $this->tableName . " p
                     LEFT JOIN categories c ON p.category_id = c.id
                     WHERE p.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getProductById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách tất cả sản phẩm
     * @return array|false
     */
    public function getAllProducts() {
        try {
            $query = "SELECT p.*, c.name as category_name, c.id as category_id 
                     FROM " . $this->tableName . " p
                     LEFT JOIN categories c ON p.category_id = c.id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllProducts: " . $e->getMessage());
            return false;
        }
    }

    public function getProductByCategoryId($categoryId) {
        try {
            $query = "Select p.*, c.name as category_name from " . $this->tableName . " p join categories c on p.category_id = c.id where p.category_id = :categoryId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':categoryId', $categoryId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getProductByCategoryId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @return int|false ID của sản phẩm mới tạo
     */
    public function create() {
        try {
            $query = "INSERT INTO " . $this->tableName . "
                    (name, description, price, image, category_id, status)
                    VALUES
                    (:name, :description, :price, :image, :categoryId, :status)";

            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':image', $this->image);
            $stmt->bindParam(':categoryId', $this->categoryId);
            $stmt->bindParam(':status', $this->status);

            if($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @return boolean
     */
    public function update() {
        try {
            $query = "UPDATE " . $this->tableName . "
                    SET name = :name,
                        description = :description,
                        price = :price,
                        image = :image,
                        category_id = :categoryId,
                        status = :status,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':image', $this->image);
            $stmt->bindParam(':categoryId', $this->categoryId);
            $stmt->bindParam(':status', $this->status);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
            return false;
        }
    }

    // Search product
    public function searchProduct($keyword) {
        try {
            $searchKeyword = "%{$keyword}%";
            $query = "SELECT p.*, c.name as category_name FROM " . $this->tableName . " p JOIN categories c ON p.category_id = c.id WHERE p.name LIKE :keyword";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':keyword', $searchKeyword);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in searchProduct: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa sản phẩm
     * @return boolean
     */
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->tableName . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            return false;
        }
    }

    public function getTopSellingProducts($limit = 5, $month = null, $year = null) {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        // Lay thang truoc
        $preMonth = $month == 1 ? 12 : $month -1;
        $preYear = $month == 1 ? $year - 1 : $year;

        $query = "
            WITH current_month AS (
                SELECT 
                    p.id,
                    p.name,
                    p.price,
                    p.image_url,
                    SUM(oi.quantity) as current_sold,
                    SUM(o.total_amount) as current_revenue
                FROM products p
                JOIN order_items oi ON oi.product_id = p.id
                JOIN orders o ON o.id = oi.order_id
                WHERE MONTH(o.created_at) = :month 
                AND YEAR(o.created_at) = :year 
                AND o.status = 1
                GROUP BY p.id, p.name, p.price, p.image_url
            ),
            previous_month AS (
                SELECT 
                    p.id,
                    SUM(oi.quantity) as prev_sold
                FROM products p
                JOIN order_items oi ON oi.product_id = p.id
                JOIN orders o ON o.id = oi.order_id
                WHERE MONTH(o.created_at) = :prev_month 
                AND YEAR(o.created_at) = :prev_year 
                AND o.status = 1
                GROUP BY p.id
            )
            SELECT 
                cm.*,
                COALESCE(pm.prev_sold, 0) as prev_sold,
                CASE 
                    WHEN COALESCE(pm.prev_sold, 0) = 0 THEN 100
                    ELSE ((cm.current_sold - COALESCE(pm.prev_sold, 0)) / COALESCE(pm.prev_sold, 0)) * 100
                END as trend_percentage
            FROM current_month cm
            LEFT JOIN previous_month pm ON cm.id = pm.id
            ORDER BY cm.current_sold DESC, cm.current_revenue DESC
            LIMIT :limit
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':month', $month, PDO::PARAM_INT);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->bindValue(':prev_month', $preMonth, PDO::PARAM_INT);
        $stmt->bindValue(':prev_year', $preYear, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as &$result) {
            $result['trend'] = $this->getTrendIcon($result['trend_percentage']);
        }
        return $results;
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