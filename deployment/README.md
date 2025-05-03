# Laravel EC2-RDS Deployment with Ansible

This Ansible playbook deploys a Laravel application from a Git repository subdirectory to an EC2 instance, connecting it to an RDS MySQL database.

## Security Features

This deployment uses Ansible Vault to securely store sensitive information:
- Database credentials
- RDS endpoint
- Laravel app key
- Other secrets

## Prerequisites

- Ansible installed on your local machine
- EC2 instance running Ubuntu (tested on 20.04/22.04)
- RDS MySQL instance
- SSH access to your EC2 instance

## Initial Setup

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/your-deployment-repo.git
cd your-deployment-repo
```

### 2. Create Vault File

Create an encrypted vault file to store your sensitive information:

```bash
ansible-vault create vault.yml
```

This will prompt you for a new vault password. Add the following content to the file:

```yaml
vault_rds_endpoint: your-rds-endpoint.rds.amazonaws.com
vault_db_name: your_database_name
vault_db_user: your_database_user
vault_db_password: your_secure_password
vault_app_key: base64:YourLaravelAppKey=  # Optional, will be generated if blank
```

### 3. Set Up Inventory

Copy the sample inventory file:

```bash
cp inventory.sample.ini inventory.ini
```

Edit the inventory.ini file with your EC2 instance details:

```ini
[ec2_instances]
ec2-laravel ansible_host=your-ec2-public-ip ansible_user=ubuntu ansible_ssh_private_key_file=/path/to/your-key.pem
```

### 4. Run the Playbook

Execute the playbook with the vault password:

```bash
ansible-playbook -i inventory.ini main.yml --ask-vault-pass
```

## Optional: CI/CD Integration

For CI/CD pipelines, you can store the vault password in a file:

```bash
echo "your-vault-password" > .vault_password
chmod 600 .vault_password
```

Then run the playbook with:

```bash
ansible-playbook -i inventory.ini main.yml --vault-password-file .vault_password
```

Make sure to add `.vault_password` to your `.gitignore` file.

## Selective Task Execution

The playbook includes tags to run specific tasks:

```bash
# Run only migrations
ansible-playbook -i inventory.ini main.yml --ask-vault-pass --tags migrate

# Generate app key only
ansible-playbook -i inventory.ini main.yml --ask-vault-pass --tags app-key
```

## Troubleshooting

If you encounter issues:

1. Check Laravel logs on the EC2 instance:
   ```bash
   sudo tail -f /var/www/laravel-app/storage/logs/laravel.log
   ```

2. Check Nginx logs:
   ```bash
   sudo tail -f /var/log/nginx/error.log
   ```

3. Verify RDS connectivity:
   ```bash
   mysql -h your-rds-endpoint.rds.amazonaws.com -u your_db_user -p
   ```