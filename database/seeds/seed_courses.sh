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
  "CS231|Discrete Structures 2|3|Core|Study of mathematical structures that are fundamentally discrete rather than continuous.|Computer Science"
  "CS243|Computer Organization and Architecture|3|Core|Instruction set architectures, CPU design, memory hierarchy, and I/O structures.|Computer Science"
  "CSIT104|Platform-based Development 1 (Multimedia)|1|Core|Development of applications on specific platforms with a focus on rich multimedia assets.|Information Technology"
  "CSIT213|Social Issues and Professional Practice|3|Core|Legal, ethical, and social issues associated with the computing profession.|Information Technology"
  "CSIT221|Data Structures and Algorithms|3|Core|Design, analysis, and implementation of fundamental data structures and algorithms.|Information Technology"
  "CSIT227|Object-oriented Programming 1|3|Core|Introduction to object-oriented programming design, classes, objects, inheritance, and polymorphism.|Information Technology"
  "SDG031|Sustainable Development Goals|3|General Education|Interdisciplinary study of the UN's Sustainable Development Goals.|Natural Sciences"
  "PE205|PATHFit 3 – Menu of Sports|2|General Education|Physical Activities Towards Health and Fitness with a menu of individual/dual sports.|Physical Education"
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
