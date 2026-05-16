#!/bin/bash
set -euo pipefail

# Database Setup Script
# This script runs migrations and the shell seed scripts.
# Use it inside the app container or via docker-compose exec.

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DB_HOST=${DB_HOST:-db}
DB_USER=${DB_USER:-myuser}
DB_PASSWORD=${DB_PASSWORD:-mypassword}
DB_NAME=${DB_NAME:-mydb}

echo "================================"
echo "Database Setup Script"
echo "================================"

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

echo ""
echo "Running migrations..."
echo SCRIPTS_DIR="$SCRIPT_DIR/migration"
MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" < "$SCRIPT_DIR/migration/001_create_users_table.sql"
if [ $? -eq 0 ]; then
  echo "✓ Migrations completed successfully"
else
  echo "✗ Migrations failed"
  exit 1
fi

echo ""
echo "Running seeds..."

echo "Loading users data..."
chmod +x "$SCRIPT_DIR/seeds/seed_users.sh"
"$SCRIPT_DIR/seeds/seed_users.sh"
if [ $? -eq 0 ]; then
  echo "  ✓ Users seed loaded"
else
  echo "  ✗ Users seed failed"
  exit 1
fi

echo "Loading courses data..."
chmod +x "$SCRIPT_DIR/seeds/seed_courses.sh"
"$SCRIPT_DIR/seeds/seed_courses.sh"
if [ $? -eq 0 ]; then
  echo "  ✓ Courses seed loaded"
else
  echo "  ✗ Courses seed failed"
  exit 1
fi

echo "Loading sections data..."
chmod +x "$SCRIPT_DIR/seeds/seed_sections.sh"
"$SCRIPT_DIR/seeds/seed_sections.sh"
if [ $? -eq 0 ]; then
  echo "  ✓ Sections seed loaded"
else
  echo "  ✗ Sections seed failed"
  exit 1
fi

echo "Loading schedules data..."
chmod +x "$SCRIPT_DIR/seeds/seed_schedules.sh"
"$SCRIPT_DIR/seeds/seed_schedules.sh"
if [ $? -eq 0 ]; then
  echo "  ✓ Schedules seed loaded"
else
  echo "  ✗ Schedules seed failed"
  exit 1
fi

echo "Loading enrollment updates data..."
chmod +x "$SCRIPT_DIR/seeds/seed_updates.sh"
"$SCRIPT_DIR/seeds/seed_updates.sh"
if [ $? -eq 0 ]; then
  echo "  ✓ Enrollment updates seed loaded"
else
  echo "  ✗ Enrollment updates seed failed"
  exit 1
fi

echo "Loading notifications data..."
chmod +x "$SCRIPT_DIR/seeds/seed_notifications.sh"
"$SCRIPT_DIR/seeds/seed_notifications.sh"
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
echo ""
echo "Demo Credentials:"
echo "Email: demo@example.com"
echo "Password: password123"
echo ""
echo "Access the application at: http://localhost:8000"
echo "Login page: http://localhost:8000/login"
echo "Users page: http://localhost:8000/users"
echo ""
