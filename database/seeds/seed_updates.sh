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

echo "Seeding updates..."

declare -a updates=(
  "CSIT122 F1 - Section Full|Section F1 (Intermediate Programming II) is now at full capacity (40/40 students). Students are advised to consider Section F2 or F3.|Critical"
  "CSIT122 F2 - New Section Opened|Due to high demand, a new section F4 for CSIT122 has been opened. Enrollment starts tomorrow at 9 AM.|New"
  "MATH215 F2 - Room Change|The room for Discrete Mathematics Section F2 has been changed from Room 108 to Room 110. Please update your plotted schedule.|Advisory"
  "CSIT228 F3 - New Section Opened|A new block (Section F3) for Database Management Systems has been opened to accommodate high demand. Schedule: TTH 3:00-4:30 PM.|New"
)

for line in "${updates[@]}"; do
  IFS='|' read -r title description status isRead <<< "$line"

  titleEscaped=$(sql_quote "$title")
  descriptionEscaped=$(sql_quote "$description")
  typeEscaped=$(sql_quote "$status")

  # SINGLE QUERY DESIGN:
  # We use INSERT INTO ... SELECT to find the studentID on the fly.
  query="
    INSERT INTO enrollmentUpdates (title, description, status)
    VALUES
        ('$titleEscaped', '$descriptionEscaped', '$typeEscaped')
  "

  if mysql_exec "$query" > /dev/null 2>&1; then
     # Note: This checks if the SQL command succeeded, 
     # but you might want to check row counts if the student number doesn't exist.
     echo "  Successfully processed update for $title"
  else
     echo "  ERROR: Failed to insert update for $title"
     exit 1
  fi
done

echo "Enrollment updates seed complete."
