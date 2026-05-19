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

echo "Seeding schedules and planned items..."

# Create schedules for each student
declare -a schedules=(
  "26-0000-000|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-001|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-002|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-003|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-004|1st Semester|2026|draft|Initial enrollment plan"
)

for line in "${schedules[@]}"; do
  IFS='|' read -r studentNumber semester academicYear status notes <<< "$line"
  studentNumberEscaped=$(sql_quote "$studentNumber")
  semesterEscaped=$(sql_quote "$semester")
  notesEscaped=$(sql_quote "$notes")

  # Get studentID
  studentID=$(mysql_exec "SELECT studentID FROM Student WHERE studentNumber = '$studentNumberEscaped' LIMIT 1;" | tail -1)
  if [[ -z "$studentID" || "$studentID" == "studentID" ]]; then
    echo "  ERROR: student number $studentNumber not found. Seed users first."
    exit 1
  fi

  # Create schedule using transaction
  query="
    START TRANSACTION;
    INSERT INTO Schedule (studentID, semester, academicYear, status) 
    VALUES ($studentID, '$semesterEscaped', $academicYear, '$status');
    COMMIT;
  "
  
  mysql_exec "$query" > /dev/null || {
    echo "  ERROR: Failed to create schedule for student $studentNumber"
    exit 1
  }
  echo "  Created schedule for student $studentNumber"
done

# Add planned items (course registrations)
declare -a planned_items=(
  "26-0000-000|CS231-F1|1|planned"
  "26-0000-000|CS243-F1|2|planned"
  "26-0000-000|CSIT104-F1|3|planned"

  "26-0000-001|CS231-F2|1|planned"
  "26-0000-001|CS243-F2|2|planned"
  "26-0000-001|CSIT213-F2|3|planned"

  "26-0000-002|CSIT221-F1|1|planned"
  "26-0000-002|CSIT227-F1|2|planned"
  "26-0000-002|SDG031-F1|3|planned"

  "26-0000-003|CS231-F3|1|planned"
  "26-0000-003|CSIT221-F3|2|planned"
  "26-0000-003|PE205-F3|3|planned"

  "26-0000-004|CSIT227-F1|1|planned"
  "26-0000-004|SDG031-F1|2|planned"
  "26-0000-004|PE205-F1|3|planned"
)

for line in "${planned_items[@]}"; do
  IFS='|' read -r studentNumber sectionCode priority enrollmentStatus <<< "$line"
  studentNumberEscaped=$(sql_quote "$studentNumber")
  sectionCodeEscaped=$(sql_quote "$sectionCode")
  enrollmentStatusEscaped=$(sql_quote "$enrollmentStatus")

  # Get IDs
  studentID=$(mysql_exec "SELECT studentID FROM Student WHERE studentNumber = '$studentNumberEscaped' LIMIT 1;" | tail -1)
  if [[ -z "$studentID" || "$studentID" == "studentID" ]]; then
    echo "  ERROR: student number $studentNumber not found."
    exit 1
  fi

  scheduleID=$(mysql_exec "SELECT scheduleID FROM Schedule WHERE studentID = $studentID LIMIT 1;" | tail -1)
  if [[ -z "$scheduleID" || "$scheduleID" == "scheduleID" ]]; then
    echo "  ERROR: schedule missing for student $studentNumber"
    exit 1
  fi

  sectionID=$(mysql_exec "SELECT sectionID FROM Section WHERE sectionCode = '$sectionCodeEscaped' LIMIT 1;" | tail -1)
  if [[ -z "$sectionID" || "$sectionID" == "sectionID" ]]; then
    echo "  ERROR: section $sectionCode not found. Seed sections first."
    exit 1
  fi

  mysql_exec "INSERT INTO PlannedItem (scheduleID, sectionID, priority, enrollmentStatus) 
  VALUES ($scheduleID, $sectionID, $priority, '$enrollmentStatusEscaped');" > /dev/null || {
    echo "  ERROR: Failed to add planned item for $studentNumber / $sectionCode"
    exit 1
  }
  echo "  Added planned item $sectionCode for student $studentNumber"
done

echo "Schedules and planned items seed complete."
