import json
from connection import DatabaseConnection
import sys
import os
import decimal

sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))


def decimal_default(obj):
    if isinstance(obj, decimal.Decimal):
        return float(obj)
    return str(obj)


def export_database():
    db = DatabaseConnection()
    orders = db.execute_query(
        """
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
    """
    )
    order_items = db.execute_query(
        """
        SELECT 
            oi.*,
            p.name as product_name,
            p.price as product_price,
            c.name as category_name
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id;
    """
    )

    delivery_data = db.execute_query(
        """
            SELECT 
                d.*, o.receiver_name, o.receiver_phone, o.receiver_address
            FROM deliveries d
            LEFT JOIN orders o ON d.order_id = o.id;
        """
    )

    order_items_map = {}
    for item in order_items:
        order_id = item["order_id"]
        if order_id not in order_items_map:
            order_items_map[order_id] = []
        order_items_map[order_id].append(item)

    for order in orders:
        order["items"] = order_items_map.get(order["id"], [])

    data = {
        "orders": orders,
        "deliveries": delivery_data,
    }
    with open("database.json", "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=2, default=decimal_default)


if __name__ == "__main__":
    export_database()
