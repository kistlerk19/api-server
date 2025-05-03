# API and Deployment

This repository contains the code for an API providing reports and analysis from an AWS RDS MySQL database, along with Ansible playbooks for deploying the application to an EC2 instance.

## API Documentation

This API provides access to reports and analysis for data stored in an AWS RDS MySQL database.

### Base URL

http://domain.com/api

### Authentication

Currently, the API endpoints are public and do not require authentication.

### API Endpoints

#### 1. Top Customers by Spending

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

#### 2. Monthly Sales Report

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

#### 3. Products Never Ordered

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

#### 4. Average Order Value by Country

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

#### 5. Frequent Buyers

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
        // more customers...
      ],
      "message": "Frequent buyers retrieved successfully."
    }
    ```

### Error Handling

All endpoints return a JSON response with the following structure in case of error:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
  }
}
API Setup and Deployment (Manual)
This section outlines the manual steps to set up and run the API.

Requirements
PHP 8.1 or higher
Composer
MySQL (AWS RDS)
Laravel 12.x
Installation
Clone the repository
Run composer install
Configure the .env file with AWS RDS database credentials
Run php artisan key:generate
Serve the application with php artisan serve
AWS RDS Configuration
The application is configured to connect to an AWS RDS MySQL instance. Make sure to update the .env file with appropriate connection details:

DB_CONNECTION=mysql
DB_HOST=your-aws-rds-endpoint.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=testdb
DB_USERNAME=root
DB_PASSWORD=Password123!
Laravel EC2-RDS Deployment with Ansible
This Ansible playbook deploys the Laravel application from a Git repository subdirectory to an EC2 instance, connecting it to an RDS MySQL database.

Security Features
This deployment uses Ansible Vault to securely store sensitive information:

Database credentials
RDS endpoint
Laravel app key
Other secrets
Prerequisites
Ansible installed on your local machine
EC2 instance running Ubuntu (tested on 20.04/22.04)
RDS MySQL instance
SSH access to your EC2 instance
Initial Setup
1. Clone the Repository
Bash

git clone [https://github.com/yourusername/your-deployment-repo.git](https://github.com/yourusername/your-deployment-repo.git)
cd your-deployment-repo
(Note: If your API code is in a subdirectory of this deployment repo, adjust paths in Ansible playbook accordingly.)

2. Create Vault File
Create an encrypted vault file to store your sensitive information:

Bash

ansible-vault create vault.yml
This will prompt you for a new vault password. Add the following content to the file:

YAML

vault_rds_endpoint: your-rds-endpoint.rds.amazonaws.com
vault_db_name: your_database_name
vault_db_user: your_database_user
vault_db_password: your_secure_password
vault_app_key: base64:YourLaravelAppKey=  # Optional, will be generated if blank
3. Set Up Inventory
Copy the sample inventory file:

Bash

cp inventory.sample.ini inventory.ini
Edit the inventory.ini file with your EC2 instance details:

Ini, TOML

[ec2_instances]
ec2-laravel ansible_host=your-ec2-public-ip ansible_user=ubuntu ansible_ssh_private_key_file=/path/to/your-key.pem
4. Run the Playbook
Execute the playbook with the vault password:

Bash

ansible-playbook -i inventory.ini main.yml --ask-vault-pass
Optional: CI/CD Integration
For CI/CD pipelines, you can store the vault password in a file:

Bash

echo "your-vault-password" > .vault_password
chmod 600 .vault_password
Then run the playbook with:

Bash

ansible-playbook -i inventory.ini main.yml --vault-password-file .vault_password
Make sure to add .vault_password to your .gitignore file.

Selective Task Execution
The playbook includes tags to run specific tasks:

Bash

# Run only migrations
ansible-playbook -i inventory.ini main.yml --ask-vault-pass --tags migrate

# Generate app key only
ansible-playbook -i inventory.ini main.yml --ask-vault-pass --tags app-key
Troubleshooting
If you encounter issues:

Check Laravel logs on the EC2 instance:

Bash

sudo tail -f /var/www/laravel-app/storage/logs/laravel.log
Check Nginx logs:

Bash

sudo tail -f /var/log/nginx/error.log
Verify RDS connectivity:

Bash

mysql -h your-rds-endpoint.rds.amazonaws.com -u your_db_user -p