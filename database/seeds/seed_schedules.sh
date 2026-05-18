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
  "STU001|1st Semester|2026|draft|Initial enrollment plan"
  "STU002|1st Semester|2026|draft|Initial enrollment plan"
  "STU003|1st Semester|2026|draft|Initial enrollment plan"
  "STU004|1st Semester|2026|draft|Initial enrollment plan"
  "STU005|1st Semester|2026|draft|Initial enrollment plan"
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
    INSERT INTO Schedule (studentID, semester, academicYear, status, notes) 
    VALUES ($studentID, '$semesterEscaped', $academicYear, '$status', '$notesEscaped');
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
  "STU001|CS101-A-S1|8|1|planned"
  "STU001|CS201-A-S1|9|2|planned"
  "STU001|CS401-A-S1|7|3|planned"
  "STU002|CS101-B-S1|9|1|planned"
  "STU002|CS201-B-S1|8|2|planned"
  "STU002|IT101-A-S1|6|3|planned"
  "STU003|CS101-A-S1|7|1|planned"
  "STU003|IT201-A-S1|8|2|planned"
  "STU003|IT301-A-S1|5|3|planned"
  "STU004|CS101-B-S1|9|1|planned"
  "STU004|IT201-A-S1|7|2|planned"
  "STU004|IT401-A-S1|8|3|planned"
  "STU005|CS201-A-S1|6|1|planned"
  "STU005|CS401-A-S1|9|2|planned"
  "STU005|IT301-A-S1|7|3|planned"
)

for line in "${planned_items[@]}"; do
  IFS='|' read -r studentNumber sectionCode commitmentLevel priority enrollmentStatus <<< "$line"
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

  mysql_exec "INSERT INTO PlannedItem (scheduleID, sectionID, commitmentLevel, priority, enrollmentStatus) 
  VALUES ($scheduleID, $sectionID, $commitmentLevel, $priority, '$enrollmentStatusEscaped');" > /dev/null || {
    echo "  ERROR: Failed to add planned item for $studentNumber / $sectionCode"
    exit 1
  }
  echo "  Added planned item $sectionCode for student $studentNumber"
done

echo "Schedules and planned items seed complete."
