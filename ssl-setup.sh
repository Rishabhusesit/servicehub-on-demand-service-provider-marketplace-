#!/bin/bash

# SSL Certificate Setup Script for ServiceHub
# Run this after configuring your domain

set -e

echo "🔒 Setting up SSL certificate for ServiceHub..."

# Check if domain is provided
if [ -z "$1" ]; then
    echo "❌ Please provide your domain name as an argument"
    echo "Usage: ./ssl-setup.sh yourdomain.com"
    exit 1
fi

DOMAIN=$1

# Update Apache configuration with domain
echo "🌐 Updating Apache configuration..."
sudo sed -i "s/your-domain.com/$DOMAIN/g" /etc/httpd/conf.d/servicehub.conf
sudo sed -i "s/www.your-domain.com/www.$DOMAIN/g" /etc/httpd/conf.d/servicehub.conf

# Restart Apache
echo "🔄 Restarting Apache..."
sudo systemctl restart httpd

# Get SSL certificate
echo "🔐 Obtaining SSL certificate..."
sudo certbot --apache -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email admin@$DOMAIN

# Set up auto-renewal
echo "⏰ Setting up certificate auto-renewal..."
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -

# Update .env file with HTTPS URL
echo "📝 Updating .env file with HTTPS URL..."
sudo sed -i "s|APP_URL=http://your-domain.com|APP_URL=https://$DOMAIN|g" /var/www/html/Files/core/.env

# Clear caches
echo "🧹 Clearing caches..."
cd /var/www/html/Files/core
sudo -u apache php artisan config:clear
sudo -u apache php artisan cache:clear

echo "✅ SSL setup completed!"
echo "🌐 Your application is now accessible at: https://$DOMAIN"
echo "🔒 SSL certificate will auto-renew every 90 days"
