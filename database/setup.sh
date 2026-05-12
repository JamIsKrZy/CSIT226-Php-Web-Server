#!/bin/bash

# Database Setup Script
# This script sets up the database by running migrations and seeds

echo "================================"
echo "Database Setup Script"
echo "================================"



# Get credentials from environment or use defaults
DB_HOST=${DB_HOST:-db}
DB_USER=${DB_USER:-myuser}
DB_PASSWORD=${DB_PASSWORD:-mypassword}
DB_NAME=${DB_NAME:-mydb}

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
MAX_TRIES=30
COUNT=0
until MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl -e "SELECT 1" "$DB_NAME" > /dev/null 2>&1; do
  COUNT=$((COUNT + 1))
  if [ $COUNT -ge $MAX_TRIES ]; then
    echo "✗ MySQL did not become ready in time"
    exit 1
  fi
  echo "  MySQL not ready yet, retrying in 2s... ($COUNT/$MAX_TRIES)"
  sleep 2
done
echo "✓ MySQL is ready"

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
chmod +x /var/www/html/database/seeds/seed_users.sh
source /var/www/html/database/seeds/seed_users.sh
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
