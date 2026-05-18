#!/bin/bash
set -euo pipefail

DB_HOST=${DB_HOST:-db}
DB_USER=${DB_USER:-myuser}
DB_PASSWORD=${DB_PASSWORD:-mypassword}
DB_NAME=${DB_NAME:-mydb}

mysql_exec() {
  local query="$1"
  MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" -e "$query" 2>&1
}

sql_quote() {
  printf '%s' "$1" | sed "s/'/''/g"
}

echo "Seeding notifications..."

declare -a notifications=(
  "26-0000-000|Enrollment|Your enrollment plan has been created|1"
  "26-0000-000|Schedule|Your schedule is ready for review|0"
  "26-0000-001|Enrollment|Enrollment deadline approaching|0"
  "26-0000-001|Update|Course CS231 section F1 is now full|1"
  "26-0000-002|Enrollment|Your enrollment plan has been created|0"
  "26-0000-002|Schedule|Conflict detected in your schedule|0"
  "26-0000-003|Enrollment|Your enrollment has been confirmed|1"
  "26-0000-003|Schedule|New high-demand section available|0"
  "26-0000-004|Enrollment|Waitlist notification for CS231-F1|0"
  "26-0000-004|Update|Course availability updated|1"
)

for line in "${notifications[@]}"; do
  IFS='|' read -r studentNum type title isRead <<< "$line"

  # Escape inputs
  studentNumEscaped=$(sql_quote "$studentNum")
  typeEscaped=$(sql_quote "$type")
  titleEscaped=$(sql_quote "$title")

  # SINGLE QUERY DESIGN:
  # We use INSERT INTO ... SELECT to find the studentID on the fly.
  query="
    INSERT INTO Notification (type, title, message, isRead, studentID)
    SELECT 
        '$typeEscaped', 
        '$titleEscaped', 
        '$titleEscaped', -- Using title as message for example
        $isRead, 
        studentID 
    FROM Student 
    WHERE studentNumber = '$studentNumEscaped' 
    LIMIT 1;
  "

  if mysql_exec "$query" > /dev/null 2>&1; then
     # Note: This checks if the SQL command succeeded, 
     # but you might want to check row counts if the student number doesn't exist.
     echo "  Successfully processed notification for $studentNum"
  else
     echo "  ERROR: Failed to insert notification for $studentNum"
     exit 1
  fi
done

echo "Notifications seed complete."
