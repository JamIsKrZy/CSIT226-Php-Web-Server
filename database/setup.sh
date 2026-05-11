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

if [ $? -eq 0 ]; then
    echo "✓ Migrations completed successfully"
else
    echo "✗ Migrations failed"
    exit 1
fi

# Run seeds
echo ""
echo "Running seeds..."

# Load users seed (User, Student, Admin tables)
echo "Loading users data..."
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/seeds/users_seed.sql
if [ $? -eq 0 ]; then
    echo "  ✓ Users seed loaded"
else
    echo "  ✗ Users seed failed"
    exit 1
fi

# Load courses seed
echo "Loading courses data..."
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/seeds/courses_seed.sql
if [ $? -eq 0 ]; then
    echo "  ✓ Courses seed loaded"
else
    echo "  ✗ Courses seed failed"
    exit 1
fi

# Load sections seed
echo "Loading sections data..."
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/seeds/sections_seed.sql
if [ $? -eq 0 ]; then
    echo "  ✓ Sections seed loaded"
else
    echo "  ✗ Sections seed failed"
    exit 1
fi

# Load schedules and planned items seed
echo "Loading schedules data..."
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/seeds/schedules_seed.sql
if [ $? -eq 0 ]; then
    echo "  ✓ Schedules seed loaded"
else
    echo "  ✗ Schedules seed failed"
    exit 1
fi

# Load notifications seed
echo "Loading notifications data..."
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < /var/www/html/database/seeds/notifications_seed.sql
if [ $? -eq 0 ]; then
    echo "  ✓ Notifications seed loaded"
else
    echo "  ✗ Notifications seed failed"
    exit 1
fi

echo ""
echo "================================"
echo "✓ Database setup completed!"
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
