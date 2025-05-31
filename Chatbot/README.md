# AI-Powered Chatbot System

## Overview

The AI-Powered Chatbot System is an intelligent conversational agent designed to enhance customer service and support for the Vy Food restaurant's order management system. Built with Python and leveraging OpenRouter's AI capabilities, this chatbot provides natural language understanding and response generation capabilities.

## Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Development Guidelines](#development-guidelines)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Natural Language Processing**

  - Intent recognition
  - Entity extraction
  - Context understanding
  - Multi-turn conversations

- **AI Integration**

  - OpenRouter API integration
  - Google Gemini 2.0 Flash model
  - Custom prompt engineering
  - Response generation

- **Data Management**

  - Database integration
  - Data export functionality
  - JSON data handling
  - System data synchronization

- **Integration Capabilities**

  - RESTful API endpoints
  - Frontend communication
  - CORS support
  - Error handling

## Technology Stack

- **Core**

  - Python 3.8+
  - FastAPI
  - Uvicorn
  - Requests

- **AI & ML**

  - OpenRouter API
  - Google Gemini 2.0 Flash
  - Custom prompt templates
  - JSON data processing

- **Development Tools**

  - Python-dotenv
  - Pydantic
  - JSON handling
  - Type hints

## Prerequisites

- Python 3.8 or higher
- OpenRouter API key
- Virtual environment (venv)
- Modern web browser

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/nguyenquyen1910/Order-Management-System
cd order-management/Chatbot
```

2. **Create and activate virtual environment**

```bash
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate
```

3. **Install dependencies**

```bash
pip install -r requirements.txt
```

4. **Configure environment**

```bash
cp .env.example .env
# Edit .env with your configuration
```

## Configuration

### Environment Variables

```env
# OpenRouter Configuration
OPENROUTER_API_KEY=your_api_key
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1
MODEL=google/gemini-2.0-flash-001

# API Configuration
API_HOST=0.0.0.0
API_PORT=8000
```

## Project Structure

```
Chatbot/
├── api/                # API endpoints
│   └── router.py      # API routing and request handling
├── services/          # Business logic
│   ├── ai_service.py  # OpenRouter AI integration
│   └── data_service.py # Data handling and export
├── config/            # Configuration files
├── database/          # Database files
├── main.py           # Application entry point
├── test.py           # Test cases
├── database.json     # Exported database schema
└── requirements.txt  # Dependencies
```

## API Documentation

### Endpoints

#### Chat

- `POST /api/chat`
  - Send message to chatbot
  - Request body: `{ "message": "string" }`
  - Response: `{ "status": "success", "data": { "message": "string" } }`

## Development Guidelines

### Code Style

- Follow PEP 8 guidelines
- Use type hints
- Write comprehensive docstrings
- Implement proper error handling

### Python Best Practices

```python
from typing import Dict, Any
from pydantic import BaseModel

class ChatRequest(BaseModel):
    message: str

class ChatResponse(BaseModel):
    status: str
    data: Dict[str, Any]
```

### Git Workflow

1. Create feature branch from develop
2. Make changes and commit with descriptive messages
3. Create pull request to develop branch
4. Code review and merge

## Testing

- Unit tests for AI services
- Integration tests for API endpoints
- Response validation
- Error handling tests

## Deployment

1. Set up production environment
2. Configure environment variables
3. Deploy application
4. Configure reverse proxy
5. Set up monitoring

## Contributing

1. Fork the repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, email jrnguyen14@gmail.com or create an issue in the repository.

---

Built with ❤️ by Nguyen Viet Quyen
