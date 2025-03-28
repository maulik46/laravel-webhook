## Webhook Handler Microservice

## Project Overview

This Laravel-based microservice is designed to handle incoming webhooks from various sources, providing a flexible and robust solution for processing and storing webhook payloads.

## Features

*   Multi-source webhook support (GitHub, Stripe)
*   Comprehensive payload validation
*   Detailed error handling and logging
*   Flexible database storage for different webhook types

## Design Choices

### Architecture

*   **Repository Pattern**: Separates data access logic
*   **Strategy Pattern**: Allows dynamic webhook processing based on source
*   **Dependency Injection**: Enables loose coupling and easier testing

### Key Components

*   `WebhookController`: Handles incoming webhook requests
*   `WebhookService`: Processes webhooks based on source
*   `Repositories`: Manage database interactions
*   Custom Exception Handler: Provides detailed error management

### Validation Approach

*   Source-specific payload validation
*   Comprehensive error handling
*   Detailed logging for traceability

## Prerequisites

*   PHP 8.1+
*   Composer
*   MySQL 5.7+
*   Laravel 10.x

## Installation Steps

### 1\. Clone the Repository

```plaintext
git clone https://github.com/maulik46/laravel-webhook.git
cd laravel-webhook
```

### 2\. Install Dependencies

```plaintext
composer install
```

### 3\. Environment Configuration

```plaintext
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4\. Database Setup

Configure Database in `.env`

```plaintext
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webhook_handler
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run Migrations

```plaintext
php artisan migrate
```

### 5\. Running the Application

```plaintext
# Start development server
php artisan serve
```

Webhook endpoint will be available at: `http://localhost:8000/api/webhook`

### Webhook Testing

#### GitHub Webhook Test

```plaintext
curl -X POST http://localhost:8000/api/webhook \
     -H "Content-Type: application/json" \
     -H "X-GitHub-Event: push" \
     -d '{
   "commits": [
     {
       "id": "abc123",
       "message": "Test commit",
       "author": {
         "name": "John Doe",
         "email": "john@example.com"
       }
     }
   ]
}'
```

#### Stripe Webhook Test

```plaintext
curl -X POST http://localhost:8000/api/webhook \
     -H "Content-Type: application/json" \
     -H "Stripe-Signature: test_signature" \
     -d '{
   "type": "payment_intent.succeeded",
   "data": {
     "object": {
       "id": "ch_test123",
       "amount": 5000,
       "currency": "usd"
     }
   }
}'
```

## Troubleshooting

*   Ensure all PHP extensions are installed
*   Check Laravel's system requirements
*   Verify database connection
*   Review error logs for specific issues
