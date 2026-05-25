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
  "26-0000-005|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-006|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-007|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-008|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-009|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-010|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-011|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-012|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-013|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-014|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-015|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-016|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-017|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-018|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-019|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-020|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-021|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-022|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-023|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-024|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-025|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-026|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-027|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-028|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-029|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-030|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-031|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-032|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-033|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-034|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-035|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-036|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-037|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-038|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-039|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-040|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-041|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-042|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-043|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-044|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-045|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-046|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-047|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-048|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-049|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-050|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-051|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-052|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-053|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-054|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-055|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-056|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-057|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-058|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-059|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-060|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-061|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-062|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-063|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-064|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-065|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-066|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-067|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-068|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-069|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-070|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-071|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-072|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-073|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-074|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-075|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-076|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-077|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-078|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-079|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-080|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-081|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-082|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-083|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-084|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-085|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-086|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-087|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-088|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-089|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-090|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-091|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-092|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-093|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-094|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-095|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-096|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-097|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-098|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-099|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-100|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-101|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-102|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-103|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-104|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-105|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-106|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-107|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-108|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-109|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-110|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-111|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-112|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-113|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-114|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-115|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-116|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-117|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-118|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-119|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-120|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-121|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-122|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-123|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-124|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-125|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-126|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-127|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-128|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-129|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-130|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-131|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-132|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-133|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-134|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-135|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-136|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-137|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-138|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-139|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-140|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-141|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-142|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-143|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-144|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-145|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-146|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-147|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-148|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-149|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-150|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-151|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-152|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-153|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-154|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-155|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-156|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-157|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-158|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-159|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-160|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-161|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-162|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-163|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-164|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-165|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-166|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-167|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-168|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-169|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-170|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-171|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-172|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-173|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-174|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-175|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-176|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-177|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-178|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-179|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-180|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-181|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-182|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-183|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-184|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-185|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-186|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-187|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-188|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-189|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-190|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-191|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-192|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-193|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-194|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-195|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-196|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-197|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-198|1st Semester|2026|draft|Initial enrollment plan"
  "26-0000-199|1st Semester|2026|draft|Initial enrollment plan"
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

  "26-0000-005|CS231-F1|1|planned"
  "26-0000-005|CS243-F1|2|planned"
  "26-0000-005|CSIT104-F1|3|planned"

  "26-0000-006|CS231-F2|1|planned"
  "26-0000-006|CS243-F2|2|planned"
  "26-0000-006|CSIT213-F2|3|planned"

  "26-0000-007|CSIT221-F1|1|planned"
  "26-0000-007|CSIT227-F1|2|planned"
  "26-0000-007|SDG031-F1|3|planned"

  "26-0000-008|CS231-F3|1|planned"
  "26-0000-008|CSIT221-F3|2|planned"
  "26-0000-008|PE205-F3|3|planned"

  "26-0000-009|CSIT227-F1|1|planned"
  "26-0000-009|SDG031-F1|2|planned"
  "26-0000-009|PE205-F1|3|planned"

  "26-0000-010|CS231-F1|1|planned"
  "26-0000-010|CS243-F1|2|planned"
  "26-0000-010|CSIT104-F2|3|planned"

  "26-0000-011|CS231-F2|1|planned"
  "26-0000-011|CS243-F2|2|planned"
  "26-0000-011|CSIT213-F1|3|planned"

  "26-0000-012|CSIT221-F2|1|planned"
  "26-0000-012|CSIT227-F2|2|planned"
  "26-0000-012|SDG031-F2|3|planned"

  "26-0000-013|CS231-F3|1|planned"
  "26-0000-013|CSIT221-F3|2|planned"
  "26-0000-013|PE205-F2|3|planned"

  "26-0000-014|CSIT227-F2|1|planned"
  "26-0000-014|SDG031-F2|2|planned"
  "26-0000-014|PE205-F2|3|planned"

  "26-0000-015|CS231-F1|1|planned"
  "26-0000-015|CS243-F3|2|planned"
  "26-0000-015|CSIT104-F3|3|planned"

  "26-0000-016|CS231-F2|1|planned"
  "26-0000-016|CS243-F3|2|planned"
  "26-0000-016|CSIT213-F3|3|planned"

  "26-0000-017|CSIT221-F3|1|planned"
  "26-0000-017|CSIT227-F3|2|planned"
  "26-0000-017|SDG031-F3|3|planned"

  "26-0000-018|CS231-F3|1|planned"
  "26-0000-018|CSIT221-F1|2|planned"
  "26-0000-018|PE205-F1|3|planned"

  "26-0000-019|CSIT227-F3|1|planned"
  "26-0000-019|SDG031-F3|2|planned"
  "26-0000-019|PE205-F3|3|planned"

  "26-0000-020|CS231-F1|1|planned"
  "26-0000-020|CS243-F1|2|planned"
  "26-0000-020|CSIT104-F1|3|planned"

  "26-0000-021|CS231-F2|1|planned"
  "26-0000-021|CS243-F2|2|planned"
  "26-0000-021|CSIT213-F2|3|planned"

  "26-0000-022|CSIT221-F1|1|planned"
  "26-0000-022|CSIT227-F1|2|planned"
  "26-0000-022|SDG031-F1|3|planned"

  "26-0000-023|CS231-F3|1|planned"
  "26-0000-023|CSIT221-F3|2|planned"
  "26-0000-023|PE205-F3|3|planned"

  "26-0000-024|CSIT227-F1|1|planned"
  "26-0000-024|SDG031-F1|2|planned"
  "26-0000-024|PE205-F1|3|planned"

  "26-0000-025|CS231-F1|1|planned"
  "26-0000-025|CS243-F1|2|planned"
  "26-0000-025|CSIT104-F2|3|planned"

  "26-0000-026|CS231-F2|1|planned"
  "26-0000-026|CS243-F2|2|planned"
  "26-0000-026|CSIT213-F1|3|planned"

  "26-0000-027|CSIT221-F2|1|planned"
  "26-0000-027|CSIT227-F2|2|planned"
  "26-0000-027|SDG031-F2|3|planned"

  "26-0000-028|CS231-F3|1|planned"
  "26-0000-028|CSIT221-F3|2|planned"
  "26-0000-028|PE205-F2|3|planned"

  "26-0000-029|CSIT227-F2|1|planned"
  "26-0000-029|SDG031-F2|2|planned"
  "26-0000-029|PE205-F2|3|planned"

  "26-0000-030|CS231-F1|1|planned"
  "26-0000-030|CS243-F3|2|planned"
  "26-0000-030|CSIT104-F3|3|planned"

  "26-0000-031|CS231-F2|1|planned"
  "26-0000-031|CS243-F3|2|planned"
  "26-0000-031|CSIT213-F3|3|planned"

  "26-0000-032|CSIT221-F3|1|planned"
  "26-0000-032|CSIT227-F3|2|planned"
  "26-0000-032|SDG031-F3|3|planned"

  "26-0000-033|CS231-F3|1|planned"
  "26-0000-033|CSIT221-F1|2|planned"
  "26-0000-033|PE205-F1|3|planned"

  "26-0000-034|CSIT227-F3|1|planned"
  "26-0000-034|SDG031-F3|2|planned"
  "26-0000-034|PE205-F3|3|planned"

  "26-0000-035|CS231-F1|1|planned"
  "26-0000-035|CS243-F1|2|planned"
  "26-0000-035|CSIT104-F1|3|planned"

  "26-0000-036|CS231-F2|1|planned"
  "26-0000-036|CS243-F2|2|planned"
  "26-0000-036|CSIT213-F2|3|planned"

  "26-0000-037|CSIT221-F1|1|planned"
  "26-0000-037|CSIT227-F1|2|planned"
  "26-0000-037|SDG031-F1|3|planned"

  "26-0000-038|CS231-F3|1|planned"
  "26-0000-038|CSIT221-F3|2|planned"
  "26-0000-038|PE205-F3|3|planned"

  "26-0000-039|CSIT227-F1|1|planned"
  "26-0000-039|SDG031-F1|2|planned"
  "26-0000-039|PE205-F1|3|planned"

  "26-0000-040|CS231-F1|1|planned"
  "26-0000-040|CS243-F1|2|planned"
  "26-0000-040|CSIT104-F2|3|planned"

  "26-0000-041|CS231-F2|1|planned"
  "26-0000-041|CS243-F2|2|planned"
  "26-0000-041|CSIT213-F1|3|planned"

  "26-0000-042|CSIT221-F2|1|planned"
  "26-0000-042|CSIT227-F2|2|planned"
  "26-0000-042|SDG031-F2|3|planned"

  "26-0000-043|CS231-F3|1|planned"
  "26-0000-043|CSIT221-F3|2|planned"
  "26-0000-043|PE205-F2|3|planned"

  "26-0000-044|CSIT227-F2|1|planned"
  "26-0000-044|SDG031-F2|2|planned"
  "26-0000-044|PE205-F2|3|planned"

  "26-0000-045|CS231-F1|1|planned"
  "26-0000-045|CS243-F3|2|planned"
  "26-0000-045|CSIT104-F3|3|planned"

  "26-0000-046|CS231-F2|1|planned"
  "26-0000-046|CS243-F3|2|planned"
  "26-0000-046|CSIT213-F3|3|planned"

  "26-0000-047|CSIT221-F3|1|planned"
  "26-0000-047|CSIT227-F3|2|planned"
  "26-0000-047|SDG031-F3|3|planned"

  "26-0000-048|CS231-F3|1|planned"
  "26-0000-048|CSIT221-F1|2|planned"
  "26-0000-048|PE205-F1|3|planned"

  "26-0000-049|CSIT227-F3|1|planned"
  "26-0000-049|SDG031-F3|2|planned"
  "26-0000-049|PE205-F3|3|planned"

  "26-0000-050|CS231-F1|1|planned"
  "26-0000-050|CS243-F1|2|planned"
  "26-0000-050|CSIT104-F1|3|planned"

  "26-0000-051|CS231-F2|1|planned"
  "26-0000-051|CS243-F2|2|planned"
  "26-0000-051|CSIT213-F2|3|planned"

  "26-0000-052|CSIT221-F1|1|planned"
  "26-0000-052|CSIT227-F1|2|planned"
  "26-0000-052|SDG031-F1|3|planned"

  "26-0000-053|CS231-F3|1|planned"
  "26-0000-053|CSIT221-F3|2|planned"
  "26-0000-053|PE205-F3|3|planned"

  "26-0000-054|CSIT227-F1|1|planned"
  "26-0000-054|SDG031-F1|2|planned"
  "26-0000-054|PE205-F1|3|planned"

  "26-0000-055|CS231-F1|1|planned"
  "26-0000-055|CS243-F1|2|planned"
  "26-0000-055|CSIT104-F2|3|planned"

  "26-0000-056|CS231-F2|1|planned"
  "26-0000-056|CS243-F2|2|planned"
  "26-0000-056|CSIT213-F1|3|planned"

  "26-0000-057|CSIT221-F2|1|planned"
  "26-0000-057|CSIT227-F2|2|planned"
  "26-0000-057|SDG031-F2|3|planned"

  "26-0000-058|CS231-F3|1|planned"
  "26-0000-058|CSIT221-F3|2|planned"
  "26-0000-058|PE205-F2|3|planned"

  "26-0000-059|CSIT227-F2|1|planned"
  "26-0000-059|SDG031-F2|2|planned"
  "26-0000-059|PE205-F2|3|planned"

  "26-0000-060|CS231-F1|1|planned"
  "26-0000-060|CS243-F3|2|planned"
  "26-0000-060|CSIT104-F3|3|planned"

  "26-0000-061|CS231-F2|1|planned"
  "26-0000-061|CS243-F3|2|planned"
  "26-0000-061|CSIT213-F3|3|planned"

  "26-0000-062|CSIT221-F3|1|planned"
  "26-0000-062|CSIT227-F3|2|planned"
  "26-0000-062|SDG031-F3|3|planned"

  "26-0000-063|CS231-F3|1|planned"
  "26-0000-063|CSIT221-F1|2|planned"
  "26-0000-063|PE205-F1|3|planned"

  "26-0000-064|CSIT227-F3|1|planned"
  "26-0000-064|SDG031-F3|2|planned"
  "26-0000-064|PE205-F3|3|planned"

  "26-0000-065|CS231-F1|1|planned"
  "26-0000-065|CS243-F1|2|planned"
  "26-0000-065|CSIT104-F1|3|planned"

  "26-0000-066|CS231-F2|1|planned"
  "26-0000-066|CS243-F2|2|planned"
  "26-0000-066|CSIT213-F2|3|planned"

  "26-0000-067|CSIT221-F1|1|planned"
  "26-0000-067|CSIT227-F1|2|planned"
  "26-0000-067|SDG031-F1|3|planned"

  "26-0000-068|CS231-F3|1|planned"
  "26-0000-068|CSIT221-F3|2|planned"
  "26-0000-068|PE205-F3|3|planned"

  "26-0000-069|CSIT227-F1|1|planned"
  "26-0000-069|SDG031-F1|2|planned"
  "26-0000-069|PE205-F1|3|planned"

  "26-0000-070|CS231-F1|1|planned"
  "26-0000-070|CS243-F1|2|planned"
  "26-0000-070|CSIT104-F2|3|planned"

  "26-0000-071|CS231-F2|1|planned"
  "26-0000-071|CS243-F2|2|planned"
  "26-0000-071|CSIT213-F1|3|planned"

  "26-0000-072|CSIT221-F2|1|planned"
  "26-0000-072|CSIT227-F2|2|planned"
  "26-0000-072|SDG031-F2|3|planned"

  "26-0000-073|CS231-F3|1|planned"
  "26-0000-073|CSIT221-F3|2|planned"
  "26-0000-073|PE205-F2|3|planned"

  "26-0000-074|CSIT227-F2|1|planned"
  "26-0000-074|SDG031-F2|2|planned"
  "26-0000-074|PE205-F2|3|planned"

  "26-0000-075|CS231-F1|1|planned"
  "26-0000-075|CS243-F3|2|planned"
  "26-0000-075|CSIT104-F3|3|planned"

  "26-0000-076|CS231-F2|1|planned"
  "26-0000-076|CS243-F3|2|planned"
  "26-0000-076|CSIT213-F3|3|planned"

  "26-0000-077|CSIT221-F3|1|planned"
  "26-0000-077|CSIT227-F3|2|planned"
  "26-0000-077|SDG031-F3|3|planned"

  "26-0000-078|CS231-F3|1|planned"
  "26-0000-078|CSIT221-F1|2|planned"
  "26-0000-078|PE205-F1|3|planned"

  "26-0000-079|CSIT227-F3|1|planned"
  "26-0000-079|SDG031-F3|2|planned"
  "26-0000-079|PE205-F3|3|planned"

  "26-0000-080|CS231-F1|1|planned"
  "26-0000-080|CS243-F1|2|planned"
  "26-0000-080|CSIT104-F1|3|planned"

  "26-0000-081|CS231-F2|1|planned"
  "26-0000-081|CS243-F2|2|planned"
  "26-0000-081|CSIT213-F2|3|planned"

  "26-0000-082|CSIT221-F1|1|planned"
  "26-0000-082|CSIT227-F1|2|planned"
  "26-0000-082|SDG031-F1|3|planned"

  "26-0000-083|CS231-F3|1|planned"
  "26-0000-083|CSIT221-F3|2|planned"
  "26-0000-083|PE205-F3|3|planned"

  "26-0000-084|CSIT227-F1|1|planned"
  "26-0000-084|SDG031-F1|2|planned"
  "26-0000-084|PE205-F1|3|planned"

  "26-0000-085|CS231-F1|1|planned"
  "26-0000-085|CS243-F1|2|planned"
  "26-0000-085|CSIT104-F2|3|planned"

  "26-0000-086|CS231-F2|1|planned"
  "26-0000-086|CS243-F2|2|planned"
  "26-0000-086|CSIT213-F1|3|planned"

  "26-0000-087|CSIT221-F2|1|planned"
  "26-0000-087|CSIT227-F2|2|planned"
  "26-0000-087|SDG031-F2|3|planned"

  "26-0000-088|CS231-F3|1|planned"
  "26-0000-088|CSIT221-F3|2|planned"
  "26-0000-088|PE205-F2|3|planned"

  "26-0000-089|CSIT227-F2|1|planned"
  "26-0000-089|SDG031-F2|2|planned"
  "26-0000-089|PE205-F2|3|planned"

  "26-0000-090|CS231-F1|1|planned"
  "26-0000-090|CS243-F3|2|planned"
  "26-0000-090|CSIT104-F3|3|planned"

  "26-0000-091|CS231-F2|1|planned"
  "26-0000-091|CS243-F3|2|planned"
  "26-0000-091|CSIT213-F3|3|planned"

  "26-0000-092|CSIT221-F3|1|planned"
  "26-0000-092|CSIT227-F3|2|planned"
  "26-0000-092|SDG031-F3|3|planned"

  "26-0000-093|CS231-F3|1|planned"
  "26-0000-093|CSIT221-F1|2|planned"
  "26-0000-093|PE205-F1|3|planned"

  "26-0000-094|CSIT227-F3|1|planned"
  "26-0000-094|SDG031-F3|2|planned"
  "26-0000-094|PE205-F3|3|planned"

  "26-0000-095|CS231-F1|1|planned"
  "26-0000-095|CS243-F1|2|planned"
  "26-0000-095|CSIT104-F1|3|planned"

  "26-0000-096|CS231-F2|1|planned"
  "26-0000-096|CS243-F2|2|planned"
  "26-0000-096|CSIT213-F2|3|planned"

  "26-0000-097|CSIT221-F1|1|planned"
  "26-0000-097|CSIT227-F1|2|planned"
  "26-0000-097|SDG031-F1|3|planned"

  "26-0000-098|CS231-F3|1|planned"
  "26-0000-098|CSIT221-F3|2|planned"
  "26-0000-098|PE205-F3|3|planned"

  "26-0000-099|CSIT227-F1|1|planned"
  "26-0000-099|SDG031-F1|2|planned"
  "26-0000-099|PE205-F1|3|planned"

  "26-0000-100|CS231-F1|1|planned"
  "26-0000-100|CS243-F1|2|planned"
  "26-0000-100|CSIT104-F2|3|planned"

  "26-0000-101|CS231-F2|1|planned"
  "26-0000-101|CS243-F2|2|planned"
  "26-0000-101|CSIT213-F1|3|planned"

  "26-0000-102|CSIT221-F2|1|planned"
  "26-0000-102|CSIT227-F2|2|planned"
  "26-0000-102|SDG031-F2|3|planned"

  "26-0000-103|CS231-F3|1|planned"
  "26-0000-103|CSIT221-F3|2|planned"
  "26-0000-103|PE205-F2|3|planned"

  "26-0000-104|CSIT227-F2|1|planned"
  "26-0000-104|SDG031-F2|2|planned"
  "26-0000-104|PE205-F2|3|planned"

  "26-0000-105|CS231-F1|1|planned"
  "26-0000-105|CS243-F3|2|planned"
  "26-0000-105|CSIT104-F3|3|planned"

  "26-0000-106|CS231-F2|1|planned"
  "26-0000-106|CS243-F3|2|planned"
  "26-0000-106|CSIT213-F3|3|planned"

  "26-0000-107|CSIT221-F3|1|planned"
  "26-0000-107|CSIT227-F3|2|planned"
  "26-0000-107|SDG031-F3|3|planned"

  "26-0000-108|CS231-F3|1|planned"
  "26-0000-108|CSIT221-F1|2|planned"
  "26-0000-108|PE205-F1|3|planned"

  "26-0000-109|CSIT227-F3|1|planned"
  "26-0000-109|SDG031-F3|2|planned"
  "26-0000-109|PE205-F3|3|planned"

  "26-0000-110|CS231-F1|1|planned"
  "26-0000-110|CS243-F1|2|planned"
  "26-0000-110|CSIT104-F1|3|planned"

  "26-0000-111|CS231-F2|1|planned"
  "26-0000-111|CS243-F2|2|planned"
  "26-0000-111|CSIT213-F2|3|planned"

  "26-0000-112|CSIT221-F1|1|planned"
  "26-0000-112|CSIT227-F1|2|planned"
  "26-0000-112|SDG031-F1|3|planned"

  "26-0000-113|CS231-F3|1|planned"
  "26-0000-113|CSIT221-F3|2|planned"
  "26-0000-113|PE205-F3|3|planned"

  "26-0000-114|CSIT227-F1|1|planned"
  "26-0000-114|SDG031-F1|2|planned"
  "26-0000-114|PE205-F1|3|planned"

  "26-0000-115|CS231-F1|1|planned"
  "26-0000-115|CS243-F1|2|planned"
  "26-0000-115|CSIT104-F2|3|planned"

  "26-0000-116|CS231-F2|1|planned"
  "26-0000-116|CS243-F2|2|planned"
  "26-0000-116|CSIT213-F1|3|planned"

  "26-0000-117|CSIT221-F2|1|planned"
  "26-0000-117|CSIT227-F2|2|planned"
  "26-0000-117|SDG031-F2|3|planned"

  "26-0000-118|CS231-F3|1|planned"
  "26-0000-118|CSIT221-F3|2|planned"
  "26-0000-118|PE205-F2|3|planned"

  "26-0000-119|CSIT227-F2|1|planned"
  "26-0000-119|SDG031-F2|2|planned"
  "26-0000-119|PE205-F2|3|planned"

  "26-0000-120|CS231-F1|1|planned"
  "26-0000-120|CS243-F3|2|planned"
  "26-0000-120|CSIT104-F3|3|planned"

  "26-0000-121|CS231-F2|1|planned"
  "26-0000-121|CS243-F3|2|planned"
  "26-0000-121|CSIT213-F3|3|planned"

  "26-0000-122|CSIT221-F3|1|planned"
  "26-0000-122|CSIT227-F3|2|planned"
  "26-0000-122|SDG031-F3|3|planned"

  "26-0000-123|CS231-F3|1|planned"
  "26-0000-123|CSIT221-F1|2|planned"
  "26-0000-123|PE205-F1|3|planned"

  "26-0000-124|CSIT227-F3|1|planned"
  "26-0000-124|SDG031-F3|2|planned"
  "26-0000-124|PE205-F3|3|planned"

  "26-0000-125|CS231-F1|1|planned"
  "26-0000-125|CS243-F1|2|planned"
  "26-0000-125|CSIT104-F1|3|planned"

  "26-0000-126|CS231-F2|1|planned"
  "26-0000-126|CS243-F2|2|planned"
  "26-0000-126|CSIT213-F2|3|planned"

  "26-0000-127|CSIT221-F1|1|planned"
  "26-0000-127|CSIT227-F1|2|planned"
  "26-0000-127|SDG031-F1|3|planned"

  "26-0000-128|CS231-F3|1|planned"
  "26-0000-128|CSIT221-F3|2|planned"
  "26-0000-128|PE205-F3|3|planned"

  "26-0000-129|CSIT227-F1|1|planned"
  "26-0000-129|SDG031-F1|2|planned"
  "26-0000-129|PE205-F1|3|planned"

  "26-0000-130|CS231-F1|1|planned"
  "26-0000-130|CS243-F1|2|planned"
  "26-0000-130|CSIT104-F2|3|planned"

  "26-0000-131|CS231-F2|1|planned"
  "26-0000-131|CS243-F2|2|planned"
  "26-0000-131|CSIT213-F1|3|planned"

  "26-0000-132|CSIT221-F2|1|planned"
  "26-0000-132|CSIT227-F2|2|planned"
  "26-0000-132|SDG031-F2|3|planned"

  "26-0000-133|CS231-F3|1|planned"
  "26-0000-133|CSIT221-F3|2|planned"
  "26-0000-133|PE205-F2|3|planned"

  "26-0000-134|CSIT227-F2|1|planned"
  "26-0000-134|SDG031-F2|2|planned"
  "26-0000-134|PE205-F2|3|planned"

  "26-0000-135|CS231-F1|1|planned"
  "26-0000-135|CS243-F3|2|planned"
  "26-0000-135|CSIT104-F3|3|planned"

  "26-0000-136|CS231-F2|1|planned"
  "26-0000-136|CS243-F3|2|planned"
  "26-0000-136|CSIT213-F3|3|planned"

  "26-0000-137|CSIT221-F3|1|planned"
  "26-0000-137|CSIT227-F3|2|planned"
  "26-0000-137|SDG031-F3|3|planned"

  "26-0000-138|CS231-F3|1|planned"
  "26-0000-138|CSIT221-F1|2|planned"
  "26-0000-138|PE205-F1|3|planned"

  "26-0000-139|CSIT227-F3|1|planned"
  "26-0000-139|SDG031-F3|2|planned"
  "26-0000-139|PE205-F3|3|planned"

  "26-0000-140|CS231-F1|1|planned"
  "26-0000-140|CS243-F1|2|planned"
  "26-0000-140|CSIT104-F1|3|planned"

  "26-0000-141|CS231-F2|1|planned"
  "26-0000-141|CS243-F2|2|planned"
  "26-0000-141|CSIT213-F2|3|planned"

  "26-0000-142|CSIT221-F1|1|planned"
  "26-0000-142|CSIT227-F1|2|planned"
  "26-0000-142|SDG031-F1|3|planned"

  "26-0000-143|CS231-F3|1|planned"
  "26-0000-143|CSIT221-F3|2|planned"
  "26-0000-143|PE205-F3|3|planned"

  "26-0000-144|CSIT227-F1|1|planned"
  "26-0000-144|SDG031-F1|2|planned"
  "26-0000-144|PE205-F1|3|planned"

  "26-0000-145|CS231-F1|1|planned"
  "26-0000-145|CS243-F1|2|planned"
  "26-0000-145|CSIT104-F2|3|planned"

  "26-0000-146|CS231-F2|1|planned"
  "26-0000-146|CS243-F2|2|planned"
  "26-0000-146|CSIT213-F1|3|planned"

  "26-0000-147|CSIT221-F2|1|planned"
  "26-0000-147|CSIT227-F2|2|planned"
  "26-0000-147|SDG031-F2|3|planned"

  "26-0000-148|CS231-F3|1|planned"
  "26-0000-148|CSIT221-F3|2|planned"
  "26-0000-148|PE205-F2|3|planned"

  "26-0000-149|CSIT227-F2|1|planned"
  "26-0000-149|SDG031-F2|2|planned"
  "26-0000-149|PE205-F2|3|planned"

  "26-0000-150|CS231-F1|1|planned"
  "26-0000-150|CS243-F3|2|planned"
  "26-0000-150|CSIT104-F3|3|planned"

  "26-0000-151|CS231-F2|1|planned"
  "26-0000-151|CS243-F3|2|planned"
  "26-0000-151|CSIT213-F3|3|planned"

  "26-0000-152|CSIT221-F3|1|planned"
  "26-0000-152|CSIT227-F3|2|planned"
  "26-0000-152|SDG031-F3|3|planned"

  "26-0000-153|CS231-F3|1|planned"
  "26-0000-153|CSIT221-F1|2|planned"
  "26-0000-153|PE205-F1|3|planned"

  "26-0000-154|CSIT227-F3|1|planned"
  "26-0000-154|SDG031-F3|2|planned"
  "26-0000-154|PE205-F3|3|planned"

  "26-0000-155|CS231-F1|1|planned"
  "26-0000-155|CS243-F1|2|planned"
  "26-0000-155|CSIT104-F1|3|planned"

  "26-0000-156|CS231-F2|1|planned"
  "26-0000-156|CS243-F2|2|planned"
  "26-0000-156|CSIT213-F2|3|planned"

  "26-0000-157|CSIT221-F1|1|planned"
  "26-0000-157|CSIT227-F1|2|planned"
  "26-0000-157|SDG031-F1|3|planned"

  "26-0000-158|CS231-F3|1|planned"
  "26-0000-158|CSIT221-F3|2|planned"
  "26-0000-158|PE205-F3|3|planned"

  "26-0000-159|CSIT227-F1|1|planned"
  "26-0000-159|SDG031-F1|2|planned"
  "26-0000-159|PE205-F1|3|planned"

  "26-0000-160|CS231-F1|1|planned"
  "26-0000-160|CS243-F1|2|planned"
  "26-0000-160|CSIT104-F2|3|planned"

  "26-0000-161|CS231-F2|1|planned"
  "26-0000-161|CS243-F2|2|planned"
  "26-0000-161|CSIT213-F1|3|planned"

  "26-0000-162|CSIT221-F2|1|planned"
  "26-0000-162|CSIT227-F2|2|planned"
  "26-0000-162|SDG031-F2|3|planned"

  "26-0000-163|CS231-F3|1|planned"
  "26-0000-163|CSIT221-F3|2|planned"
  "26-0000-163|PE205-F2|3|planned"

  "26-0000-164|CSIT227-F2|1|planned"
  "26-0000-164|SDG031-F2|2|planned"
  "26-0000-164|PE205-F2|3|planned"

  "26-0000-165|CS231-F1|1|planned"
  "26-0000-165|CS243-F3|2|planned"
  "26-0000-165|CSIT104-F3|3|planned"

  "26-0000-166|CS231-F2|1|planned"
  "26-0000-166|CS243-F3|2|planned"
  "26-0000-166|CSIT213-F3|3|planned"

  "26-0000-167|CSIT221-F3|1|planned"
  "26-0000-167|CSIT227-F3|2|planned"
  "26-0000-167|SDG031-F3|3|planned"

  "26-0000-168|CS231-F3|1|planned"
  "26-0000-168|CSIT221-F1|2|planned"
  "26-0000-168|PE205-F1|3|planned"

  "26-0000-169|CSIT227-F3|1|planned"
  "26-0000-169|SDG031-F3|2|planned"
  "26-0000-169|PE205-F3|3|planned"

  "26-0000-170|CS231-F1|1|planned"
  "26-0000-170|CS243-F1|2|planned"
  "26-0000-170|CSIT104-F1|3|planned"

  "26-0000-171|CS231-F2|1|planned"
  "26-0000-171|CS243-F2|2|planned"
  "26-0000-171|CSIT213-F2|3|planned"

  "26-0000-172|CSIT221-F1|1|planned"
  "26-0000-172|CSIT227-F1|2|planned"
  "26-0000-172|SDG031-F1|3|planned"

  "26-0000-173|CS231-F3|1|planned"
  "26-0000-173|CSIT221-F3|2|planned"
  "26-0000-173|PE205-F3|3|planned"

  "26-0000-174|CSIT227-F1|1|planned"
  "26-0000-174|SDG031-F1|2|planned"
  "26-0000-174|PE205-F1|3|planned"

  "26-0000-175|CS231-F1|1|planned"
  "26-0000-175|CS243-F1|2|planned"
  "26-0000-175|CSIT104-F2|3|planned"

  "26-0000-176|CS231-F2|1|planned"
  "26-0000-176|CS243-F2|2|planned"
  "26-0000-176|CSIT213-F1|3|planned"

  "26-0000-177|CSIT221-F2|1|planned"
  "26-0000-177|CSIT227-F2|2|planned"
  "26-0000-177|SDG031-F2|3|planned"

  "26-0000-178|CS231-F3|1|planned"
  "26-0000-178|CSIT221-F3|2|planned"
  "26-0000-178|PE205-F2|3|planned"

  "26-0000-179|CSIT227-F2|1|planned"
  "26-0000-179|SDG031-F2|2|planned"
  "26-0000-179|PE205-F2|3|planned"

  "26-0000-180|CS231-F1|1|planned"
  "26-0000-180|CS243-F3|2|planned"
  "26-0000-180|CSIT104-F3|3|planned"

  "26-0000-181|CS231-F2|1|planned"
  "26-0000-181|CS243-F3|2|planned"
  "26-0000-181|CSIT213-F3|3|planned"

  "26-0000-182|CSIT221-F3|1|planned"
  "26-0000-182|CSIT227-F3|2|planned"
  "26-0000-182|SDG031-F3|3|planned"

  "26-0000-183|CS231-F3|1|planned"
  "26-0000-183|CSIT221-F1|2|planned"
  "26-0000-183|PE205-F1|3|planned"

  "26-0000-184|CSIT227-F3|1|planned"
  "26-0000-184|SDG031-F3|2|planned"
  "26-0000-184|PE205-F3|3|planned"

  "26-0000-185|CS231-F1|1|planned"
  "26-0000-185|CS243-F1|2|planned"
  "26-0000-185|CSIT104-F1|3|planned"

  "26-0000-186|CS231-F2|1|planned"
  "26-0000-186|CS243-F2|2|planned"
  "26-0000-186|CSIT213-F2|3|planned"

  "26-0000-187|CSIT221-F1|1|planned"
  "26-0000-187|CSIT227-F1|2|planned"
  "26-0000-187|SDG031-F1|3|planned"

  "26-0000-188|CS231-F3|1|planned"
  "26-0000-188|CSIT221-F3|2|planned"
  "26-0000-188|PE205-F3|3|planned"

  "26-0000-189|CSIT227-F1|1|planned"
  "26-0000-189|SDG031-F1|2|planned"
  "26-0000-189|PE205-F1|3|planned"

  "26-0000-190|CS231-F1|1|planned"
  "26-0000-190|CS243-F1|2|planned"
  "26-0000-190|CSIT104-F2|3|planned"

  "26-0000-191|CS231-F2|1|planned"
  "26-0000-191|CS243-F2|2|planned"
  "26-0000-191|CSIT213-F1|3|planned"

  "26-0000-192|CSIT221-F2|1|planned"
  "26-0000-192|CSIT227-F2|2|planned"
  "26-0000-192|SDG031-F2|3|planned"

  "26-0000-193|CS231-F3|1|planned"
  "26-0000-193|CSIT221-F3|2|planned"
  "26-0000-193|PE205-F2|3|planned"

  "26-0000-194|CSIT227-F2|1|planned"
  "26-0000-194|SDG031-F2|2|planned"
  "26-0000-194|PE205-F2|3|planned"

  "26-0000-195|CS231-F1|1|planned"
  "26-0000-195|CS243-F3|2|planned"
  "26-0000-195|CSIT104-F3|3|planned"

  "26-0000-196|CS231-F2|1|planned"
  "26-0000-196|CS243-F3|2|planned"
  "26-0000-196|CSIT213-F3|3|planned"

  "26-0000-197|CSIT221-F3|1|planned"
  "26-0000-197|CSIT227-F3|2|planned"
  "26-0000-197|SDG031-F3|3|planned"

  "26-0000-198|CS231-F3|1|planned"
  "26-0000-198|CSIT221-F1|2|planned"
  "26-0000-198|PE205-F1|3|planned"

  "26-0000-199|CSIT227-F3|1|planned"
  "26-0000-199|SDG031-F3|2|planned"
  "26-0000-199|PE205-F3|3|planned"
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
