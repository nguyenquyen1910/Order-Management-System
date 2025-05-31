<?php

require_once __DIR__ . '/../models/Employee.php';

class EmployeeController {
    private $db;
    private $employee;

    public function __construct($db) {
        $this->db = $db;
        $this->employee = new Employee($db);
    }

    public function getAllEmployees() {
        $employees = $this->employee->getAllEmployee();
        return $this->jsonResponse(200, "Thành công", $employees);
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