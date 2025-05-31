<?php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Notification.php';

class OrderController {
    private $db;
    private $order;

    public function __construct($db) {
        $this->db = $db;
        $this->order = new Order($db);
    }

    public function getAllOrder() {
        $result = $this->order->getAllOrders();
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(200, "Không có đơn hàng nào", []);
        }
    }

    public function getOrder($id) {
        $order = $this->order->getOrderWithDetails($id);
        if($order) {
            if(empty($order['items'])) {
                $order['items'] = new OrderItem($this->db);
                $items = $order['items']->getByOrderId($id);
                $order['items'] = $items;
            }
            return $this->jsonResponse(200, "Thành công", $order);
        } else {
            return $this->jsonResponse(404, "Không tìm thấy đơn hàng", null);
        }
    }

    public function getOrdersByCustomer($customerId) {
        $orders = $this->order->getOrdersByCustomer($customerId);
        if($orders) {
            return $this->jsonResponse(200, "Thành công", $orders);
        } else {
            return $this->jsonResponse(200, "Không tìm thấy đơn hàng của khách hàng này", []);
        }
    }

    public function searchOrder($keyword) {
        $result = $this->order->searchOrder($keyword);
        if($result) {
            // Lấy thêm chi tiết cho từng đơn hàng tìm được
            foreach($result as &$order) {
                $orderDetails = $this->order->getOrderWithDetails($order['id']);
                if($orderDetails) {
                    $order = $orderDetails;
                }
            }
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(200, "Không tìm thấy đơn hàng phù hợp", []);
        }
    }

    public function createOrder($data) {
        if (empty($data['deliveryMethod']) || 
            empty($data['deliveryTime']) ||
            empty($data['orderItems']) || 
            !is_array($data['orderItems']) ||
            empty($data['orderItems'])) {
            return $this->jsonResponse(400, "Vui lòng điền đầy đủ thông tin bắt buộc", null);
        }

        try {
            $this->db->beginTransaction();

            $customer = new Customer($this->db);
            
            // Trường hợp 1: Khách hàng mới
            if (isset($data['isNewCustomer']) && $data['isNewCustomer']) {
                if (empty($data['receiverName']) || 
                    empty($data['receiverPhone']) || 
                    empty($data['receiverAddress'])) 
                {
                    return $this->jsonResponse(400, "Vui lòng điền đầy đủ thông tin khách hàng mới", null);
                }

                if (!preg_match('/^[0-9]{10}$/', $data['receiverPhone'])) {
                    return $this->jsonResponse(400, "Số điện thoại không hợp lệ. Vui lòng nhập 10 chữ số", null);
                }

                $existingCustomer = $customer->getCustomerByPhone($data['receiverPhone']);
                if ($existingCustomer) {
                    return $this->jsonResponse(400, "Số điện thoại này đã được đăng ký. Vui lòng chọn 'Khách hàng đã có'", null);
                }

                // Tạo khách hàng mới
                $customer->setName($data['receiverName']);
                $customer->setPhone($data['receiverPhone']);
                $customer->setPassword("123456");
                $customer->setAddress($data['receiverAddress']);
                $customer->setStatus(1);
                
                $customerId = $customer->createCustomer();
                if (!$customerId) {
                    throw new Exception("Lỗi khi tạo thông tin khách hàng");
                }

                $this->order->setCustomerId($customerId);
                $this->order->setReceiverName($data['receiverName']);
                $this->order->setReceiverPhone($data['receiverPhone']);
                $this->order->setReceiverAddress($data['receiverAddress']);
            }
            // Trường hợp 2: Khách hàng đã có
            else {
                if (empty($data['customerId'])) {
                    return $this->jsonResponse(400, "Vui lòng chọn khách hàng", null);
                }

                $customerData = $customer->getCustomerById($data['customerId']);
                if (!$customerData) {
                    return $this->jsonResponse(404, "Không tìm thấy thông tin khách hàng", null);
                }

                $this->order->setCustomerId($data['customerId']);
                $this->order->setReceiverName($customerData['name']);
                $this->order->setReceiverPhone($customerData['phone']);
                $this->order->setReceiverAddress($customerData['address']); 
            }

            // Tính tổng tiền
            $totalAmount = 0;
            $product = new Product($this->db);
            $orderItems = [];
            
            foreach ($data['orderItems'] as $item) {
                if (!isset($item['productId']) || !isset($item['quantity']) || intval($item['quantity']) <= 0) {
                    throw new Exception("Thông tin sản phẩm không hợp lệ hoặc số lượng phải lớn hơn 0");
                }

                $productData = $product->getProductById($item['productId']);
                if (!$productData) {
                    throw new Exception("Không tìm thấy sản phẩm với ID: " . $item['productId']);
                }

                $itemPrice = floatval($productData['price']);
                $itemQuantity = intval($item['quantity']);
                $itemTotal = $itemPrice * $itemQuantity;
                $totalAmount += $itemTotal;
                
                $orderItems[] = [
                    'productId' => $item['productId'],
                    'name' => $productData['name'],
                    'price' => $itemPrice,
                    'quantity' => $itemQuantity,
                    'note' => isset($item['note']) ? $item['note'] : ''
                ];
            }

            $this->order->setTotalAmount($totalAmount);
            $this->order->setDeliveryMethod($data['deliveryMethod']);
            $this->order->setDeliveryDate($data['deliveryTime']);
            $this->order->setNote(isset($data['note']) ? $data['note'] : '');
            $this->order->setStatus(1); 
            
            if (isset($data['employeeId'])) {
                $this->order->setEmployeeId($data['employeeId']);
            } else {
                $this->order->setEmployeeId(null);
            }

            // Tạo đơn hàng
            $orderId = $this->order->createOrder();
            if(!$orderId) {
                throw new Exception("Lỗi khi tạo đơn hàng");
            }

            $orderItem = new OrderItem($this->db);
            foreach ($data['orderItems'] as $item) {
                $orderItem->setOrderId($orderId);
                $orderItem->setProductId($item['productId']);
                $orderItem->setQuantity($item['quantity']);
                
                $productData = $product->getProductById($item['productId']);
                $orderItem->setPrice($productData['price']);
                $orderItem->setSubtotal($productData['price'] * $item['quantity']);
                
                $orderItem->setNote(isset($item['note']) ? $item['note'] : '');
                
                if (!$orderItem->create()) {
                    throw new Exception("Lỗi khi thêm sản phẩm vào đơn hàng");
                }
            }

            $this->db->commit();
            
            // Trả về thông tin đơn hàng
            $response = [
                "orderId" => $orderId,
                "totalAmount" => $totalAmount,
                "deliveryMethod" => $data['deliveryMethod'],
                "deliveryTime" => $data['deliveryTime'],
                "note" => isset($data['note']) ? $data['note'] : '',
                "employeeId" => $this->order->getEmployeeId(),
                "orderItems" => $orderItems
            ];
            
            if (isset($data['isNewCustomer']) && $data['isNewCustomer']) {
                $response["customerInfo"] = [
                    "id" => $customerId,
                    "name" => $data['receiverName'],
                    "phone" => $data['receiverPhone'],
                    "address" => $data['receiverAddress'],
                    "password" => "123456"
                ];
            }
            $notification = new Notification($this->db);
            $notification->createNotification($orderId, 'success', "Đơn hàng #{$orderId} đã được tạo.", "Đơn hàng #{$orderId} đã được tạo thành công.");
            return $this->jsonResponse(200, "Tạo đơn hàng thành công", $response);

        } catch (Exception $e) {
            $this->db->rollBack();
            return $this->jsonResponse(500, "Lỗi khi tạo đơn hàng: " . $e->getMessage(), null);
        }
    }

    public function updateOrder($id, $data) {
        try {
            $existingOrder = $this->order->getOrder($id);
            if(!$existingOrder) {
                return $this->jsonResponse(404, "Không tìm thấy đơn hàng", null);
            }
            
            if (isset($data['receiverName'])) {
                $this->order->setReceiverName($data['receiverName']);
            }
            if (isset($data['receiverPhone'])) {
                $this->order->setReceiverPhone($data['receiverPhone']);
            }
            if (isset($data['receiverAddress'])) {
                $this->order->setReceiverAddress($data['receiverAddress']);
            }
            if (isset($data['deliveryMethod'])) {
                $this->order->setDeliveryMethod($data['deliveryMethod']);
            }
            if (isset($data['deliveryTime'])) {
                $this->order->setDeliveryDate($data['deliveryTime']);
            }
            if (isset($data['note'])) {
                $this->order->setNote($data['note']);
            }
            
            $result = $this->order->updateOrder($id);
            
            if(isset($data['items']) && is_array($data['items'])) {
                $orderItem = new OrderItem($this->db);
                
                // Xóa tất cả các món hàng cũ của đơn hàng này
                $orderItem->deleteByOrderId($id);
                
                $totalAmount = 0;
                foreach ($data['items'] as $item) {
                    if (!isset($item['productId']) || !isset($item['quantity']) || !isset($item['price'])) {
                        continue;
                    }
                    $orderItem->setOrderId($id);
                    $orderItem->setProductId($item['productId']);
                    $orderItem->setQuantity($item['quantity']);
                    $orderItem->setPrice($item['price']);
                    $orderItem->setNote(isset($item['note']) ? $item['note'] : '');
                    $orderItem->setSubtotal($item['price'] * $item['quantity']);
                    $orderItem->create();
                    $totalAmount += $item['price'] * $item['quantity'];
                }
                $this->order->setTotalAmount($totalAmount);
                $this->order->updateOrder($id);
                $notification = new Notification($this->db);
                $notification->createNotification($id, 'success', "Đơn hàng #{$id} đã được cập nhật.", "Đơn hàng #{$id} đã được cập nhật thành công.");
                return $this->jsonResponse(200, "Cập nhật đơn hàng thành công", null);
            }
        } catch(Exception $e) {
            return $this->jsonResponse(500, $e->getMessage(), null);
        }
    }

    public function deleteOrder($id) {
        $result = $this->order->deleteOrder($id);
        if($result) {
            $notification = new Notification($this->db);
            $notification->createNotification($id, 'danger', "Đơn hàng #{$id} đã được xóa.", "Đơn hàng #{$id} đã được xóa thành công.");
            return $this->jsonResponse(200, "Xóa đơn hàng thành công", null);
        } else {
            return $this->jsonResponse(404, "Không tìm thấy đơn hàng", null);
        }
    }

    public function handleOrder($id, $status) {
        $result = $this->order->handleOrder($id, $status);
        if($result) {
            $notification = new Notification($this->db);
            if($status==1) {
                $notification->createNotification($id, 'success', "Đơn hàng #{$id} đã được xử lý thành công.", "Đơn hàng #{$id} đã được xử lý thành công.");   
            } else {
                $notification->createNotification($id, 'danger', "Đơn hàng #{$id} đã bị hủy.", "Đơn hàng #{$id} đã bị hủy.");
            }
            return $this->jsonResponse(200, "Cập nhật trạng thái đơn hàng thành công", null);
        } else {
            return $this->jsonResponse(500, "Lỗi khi cập nhật trạng thái đơn hàng", null);
        }
    }

    // Xu ly giao hang
    public function handleDelivery($id, $data) {
        $order = $this->order->getOrder($id);
        if(!$order) {
            return $this->jsonResponse(404, "Không tìm thấy đơn hàng", null);
        }
        $shippingStatus = $data['shippingStatus'] ?? null;
        $delivery_expected_time = $data['delivery_expected_time'] ?? null;
        $delivery_actual_time = $data['delivery_actual_time'] ?? null;
        $result = $this->order->handleDelivery($id, $data);
        
        if($shippingStatus == 3 && $delivery_actual_time) {
            if($delivery_actual_time < $delivery_expected_time) {
                $lateTime = round(strtotime($delivery_actual_time) - strtotime($delivery_expected_time) / 60);
                $notification = new Notification(($this->db));
                $notification->createNotification($id, 'warning', "Đơn hàng #{$id} đã được giao trễ {$lateTime} phút.", "Đơn hàng #{$id} đã được giao trễ {$lateTime} phút.");
            }
            else {
                $notification = new Notification(($this->db));
                $notification->createNotification($id, 'success', "Đơn hàng #{$id} đã được giao đúng hạn.", "Đơn hàng #{$id} đã được giao đúng hạn.");
            }
        }
        return $this->jsonResponse(200, "Cập nhật trạng thái giao hàng thành công", null);
    }

    // Xu ly don hang qua thoi gian xu ly
    public function checkOverdueOrders($timeoutMinutes = 60) {
        $query = "SELECT id, created_at FROM orders WHERE status = 0 AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) > :timeout";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['timeout' => $timeoutMinutes]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $notification = new Notification($this->db);
        foreach($orders as $order) {
            $title = "Đơn hàng #{$order['id']} đã quá thời gian xử lý";
            $check = $this->db->prepare("SELECT id FROM notifications WHERE title = :title");
            $check->execute(['title' => $title]);
            if (!$check->fetch()) {
                $notification->createNotification($order['id'], 'danger', $title, $title);
            }
        }
        return $this->jsonResponse(200, "Đã kiểm tra và thông báo đơn hàng quá thời gian xử lý", null);
    }

    public function getRecentOrders($limit = 5) {
        $orders = $this->order->getRecentOrders($limit);
        if($orders) {
            return $this->jsonResponse(200, "Thành công", $orders);
        } else {
            return $this->jsonResponse(200, "Không có đơn hàng gần đây", []);
        }
    } 

    public function getFilteredOrders($fromDate, $toDate, $status, $employee) {
        try {
            $result = $this->order->getFilteredOrders($fromDate, $toDate, $status, $employee);
            if ($result) {
                return $this->jsonResponse(200, "Thành công", $result);
            } else {
                return $this->jsonResponse(500, "Không tìm thấy đơn hàng nào", []);
            }
        } catch (Exception $e) {
            return $this->jsonResponse(500, "Lỗi khi lấy danh sách đơn hàng: " . $e->getMessage(), null);
        }
    }

    // Ham lay thong tin thong ke
    public function getStatistic() {
        $totalOrders = $this->order->getTotalOrdersBetweenDates("2025-01-01", date("Y-m-d"));
        $totalSuccessOrders = $this->order->getTotalSuccessOrder();
        $totalPendingOrders = $this->order->getTotalPendingOrder();
        $totalCanceledOrders = $this->order->getTotalCanceledOrder();
        $totalRevenue = $this->order->getTotalRevenueBetweenDates("2025-01-01", date("Y-m-d"));
        $totalAverageOrderValue = $this->order->getAverageOrderValueBetweenDates("2025-01-01", date("Y-m-d"));
        
        return $this->jsonResponse(200, "Thành công", [
            "totalOrders" => $totalOrders,
            "totalSuccessOrders" => $totalSuccessOrders,
            "totalPendingOrders" => $totalPendingOrders,
            "totalCanceledOrders" => $totalCanceledOrders,
            "totalRevenue" => (int)$totalRevenue,
            "totalAverageOrderValue" => (int)$totalAverageOrderValue
        ]);
    }

    // Lay don hang chi tiet
    public function getOrderDetails() {
        try {
            $result = $this->order->getOrderDetails();
            if($result) {
                return $this->jsonResponse(200, "Lấy danh sách đơn hàng chi tiết thành công", $result);
            } else {
                return $this->jsonResponse(500, "Không tìm thấy đơn hàng chi tiết", []);
            }
        } catch (Exception $e) {
            return $this->jsonResponse(500, "Lỗi khi lấy danh sách đơn hàng chi tiết: " . $e->getMessage(), null);
        }
    }

    // Lay cac quan cua dia chi don hang
    public function getDistricts() {
        $result = $this->order->getDistricts();
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(500, "Không tìm thấy quận", []);
        }
    }

    // Loc danh sach don hang theo van chuyen
    public function filterOrderByDelivery($timeRange='today', $district=null, $staffName=null, $keyword=null) {
        $result = $this->order->filterOrdersByDelivery($timeRange, $district, $staffName, $keyword);
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(500, "Không tìm thấy đơn hàng", []);
        }
    }

    // Lay danh sach don hang dang giao
    public function getOrdersDelivering() {
        $result = $this->order->getOrdersDelivering();
        if($result) {
            return $this->jsonResponse(200, "Thành công", $result);
        } else {
            return $this->jsonResponse(500, "Không tìm thấy đơn hàng", []);
        }
    }
    /**
     * Trả về JSON response
    */
    private function jsonResponse($status, $message, $data) {
        $response = [
            'status' => $status,
            "message" => $message,
            "data" => $data
        ];
        return $response;
    }
}