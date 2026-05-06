#!/bin/bash

# The URL where your handleSignup logic is routed
URL="http://localhost:80/signup"

# Array of users to seed
# Format: "first_name:last_name:email"
USERS=(
    "Demo:User:demo@example.com"
    "John:Doe:john.doe@example.com"
    "Jane:Smith:jane.smith@example.com"
    "Bob:Wilson:bob.wilson@example.com"
    "Alice:Johnson:alice.johnson@example.com"
)

echo "Starting user seeding to $URL..."

for USER in "${USERS[@]}"; do
    # Split the string into variables
    IFS=":" read -r FNAME LNAME EMAIL <<< "$USER"
    
    echo "Registering $FNAME $LNAME ($EMAIL)..."

    # Send the POST request
    # -L follows redirects (since your controller uses header('Location: ...'))
    # -d sends the form data
    curl -X POST -L "$URL" \
        -d "first_name=$FNAME" \
        -d "last_name=$LNAME" \
        -d "email=$EMAIL" \
        -d "password=password123" \
        -d "confirm_password=password123" \
        -d "signup=true"

    echo -e "\n--------------------------"
done

echo "Seeding complete!"