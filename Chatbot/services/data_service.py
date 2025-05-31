import json
from typing import Dict, Any


class DataService:
    def __init__(self):
        self.data = self._load_data()

    def _load_data(self) -> Dict[str, Any]:
        try:
            with open("database.json", "r", encoding="utf-8") as f:
                return json.load(f)
        except Exception as e:
            print(f"Lỗi khi đọc file JSON: {str(e)}")
            return {}

    def get_data(self) -> Dict[str, Any]:
        return self.data
