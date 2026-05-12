#!/bin/bash

# The URL where your signup API/controller is routed
URL="http://localhost:80/signup"

# Shared password for all seeded users
PASSWORD="password123"

# Array of users to seed
# Format:
# "FirstName:LastName:Email:AcademicYear:UserType:Status"
USERS=(
    "John:Doe:john.doe@university.edu:2026:student:active"
    "Jane:Smith:jane.smith@university.edu:2026:student:active"
    "Bob:Wilson:bob.wilson@university.edu:2026:student:active"
    "Alice:Johnson:alice.johnson@university.edu:2026:student:active"
    "Charlie:Brown:charlie.brown@university.edu:2026:student:active"
)

echo "Starting user seeding to $URL..."

for USER in "${USERS[@]}"; do
    # Split values
    IFS=":" read -r FNAME LNAME EMAIL YEAR TYPE STATUS <<< "$USER"

    echo "Registering $FNAME $LNAME ($EMAIL)..."

    # Send POST request to your existing signup endpoint
    curl -X POST -L "$URL" \
        -d "first_name=$FNAME" \
        -d "last_name=$LNAME" \
        -d "email=$EMAIL" \
        -d "password=$PASSWORD" \
        -d "confirm_password=$PASSWORD" \
        -d "academic_year=$YEAR" \
        -d "user_type=$TYPE" \
        -d "status=$STATUS" \
        -d "signup=true"

    echo -e "\n--------------------------"
done

echo "Seeding complete!"