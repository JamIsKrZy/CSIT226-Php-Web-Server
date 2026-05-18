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
  # CS Sections
  "CS101|CS101-A-S1|MWF 08:00-09:00|Room 101|50|48|Dr. Smith|1st Semester"
  "CS101|CS101-B-S1|MWF 10:00-11:00|Room 102|50|42|Prof. Johnson|1st Semester"
  "CS101|CS101-C-S2|TTh 09:00-10:30|Room 101|50|35|Dr. Smith|2nd Semester"
  "CS201|CS201-A-S1|TTh 09:00-10:30|Room 201|40|39|Dr. Anderson|1st Semester"
  "CS201|CS201-B-S1|TTh 14:00-15:30|Room 202|40|35|Prof. Davis|1st Semester"
  "CS202|CS202-A-S1|MWF 13:00-14:00|Room 201|45|41|Prof. Davis|1st Semester"
  "CS301|CS301-A-S1|MWF 11:00-12:00|Lab 301|30|30|Dr. Wilson|1st Semester"
  "CS301|CS301-B-S2|TTh 13:00-14:30|Lab 301|30|12|Dr. Wilson|2nd Semester"
  "CS302|CS302-A-S1|TTh 10:30-12:00|Room 303|40|37|Dr. Anderson|1st Semester"
  "CS303|CS303-A-S2|MWF 09:00-10:00|Room 202|40|25|Prof. Martinez|2nd Semester"
  "CS401|CS401-A-S1|TTh 16:00-17:30|Room 303|35|28|Prof. Martinez|1st Semester"
  "CS402|CS402-A-S1|MWF 15:00-16:00|Lab 302|30|29|Dr. Wilson|1st Semester"
  "CS403|CS403-A-S2|TTh 14:30-16:00|Room 104|25|19|Dr. Anderson|2nd Semester"
  "CS404|CS404-A-S1|Sat 09:00-12:00|Lab 302|25|22|Prof. Garces|1st Semester"

  # IT Sections
  "IT101|IT101-A-S1|MWF 13:00-14:00|Room 104|50|38|Dr. Brown|1st Semester"
  "IT101|IT101-B-S2|TTh 09:00-10:30|Room 104|50|45|Prof. Garcia|2nd Semester"
  "IT201|IT201-A-S1|TTh 11:00-12:30|Lab 201|35|25|Prof. Taylor|1st Semester"
  "IT202|IT202-A-S1|MWF 10:00-11:00|Lab 201|35|32|Dr. Brown|1st Semester"
  "IT301|IT301-A-S1|MWF 15:00-16:00|Room 305|40|18|Dr. Lee|1st Semester"
  "IT302|IT302-A-S2|MWF 16:00-17:00|Lab 201|30|27|Dr. Lee|2nd Semester"
  "IT401|IT401-A-S1|TTh 13:00-14:30|Room 306|30|20|Prof. Garcia|1st Semester"
  "IT402|IT402-A-S2|TTh 16:00-17:30|Room 305|40|33|Prof. Taylor|2nd Semester"

  # Math Sections
  "MATH101|MATH101-A-S1|MWF 08:00-09:00|Room 401|60|58|Prof. Sison|1st Semester"
  "MATH101|MATH101-B-S1|MWF 09:00-10:00|Room 401|60|54|Prof. Sison|1st Semester"
  "MATH201|MATH201-A-S1|MWF 10:00-11:30|Room 402|45|44|Dr. Reyes|1st Semester"
  "MATH201|MATH201-B-S2|TTh 08:30-10:00|Room 402|45|30|Dr. Reyes|2nd Semester"
  "MATH202|MATH202-A-S1|TTh 10:30-12:00|Room 402|40|38|Dr. Reyes|1st Semester"
  "MATH301|MATH301-A-S1|MWF 14:00-15:00|Room 405|35|31|Prof. Cruz|1st Semester"
  "MATH302|MATH302-A-S1|TTh 13:00-14:30|Room 401|50|49|Prof. Cruz|1st Semester"
  "MATH302|MATH302-B-S1|MWF 11:00-12:15|Room 405|50|47|Prof. Sison|1st Semester"
  "MATH401|MATH401-A-S1|MWF 13:00-14:00|Room 402|45|42|Dr. Santos|1st Semester"

  # Engineering Sections
  "EE101|EE101-A-S1|MWF 09:00-10:00|Room 501|40|36|Engr. Castro|1st Semester"
  "ECE201|ECE201-A-S1|TTh 09:00-10:30|Lab 502|30|28|Engr. Villa|1st Semester"
  "ECE301|ECE301-A-S1|MWF 14:00-16:00|Lab 505|25|24|Dr. Aquino|1st Semester"
  "ECE401|ECE401-A-S2|TTh 15:00-16:30|Room 501|30|15|Engr. Villa|2nd Semester"

  # Business & Finance Sections
  "BUS101|BUS101-A-S1|TTh 08:00-09:30|Room 204|60|55|Dean Sy|1st Semester"
  "BUS101|BUS101-B-S2|MWF 09:00-10:00|Room 204|60|59|Prof. Valdez|2nd Semester"
  "BUS201|BUS201-A-S1|MWF 10:00-11:00|Room 205|50|48|Prof. Valdez|1st Semester"
  "BUS202|BUS202-A-S2|MWF 11:00-12:00|Room 205|50|41|Prof. Valdez|2nd Semester"
  "FIN301|FIN301-A-S1|TTh 10:30-12:00|Room 206|45|43|Dr. Lim|1st Semester"
  "FIN401|FIN401-A-S1|TTh 14:00-15:30|Room 206|35|22|Dr. Lim|1st Semester"
  "ENT101|ENT101-A-S1|MWF 15:00-16:30|Room 204|40|36|Dean Sy|1st Semester"
  "ERP301|ERP301-A-S1|Sat 13:00-16:00|Lab 301|30|26|Prof. Mendoza|1st Semester"

  # Natural Sciences Sections
  "PHYS101|PHYS101-A-S1|MWF 11:00-13:00|SciLab 1|35|34|Dr. Torres|1st Semester"
  "PHYS102|PHYS102-A-S2|MWF 11:00-13:00|SciLab 1|35|29|Dr. Torres|2nd Semester"
  "CHEM101|CHEM101-A-S1|TTh 13:00-15:30|SciLab 2|30|30|Dr. Dela Cruz|1st Semester"

  # Digital Arts Sections
  "DA101|DA101-A-S1|MWF 09:00-10:30|MediaLab A|25|25|Prof. Enriquez|1st Semester"
  "DA201|DA201-A-S1|TTh 14:00-16:00|MediaLab B|25|23|Prof. Enriquez|1st Semester"
  "DA301|DA301-A-S1|MWF 14:00-15:30|MediaLab A|30|28|Prof. Go|1st Semester"
  "DA302|DA302-A-S2|TTh 11:00-12:30|Room 306|35|31|Prof. Go|2nd Semester"

  # Humanities Sections
  "ENG101|ENG101-A-S1|MWF 08:00-09:00|Room 105|50|50|Prof. Abad|1st Semester"
  "ENG101|ENG101-B-S1|MWF 09:00-10:00|Room 105|50|47|Prof. Abad|1st Semester"
  "ENG202|ENG202-A-S1|TTh 13:00-14:30|Room 105|40|39|Prof. Abad|1st Semester"
  "ETH101|ETH101-A-S1|TTh 15:00-16:30|Room 101|50|46|Dr. Borromeo|1st Semester"
  "ETH101|ETH101-B-S2|MWF 16:00-17:30|Room 102|50|38|Dr. Borromeo|2nd Semester"
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
