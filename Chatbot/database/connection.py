import mysql.connector
import sys
import os

sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from config.settings import settings


class DatabaseConnection:
    _instance = None

    def __new__(cls):
        if cls._instance is None:
            cls._instance = super().__new__(cls)
        return cls._instance

    def __init__(self):
        if not hasattr(self, "connection"):
            self.config = {
                "host": settings.MYSQL_HOST,
                "user": settings.MYSQL_USER,
                "password": settings.MYSQL_PASSWORD,
                "database": settings.MYSQL_DATABASE,
                "port": settings.MYSQL_PORT,
            }

    def get_connection(self):
        return mysql.connector.connect(**self.config)

    def execute_query(self, query, params=None):
        try:
            conn = self.get_connection()
            cursor = conn.cursor(dictionary=True)
            cursor.execute(query, params)
            result = cursor.fetchall()
            cursor.close()
            conn.close()
            return result
        except Exception as e:
            print(f"Error executing query: {e}")
            return None
