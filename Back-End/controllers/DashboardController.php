<?php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Product.php';

class DashboardController {
    private $db;
    private $customerModel;
    private $productModel;
    private $orderModel;

    public function __construct($db) {
        $this->db = $db;

        $this->orderModel = new Order($db);
        $this->customerModel = new Customer($db);
        $this->productModel = new Product($db);
    } 

    public function getDashboardData($period = 'month') {
        try {
            $revenueData = $this->getRevenueData($period);
            $orderData = $this->getOrderData($period);
            $customerData = $this->getCustomerData($period);
            $avgOrderData = $this->getAvgOrderData($period);
            $orderStatusData = $this->getStatusData($period);
            $orderHourData = $this->getOrderHourData($period);
            $revenueByMonthData = $this->getRevenueByMonthData($period);
            return [
                'revenue' => $revenueData,
                'orders' => $orderData,
                'customers' => $customerData,
                'avgOrder' => $avgOrderData,
                'revenueByMonth' => $revenueByMonthData,
                'orderStatus' => $orderStatusData,
                'ordersByHour' => $orderHourData
            ];
        } catch(Exception $e) {
            error_log("Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    private function getRevenueData($period = 'month') {
        $startDate = '2025-05-10';
        // Doanh thu hien tai
        $currentRevenue = $this->orderModel->getTotalRevenueBetweenDates($startDate, date('Y-m-d'));
        $preStartDate = '2025-01-01';
        $preEndDate = '2025-05-10';

        // Doanh thu ki truoc
        $preRevenue = $this->orderModel->getTotalRevenueBetweenDates($preStartDate, $preEndDate);

        // Tang truong
        $growth = 0;
        if ($preRevenue > 0) {
            $growth = round((($currentRevenue - $preRevenue) / $preRevenue) * 100, 1);
        }

        // Xu huong doanh thu trong 12 thang
        $revenueTrend = $this->orderModel->getRevenueByMonths();

        return [
            'current' => round($currentRevenue/1e6, 1),
            'pre' => round($preRevenue/1e6, 1),
            'trend' => $revenueTrend,
            'growth' => $growth
        ];
    }

    private function getRevenueByMonthData($period = 'month') {
        $revenueTrend = $this->orderModel->getRevenueByMonths();
        return $revenueTrend;
    }

    private function getOrderData($period = 'month') {
        $startDate = '2025-03-15';
        $currentOrder = $this->orderModel->getOrderCountByStatus($startDate, date('Y-m-d'));
        $preStartDate = '2025-01-01';
        $preEndDate = '2025-03-15';

        // Lay so don hang ki truoc
        $preOrder = $this->orderModel->getOrderCountByStatus($preStartDate, $preEndDate);

        // Tinh tong so don hang (processed + pending + cancelled)
        $currentTotal = $currentOrder['processed'] + $currentOrder['pending'] + $currentOrder['cancelled'];
        $preTotal = $preOrder['processed'] + $preOrder['pending'] + $preOrder['cancelled'];

        // Tang truong
        $growth = 0;
        if ($preTotal > 0) {
            $growth = round((($currentTotal - $preTotal) / $preTotal) * 100, 1);
        }

        // Xu huong so don hang trong 12 thang
        $orderTrend = $this->orderModel->getOrdersTrendLastYear();

        return [
            'current' => $currentTotal,
            'preTotal' => $preTotal,
            'trend' => $orderTrend,
            'growth' => $growth
        ];
    }

    public function getCustomerData($period = 'month') {
        $currentTotal = $this->customerModel->getTotalCustomers();

        // Lay tong so khach hang thang truoc
        $startDate = '2025-01-01';
        $endDate = '2025-05-02';
        $preTotal = $this->customerModel->getTotalCustomersInChainDay($startDate, $endDate);

        // Tang truong
        $growth = 0;
        if ($preTotal > 0) {
            $growth = round((($currentTotal - $preTotal) / $preTotal) * 100, 1);
        }

        // Lay tang truong khach hang hang ngay trong thang hien tai
        $firstDayOfMonth = date('Y-m-01');
        $dailyGrowth = $this->customerModel->getDailyCustomerGrowth($firstDayOfMonth, date('Y-m-d'));

        $customerTrend = $this->customerModel->getCustomerTrendLastYear();

        return [
            'current' => $currentTotal,
            'trend' => $customerTrend,
            'growth' => $growth,
            'dailyGrowth' => $dailyGrowth
        ];
    }

    private function getAvgOrderData($period = 'month') {
        $startDate = '2025-05-08';
        $currentAvg = round($this->orderModel->getAverageOrderValueBetweenDates($startDate, date('Y-m-d')), 2);

        // Lay khoang thoi gian truoc
        $preStartDate = '2025-04-01';
        $preEndDate = '2025-05-08';

        // Lay gia tri trung binh don hang ki truoc
        $preAvg = round($this->orderModel->getAverageOrderValueBetweenDates($preStartDate, $preEndDate), 2);

        // Tang truong
        $growth = 0;
        if ($preAvg > 0) {
            $growth = round((($currentAvg - $preAvg) / $preAvg) * 100, 1);
        }

        // Xu huong gia tri trung binh don hang trong 12 thang
        $avgTrend = $this->getAvgOrderTrendLastMonths(7);

        return [
            'current' => (int)$currentAvg,
            'pre' => (int)$preAvg,
            'trend' => $avgTrend,
            'growth' => $growth
        ];
    }

    private function getAvgOrderTrendLastMonths($months) {
        $query = "SELECT 
                    YEAR(created_at) as year,
                    MONTH(created_at) as month,
                    AVG(total_amount) as avg_amount
                FROM orders 
                WHERE created_at >= DATE_SUB(LAST_DAY(CURRENT_DATE), INTERVAL 7 MONTH)
                GROUP BY year, month
                ORDER BY year, month";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = array();
        $data = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $monthIndex = ($row['month'] -1);
            $data[$monthIndex] = (float)$row['avg_amount'];
        }

        // Fill first 2 elements with 0
        for($i = 0; $i < 2; $i++) {
            $result[] = 0;
        }

        // Add the rest of the data
        for($i = 2; $i < 7; $i++) {
            $result[] = isset($data[$i-2]) ? $data[$i-2] : 0;
        }

        return $result;
    }

    private function getStatusData($period = 'month') {
        $startDate = '2025-01-01';
        $currentStatus = $this->orderModel->getOrderCountByStatus($startDate, date('Y-m-d 23:59:59'));
        
        $total = (int)$currentStatus['processed'] + (int)$currentStatus['pending'] + (int)$currentStatus['cancelled'];
        $processed = $total > 0 ? round(($currentStatus['processed'] / $total) * 100) : 0;
        $pending = $total > 0 ? round(($currentStatus['pending'] / $total) * 100) : 0;
        $cancelled = $total > 0 ? round(($currentStatus['cancelled'] / $total) * 100) : 0;

        return [
            'processed' => $processed,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'total' => $total
        ];
        
    }

    private function getOrderHourData($period = 'month') {
        $startDate = $this->getStartDateByPeriod($period);
        $ordersByHour = $this->orderModel->getOrderCountByHour($startDate, date('Y-m-d'));

        return $ordersByHour;
    }

    private function getStartDateByPeriod($period) {
        switch($period) {
            case 'today':
                return date('Y-m-d');
            case 'week':
                return date('Y-m-d', strtotime('-1 week'));
            case 'month':
                return date('Y-m-01');
            case 'year':
                return date('Y-01-01');
            default:
                return date('Y-m-01');
        }
    }

    private function getPreStartDate($period, $startDate) {
        switch($period) {
            case 'today':
                return date('Y-m-d', strtotime('-1 day',));
            case 'week':
                return date('Y-m-d', strtotime('-1 week', strtotime($startDate)));
            case 'month':
                return date('Y-m-01', strtotime('-1 month', strtotime($startDate)));
            case 'year':
                return date('Y-01-01', strtotime('-1 year', strtotime($startDate)));
            default:
                return date('Y-m-01', strtotime('-1 month', strtotime($startDate)));
        }
    }
}