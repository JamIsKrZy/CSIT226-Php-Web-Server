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
  "STU001|Enrollment|Your enrollment plan has been created|1"
  "STU001|Schedule|Your schedule is ready for review|0"
  "STU002|Enrollment|Enrollment deadline approaching|0"
  "STU002|Update|Course CS201 section A is now full|1"
  "STU003|Enrollment|Your enrollment plan has been created|0"
  "STU003|Schedule|Conflict detected in your schedule|0"
  "STU004|Enrollment|Your enrollment has been confirmed|1"
  "STU004|Schedule|New high-demand section available|0"
  "STU005|Enrollment|Waitlist notification for CS301-A|0"
  "STU005|Update|Course availability updated|1"
)

for line in "${notifications[@]}"; do
  IFS='|' read -r studentNumber type title message isRead <<< "$line"
  studentNumberEscaped=$(sql_quote "$studentNumber")
  typeEscaped=$(sql_quote "$type")
  titleEscaped=$(sql_quote "$title")
  messageEscaped=$(sql_quote "$message")

  # Get studentID
  studentID=$(mysql_exec "SELECT studentID FROM Student WHERE studentNumber = '$studentNumberEscaped' LIMIT 1;" | tail -1)
  if [[ -z "$studentID" || "$studentID" == "studentID" ]]; then
    echo "  ERROR: student number $studentNumber not found. Seed users first."
    exit 1
  fi

  mysql_exec "INSERT INTO Notification (type, title, message, isRead, studentID) 
  VALUES ('$typeEscaped', '$titleEscaped', '$messageEscaped', $isRead, $studentID);" > /dev/null || {
    echo "  ERROR: Failed to add notification for $studentNumber"
    exit 1
  }
  echo "  Added notification for $studentNumber: $title"
done

echo "Notifications seed complete."
