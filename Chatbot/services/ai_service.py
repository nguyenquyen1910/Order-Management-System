from config.settings import settings
import json
from typing import Dict, Any
import requests


class AIService:
    def __init__(self):
        self.base_url = settings.base_url
        self.api_key = settings.api_key
        self.model = "google/gemini-2.0-flash-001"

    async def get_response(self, message: str, data: Dict[str, Any]) -> str:
        try:
            data_str = json.dumps(data, indent=2, default=str)
            system_message = f"""
                {settings.SYSTEM_PROMPT}
                Dữ liệu hệ thống:
                {data_str}
            """
            response = requests.post(
                url=f"{self.base_url}/chat/completions",
                headers={
                    "Authorization": f"Bearer {self.api_key}",
                    "Content-Type": "application/json",
                    "HTTP-Referer": "http://localhost/Order%20Management/Front-End/login.php",
                    "X-Title": "Vy Food",
                },
                data=json.dumps(
                    {
                        "model": self.model,
                        "messages": [
                            {"role": "system", "content": system_message},
                            {
                                "role": "user",
                                "content": [
                                    {"type": "text", "text": message},
                                ],
                            },
                        ],
                    }
                ),
            )

            response_data = response.json()

            if "choices" in response_data and len(response_data["choices"]) > 0:
                return response_data["choices"][0]["message"]["content"]
            else:
                return "Xin lỗi, không nhận được phản hồi từ AI."
        except Exception as e:
            print(f"Lỗi khi gọi OpenRouter API: {str(e)}")
            return f"Xin lỗi, có lỗi xảy ra: {str(e)}"
