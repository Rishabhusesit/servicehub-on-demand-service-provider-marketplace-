#!/bin/bash

# ServiceHub Deployment Script for EC2
# Run this script on your EC2 instance via Session Manager

set -e

echo "🚀 Starting ServiceHub Deployment on EC2..."

# Update system packages
echo "📦 Updating system packages..."
sudo yum update -y

# Install required packages
echo "🔧 Installing required packages..."
sudo yum install -y httpd php php-mysqlnd php-json php-mbstring php-xml php-zip php-curl php-gd php-intl php-soap php-bcmath php-ldap git unzip

# Install Composer
echo "📥 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js and NPM
echo "📦 Installing Node.js..."
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Install MariaDB
echo "🗄️ Installing MariaDB..."
sudo yum install -y mariadb-server mariadb
sudo systemctl start mariadb
sudo systemctl enable mariadb

# Secure MariaDB installation
echo "🔒 Securing MariaDB..."
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
echo "🗃️ Creating database..."
mysql -u root -p'your_strong_password' <<EOF
CREATE DATABASE servicehub;
CREATE USER 'servicehub'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON servicehub.* TO 'servicehub'@'localhost';
FLUSH PRIVILEGES;
EOF

# Clone the repository
echo "📥 Cloning repository..."
cd /var/www
sudo git clone https://github.com/Rishabhusesit/servicehub-on-demand-service-provider-marketplace-.git html
cd html

# Set proper permissions
echo "🔐 Setting permissions..."
sudo chown -R apache:apache /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 777 /var/www/html/storage
sudo chmod -R 777 /var/www/html/bootstrap/cache

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
cd Files/core
sudo -u apache composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
sudo -u apache npm install
sudo -u apache npm run production

# Configure Apache
echo "🌐 Configuring Apache..."
sudo tee /etc/httpd/conf.d/servicehub.conf > /dev/null <<EOF
<VirtualHost *:80>
    DocumentRoot /var/www/html
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog /var/log/httpd/servicehub_error.log
    CustomLog /var/log/httpd/servicehub_access.log combined
</VirtualHost>
EOF

# Enable mod_rewrite
sudo sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /etc/httpd/conf/httpd.conf

# Start and enable Apache
echo "🚀 Starting Apache..."
sudo systemctl start httpd
sudo systemctl enable httpd

# Configure firewall
echo "🔥 Configuring firewall..."
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload

# Install Certbot for SSL
echo "🔒 Installing Certbot for SSL..."
sudo yum install -y epel-release
sudo yum install -y certbot python3-certbot-apache

echo "✅ Deployment completed successfully!"
echo "🌐 Your application should be accessible at: http://your-ec2-public-ip"
echo "📝 Next steps:"
echo "   1. Update your domain name in /etc/httpd/conf.d/servicehub.conf"
echo "   2. Run: sudo certbot --apache to get SSL certificate"
echo "   3. Configure your .env file in /var/www/html/Files/core"
echo "   4. Import the database from Files/install/database.sql"
