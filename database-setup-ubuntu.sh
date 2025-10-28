#!/bin/bash

# Database Setup Script for ServiceHub on Ubuntu
# Run this to import the database and set up initial data

set -e

echo "Setting up ServiceHub database..."

# Check if database exists
if ! mysql -u root -p'your_strong_password' -e "USE servicehub;" 2>/dev/null; then
    echo "Database 'servicehub' does not exist. Please run deploy-ubuntu.sh first."
    exit 1
fi

# Import database
echo "Importing database..."
cd /var/www/html/Files/install
mysql -u servicehub -p'your_strong_password' servicehub < database.sql

# Run migrations
echo "Running migrations..."
cd /var/www/html/Files/core
sudo -u www-data php artisan migrate --force

# Seed database (if needed)
echo "Seeding database..."
sudo -u www-data php artisan db:seed --force

# Set up admin user (optional)
echo "Setting up admin user..."
mysql -u servicehub -p'your_strong_password' servicehub <<EOF
-- Update admin credentials (change these!)
UPDATE admins SET 
    username = 'admin',
    email = 'admin@yourdomain.com',
    password = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' -- password
WHERE id = 1;
EOF

echo "Database setup completed!"
echo "Default admin credentials:"
echo "   Username: admin"
echo "   Password: password"
echo "   Please change these credentials immediately!"
