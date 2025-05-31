# Order Management System - Backend

## Overview

The Order Management System Backend is a robust RESTful API service built with PHP, designed to handle all business logic and data management for the Vy Food restaurant's order management system. This service provides endpoints for managing orders, products, customers, and delivery operations.

## Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [API Documentation](#api-documentation)
- [Database Schema](#database-schema)
- [Project Structure](#project-structure)
- [Development Guidelines](#development-guidelines)
- [Testing](#testing)
- [Deployment](#deployment)
- [Security](#security)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Order Management**

  - Create, read, update, and delete orders
  - Order status tracking
  - Order history and analytics
  - Real-time order updates
- **Customer Management**

  - Customer profile management
  - Order history per customer
  - Customer loyalty tracking
  - Contact information management
- **Product Management**

  - Product catalog management
  - Inventory tracking
  - Category management
  - Price management
- **Delivery Management**

  - Delivery tracking
  - Route optimization
  - Delivery staff management
  - Real-time location tracking
- **Analytics & Reporting**

  - Sales analytics
  - Revenue tracking
  - Customer behavior analysis
  - Performance metrics

## Technology Stack

- **Core**

  - PHP 8.1+
  - MySQL 8.0+
  - Apache/Nginx
- **Libraries & Tools**

  - PDO for database operations
  - JWT for authentication
  - PHPMailer for email notifications
  - Google Maps API for location services

## Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- Composer for dependency management
- SSL certificate for secure connections
- Google Maps API key (for delivery features)

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/nguyenquyen1910/Order-Management-System
cd order-management/Back-End
```

2. **Install dependencies**

```bash
composer install
```

3. **Configure environment**

```bash
cp .env.example .env
# Edit .env with your configuration
```

4. **Database setup**

```bash
# Create database
mysql -u root -p
CREATE DATABASE order_management;

# Import schema
mysql -u root -p order_management < database/schema.sql
```

5. **Set permissions**

```bash
chmod -R 755 .
chmod -R 777 storage/logs
```

## Configuration

### Environment Variables

```env
DB_HOST=localhost
DB_NAME=order_management
DB_USER=your_username
DB_PASS=your_password

JWT_SECRET=your_jwt_secret
GOOGLE_MAPS_API_KEY=your_google_maps_api_key

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_email_password
```

### Database Configuration

The system uses MySQL with the following default settings:

- Host: localhost
- Port: 3306
- Character Set: utf8mb4
- Collation: utf8mb4_unicode_ci

## API Documentation

### Authentication

All API endpoints require JWT authentication. Include the token in the Authorization header:

```
Authorization: Bearer <your_jwt_token>
```

### Endpoints

#### Orders

- `GET /api/orders` - Get all orders
- `GET /api/orders/{id}` - Get order details
- `POST /api/orders` - Create new order
- `PUT /api/orders/{id}` - Update order
- `DELETE /api/orders/{id}` - Delete order

#### Products

- `GET /api/products` - Get all products
- `GET /api/products/{id}` - Get product details
- `POST /api/products` - Create new product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

#### Customers

- `GET /api/customers` - Get all customers
- `GET /api/customers/{id}` - Get customer details
- `POST /api/customers` - Create new customer
- `PUT /api/customers/{id}` - Update customer
- `DELETE /api/customers/{id}` - Delete customer

#### Delivery

- `GET /api/delivery/orders` - Get delivery orders
- `POST /api/delivery/assign` - Assign delivery
- `PUT /api/delivery/status/{id}` - Update delivery status
- `GET /api/delivery/tracking/{id}` - Track delivery

## Database Schema

### Orders Table

```sql
CREATE TABLE orders (
    id VARCHAR(10) PRIMARY KEY,
    customer_id INT,
    employee_id INT,
    total_amount DECIMAL(10,2),
    delivery_method VARCHAR(50),
    delivery_date DATETIME,
    receiver_name VARCHAR(100),
    receiver_phone VARCHAR(20),
    receiver_address TEXT,
    note TEXT,
    status TINYINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Products Table

```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    category VARCHAR(50),
    price DECIMAL(10,2),
    description TEXT,
    image_url VARCHAR(255),
    status TINYINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Project Structure

```
Back-End/
├── api/                 # API endpoints
├── config/             # Configuration files
├── models/             # Data models
├── utils/              # Utility functions
├── database/           # Database migrations and seeds
├── storage/            # File storage
│   ├── logs/          # Application logs
│   └── uploads/       # Uploaded files
└── tests/             # Unit and integration tests
```

## Development Guidelines

### Code Style

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Write comprehensive comments
- Implement proper error handling

### Git Workflow

1. Create feature branch from develop
2. Make changes and commit with descriptive messages
3. Create pull request to develop branch
4. Code review and merge

### Testing

- Write unit tests for new features
- Run tests before committing
- Maintain minimum 80% code coverage

## Security

- All API endpoints are protected with JWT authentication
- Input validation and sanitization
- SQL injection prevention using prepared statements
- XSS protection
- CSRF protection
- Rate limiting
- Secure password hashing

## Deployment

1. Set up production environment
2. Configure SSL certificate
3. Set up database
4. Deploy code
5. Run migrations
6. Configure web server
7. Set up monitoring

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
