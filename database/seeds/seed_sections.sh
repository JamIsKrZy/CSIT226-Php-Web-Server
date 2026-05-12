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

echo "Seeding sections..."

declare -a sections=(
  "CS101|CS101-A|MWF 08:00-09:00|Room 101|50|35|Dr. Smith|1st Semester"
  "CS101|CS101-B|MWF 10:00-11:00|Room 102|50|42|Prof. Johnson|1st Semester"
  "CS201|CS201-A|TTh 09:00-10:30|Room 201|40|28|Dr. Anderson|1st Semester"
  "CS201|CS201-B|TTh 14:00-15:30|Room 202|40|35|Prof. Davis|1st Semester"
  "CS301|CS301-A|MWF 11:00-12:00|Lab 301|30|22|Dr. Wilson|1st Semester"
  "CS401|CS401-A|TTh 16:00-17:30|Room 303|35|28|Prof. Martinez|1st Semester"
  "IT101|IT101-A|MWF 13:00-14:00|Room 104|50|38|Dr. Brown|1st Semester"
  "IT201|IT201-A|TTh 11:00-12:30|Lab 201|35|25|Prof. Taylor|1st Semester"
  "IT301|IT301-A|MWF 15:00-16:00|Room 305|40|18|Dr. Lee|1st Semester"
  "IT401|IT401-A|TTh 13:00-14:30|Room 306|30|20|Prof. Garcia|1st Semester"
)

for line in "${sections[@]}"; do
  IFS='|' read -r courseCode sectionCode timeslot room capacity enrolledCount instructor semester <<< "$line"
  courseCodeEscaped=$(sql_quote "$courseCode")
  sectionCodeEscaped=$(sql_quote "$sectionCode")

  # Get courseID
  courseID=$(mysql_exec "SELECT courseID FROM Course WHERE courseCode = '$courseCodeEscaped' LIMIT 1;" | tail -1)
  if [[ -z "$courseID" || "$courseID" == "courseID" ]]; then
    echo "  ERROR: course $courseCode not found. Seed courses first."
    exit 1
  fi

  existing=$(mysql_exec "SELECT sectionID FROM Section WHERE sectionCode = '$sectionCodeEscaped' LIMIT 1;" | tail -1)
  if [[ -n "$existing" && "$existing" != "sectionID" ]]; then
    echo "  Skipping existing section $sectionCode"
    continue
  fi

  timeslotEscaped=$(sql_quote "$timeslot")
  roomEscaped=$(sql_quote "$room")
  instructorEscaped=$(sql_quote "$instructor")
  semesterEscaped=$(sql_quote "$semester")

  mysql_exec "INSERT INTO Section (courseID, sectionCode, timeslot, room, capacity, enrolledCount, instructor, semester) 
  VALUES ($courseID, '$sectionCodeEscaped', '$timeslotEscaped', '$roomEscaped', $capacity, $enrolledCount, '$instructorEscaped', '$semesterEscaped');" > /dev/null || {
    echo "  ERROR: Failed to insert section $sectionCode"
    exit 1
  }
  echo "  Inserted section $sectionCode"
done

echo "Sections seed complete."
