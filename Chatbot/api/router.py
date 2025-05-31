from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from services.data_service import DataService
from services.ai_service import AIService
import json


class ChatRequest(BaseModel):
    message: str


class ChatResponse(BaseModel):
    status: str
    data: dict


router = APIRouter()


@router.post("/chat", response_model=ChatResponse)
async def chat(request: ChatRequest):
    try:
        data_service = DataService()
        ai_service = AIService()
        data = data_service.get_data()

        if not data:
            return ChatResponse(
                status="error",
                data={"message": "Không tìm thấy dữ liệu trong hệ thống"},
            )

        ai_service = AIService()
        response = await ai_service.get_response(request.message, data)

        return ChatResponse(status="success", data={"message": response})
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
