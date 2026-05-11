#!/bin/bash

# Database Setup Script
# This script sets up the database by running migrations and seeds

echo "================================"
echo "Database Setup Script"
echo "================================"

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
sleep 5

# Get credentials from environment or use defaults
DB_HOST=${DB_HOST:-db}
DB_USER=${DB_USER:-myuser}
DB_PASSWORD=${DB_PASSWORD:-mypassword}
DB_NAME=${DB_NAME:-mydb}

echo "Connecting to MySQL at $DB_HOST..."

# Run migrations
echo ""
echo "Running migrations..."
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/migration/001_create_users_table.sql
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/migration/002_update_schema.sql

if [ $? -eq 0 ]; then
    echo "✓ Migrations completed successfully"
else
    echo "✗ Migrations failed"
    exit 1
fi

# Run seeds
echo ""
echo "Running seeds..."
# MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/seeds/users_seed.sql
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/seeds/admin_and_updates_seed.sql

if [ $? -eq 0 ]; then
    echo "✓ Seeds completed successfully"
else
    echo "✗ Seeds failed"
    exit 1
fi

echo ""
echo "================================"
echo "Setup Complete!"
echo "================================"
echo ""
echo "Demo Credentials:"
echo "Email: demo@example.com"
echo "Password: password123"
echo ""
echo "Access the application at: http://localhost:8000"
echo "Login page: http://localhost:8000/login"
echo "Users page: http://localhost:8000/users"
echo ""
