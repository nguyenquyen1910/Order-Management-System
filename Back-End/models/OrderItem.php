<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Product.php';

class OrderItem {
    private $conn;
    private $tableName = "order_items";

    private $id;
    private $orderId;
    private $productId;
    private $quantity;
    private $price;
    private $subtotal;
    private $note;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters và Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }

    public function getOrderId() { return $this->orderId; }
    public function setOrderId($orderId) { $this->orderId = $orderId; return $this; }

    public function getProductId() { return $this->productId; }
    public function setProductId($productId) { $this->productId = $productId; return $this; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; return $this; }

    public function getPrice() { return $this->price; }
    public function setPrice($price) { $this->price = $price; return $this; }
    public function getSubtotal() { return $this->subtotal; }
    public function setSubtotal($subtotal) { $this->subtotal = $subtotal; return $this; }
    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; return $this; }

    /**
     * Tạo chi tiết đơn hàng mới
     * @return boolean
     */
    public function create() {
        try {
            $query = "INSERT INTO " . $this->tableName . "
                    (order_id, product_id, quantity, price, subtotal, note)
                    VALUES (:orderId, :productId, :quantity, :price, :subtotal, :note)";

            $stmt = $this->conn->prepare($query);

            $product = new Product($this->conn);
            $productData = $product->getProductById($this->productId);
            $this->price = $productData['price'];
            $this->subtotal = $this->price * $this->quantity;
            // Bind parameters
            $stmt->bindParam(':orderId', $this->orderId);
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':quantity', $this->quantity);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':note', $this->note);
            $stmt->bindParam(':subtotal', $this->subtotal);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in OrderItem create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả chi tiết của một đơn hàng
     * @param int $orderId ID của đơn hàng
     * @return array|false
     */
    public function getByOrderId($orderId) {
        try {
            $query = "SELECT oi.id, oi.order_id, oi.product_id, oi.quantity, oi.price, oi.note,
                        p.name as product_name, p.image_url as product_image, p.price as product_price
                        FROM " . $this->tableName . " oi
                        LEFT JOIN products p ON oi.product_id = p.id
                        WHERE oi.order_id = :orderId";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getByOrderId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $quantity Số lượng mới
     * @return boolean
     */
    public function updateQuantity($quantity) {
        try {
            $query = "UPDATE " . $this->tableName . "
                    SET quantity = :quantity
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':id', $this->id);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in updateQuantity: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa một item khỏi đơn hàng
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

    /**
     * Xóa tất cả các sản phẩm của một đơn hàng
     * @param int $orderId ID của đơn hàng
     * @return boolean
     */
    public function deleteByOrderId($orderId) {
        try {
            $query = "DELETE FROM " . $this->tableName . " WHERE order_id = :orderId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':orderId', $orderId);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error in deleteByOrderId: " . $e->getMessage());
            return false;
        }
    }

} 