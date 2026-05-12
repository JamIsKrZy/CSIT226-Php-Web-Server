# Database Seeding Guide

This document explains how to seed the database with sample data in a Docker environment.

## Prerequisites

- Docker and Docker Compose running
- Containers started with `docker-compose up -d`
- MySQL container is healthy

## Seeding the Database

### Option 1: Using the Docker Wrapper Script (Recommended)

From your project root directory:

```bash
./docker-run-db-setup.sh
```

This script will:
1. Wait for MySQL to be ready
2. Run migrations (create tables)
3. Seed users (students + admins)
4. Seed courses
5. Seed sections
6. Seed schedules and planned items
7. Seed notifications

### Option 2: Manual Docker Exec

If you prefer to run it directly:

```bash
docker-compose exec app bash -lc "cd /var/www/html && ./database/setup.sh"
```

### Option 3: Individual Seed Scripts

You can run specific seed scripts individually:

```bash
# Seed only users
docker-compose exec app bash -lc "cd /var/www/html && ./database/seeds/seed_users.sh"

# Seed only courses
docker-compose exec app bash -lc "cd /var/www/html && ./database/seeds/seed_courses.sh"

# Seed only sections
docker-compose exec app bash -lc "cd /var/www/html && ./database/seeds/seed_sections.sh"

# Seed schedules and planned items
docker-compose exec app bash -lc "cd /var/www/html && ./database/seeds/seed_schedules.sh"

# Seed notifications
docker-compose exec app bash -lc "cd /var/www/html && ./database/seeds/seed_notifications.sh"
```

## What Gets Seeded

### Users (5 Students + 2 Admins)

**Students:**
- John Doe (john.doe@university.edu) - STU001
- Jane Smith (jane.smith@university.edu) - STU002
- Bob Wilson (bob.wilson@university.edu) - STU003
- Alice Johnson (alice.johnson@university.edu) - STU004
- Charlie Brown (charlie.brown@university.edu) - STU005

**Admins:**
- Dr. Anderson (admin.anderson@university.edu) - ADM001
- Ms. White (admin.white@university.edu) - ADM002

**Default Password:** `password123`

### Courses (8 Courses)

Computer Science:
- CS101: Introduction to Computer Science
- CS201: Data Structures
- CS301: Database Systems
- CS401: Web Development

Information Technology:
- IT101: Information Technology Fundamentals
- IT201: Network Administration
- IT301: Cybersecurity
- IT401: Cloud Computing

### Sections (10 Sections)

Each course has 1-2 sections with different timeslots and instructors.

### Schedules & Planned Items

Each student gets:
- 1 schedule for "1st Semester 2026"
- 3 planned course sections with priorities and commitment levels

### Notifications

Each student gets 2 notifications of different types (Enrollment, Schedule, Update).

## Troubleshooting

### Error: "Cannot add or update a child row: a foreign key constraint fails"

This means a parent record doesn't exist. Make sure you run seeders in order:
1. `seed_users.sh`
2. `seed_courses.sh`
3. `seed_sections.sh`
4. `seed_schedules.sh`
5. `seed_notifications.sh`

Or use the main `database/setup.sh` which handles ordering automatically.

### Error: "MySQL did not become ready in time"

The database container is still starting. Wait a few more seconds and retry:

```bash
sleep 5
./docker-run-db-setup.sh
```

### Error: "userID = 0" or "courseID = 0"

This was a bug in earlier versions. All scripts have been updated to use transactions and proper error handling. If you still see this, ensure your seed scripts are up to date.

## Manual Seeding via API

You can also seed data manually using the REST API endpoints:

```bash
# Create a course
curl -X POST http://localhost:8000/api/courses \
  -H "Content-Type: application/json" \
  -d '{
    "courseCode": "CS501",
    "courseName": "Advanced Topics",
    "credits": 3,
    "category": "Elective",
    "department": "Computer Science"
  }'

# Create a section
curl -X POST http://localhost:8000/api/sections \
  -H "Content-Type: application/json" \
  -d '{
    "courseID": 1,
    "sectionCode": "CS501-A",
    "timeslot": "MWF 09:00-10:00",
    "room": "Room 401",
    "capacity": 40,
    "instructor": "Dr. Expert",
    "semester": "2nd Semester"
  }'
```

See the API routes in [routes/web.php](routes/web.php) for a complete list of endpoints.

## Demo Credentials

After seeding, you can log in with:

- **Email:** john.doe@university.edu
- **Password:** password123

Or admin:

- **Email:** admin.anderson@university.edu
- **Password:** password123

## Re-seeding

If you need to clear and re-seed:

```bash
# Stop containers
docker-compose down

# Remove the MySQL volume to clear data
docker volume rm <project>_mysql-data

# Start containers fresh
docker-compose up -d

# Seed the database
./docker-run-db-setup.sh
```

Replace `<project>` with your actual project folder name (e.g., `csit226` or similar).
