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
  # CS231 Sections (Discrete Structures 2)
  "CS231|CS231-F1|MWF 08:00-09:00|Room 201|40|0|Dr. Reyes|1st Semester"
  "CS231|CS231-F2|MWF 13:00-14:00|Room 201|40|0|Dr. Reyes|1st Semester"
  "CS231|CS231-F3|MWF 10:00-11:00|Room 201|40|0|Dr. Reyes|1st Semester"

  # CS243 Sections (Computer Organization and Architecture)
  "CS243|CS243-F1|MWF 09:00-10:00|Room 202|40|0|Prof. Cruz|1st Semester"
  "CS243|CS243-F2|MWF 14:00-15:00|Room 202|40|0|Prof. Cruz|1st Semester"
  "CS243|CS243-F3|MWF 11:00-12:00|Room 202|40|0|Prof. Cruz|1st Semester"

  # CSIT104 Sections (Platform-based Development 1)
  "CSIT104|CSIT104-F1|MW 10:00-11:00|Lab 301|40|0|Prof. Valdez|1st Semester"
  "CSIT104|CSIT104-F2|MW 15:00-16:00|Lab 301|40|0|Prof. Valdez|1st Semester"
  "CSIT104|CSIT104-F3|MW 08:00-09:00|Lab 301|40|0|Prof. Valdez|1st Semester"

  # CSIT213 Sections (Social Issues and Professional Practice)
  "CSIT213|CSIT213-F1|MWF 11:00-12:00|Room 203|40|0|Dr. Lim|1st Semester"
  "CSIT213|CSIT213-F2|MWF 16:00-17:00|Room 203|40|0|Dr. Lim|1st Semester"
  "CSIT213|CSIT213-F3|MWF 09:00-10:00|Room 203|40|0|Dr. Lim|1st Semester"

  # CSIT221 Sections (Data Structures and Algorithms)
  "CSIT221|CSIT221-F1|TTh 08:00-09:30|Lab 302|40|0|Prof. Garcia|1st Semester"
  "CSIT221|CSIT221-F2|TTh 13:00-14:30|Lab 302|40|0|Prof. Garcia|1st Semester"
  "CSIT221|CSIT221-F3|TTh 09:30-11:00|Lab 302|40|0|Prof. Garcia|1st Semester"

  # CSIT227 Sections (Object-oriented Programming 1)
  "CSIT227|CSIT227-F1|TTh 09:30-11:00|Lab 303|40|0|Dr. Lee|1st Semester"
  "CSIT227|CSIT227-F2|TTh 14:30-16:00|Lab 303|40|0|Dr. Lee|1st Semester"
  "CSIT227|CSIT227-F3|TTh 11:00-12:30|Lab 303|40|0|Dr. Lee|1st Semester"

  # SDG031 Sections (Sustainable Development Goals)
  "SDG031|SDG031-F1|TTh 11:00-12:30|Room 204|40|0|Prof. Mendoza|1st Semester"
  "SDG031|SDG031-F2|TTh 16:00-17:30|Room 204|40|0|Prof. Mendoza|1st Semester"
  "SDG031|SDG031-F3|TTh 08:00-09:30|Room 204|40|0|Prof. Mendoza|1st Semester"

  # PE205 Sections (PATHFit 3)
  "PE205|PE205-F1|TTh 13:00-14:00|Gym|40|0|Coach Abad|1st Semester"
  "PE205|PE205-F2|TTh 11:00-12:00|Gym|40|0|Coach Abad|1st Semester"
  "PE205|PE205-F3|TTh 14:30-15:30|Gym|40|0|Coach Abad|1st Semester"
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

  mysql_exec "INSERT INTO Section (courseID, sectionCode, timeslot, room, capacity, instructor, semester) 
  VALUES ($courseID, '$sectionCodeEscaped', '$timeslotEscaped', '$roomEscaped', $capacity, '$instructorEscaped', '$semesterEscaped');" > /dev/null || {
    echo "  ERROR: Failed to insert section $sectionCode"
    exit 1
  }
  echo "  Inserted section $sectionCode"
done

echo "Sections seed complete."
