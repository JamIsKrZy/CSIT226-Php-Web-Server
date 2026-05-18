<?php
/**
 * Bulk Seeder for Waitlist Simulation Testing
 * This seeds 45 new student users and places them into the CS231-F1 section
 * to exceed the capacity limit of 40 and test the waitlist queuing system.
 */

// Connection details matching docker-compose
$host = getenv('DB_HOST') ?: 'db';
$db = getenv('DB_NAME') ?: 'mydb';
$user = getenv('DB_USER') ?: 'myuser';
$password = getenv('DB_PASSWORD') ?: 'mypassword';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "Successfully connected to MySQL database.\n";
    
    // Hash password for all seeded students
    $passwordHash = password_hash('password123', PASSWORD_BCRYPT);
    
    // Find sectionID for CS231-F1
    $sectionStmt = $pdo->prepare("SELECT sectionID, capacity FROM Section WHERE sectionCode = ? LIMIT 1");
    $sectionStmt->execute(['CS231-F1']);
    $section = $sectionStmt->fetch();
    
    if (!$section) {
        throw new Exception("Section CS231-F1 not found. Please run the standard seeds first.");
    }
    
    $sectionID = (int)$section['sectionID'];
    $capacity = (int)$section['capacity'];
    echo "Found CS231-F1 section with ID: $sectionID (Capacity: $capacity)\n";
    
    // Begin bulk insert
    $pdo->beginTransaction();
    
    $studentsToSeed = 45;
    echo "Seeding $studentsToSeed student users...\n";
    
    // Prepare statements
    $insertUser = $pdo->prepare("
        INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
        VALUES (?, ?, ?, ?, 2026, 'student', 'active')
    ");
    
    $insertStudent = $pdo->prepare("
        INSERT INTO Student (userID, studentNumber, program, yearLevel) 
        VALUES (?, ?, 'BSCS', 2)
    ");
    
    $insertSchedule = $pdo->prepare("
        INSERT INTO Schedule (studentID, semester, academicYear, status, notes) 
        VALUES (?, '1st Semester', 2026, 'draft', 'Waitlist Simulation Seed Plan')
    ");
    
    $insertPlannedItem = $pdo->prepare("
        INSERT INTO PlannedItem (scheduleID, sectionID, priority, enrollmentStatus, createdAt) 
        VALUES (?, ?, 1, 'planned', ?)
    ");
    
    $baseTime = time() - ($studentsToSeed * 60); // Spread created timestamps over the last 45 minutes
    
    for ($i = 1; $i <= $studentsToSeed; $i++) {
        $numStr = sprintf('%03d', $i);
        $firstName = "TestUser";
        $lastName = "Waitlist$numStr";
        $email = "waitlist.student$numStr@university.edu";
        $studentNumber = "26-1000-$numStr";
        
        // 1. Insert User
        $insertUser->execute([$firstName, $lastName, $email, $passwordHash]);
        $userID = $pdo->lastInsertId();
        
        // 2. Insert Student
        $insertStudent->execute([$userID, $studentNumber]);
        $studentID = $pdo->lastInsertId();
        
        // 3. Insert Schedule
        $insertSchedule->execute([$studentID]);
        $scheduleID = $pdo->lastInsertId();
        
        // 4. Insert PlannedItem (discrete structure section F1) with staggered timestamp
        $timestamp = date('Y-m-d H:i:s', $baseTime + ($i * 60));
        $insertPlannedItem->execute([$scheduleID, $sectionID, $timestamp]);
        
        if ($i % 10 === 0 || $i === $studentsToSeed) {
            echo "  Seeded $i / $studentsToSeed students...\n";
        }
    }
    
    $pdo->commit();
    echo "\n✓ Waitlist simulation seeding completed successfully!\n";
    echo "45 students registered and planned for CS231-F1. The waitlist queue is now fully simulated.\n";
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
