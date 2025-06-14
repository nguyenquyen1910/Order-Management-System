from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from api.router import router as chat_router
import uvicorn

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(chat_router, prefix="/api")

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)
