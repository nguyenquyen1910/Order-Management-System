from datetime import datetime, timedelta


class FullQueryData:
    @staticmethod
    def get_full_data():
        return """
        -- Lấy toàn bộ dữ liệu khách hàng
        SELECT * FROM customers;
        
        -- Lấy toàn bộ dữ liệu nhân viên
        SELECT * FROM employees;
        
        -- Lấy toàn bộ dữ liệu danh mục
        SELECT * FROM categories;
        
        -- Lấy toàn bộ dữ liệu sản phẩm
        SELECT p.*, c.name as category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id;
        
        -- Lấy toàn bộ dữ liệu đơn hàng
        SELECT 
            o.*,
            c.name as customer_name,
            c.phone as customer_phone,
            c.address as customer_address,
            o.total_amount as total_amount,
            o.status as status,
            o.delivery_method as delivery_method,
            o.delivery_date as delivery_date,
            o.receiver_name as receiver_name,
            o.receiver_address as receiver_address,
            e.name as employee_name,
            e.role as employee_role
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.id
        LEFT JOIN employees e ON o.employee_id = e.id;
        
        -- Lấy toàn bộ chi tiết đơn hàng
        SELECT 
            oi.*,
            p.name as product_name,
            p.price as product_price,
            c.name as category_name
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id;
        """
