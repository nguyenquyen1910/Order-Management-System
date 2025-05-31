<?php

class Category {
    private $conn;
    private $tableName = "categories";

    private $id;
    private $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters và Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; return $this; }
    /**
     * Lấy thông tin danh mục theo ID
     * @param int $id ID của danh mục
     * @return array|false
     */
    public function getCategoryById($id) {
        try {
            $query = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getCategoryById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách tất cả danh mục
     * @return array|false
     */
    public function getAllCategories() {
        try {
            $query = "SELECT * FROM " . $this->tableName;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in getAllCategories: " . $e->getMessage());
            return false;
        }
    }
} 