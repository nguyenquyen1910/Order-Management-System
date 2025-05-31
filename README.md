# Order Management System

## Overview

The Order Management System is a comprehensive solution designed for Vy Food restaurant to streamline and optimize their order management processes. This system integrates a modern web interface, robust backend services, and an AI-powered chatbot to provide a complete solution for managing orders, customers, products, and deliveries.

## Table of Contents

- [Features](#features)
- [System Architecture](#system-architecture)
- [Technology Stack](#technology-stack)
- [Prerequisites](#prerequisites)
- [Local Development Environment Setup](#local-development-environment-setup)
- [Installation](#installation)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [Development Guidelines](#development-guidelines)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Order Management**

  - Real-time order tracking
  - Order status updates
  - Order history and analytics
  - Bulk order operations
- **Customer Management**

  - Customer profiles
  - Order history per customer
  - Customer loyalty tracking
  - Contact management
- **Product Management**

  - Product catalog
  - Inventory tracking
  - Category management
  - Price management
- **Delivery Management**

  - Real-time delivery tracking
  - Route optimization
  - Delivery staff management
  - Status updates
- **AI-Powered Chatbot**

  - Natural language processing
  - Order assistance
  - Customer support
  - Real-time responses

## System Architecture

The system is built with a modular architecture consisting of three main components:

1. **Frontend (PHP + JavaScript)**

   - User interface and interactions
   - Real-time updates
   - Responsive design
   - AJAX communication
2. **Backend (PHP)**

   - RESTful API services
   - Business logic
   - Database operations
   - Authentication & Authorization
3. **Chatbot (Python)**

   - AI-powered customer support
   - Natural language processing
   - Integration with main system
   - Real-time responses

## Technology Stack

### Frontend

- PHP 8.1+
- JavaScript (ES6+)
- jQuery 3.x
- Bootstrap 5.x
- AJAX for API communication

### Backend

- PHP 8.1+
- MySQL 8.0+
- RESTful API architecture
- JWT authentication

### Chatbot

- Python 3.8+
- FastAPI
- OpenRouter API
- Google Gemini 2.0 Flash

### Development Tools

- Git for version control
- VS Code recommended
- XAMPP/Laragon for local development
- Postman for API testing

## Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Python 3.8 or higher
- Laragon or XAMPP
- Composer for PHP dependencies
- pip for Python dependencies
- Modern web browser
- Git

## Local Development Environment Setup

### Option 1: Using Laragon (Recommended)

1. **Install Laragon**

   - Download Laragon from [https://laragon.org/download/](https://laragon.org/download/)
   - Choose the full version which includes PHP, MySQL, and other tools
   - Run the installer and follow the installation wizard
2. **Configure Laragon**

   - Open Laragon
   - Click on "Menu" → "Preferences"
   - Set PHP version to 8.1 or higher
   - Set MySQL version to 8.0 or higher
   - Configure the following settings:
     ```
     Document Root: C:\laragon\www
     PHP Version: 8.1
     MySQL Version: 8.0
     ```
3. **Start Laragon Services**

   - Click "Start All" in Laragon
   - Verify services are running:
     - Apache should be running on port 80
     - MySQL should be running on port 3306
     - Check Laragon tray icon for service status

### Option 2: Using XAMPP

1. **Install XAMPP**

   - Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Choose the version with PHP 8.1
   - Run the installer and follow the installation wizard
2. **Configure XAMPP**

   - Open XAMPP Control Panel
   - Click "Config" for Apache
   - Set the following in httpd.conf:
     ```apache
     DocumentRoot "C:/xampp/htdocs"
     <Directory "C:/xampp/htdocs">
     ```
3. **Start XAMPP Services**

   - Start Apache and MySQL from XAMPP Control Panel
   - Verify services are running:
     - Apache should be running on port 80
     - MySQL should be running on port 3306

### Verify Installation

1. **Check PHP Installation**

   ```bash
   # Create a test file
   echo "<?php phpinfo(); ?>" > C:\laragon\www\phpinfo.php
   # Or for XAMPP
   echo "<?php phpinfo(); ?>" > C:\xampp\htdocs\phpinfo.php

   # Access in browser
   http://localhost/phpinfo.php
   ```
2. **Check MySQL Installation**

   ```bash
   # Access phpMyAdmin
   http://localhost/phpmyadmin
   ```
3. **Verify Required Extensions**

   - Open phpinfo.php in browser
   - Check for required extensions:
     - mysqli
     - pdo_mysql
     - json
     - mbstring
     - openssl

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/nguyenquyen1910/Order-Management-System
cd Order-Management-System
```

2. **Frontend Setup**

```bash
# For Laragon
cp -r Front-End/* C:/laragon/www/Order\ Management/Front-End/

# For XAMPP
cp -r Front-End/* C:/xampp/htdocs/Order\ Management/Front-End/
```

3. **Backend Setup**

```bash
# For Laragon
cp -r Back-End/* C:/laragon/www/Order\ Management/Back-End/

# For XAMPP
cp -r Back-End/* C:/xampp/htdocs/Order\ Management/Back-End/
```

4. **Chatbot Setup**

```bash
cd Chatbot
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate
pip install -r requirements.txt
```

5. **Database Setup**

```bash
# Access phpMyAdmin
http://localhost/phpmyadmin

# Create database
CREATE DATABASE order_management;

# Import database schema
# Use the SQL file in the database directory
```

## Configuration

### Database Configuration

```sql
-- Access phpMyAdmin
http://localhost/phpmyadmin/index.php?route=/database/structure&db=order_management

-- Default credentials
Username: root
Password: (empty for Laragon, 'root' for XAMPP)
```

### Frontend Configuration

```php
// Update database connection in config files
// Configure API endpoints
// Access the application
http://localhost/Order%20Management/Front-End/login.php
```

### Backend Configuration

```php
// Set up database connection
// Configure JWT settings
// Set up API routes
```

### Chatbot Configuration

```env
# OpenRouter Configuration
OPENROUTER_API_KEY=your_api_key
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1
MODEL=google/gemini-2.0-flash-001
```

## Project Structure

```
Order-Management-System/
├── Front-End/           # PHP + JavaScript frontend
│   ├── assets/         # Static assets
│   ├── js/            # JavaScript files
│   └── *.php          # PHP pages
├── Back-End/           # PHP backend
│   ├── api/           # API endpoints
│   ├── config/        # Configuration
│   ├── models/        # Data models
│   └── utils/         # Utilities
├── Chatbot/           # Python chatbot
│   ├── api/           # API endpoints
│   ├── services/      # Business logic
│   └── config/        # Configuration
└── README.md          # Project documentation
```

## Development Guidelines

### Code Style

- Follow PSR-12 for PHP
- Use ESLint for JavaScript
- Follow PEP 8 for Python
- Write comprehensive comments

### Git Workflow

1. Create feature branch from develop
2. Make changes and commit with descriptive messages
3. Create pull request to develop branch
4. Code review and merge

## Testing

- Frontend testing

  - Cross-browser testing
  - Responsive design testing
  - JavaScript unit tests
- Backend testing

  - API endpoint testing
  - Database operations testing
  - Authentication testing
- Chatbot testing

  - Response validation
  - Error handling
  - Integration testing

## Deployment

1. Set up production environment
2. Configure web server
3. Set up database
4. Deploy frontend
5. Deploy backend
6. Deploy chatbot
7. Configure SSL
8. Set up monitoring

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
