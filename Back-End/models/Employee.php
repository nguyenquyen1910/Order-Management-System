<?php

class Employee {
    private $conn;
    private $tableName = 'employees';
    private $id;
    private $name;
    private $username;
    private $password;
    private $phone;
    private $email;
    private $role;
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

    public function getUsername() { return $this->username; }
    public function setUsername($username) { $this->username = $username; return $this; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; return $this; }   

    public function getPhone() { return $this->phone; }
    public function setPhone($phone) { $this->phone = $phone; return $this; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; return $this; }   

    public function getRole() { return $this->role; }
    public function setRole($role) { $this->role = $role; return $this; }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; return $this; }   

    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }

    public function findByUsername($usernameOrEmail) {
        $query = "SELECT * FROM " . $this->tableName . " WHERE username = ? OR email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usernameOrEmail);
        $stmt->bindParam(2, $usernameOrEmail);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($usernameOrEmail, $password) {
        $user = $this->findByUsername($usernameOrEmail);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }    

    public function register($data) {
        $query = "Select id from " . $this->tableName . " where email = ? limit 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data['email']);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->tableName . " (name, username, password, phone, email, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data['name']);
        $stmt->bindParam(2, $data['username']);
        $stmt->bindParam(3, $hashedPassword);
        $stmt->bindParam(4, $data['phone']);
        $stmt->bindParam(5, $data['email']);
        $stmt->bindParam(6, $data['role']);
        $stmt->bindParam(7, $data['status']);
        return $stmt->execute();
    }
    
    public function getAllEmployee() {
        $query = "
            Select * from employees
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}