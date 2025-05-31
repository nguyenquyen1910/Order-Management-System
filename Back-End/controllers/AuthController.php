<?php

class AuthController {
    private $db;
    private $employee;

    public function __construct($db) {
        $this->db = $db;
        $this->employee = new Employee($db);
    }

    public function login($usernameOrEmail, $password) {
        $user = $this->employee->login($usernameOrEmail, $password);
        if ($user) {
            unset($user['password']);
            return $this->jsonResponse(200, true, "Đăng nhập thành công", $user);
        } else {
            return $this->jsonResponse(401, false, "Tên tài khoản hoặc mật khẩu không chính xác", null);
        }
    }
    
    public function register($data) {
        $user = $this->employee->register($data);
        if ($user) {
            unset($user['password']);
            return $this->jsonResponse(200, true, "Đăng ký thành công", $user);
        } else {
            return $this->jsonResponse(400, false, "Đăng ký thất bại", null);
        }
    }

    private function jsonResponse($status, $success, $message, $data) {
        $response = [
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];
        return $response;
    }
}

