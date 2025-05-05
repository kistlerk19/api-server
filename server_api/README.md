# API Documentation

This API provides access to reports and analysis for data stored in an AWS RDS MySQL database.

## Base URL

```
http://domain.com/api
```

## Authentication

Currently, the API endpoints are public and do not require authentication.

## API Endpoints

### 1. Top Customers by Spending

Returns customers sorted by their total spending in descending order.

- **URL**: `/reports/top-customers`
- **Method**: `GET`
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": [
        {
          "customer_id": 1,
          "name": "Alice Smith",
          "email": "alice@example.com",
          "country": "USA",
          "total_spent": "1371.00"
        },
        {
          "customer_id": 3,
          "name": "Charlie Zhang",
          "email": "charlie@example.com",
          "country": "UK",
          "total_spent": "1200.00"
        },
      ],
    }
    ```

### 2. Monthly Sales Report

Returns sales data aggregated by month for shipped or delivered orders.

- **URL**: `/reports/monthly-sales`
- **Method**: `GET`
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": [
        {
          "month": "2023-11",
          "total_sales": "1371.00",
          "order_count": 1,
          "items_sold": 3
        },
        {
          "month": "2023-12",
          "total_sales": "300.00",
          "order_count": 1,
          "items_sold": 2
        }
      ],
    }
    ```

### 3. Products Never Ordered

Returns products that have never been ordered.

- **URL**: `/reports/products-never-ordered`
- **Method**: `GET`
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": [
        {
          "product_id": 2,
          "name": "Laptop",
          "category": "Electronics",
          "price": "800.00"
        }
      ],
    }
    ```

### 4. Average Order Value by Country

Returns the average order value grouped by customer country.

- **URL**: `/reports/average-order-value`
- **Method**: `GET`
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": [
        {
          "country": "USA",
          "average_order_value": "685.50"
        },
        {
          "country": "UK",
          "average_order_value": "1200.00"
        },
        {
          "country": "Canada",
          "average_order_value": "800.00"
        }
      ],
    }
    ```

### 5. Frequent Buyers

Returns customers who have placed more than one order.

- **URL**: `/reports/frequent-buyers`
- **Method**: `GET`
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": [
        {
          "customer_id": 1,
          "name": "Alice Smith",
          "email": "alice@example.com",
          "country": "USA",
          "order_count": 2,
          "total_spent": "1371.00"
        }
      ],
    }
    ```

## Error Handling

All endpoints return a JSON response with the following structure in case of error:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
  }
}
```

## Setup and Deployment

### Requirements

- PHP 8.1 or higher
- Composer
- MySQL (AWS RDS)
- Laravel 12.x

### Installation

1. Clone the repository
2. Run `composer install`
3. Configure the `.env` file with AWS RDS database credentials
4. Run `php artisan key:generate`
5. Serve the application with `php artisan serve`

### AWS RDS Configuration

The application is configured to connect to an AWS RDS MySQL instance. Make sure to update the `.env` file with appropriate connection details:

```
DB_CONNECTION=mysql
DB_HOST=your-aws-rds-endpoint.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=testdb
DB_USERNAME=root
DB_PASSWORD=Password123!
```

## 👥 Authors

- **Ishmael Gyamfi**

---

*This project was created as part of a SysOps automation challenge by Amalitech GTP.*
