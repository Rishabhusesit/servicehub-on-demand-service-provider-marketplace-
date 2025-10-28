# ServiceHub EC2 Deployment Guide

This guide will help you deploy ServiceHub on an EC2 instance using AWS Session Manager.

## Prerequisites

1. **EC2 Instance Requirements:**
   - Amazon Linux 2 or Ubuntu 20.04+
   - Minimum 2GB RAM, 2 CPU cores
   - At least 20GB storage
   - Security group allowing HTTP (80) and HTTPS (443)

2. **Domain Name (Optional but recommended):**
   - Point your domain to the EC2 public IP
   - Required for SSL certificate setup

## Step-by-Step Deployment

### 1. Connect to EC2 via Session Manager
```bash
# In AWS Console, go to Systems Manager > Session Manager
# Click "Start session" and select your EC2 instance
```

### 2. Download and Run Deployment Scripts
```bash
# Download the scripts to your EC2 instance
wget https://raw.githubusercontent.com/Rishabhusesit/servicehub-on-demand-service-provider-marketplace-/main/deploy.sh
wget https://raw.githubusercontent.com/Rishabhusesit/servicehub-on-demand-service-provider-marketplace-/main/env-setup.sh
wget https://raw.githubusercontent.com/Rishabhusesit/servicehub-on-demand-service-provider-marketplace-/main/database-setup.sh
wget https://raw.githubusercontent.com/Rishabhusesit/servicehub-on-demand-service-provider-marketplace-/main/ssl-setup.sh

# Make scripts executable
chmod +x *.sh

# Run the main deployment script
sudo ./deploy.sh
```

### 3. Set Up Environment
```bash
# Configure environment variables
sudo ./env-setup.sh
```

### 4. Set Up Database
```bash
# Import database and set up initial data
sudo ./database-setup.sh
```

### 5. Configure SSL (Optional)
```bash
# Replace 'yourdomain.com' with your actual domain
sudo ./ssl-setup.sh yourdomain.com
```

## Post-Deployment Configuration

### 1. Update Environment Variables
Edit `/var/www/html/Files/core/.env` and update:
- `APP_URL` - Your domain URL
- Database credentials
- Mail settings
- Payment gateway API keys
- Any other service credentials

### 2. Configure Payment Gateways
Update the gateway configurations in the admin panel or directly in the database.

### 3. Set Up Cron Jobs
```bash
# Add to crontab for scheduled tasks
sudo crontab -e

# Add this line:
* * * * * cd /var/www/html/Files/core && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Configure File Permissions
```bash
# Ensure proper permissions
sudo chown -R apache:apache /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 777 /var/www/html/storage
sudo chmod -R 777 /var/www/html/bootstrap/cache
```

## Accessing Your Application

- **Frontend:** `http://your-ec2-ip` or `https://yourdomain.com`
- **Admin Panel:** `http://your-ec2-ip/admin` or `https://yourdomain.com/admin`
- **Default Admin Credentials:**
  - Username: `admin`
  - Password: `password`
  - **⚠️ Change these immediately!**

## Troubleshooting

### Check Apache Status
```bash
sudo systemctl status httpd
sudo tail -f /var/log/httpd/error_log
```

### Check Application Logs
```bash
sudo tail -f /var/www/html/Files/core/storage/logs/laravel.log
```

### Restart Services
```bash
sudo systemctl restart httpd
sudo systemctl restart mariadb
```

### Check Database Connection
```bash
mysql -u servicehub -p'your_strong_password' -e "USE servicehub; SHOW TABLES;"
```

## Security Considerations

1. **Change Default Passwords:**
   - Database passwords
   - Admin account credentials
   - Any default API keys

2. **Firewall Configuration:**
   - Only allow necessary ports (80, 443, 22)
   - Consider using a WAF

3. **Regular Updates:**
   - Keep the system packages updated
   - Update the application regularly

4. **Backup Strategy:**
   - Set up regular database backups
   - Backup uploaded files
   - Test restore procedures

## Monitoring

### Set Up Log Monitoring
```bash
# Install log monitoring tools
sudo yum install -y logwatch

# Configure log rotation
sudo logrotate -f /etc/logrotate.conf
```

### Performance Monitoring
Consider setting up:
- CloudWatch monitoring
- Application performance monitoring (APM)
- Database performance monitoring

## Support

If you encounter issues:
1. Check the logs mentioned above
2. Verify all services are running
3. Check file permissions
4. Ensure all environment variables are set correctly

For additional help, refer to the ServiceHub documentation or create an issue in the repository.
