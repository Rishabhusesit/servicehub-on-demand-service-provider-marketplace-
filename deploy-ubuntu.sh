#!/bin/bash

# ServiceHub Deployment Script for Ubuntu EC2
# Run this script on your Ubuntu EC2 instance via Session Manager

set -e

echo "Starting ServiceHub Deployment on Ubuntu EC2..."

# Update system packages
echo "Updating system packages..."
sudo apt update -y
sudo apt upgrade -y

# Install required packages
echo "Installing required packages..."
sudo apt install -y apache2 php8.3 php8.3-mysql php8.3-json php8.3-mbstring php8.3-xml php8.3-zip php8.3-curl php8.3-gd php8.3-intl php8.3-soap php8.3-bcmath php8.3-ldap git unzip curl

# Enable Apache modules
echo "Enabling Apache modules..."
sudo a2enmod rewrite
sudo a2enmod php8.3

# Install Composer
echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js and NPM
echo "Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL
echo "Installing MySQL..."
sudo apt install -y mysql-server

# Start and enable MySQL
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL installation
echo "Securing MySQL..."
sudo mysql_secure_installation <<EOF

y
your_strong_password
your_strong_password
y
y
y
y
EOF

# Create database and user
echo "Creating database..."
sudo mysql -u root -p'your_strong_password' <<EOF
CREATE DATABASE servicehub;
CREATE USER 'servicehub'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON servicehub.* TO 'servicehub'@'localhost';
FLUSH PRIVILEGES;
EOF

# Clone the repository
echo "Cloning repository..."
cd /var/www
sudo git clone https://github.com/Rishabhusesit/servicehub-on-demand-service-provider-marketplace-.git html
cd html

# Set proper permissions
echo "Setting permissions..."
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 777 /var/www/html/storage
sudo chmod -R 777 /var/www/html/bootstrap/cache

# Install PHP dependencies
echo "Installing PHP dependencies..."
cd Files/core
sudo -u www-data composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
sudo -u www-data npm install
sudo -u www-data npm run production

# Configure Apache
echo "Configuring Apache..."
sudo tee /etc/apache2/sites-available/servicehub.conf > /dev/null <<EOF
<VirtualHost *:80>
    DocumentRoot /var/www/html
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/servicehub_error.log
    CustomLog \${APACHE_LOG_DIR}/servicehub_access.log combined
</VirtualHost>
EOF

# Enable the site
sudo a2ensite servicehub.conf
sudo a2dissite 000-default.conf

# Start and enable Apache
echo "Starting Apache..."
sudo systemctl restart apache2
sudo systemctl enable apache2

# Configure firewall
echo "Configuring firewall..."
sudo ufw allow 'Apache Full'
sudo ufw allow OpenSSH
sudo ufw --force enable

# Install Certbot for SSL
echo "Installing Certbot for SSL..."
sudo apt install -y certbot python3-certbot-apache

echo "Deployment completed successfully!"
echo "Your application should be accessible at: http://your-ec2-public-ip"
echo "Next steps:"
echo "   1. Update your domain name in /etc/apache2/sites-available/servicehub.conf"
echo "   2. Run: sudo certbot --apache to get SSL certificate"
echo "   3. Configure your .env file in /var/www/html/Files/core"
echo "   4. Import the database from Files/install/database.sql"
