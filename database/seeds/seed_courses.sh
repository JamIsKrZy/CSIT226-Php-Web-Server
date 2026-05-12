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

echo "Seeding courses..."

declare -a courses=(
  "CS101|Introduction to Computer Science|3|Core|Foundational concepts of computer science|Computer Science"
  "CS201|Data Structures|4|Core|Learn fundamental data structures and algorithms|Computer Science"
  "CS301|Database Systems|4|Core|Design and implementation of database systems|Computer Science"
  "CS401|Web Development|3|Elective|Modern web development techniques|Computer Science"
  "IT101|Information Technology Fundamentals|3|Core|Basics of IT infrastructure|Information Technology"
  "IT201|Network Administration|4|Core|Network setup and management|Information Technology"
  "IT301|Cybersecurity|3|Elective|Introduction to cybersecurity practices|Information Technology"
  "IT401|Cloud Computing|3|Elective|Cloud platforms and services|Information Technology"
)

for line in "${courses[@]}"; do
  IFS='|' read -r courseCode courseName credits category description department <<< "$line"
  courseCodeEscaped=$(sql_quote "$courseCode")
  courseNameEscaped=$(sql_quote "$courseName")
  categoryEscaped=$(sql_quote "$category")
  descriptionEscaped=$(sql_quote "$description")
  departmentEscaped=$(sql_quote "$department")

  existing=$(mysql_exec "SELECT courseID FROM Course WHERE courseCode = '$courseCodeEscaped' LIMIT 1;" | tail -1)
  if [[ -n "$existing" && "$existing" != "courseID" ]]; then
    echo "  Skipping existing course $courseCode"
    continue
  fi

  mysql_exec "INSERT INTO Course (courseCode, courseName, credits, category, description, department) 
  VALUES ('$courseCodeEscaped', '$courseNameEscaped', $credits, '$categoryEscaped', '$descriptionEscaped', '$departmentEscaped');" > /dev/null || {
    echo "  ERROR: Failed to insert course $courseCode"
    exit 1
  }
  echo "  Inserted course $courseCode"
done

echo "Courses seed complete."
