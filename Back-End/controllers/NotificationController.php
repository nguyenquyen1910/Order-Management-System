<?php

require_once __DIR__ . '/../models/Notification.php';

class NotificationController {
    private $db;
    private $notification;

    public function __construct($db) {
        $this->db = $db;
        $this->notification = new Notification($db);
    }

    // Lay thong bao gan day nhat
    public function getRecentNotifications($limit=5) {
        $data = $this->notification->getRecentNotifications($limit);
        if($data) {
            return $this->jsonResponse(200, "Thành công", $data);
        } else {
            return $this->jsonResponse(500, "Lỗi khi lấy thông báo", []);
        }
    }

    // Danh dau da doc
    public function markAsRead($id) {
        $success = $this->notification->markAsRead($id);
        if($success) {
            return $this->jsonResponse(200, "Thành công", ["message" => "Đã đánh dấu đã đọc thông báo"]);
        } else {
            return $this->jsonResponse(500, "Lỗi khi đánh dấu đã đọc thông báo", []);
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
