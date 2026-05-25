#!/bin/bash
set -euo pipefail

DB_HOST=${DB_HOST:-db}
DB_USER=${DB_USER:-myuser}
DB_PASSWORD=${DB_PASSWORD:-mypassword}
DB_NAME=${DB_NAME:-mydb}

PASSWORD="password123"

# Execute SQL with error checking
mysql_exec() {
  local query="$1"
  local output
  output=$(MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -u "$DB_USER" --skip-ssl "$DB_NAME" -e "$query" 2>&1) || {
    echo "ERROR: MySQL query failed: $query"
    echo "Error details: $output"
    return 1
  }
  echo "$output"
}

sql_quote() {
  printf '%s' "$1" | sed "s/'/''/g"
}

echo "Seeding users..."

# Student users
declare -a students=(
  "John|Doe|john.doe@university.edu|26-0000-000|BSCS|2"
  "Jane|Smith|jane.smith@university.edu|26-0000-001|BSCS|2"
  "Bob|Wilson|bob.wilson@university.edu|26-0000-002|BSCS|2"
  "Alice|Johnson|alice.johnson@university.edu|26-0000-003|BSCS|2"
  "Charlie|Brown|charlie.brown@university.edu|26-0000-004|BSCS|2"
  "David|Miller|david.miller@university.edu|26-0000-005|BSCS|2"
  "Emma|Davis|emma.davis@university.edu|26-0000-006|BSCS|2"
  "James|Garcia|james.garcia@university.edu|26-0000-007|BSCS|2"
  "Olivia|Rodriguez|olivia.rodriguez@university.edu|26-0000-008|BSCS|2"
  "Liam|Martinez|liam.martinez@university.edu|26-0000-009|BSCS|2"
  "Sophia|Hernandez|sophia.hernandez@university.edu|26-0000-010|BSCS|2"
  "Benjamin|Lopez|benjamin.lopez@university.edu|26-0000-011|BSCS|2"
  "Mia|Gonzalez|mia.gonzalez@university.edu|26-0000-012|BSCS|2"
  "Lucas|Wilson|lucas.wilson@university.edu|26-0000-013|BSCS|2"
  "Charlotte|Anderson|charlotte.anderson@university.edu|26-0000-014|BSCS|2"
  "Alexander|Thomas|alexander.thomas@university.edu|26-0000-015|BSCS|2"
  "Amelia|Taylor|amelia.taylor@university.edu|26-0000-016|BSCS|2"
  "Ethan|Moore|ethan.moore@university.edu|26-0000-017|BSCS|2"
  "Isabella|Jackson|isabella.jackson@university.edu|26-0000-018|BSCS|2"
  "Daniel|Martin|daniel.martin@university.edu|26-0000-019|BSCS|2"
  "Ava|Lee|ava.lee@university.edu|26-0000-020|BSCS|2"
  "Matthew|Perez|matthew.perez@university.edu|26-0000-021|BSCS|2"
  "Harper|Thompson|harper.thompson@university.edu|26-0000-022|BSCS|2"
  "Henry|White|henry.white@university.edu|26-0000-023|BSCS|2"
  "Evelyn|Harris|evelyn.harris@university.edu|26-0000-024|BSCS|2"
  "Joseph|Sanchez|joseph.sanchez@university.edu|26-0000-025|BSCS|2"
  "Abigail|Clark|abigail.clark@university.edu|26-0000-026|BSCS|2"
  "Samuel|Ramirez|samuel.ramirez@university.edu|26-0000-027|BSCS|2"
  "Emily|Lewis|emily.lewis@university.edu|26-0000-028|BSCS|2"
  "Jackson|Robinson|jackson.robinson@university.edu|26-0000-029|BSCS|2"
  "Elizabeth|Walker|elizabeth.walker@university.edu|26-0000-030|BSCS|2"
  "Sebastian|Young|sebastian.young@university.edu|26-0000-031|BSCS|2"
  "Sofia|Allen|sofia.allen@university.edu|26-0000-032|BSCS|2"
  "Jack|King|jack.king@university.edu|26-0000-033|BSCS|2"
  "Avery|Wright|avery.wright@university.edu|26-0000-034|BSCS|2"
  "Owen|Scott|owen.scott@university.edu|26-0000-035|BSCS|2"
  "Ella|Torres|ella.torres@university.edu|26-0000-036|BSCS|2"
  "Theodore|Nguyen|theodore.nguyen@university.edu|26-0000-037|BSCS|2"
  "Madison|Hill|madison.hill@university.edu|26-0000-038|BSCS|2"
  "Aiden|Flores|aiden.flores@university.edu|26-0000-039|BSCS|2"
  "Scarlett|Green|scarlett.green@university.edu|26-0000-040|BSCS|2"
  "Wyatt|Adams|wyatt.adams@university.edu|26-0000-041|BSCS|2"
  "Victoria|Nelson|victoria.nelson@university.edu|26-0000-042|BSCS|2"
  "Johnathan|Baker|johnathan.baker@university.edu|26-0000-043|BSCS|2"
  "Aria|Rivera|aria.rivera@university.edu|26-0000-044|BSCS|2"
  "Oliver|Campbell|oliver.campbell@university.edu|26-0000-045|BSCS|2"
  "Grace|Mitchell|grace.mitchell@university.edu|26-0000-046|BSCS|2"
  "Michael|Carter|michael.carter@university.edu|26-0000-047|BSCS|2"
  "Chloe|Roberts|chloe.roberts@university.edu|26-0000-048|BSCS|2"
  "Elijah|Gomez|elijah.gomez@university.edu|26-0000-049|BSCS|2"
  "Camila|Phillips|camila.phillips@university.edu|26-0000-050|BSCS|2"
  "Levi|Evans|levi.evans@university.edu|26-0000-051|BSCS|2"
  "Penelope|Turner|penelope.turner@university.edu|26-0000-052|BSCS|2"
  "David|Diaz|david.diaz@university.edu|26-0000-053|BSCS|2"
  "Riley|Cruz|riley.cruz@university.edu|26-0000-054|BSCS|2"
  "Leo|Parker|leo.parker@university.edu|26-0000-055|BSCS|2"
  "Layla|Edwards|layla.edwards@university.edu|26-0000-056|BSCS|2"
  "Isaiah|Collins|isaiah.collins@university.edu|26-0000-057|BSCS|2"
  "Lillian|Reyes|lillian.reyes@university.edu|26-0000-058|BSCS|2"
  "Gabriel|Stewart|gabriel.stewart@university.edu|26-0000-059|BSCS|2"
  "Nora|Morris|nora.morris@university.edu|26-0000-060|BSCS|2"
  "Julian|Morales|julian.morales@university.edu|26-0000-061|BSCS|2"
  "Zoey|Murphy|zoey.murphy|26-0000-062|BSCS|2"
  "Mateo|Cook|mateo.cook@university.edu|26-0000-063|BSCS|2"
  "Mila|Rogers|mila.rogers@university.edu|26-0000-064|BSCS|2"
  "Anthony|Gutierrez|anthony.gutierrez@university.edu|26-0000-065|BSCS|2"
  "Eleanor|Ortiz|eleanor.ortiz@university.edu|26-0000-066|BSCS|2"
  "Lincoln|Morgan|lincoln.morgan@university.edu|26-0000-067|BSCS|2"
  "Hannah|Cooper|hannah.cooper@university.edu|26-0000-068|BSCS|2"
  "Joshua|Peterson|joshua.peterson@university.edu|26-0000-069|BSCS|2"
  "Lily|Bailey|lily.bailey@university.edu|26-0000-070|BSCS|2"
  "Christopher|Reed|christopher.reed@university.edu|26-0000-071|BSCS|2"
  "Addison|Kelly|addison.kelly@university.edu|26-0000-072|BSCS|2"
  "Andrew|Howard|andrew.howard@university.edu|26-0000-073|BSCS|2"
  "Aubrey|Ramos|aubrey.ramos@university.edu|26-0000-074|BSCS|2"
  "Thomas|Kim|thomas.kim@university.edu|26-0000-075|BSCS|2"
  "Ellie|Cox|ellie.cox@university.edu|26-0000-076|BSCS|2"
  "Miles|Ward|miles.ward@university.edu|26-0000-077|BSCS|2"
  "Stella|Richardson|stella.richardson@university.edu|26-0000-078|BSCS|2"
  "Ryan|Watson|ryan.watson@university.edu|26-0000-079|BSCS|2"
  "Natalie|Brooks|natalie.brooks@university.edu|26-0000-080|BSCS|2"
  "Nathan|Chavez|nathan.chavez@university.edu|26-0000-081|BSCS|2"
  "Zoe|Wood|zoe.wood@university.edu|26-0000-082|BSCS|2"
  "Adrian|James|adrian.james@university.edu|26-0000-083|BSCS|2"
  "Leah|Bennet|leah.bennet@university.edu|26-0000-084|BSCS|2"
  "Christian|Gray|christian.gray@university.edu|26-0000-085|BSCS|2"
  "Hazel|Mendoza|hazel.mendoza@university.edu|26-0000-086|BSCS|2"
  "Colton|Ruiz|colton.ruiz@university.edu|26-0000-087|BSCS|2"
  "Violet|Hughes|violet.hughes@university.edu|26-0000-088|BSCS|2"
  "Eli|Price|eli.price@university.edu|26-0000-089|BSCS|2"
  "Aurora|Alvarez|aurora.alvarez@university.edu|26-0000-090|BSCS|2"
  "Aaron|Castillo|aaron.castillo@university.edu|26-0000-091|BSCS|2"
  "Savannah|Sanders|savannah.sanders@university.edu|26-0000-092|BSCS|2"
  "Hunter|Patel|hunter.patel@university.edu|26-0000-093|BSCS|2"
  "Audrey|Myers|audrey.myers@university.edu|26-0000-094|BSCS|2"
  "Jonathan|Long|jonathan.long@university.edu|26-0000-095|BSCS|2"
  "Brooklyn|Ross|brooklyn.ross@university.edu|26-0000-096|BSCS|2"
  "Nolan|Foster|nolan.foster@university.edu|26-0000-097|BSCS|2"
  "Bella|Jimenez|bella.jimenez@university.edu|26-0000-098|BSCS|2"
  "Jeremiah|Porter|jeremiah.porter@university.edu|26-0000-099|BSCS|2"
  "Claire|Erickson|claire.erickson@university.edu|26-0000-100|BSCS|2"
  "Ezekiel|Webb|ezekiel.webb@university.edu|26-0000-101|BSCS|2"
  "Skylar|Hamilton|skylar.hamilton@university.edu|26-0000-102|BSCS|2"
  "Grayson|Fisher|grayson.fisher@university.edu|26-0000-103|BSCS|2"
  "Lucy|Santiago|lucy.santiago@university.edu|26-0000-104|BSCS|2"
  "Josiah|Gomez|josiah.gomez@university.edu|26-0000-105|BSCS|2"
  "Paisley|Sullivan|paisley.sullivan@university.edu|26-0000-106|BSCS|2"
  "Charles|Wallace|charles.wallace@university.edu|26-0000-107|BSCS|2"
  "Everly|Hicks|everly.hicks@university.edu|26-0000-108|BSCS|2"
  "Caleb|Cole|caleb.cole@university.edu|26-0000-109|BSCS|2"
  "Anna|West|anna.west@university.edu|26-0000-110|BSCS|2"
  "Robert|Jordan|robert.jordan@university.edu|26-0000-111|BSCS|2"
  "Caroline|Owens|caroline.owens@university.edu|26-0000-112|BSCS|2"
  "Nathaniel|Reynolds|nathaniel.reynolds@university.edu|26-0000-113|BSCS|2"
  "Nova|Fisher|nova.fisher@university.edu|26-0000-114|BSCS|2"
  "Jordan|Ellis|jordan.ellis@university.edu|26-0000-115|BSCS|2"
  "Genesis|Harrison|genesis.harrison@university.edu|26-0000-116|BSCS|2"
  "Christian|Gibson|christian.gibson@university.edu|26-0000-117|BSCS|2"
  "Kennedy|Mcdonald|kennedy.mcdonald@university.edu|26-0000-118|BSCS|2"
  "Colton|Cruz|colton.cruz2@university.edu|26-0000-119|BSCS|2"
  "Sadie|Marshall|sadie.marshall@university.edu|26-0000-120|BSCS|2"
  "Landon|Ortiz|landon.ortiz2@university.edu|26-0000-121|BSCS|2"
  "Faith|Gomez|faith.gomez@university.edu|26-0000-122|BSCS|2"
  "Angel|Murray|angel.murray@university.edu|26-0000-123|BSCS|2"
  "Eva|Freeman|eva.freeman@university.edu|26-0000-124|BSCS|2"
  "Asher|Wells|asher.wells@university.edu|26-0000-125|BSCS|2"
  "Autumn|Webb|autumn.webb@university.edu|26-0000-126|BSCS|2"
  "Cameron|Simpson|cameron.simpson@university.edu|26-0000-127|BSCS|2"
  "Serenity|Stevens|serenity.stevens@university.edu|26-0000-128|BSCS|2"
  "Carson|Tucker|carson.tucker@university.edu|26-0000-129|BSCS|2"
  "Stella|Porter|stella.porter2@university.edu|26-0000-130|BSCS|2"
  "Robert|Hunter|robert.hunter@university.edu|26-0000-131|BSCS|2"
  "Julia|Hicks|julia.hicks@university.edu|26-0000-132|BSCS|2"
  "Nicholas|Crawford|nicholas.crawford@university.edu|26-0000-133|BSCS|2"
  "Aaliyah|Boyd|aaliyah.boyd@university.edu|26-0000-134|BSCS|2"
  "Dominic|Mason|dominic.mason@university.edu|26-0000-135|BSCS|2"
  "Elena|Morales|elena.morales2@university.edu|26-0000-136|BSCS|2"
  "Jaxson|Kennedy|jaxson.kennedy@university.edu|26-0000-137|BSCS|2"
  "Ariana|Warren|ariana.warren@university.edu|26-0000-138|BSCS|2"
  "Greyson|Burns|greyson.burns@university.edu|26-0000-139|BSCS|2"
  "Emilia|Shaw|emilia.shaw@university.edu|26-0000-140|BSCS|2"
  "Ian|Snyder|ian.snyder@university.edu|26-0000-141|BSCS|2"
  "Maya|Rice|maya.rice@university.edu|26-0000-142|BSCS|2"
  "Gavin|Robertson|gavin.robertson@university.edu|26-0000-143|BSCS|2"
  "Clara|Hunt|clara.hunt@university.edu|26-0000-144|BSCS|2"
  "Austin|Black|austin.black@university.edu|26-0000-145|BSCS|2"
  "Mary|Daniels|mary.daniels@university.edu|26-0000-146|BSCS|2"
  "Brandon|Palmer|brandon.palmer@university.edu|26-0000-147|BSCS|2"
  "Jade|Nichols|jade.nichols@university.edu|26-0000-148|BSCS|2"
  "Kim|Grant|kim.grant@university.edu|26-0000-149|BSCS|2"
  "Oliver|Gordon|oliver.gordon@university.edu|26-0000-150|BSCS|2"
  "Lydia|Perry|lydia.perry@university.edu|26-0000-151|BSCS|2"
  "Alex|Fowler|alex.fowler@university.edu|26-0000-152|BSCS|2"
  "Ruby|Gibson|ruby.gibson@university.edu|26-0000-153|BSCS|2"
  "Tyler|Garza|tyler.garza@university.edu|26-0000-154|BSCS|2"
  "Ivy|Berry|ivy.berry@university.edu|26-0000-155|BSCS|2"
  "Zachary|Oliver|zachary.oliver@university.edu|26-0000-156|BSCS|2"
  "Quinn|Olson|quinn.olson@university.edu|26-0000-157|BSCS|2"
  "Chase|Duncan|chase.duncan@university.edu|26-0000-158|BSCS|2"
  "Sadie|Burgess|sadie.burgess@university.edu|26-0000-159|BSCS|2"
  "Diego|Burke|diego.burke@university.edu|26-0000-160|BSCS|2"
  "Piper|Yates|piper.yates@university.edu|26-0000-161|BSCS|2"
  "Milo|Zimmerman|milo.zimmerman@university.edu|26-0000-162|BSCS|2"
  "Paige|Hodge|paige.hodge@university.edu|26-0000-163|BSCS|2"
  "Cole|Gill|cole.gill@university.edu|26-0000-164|BSCS|2"
  "Isabel|Silva|isabel.silva@university.edu|26-0000-165|BSCS|2"
  "Damian|Saunders|damian.saunders@university.edu|26-0000-166|BSCS|2"
  "Sienna|Frank|sienna.frank@university.edu|26-0000-167|BSCS|2"
  "Ryder|Lawrence|ryder.lawrence@university.edu|26-0000-168|BSCS|2"
  "Iris|Jacobs|iris.jacobs@university.edu|26-0000-169|BSCS|2"
  "Max|Rowe|max.rowe@university.edu|26-0000-170|BSCS|2"
  "Kenzie|Rhodes|kenzie.rhodes@university.edu|26-0000-171|BSCS|2"
  "Brice|Ball|brice.ball@university.edu|26-0000-172|BSCS|2"
  "Gemma|Garrison|gemma.garrison@university.edu|26-0000-173|BSCS|2"
  "Xavier|Chandler|xavier.chandler@university.edu|26-0000-174|BSCS|2"
  "Faith|Howard|faith.howard@university.edu|26-0000-175|BSCS|2"
  "Ezra|Goodwin|ezra.goodwin@university.edu|26-0000-176|BSCS|2"
  "Evie|Mccarthy|evie.mccarthy@university.edu|26-0000-177|BSCS|2"
  "Silas|Flynn|silas.flynn@university.edu|26-0000-178|BSCS|2"
  "Fiona|Blake|fiona.blake@university.edu|26-0000-179|BSCS|2"
  "Tristan|Gibbs|tristan.gibbs@university.edu|26-0000-180|BSCS|2"
  "Hope|Garner|hope.garner@university.edu|26-0000-181|BSCS|2"
  "Richard|Meadows|richard.meadows@university.edu|26-0000-182|BSCS|2"
  "Esther|Barton|esther.barton@university.edu|26-0000-183|BSCS|2"
  "Waylon|Iglesias|waylon.iglesias@university.edu|26-0000-184|BSCS|2"
  "Freya|Kirby|freya.kirby@university.edu|26-0000-185|BSCS|2"
  "Marcus|Good|marcus.good@university.edu|26-0000-186|BSCS|2"
  "Zara|Sutton|zara.sutton@university.edu|26-0000-187|BSCS|2"
  "Vincent|Vance|vincent.vance@university.edu|26-0000-188|BSCS|2"
  "Elsie|Love|elsie.love@university.edu|26-0000-189|BSCS|2"
  "Miles|Munoz|miles.munoz@university.edu|26-0000-190|BSCS|2"
  "June|Jennings|june.jennings@university.edu|26-0000-191|BSCS|2"
  "George|Fitzgerald|george.fitgerald@university.edu|26-0000-192|BSCS|2"
  "Lola|Mckinney|lola.mckinney@university.edu|26-0000-193|BSCS|2"
  "Weston|Collier|weston.collier@university.edu|26-0000-194|BSCS|2"
  "Miriam|Herring|miriam.herring@university.edu|26-0000-195|BSCS|2"
  "Declan|Soto|declan.soto@university.edu|26-0000-196|BSCS|2"
  "Joanna|Pearson|joanna.pearson@university.edu|26-0000-197|BSCS|2"
  "Ashton|Sharpe|ashton.sharpe@university.edu|26-0000-198|BSCS|2"
  "Genevieve|Bauer|genevieve.bauer@university.edu|26-0000-199|BSCS|2"
)

for line in "${students[@]}"; do
  IFS='|' read -r firstName lastName email studentNumber program yearLevel <<< "$line"
  
  emailEscaped=$(sql_quote "$email")
  firstNameEscaped=$(sql_quote "$firstName")
  lastNameEscaped=$(sql_quote "$lastName")
  programEscaped=$(sql_quote "$program")
  studentNumberEscaped=$(sql_quote "$studentNumber")
  
  # Check if student record already exists
  studentExists=$(mysql_exec "SELECT studentID FROM Student WHERE studentNumber = '$studentNumberEscaped' LIMIT 1;" | tail -1)
  if [[ -n "$studentExists" && "$studentExists" != "studentID" ]]; then
    echo "  Skipping existing student $studentNumber (studentID $studentExists)"
    continue
  fi
  
  # Check if user exists
  userID=$(mysql_exec "SELECT userID FROM User WHERE email = '$emailEscaped' LIMIT 1;" | tail -1)
  
  if [[ -z "$userID" || "$userID" == "userID" ]]; then
    # User doesn't exist, create both User and Student
    passwordHash=$(php -r "echo password_hash(\$argv[1], PASSWORD_BCRYPT);" "$PASSWORD") || {
      echo "ERROR: Failed to hash password"
      exit 1
    }
    passwordHashEscaped=$(sql_quote "$passwordHash")
    
    query="
      START TRANSACTION;
      INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
      VALUES ('$firstNameEscaped', '$lastNameEscaped', '$emailEscaped', '$passwordHashEscaped', 2026, 'student', 'active');
      SET @lastUserID = LAST_INSERT_ID();
      INSERT INTO Student (userID, studentNumber, program, yearLevel) 
      VALUES (@lastUserID, '$studentNumberEscaped', '$programEscaped', $yearLevel);
      COMMIT;
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Inserted student $email (student number $studentNumber)"
    else
      echo "  ERROR: Failed to insert student $email"
      exit 1
    fi
  else
    # User exists but Student record doesn't, create Student only
    query="
      INSERT INTO Student (userID, studentNumber, program, yearLevel) 
      VALUES ($userID, '$studentNumberEscaped', '$programEscaped', $yearLevel);
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Added student record for existing user $email (student number $studentNumber)"
    else
      echo "  ERROR: Failed to add student record for $email"
      exit 1
    fi
  fi
done

echo "Seeding admins..."

# Admin users
declare -a admins=(
  "Dr.|Anderson|admin.anderson@university.edu|26-0002|Registrar|Enrollment Services|Director"
  "Ms.|White|admin.white@university.edu|26-0003|Department|Academic Affairs|Coordinator"
)

for line in "${admins[@]}"; do
  IFS='|' read -r firstName lastName email adminCode role department designation <<< "$line"
  
  emailEscaped=$(sql_quote "$email")
  firstNameEscaped=$(sql_quote "$firstName")
  lastNameEscaped=$(sql_quote "$lastName")
  adminCodeEscaped=$(sql_quote "$adminCode")
  roleEscaped=$(sql_quote "$role")
  departmentEscaped=$(sql_quote "$department")
  designationEscaped=$(sql_quote "$designation")
  
  # Check if admin record already exists
  adminExists=$(mysql_exec "SELECT adminID FROM Admin WHERE adminCode = '$adminCodeEscaped' LIMIT 1;" | tail -1)
  if [[ -n "$adminExists" && "$adminExists" != "adminID" ]]; then
    echo "  Skipping existing admin $adminCode (adminID $adminExists)"
    continue
  fi
  
  # Check if user exists
  userID=$(mysql_exec "SELECT userID FROM User WHERE email = '$emailEscaped' LIMIT 1;" | tail -1)
  
  if [[ -z "$userID" || "$userID" == "userID" ]]; then
    # User doesn't exist, create both User and Admin
    passwordHash=$(php -r "echo password_hash(\$argv[1], PASSWORD_BCRYPT);" "$PASSWORD") || {
      echo "ERROR: Failed to hash password"
      exit 1
    }
    passwordHashEscaped=$(sql_quote "$passwordHash")
    
    query="
      START TRANSACTION;
      INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
      VALUES ('$firstNameEscaped', '$lastNameEscaped', '$emailEscaped', '$passwordHashEscaped', 2026, 'admin', 'active');
      SET @lastUserID = LAST_INSERT_ID();
      INSERT INTO Admin (userID, adminCode, role, department, designation) 
      VALUES (@lastUserID, '$adminCodeEscaped', '$roleEscaped', '$departmentEscaped', '$designationEscaped');
      COMMIT;
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Inserted admin $email (admin code $adminCode)"
    else
      echo "  ERROR: Failed to insert admin $email"
      exit 1
    fi
  else
    # User exists but Admin record doesn't, create Admin only
    query="
      INSERT INTO Admin (userID, adminCode, role, department, designation) 
      VALUES ($userID, '$adminCodeEscaped', '$roleEscaped', '$departmentEscaped', '$designationEscaped');
    "
    
    if mysql_exec "$query" > /dev/null 2>&1; then
      echo "  Added admin record for existing user $email (admin code $adminCode)"
    else
      echo "  ERROR: Failed to add admin record for $email"
      exit 1
    fi
  fi
done

echo "Users seeding complete."
