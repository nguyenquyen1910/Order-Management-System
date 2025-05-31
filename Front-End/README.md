# Order Management System - Frontend

## Overview

The Order Management System Frontend is a modern web application built with PHP and JavaScript, designed to provide an intuitive and efficient user interface for managing Vy Food restaurant's operations. This frontend application seamlessly integrates with the backend API to deliver a comprehensive order management experience.

## Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [Component Documentation](#component-documentation)
- [Development Guidelines](#development-guidelines)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Dashboard**

  - Real-time order statistics
  - Revenue analytics
  - Performance metrics
  - Quick action buttons
- **Order Management**

  - Order creation and editing
  - Real-time order status updates
  - Order history and filtering
  - Bulk order operations
- **Customer Management**

  - Customer profile management
  - Order history per customer
  - Customer search and filtering
  - Contact information management
- **Product Management**

  - Product catalog with images
  - Category management
  - Price and inventory tracking
  - Bulk product operations
- **Delivery Management**

  - Real-time delivery tracking
  - Route optimization
  - Delivery staff assignment
  - Status updates

## Technology Stack

- **Core**

  - PHP 8.1+
  - JavaScript (ES6+)
  - HTML5
  - CSS3
- **UI Framework & Styling**

  - Bootstrap 5.x
  - jQuery 3.x
  - Font Awesome
  - Custom CSS
- **JavaScript Libraries**

  - jQuery for DOM manipulation
  - Chart.js for data visualization
  - SweetAlert2 for notifications
  - DataTables for table management
- **Development Tools**

  - ESLint
  - Prettier
  - Git

## Prerequisites

- PHP 8.1 or higher
- Web server (Apache/Nginx)
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Git
- IDE (VS Code recommended)

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/nguyenquyen1910/Order-Management-System
cd order-management/Front-End
```

2. **Configure web server**

- Set document root to the Front-End directory
- Ensure PHP is properly configured
- Enable required PHP extensions

3. **Configure environment**

```bash
# Update database connection settings in config files
# Update API endpoints in JavaScript files
```

## Project Structure

```
Front-End/
├── assets/
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   │   ├── order.js
│   │   ├── create-order.js
│   │   ├── delivery.js
│   │   ├── statistic.js
│   │   ├── edit-order.js
│   │   ├── dashboard.js
│   │   ├── script.js
│   │   ├── product.js
│   │   ├── login.js
│   │   ├── notification.js
│   │   ├── toast-message.js
│   │   ├── chatbot.js
│   │   ├── home-page.js
│   │   └── customer.js
│   ├── images/        # Image assets
│   └── fonts/         # Font files
├── index.php          # Main entry point
├── create-order.php   # Order creation page
├── edit-order.php     # Order editing page
├── login.php          # Authentication page
├── not-access.php     # Access denied page
└── home-page.html     # Landing page
```

## Component Documentation

### Core Pages

- `index.php` - Main dashboard and application entry point
- `create-order.php` - Order creation interface
- `edit-order.php` - Order editing interface
- `login.php` - User authentication
- `not-access.php` - Access control page
- `home-page.html` - Public landing page

### JavaScript Modules

- `order.js` - Order management functionality
- `create-order.js` - Order creation logic
- `delivery.js` - Delivery tracking and management
- `statistic.js` - Analytics and reporting
- `dashboard.js` - Dashboard functionality
- `product.js` - Product management
- `customer.js` - Customer management
- `notification.js` - Notification system
- `chatbot.js` - AI chatbot integration

## Development Guidelines

### Code Style

- Follow PSR-12 coding standards for PHP
- Use ESLint for JavaScript
- Implement proper error handling
- Write comprehensive comments
- Follow responsive design principles

### JavaScript Best Practices

```javascript
// Example of API service
const orderService = {
  getOrders: () => axios.get("/api/orders"),
  createOrder: (data) => axios.post("/api/orders", data),
  updateOrder: (id, data) => axios.put(`/api/orders/${id}`, data),
  deleteOrder: (id) => axios.delete(`/api/orders/${id}`),
};
```

### Git Workflow

1. Create feature branch from develop
2. Make changes and commit with descriptive messages
3. Create pull request to develop branch
4. Code review and merge

## Testing

- Manual testing of all features
- Cross-browser testing
- Mobile responsiveness testing
- API integration testing

## Deployment

1. Set up production web server
2. Configure SSL certificate
3. Deploy code
4. Configure environment variables
5. Test all features
6. Monitor performance

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
