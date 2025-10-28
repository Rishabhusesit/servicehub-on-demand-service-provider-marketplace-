#!/bin/bash

# Environment Configuration Script for ServiceHub on Ubuntu
# Run this after the main deployment

set -e

echo "Setting up ServiceHub environment..."

# Create .env file
echo "Creating .env file..."
sudo tee /var/www/html/Files/core/.env > /dev/null <<EOF
APP_NAME="ServiceHub"
APP_ENV=production
APP_KEY=base64:$(openssl rand -base64 32)
APP_DEBUG=false
APP_URL=http://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=servicehub
DB_USERNAME=servicehub
DB_PASSWORD=your_strong_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"
EOF

# Set proper permissions
sudo chown www-data:www-data /var/www/html/Files/core/.env
sudo chmod 600 /var/www/html/Files/core/.env

# Generate application key
echo "Generating application key..."
cd /var/www/html/Files/core
sudo -u www-data php artisan key:generate

# Clear caches
echo "Clearing caches..."
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear

# Set up storage links
echo "Setting up storage links..."
sudo -u www-data php artisan storage:link

echo "Environment setup completed!"
echo "Please update the following in your .env file:"
echo "   - APP_URL with your actual domain"
echo "   - Database credentials if different"
echo "   - Mail settings for production"
echo "   - Any API keys for payment gateways"
