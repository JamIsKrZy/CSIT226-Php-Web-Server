#!/bin/bash
set -euo pipefail

DB_HOST=${DB_HOST:-db}
DB_USER=${DB_USER:-myuser}
DB_PASSWORD=${DB_PASSWORD:-mypassword}
DB_NAME=${DB_NAME:-mydb}

PASSWORD="password123"

# Execute SQL with error checking
mysql_exec() {
  local query="$1"
  local output
  output=$(MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" -e "$query" 2>&1) || {
    echo "ERROR: MySQL query failed: $query"
    echo "Error details: $output"
    return 1
  }
  echo "$output"
}

sql_quote() {
  printf '%s' "$1" | sed "s/'/''/g"
}

echo "Seeding users..."

# Student users
declare -a students=(
  "John|Doe|john.doe@university.edu|STU001|BSCS|2|25|Computer Science"
  "Jane|Smith|jane.smith@university.edu|STU002|BSIT|2|30|Information Technology"
  "Bob|Wilson|bob.wilson@university.edu|STU003|BSCS|1|20|Computer Science"
  "Alice|Johnson|alice.johnson@university.edu|STU004|BSIT|3|35|Information Technology"
  "Charlie|Brown|charlie.brown@university.edu|STU005|BSCS|1|28|Computer Science"
)

for line in "${students[@]}"; do
  IFS='|' read -r firstName lastName email studentNumber program yearLevel points major <<< "$line"
  
  emailEscaped=$(sql_quote "$email")
  firstNameEscaped=$(sql_quote "$firstName")
  lastNameEscaped=$(sql_quote "$lastName")
  programEscaped=$(sql_quote "$program")
  majorEscaped=$(sql_quote "$major")
  studentNumberEscaped=$(sql_quote "$studentNumber")
  
  # Check if student record already exists
  studentExists=$(mysql_exec "SELECT studentID FROM Student WHERE studentNumber = '$studentNumberEscaped' LIMIT 1;" | tail -1)
  if [[ -n "$studentExists" && "$studentExists" != "studentID" ]]; then
    echo "  Skipping existing student $studentNumber (studentID $studentExists)"
    continue
  fi
  
  # Check if user exists
  userID=$(mysql_exec "SELECT userID FROM User WHERE email = '$emailEscaped' LIMIT 1;" | tail -1)
  
  if [[ -z "$userID" || "$userID" == "userID" ]]; then
    # User doesn't exist, create both User and Student
    passwordHash=$(php -r "echo password_hash(\$argv[1], PASSWORD_BCRYPT);" "$PASSWORD") || {
      echo "ERROR: Failed to hash password"
      exit 1
    }
    passwordHashEscaped=$(sql_quote "$passwordHash")
    
    query="
      START TRANSACTION;
      INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
      VALUES ('$firstNameEscaped', '$lastNameEscaped', '$emailEscaped', '$passwordHashEscaped', 2026, 'student', 'active');
      SET @lastUserID = LAST_INSERT_ID();
      INSERT INTO Student (userID, studentNumber, program, yearLevel, points, major) 
      VALUES (@lastUserID, '$studentNumberEscaped', '$programEscaped', $yearLevel, $points, '$majorEscaped');
      COMMIT;
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Inserted student $email (student number $studentNumber)"
    else
      echo "  ERROR: Failed to insert student $email"
      exit 1
    fi
  else
    # User exists but Student record doesn't, create Student only
    query="
      INSERT INTO Student (userID, studentNumber, program, yearLevel, points, major) 
      VALUES ($userID, '$studentNumberEscaped', '$programEscaped', $yearLevel, $points, '$majorEscaped');
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Added student record for existing user $email (student number $studentNumber)"
    else
      echo "  ERROR: Failed to add student record for $email"
      exit 1
    fi
  fi
done

echo "Seeding admins..."

# Admin users
declare -a admins=(
  "Dr.|Anderson|admin.anderson@university.edu|ADM001|Registrar|Enrollment Services|Director"
  "Ms.|White|admin.white@university.edu|ADM002|Department|Academic Affairs|Coordinator"
)

for line in "${admins[@]}"; do
  IFS='|' read -r firstName lastName email adminCode role department designation <<< "$line"
  
  emailEscaped=$(sql_quote "$email")
  firstNameEscaped=$(sql_quote "$firstName")
  lastNameEscaped=$(sql_quote "$lastName")
  adminCodeEscaped=$(sql_quote "$adminCode")
  roleEscaped=$(sql_quote "$role")
  departmentEscaped=$(sql_quote "$department")
  designationEscaped=$(sql_quote "$designation")
  
  # Check if admin record already exists
  adminExists=$(mysql_exec "SELECT adminID FROM Admin WHERE adminCode = '$adminCodeEscaped' LIMIT 1;" | tail -1)
  if [[ -n "$adminExists" && "$adminExists" != "adminID" ]]; then
    echo "  Skipping existing admin $adminCode (adminID $adminExists)"
    continue
  fi
  
  # Check if user exists
  userID=$(mysql_exec "SELECT userID FROM User WHERE email = '$emailEscaped' LIMIT 1;" | tail -1)
  
  if [[ -z "$userID" || "$userID" == "userID" ]]; then
    # User doesn't exist, create both User and Admin
    passwordHash=$(php -r "echo password_hash(\$argv[1], PASSWORD_BCRYPT);" "$PASSWORD") || {
      echo "ERROR: Failed to hash password"
      exit 1
    }
    passwordHashEscaped=$(sql_quote "$passwordHash")
    
    query="
      START TRANSACTION;
      INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
      VALUES ('$firstNameEscaped', '$lastNameEscaped', '$emailEscaped', '$passwordHashEscaped', 2026, 'admin', 'active');
      SET @lastUserID = LAST_INSERT_ID();
      INSERT INTO Admin (userID, adminCode, role, department, designation) 
      VALUES (@lastUserID, '$adminCodeEscaped', '$roleEscaped', '$departmentEscaped', '$designationEscaped');
      COMMIT;
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Inserted admin $email (admin code $adminCode)"
    else
      echo "  ERROR: Failed to insert admin $email"
      exit 1
    fi
  else
    # User exists but Admin record doesn't, create Admin only
    query="
      INSERT INTO Admin (userID, adminCode, role, department, designation) 
      VALUES ($userID, '$adminCodeEscaped', '$roleEscaped', '$departmentEscaped', '$designationEscaped');
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Added admin record for existing user $email (admin code $adminCode)"
    else
      echo "  ERROR: Failed to add admin record for $email"
      exit 1
    fi
  fi
done

echo "Users seeding complete."
