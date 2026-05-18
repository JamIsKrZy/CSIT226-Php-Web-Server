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
  # === COMPUTER SCIENCE & INFORMATION TECHNOLOGY ===
  "CS101|Introduction to Computer Science|3|Core|Foundational concepts of computer science|Computer Science"
  "CS201|Data Structures and Algorithms|4|Core|Learn fundamental data structures and analysis of algorithms|Computer Science"
  "CS202|Object-Oriented Programming|3|Core|Paradigm-level understanding using Java and C++|Computer Science"
  "CS301|Database Systems|4|Core|Design, implementation, and optimization of relational databases|Computer Science"
  "CS302|Operating Systems|3|Core|Process management, memory allocation, and file systems|Computer Science"
  "CS303|Software Engineering Principles|3|Core|SDLC methodologies, design patterns, and agile workflows|Computer Science"
  "CS401|Web Development|3|Elective|Modern full-stack web development techniques and frameworks|Computer Science"
  "CS402|Artificial Intelligence|3|Elective|Introduction to search heuristics, machine learning, and NLP|Computer Science"
  "CS403|Compiler Design|4|Elective|Lexical analysis, parsing, and code generation theories|Computer Science"
  "CS404|Computer Graphics & Shaders|3|Elective|3D rendering math, pipeline operations, and GLSL/HLSL|Computer Science"
  "IT101|Information Technology Fundamentals|3|Core|Basics of IT infrastructure, hardware, and computing systems|Information Technology"
  "IT201|Network Administration|4|Core|Network setup, routing protocols, and architecture management|Information Technology"
  "IT202|Linux System Administration|3|Core|Shell scripting, user management, and system-level configuration|Information Technology"
  "IT301|Cybersecurity Fundamentals|3|Elective|Introduction to cryptography, network defense, and security|Information Technology"
  "IT302|Ethical Hacking|3|Elective|Penetration testing methodologies and vulnerability assessments|Information Technology"
  "IT401|Cloud Computing|3|Elective|Cloud architectures, microservices, and deployment patterns|Information Technology"
  "IT402|IT Project Management|3|Core|Managing complex technology projects using ITSM frameworks|Information Technology"

  # === MATHEMATICS & STATISTICS ===
  "MATH101|College Algebra|3|Core|Linear equations, matrices, functions, and graphs|Mathematics"
  "MATH201|Calculus I|4|Core|Limits, continuity, differentiation, and basic integration|Mathematics"
  "MATH202|Calculus II|4|Core|Advanced integration techniques, sequences, and infinite series|Mathematics"
  "MATH301|Linear Algebra|3|Core|Vector spaces, linear transformations, eigenvalues, and eigenvectors|Mathematics"
  "MATH302|Discrete Mathematics|3|Core|Logic, set theory, combinatorics, and graph theory for computing|Mathematics"
  "MATH401|Probability and Statistics|3|Core|Data analysis, probability distributions, and statistical inference|Mathematics"

  # === ENGINEERING & HARDWARE ===
  "EE101|Basic Electrical Engineering|3|Core|DC/AC circuit analysis, mesh laws, and nodal evaluation|Engineering"
  "ECE201|Digital Logic Design|4|Core|Boolean algebra, logic gates, combinational and sequential circuits|Engineering"
  "ECE301|Microprocessors & Embedded Systems|4|Core|Assembly programming, microcontroller interfacing, and hardware design|Engineering"
  "ECE401|Signal Processing|3|Elective|Continuous and discrete-time signals, Fourier transforms, and filters|Engineering"

  # === BUSINESS, MANAGEMENT & FINANCE ===
  "BUS101|Introduction to Business|3|General Education|Overview of business operations, marketing, and corporate structures|Business Administration"
  "BUS201|Financial Accounting|3|Core|Recording business transactions, balance sheets, and financial statements|Business Administration"
  "BUS202|Managerial Accounting|3|Core|Internal decision-making, cost accounting, and budgeting tools|Business Administration"
  "FIN301|Corporate Finance|3|Core|Time value of money, capital budgeting, and risk management|Finance"
  "FIN401|Investment Analysis|3|Elective|Portfolio theory, asset allocation, and stock/bond valuation|Finance"
  "ENT101|Entrepreneurship and Innovation|3|Elective|Ideation, business model canvas, and venture pitching mechanics|Business Administration"
  "ERP301|Enterprise Resource Planning Systems|4|Elective|Implementation and customization of modular enterprise software|Information Systems"

  # === NATURAL SCIENCES ===
  "PHYS101|General Physics I|4|Core|Mechanics, kinematics, forces, and conservation laws with lab|Natural Sciences"
  "PHYS102|General Physics II|4|Core|Electricity, magnetism, optics, and wave mechanics with lab|Natural Sciences"
  "CHEM101|General Chemistry I|4|Core|Atomic structure, chemical bonding, stoichiometry, and gas laws|Natural Sciences"

  # === DIGITAL ARTS, MEDIA & DESIGN ===
  "DA101|Fundamentals of 2D Design|3|Core|Color theory, composition, and raster/vector digital asset design|Digital Arts"
  "DA201|3D Modeling and Animation|3|Elective|Mesh topology, UV mapping, texturing, and rigging basics|Digital Arts"
  "DA301|User Interface & Experience Design|3|Core|Wireframing, prototyping, user journeys, and accessibility standards|Digital Arts"
  "DA302|Game Design Principles|3|Elective|Game loops, balance mechanics, level flow, and interactive design|Digital Arts"

  # === HUMANITIES & SOCIAL SCIENCES ===
  "ENG101|Academic Writing & Composition|3|General Education|Critical reading, essay structures, and research documentation|Humanities"
  "ENG202|Technical Communication|3|Core|Drafting project proposals, documentation, and technical reporting|Humanities"
  "ETH101|Ethics in Technology|3|General Education|Moral philosophies, intellectual property, data privacy, and AI ethics|Humanities"
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
