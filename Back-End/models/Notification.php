<?php


class Notification {
    private $db;
    private $tableName = "notifications";

    public function __construct($db) {
        $this->db = $db;
    }

    // Ham lay thong bao gan day nhat
    public function getRecentNotifications($limit=5) {
        $query = "
            SELECT * FROM $this->tableName
            ORDER BY created_at DESC
            LIMIT $limit
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ham danh dau da doc
    public function markAsRead($id) {
        $query = "UPDATE $this->tableName SET status = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Ham them thong bao
    public function createNotification($id, $type, $title, $description=null) {
        $stmt = $this->db->query("SELECT MAX(id) as max_id FROM notifications");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $newId = $row['max_id'] + 1;
    
        $query = "INSERT INTO notifications (id, type, status, title, description, created_at) VALUES (:id, :type, 0, :title, :description, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $newId);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}