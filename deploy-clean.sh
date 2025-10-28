#!/bin/bash

set -e

echo "Starting ServiceHub Deployment on Ubuntu EC2..."

echo "Updating system packages..."
sudo apt update -y
sudo apt upgrade -y

echo "Installing required packages..."
sudo apt install -y apache2 php8.3 php8.3-mysql php8.3-mbstring php8.3-xml php8.3-zip php8.3-curl php8.3-gd php8.3-intl php8.3-soap php8.3-bcmath php8.3-ldap git unzip curl

echo "Enabling Apache modules..."
sudo a2enmod rewrite
sudo a2enmod php8.3

echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
sudo chmod +x /usr/local/bin/composer

echo "Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

echo "Installing MySQL..."
sudo apt install -y mysql-server

echo "Starting MySQL..."
sudo systemctl start mysql
sudo systemctl enable mysql

echo "Creating database..."
sudo mysql -e "CREATE DATABASE servicehub;"
sudo mysql -e "CREATE USER 'servicehub'@'localhost' IDENTIFIED BY 'your_strong_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON servicehub.* TO 'servicehub'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

echo "Cloning repository..."
cd /var/www
sudo git clone https://github.com/Rishabhusesit/servicehub-on-demand-service-provider-marketplace-.git html
cd html

echo "Setting permissions..."
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 777 /var/www/html/storage
sudo chmod -R 777 /var/www/html/bootstrap/cache

echo "Installing PHP dependencies..."
cd Files/core
sudo -u www-data composer install --no-dev --optimize-autoloader

echo "Installing Node.js dependencies..."
sudo -u www-data npm install
sudo -u www-data npm run production

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

echo "Enabling site..."
sudo a2ensite servicehub.conf
sudo a2dissite 000-default.conf

echo "Starting Apache..."
sudo systemctl restart apache2
sudo systemctl enable apache2

echo "Configuring firewall..."
sudo ufw allow 'Apache Full'
sudo ufw allow OpenSSH
sudo ufw --force enable

echo "Installing Certbot..."
sudo apt install -y certbot python3-certbot-apache

echo "Deployment completed successfully!"
echo "Your application should be accessible at: http://your-ec2-public-ip"
