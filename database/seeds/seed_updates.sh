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
mysql_exec "TRUNCATE TABLE enrollmentUpdates;"

declare -a updates=(
  "CSIT221 F1 - Section Full|Section F1 (Data Structures and Algorithms) is now at full capacity (40/40 students). Students are advised to consider Section F2 or F3.|Critical"
  "CSIT227 F2 - Section Full|Section F2 (Object-oriented Programming 1) is now at full capacity (40/40 students). Students are advised to consider Section F1 or F3.|Critical"
  "CS231 F2 - Room Change|The room for Discrete Structures 2 Section F2 has been changed from Room 201 to Room 205. Please update your plotted schedule.|Advisory"
  "PE205 F3 - Section Schedule Update|A schedule update for PATHFit 3 has occurred. Section F3 schedule is now TTh 14:30-15:30.|New"
  "CS243 F1 - Section Full|Section F1 (Computer Architecture and Organization) is now at full capacity. Please check other sections.|Critical"
  "CSIT104 F2 - Instructor Update|Section F2 for Introduction to Computing has a new instructor: Dr. Alan Turing.|New"
  "CSIT213 F3 - Time Slot Adjustment|Section F3 for Applications Development and Emerging Technologies has been moved to MWF 16:00-17:00.|Advisory"
  "SDG031 F1 - Section Added|A new section F2 has been created for Social Development Goals to accommodate extra students.|New"
  "Enrollment Deadline Extended|CIT pre-enrollment deadline has been extended to Friday, June 5th to allow all students to finalize their backup plans.|Advisory"
  "CSIT227 F1 - Room Change|Section F1 of Object-oriented Programming 1 has been moved to Lab 402 for state-of-the-art workstation support.|New"
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
