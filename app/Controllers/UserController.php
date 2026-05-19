<?php
namespace App\Controllers;

use App\Core\Database;

class UserController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Display all users in a table
     */
    public function listUsers() {
        try {
            $users = $this->db->query('
                SELECT userID, firstName, lastName, email, userType, status, createdAt 
                FROM User 
                ORDER BY createdAt DESC
            ');
            return require __DIR__ . '/../../public/views/users.php';
        } catch (\Exception $e) {
            echo "Error fetching users: " . $e->getMessage();
        }
    }

    /**
     * Display all courses in a table
     */
    public function listCourses() {
        try {
            $courses = $this->db->query('
                SELECT courseID, courseCode, courseName, credits, category, department, createdAt 
                FROM Course 
                ORDER BY courseCode ASC
            ');
            return require __DIR__ . '/../../public/views/courses.php';
        } catch (\Exception $e) {
            echo "Error fetching courses: " . $e->getMessage();
        }
    }

    /**
     * Display all sections in a table
     */
    public function listSections() {
        try {
            $sections = $this->db->query('
                SELECT s.sectionID, s.courseID, s.sectionCode, s.timeslot, s.room, s.capacity, 
                       (SELECT COUNT(*) FROM PlannedItem pi WHERE pi.sectionID = s.sectionID) AS enrolledCount, 
                       s.instructor, s.semester, c.courseCode, c.courseName
                FROM Section s
                JOIN Course c ON s.courseID = c.courseID
                ORDER BY s.sectionCode ASC
            ');
            return require __DIR__ . '/../../public/views/sections.php';
        } catch (\Exception $e) {
            echo "Error fetching sections: " . $e->getMessage();
        }
    }

    /**
     * Show student dashboard
     */
    public function dashboard() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $studentID = null;
        if ($_SESSION['user']['role'] === 'student') {
            $studentID = $_SESSION['user']['student_id'] ?? null;
        }

        // If no student ID (e.g. Admin user), default to the first student in the system for demonstration
        if (!$studentID) {
            $firstStudent = $this->db->queryOne("SELECT studentID FROM Student LIMIT 1");
            $studentID = $firstStudent ? (int)$firstStudent['studentID'] : null;
        }

        $plannedSections = [];
        $totalPlannedUnits = 0;
        $highDemandCount = 0;
        $totalPlannedCount = 0;
        $readinessPercent = 0;
        $readinessText = "No profile found";
        $semesterName = "1st Semester";
        $academicYear = 2026;

        if ($studentID) {
            $activeSch = $this->db->queryOne("SELECT semester, academicYear FROM Schedule WHERE studentID = ? ORDER BY scheduleID DESC LIMIT 1", [$studentID]);
            if ($activeSch) {
                $semesterName = $activeSch['semester'];
                $academicYear = (int)$activeSch['academicYear'];
            }
            $plannedSections = $this->db->query('
                SELECT pi.plannedItemID, pi.sectionID, c.courseID, c.courseCode, c.courseName, c.credits,
                       s.sectionCode, s.timeslot, s.room, s.capacity,
                       (SELECT COUNT(*) FROM PlannedItem p WHERE p.sectionID = s.sectionID) AS enrolledCount,
                       (SELECT COUNT(*) FROM PlannedItem p WHERE p.sectionID = s.sectionID AND p.createdAt < pi.createdAt) AS studentsBefore,
                       pi.enrollmentStatus, pi.priority
                FROM PlannedItem pi
                JOIN Schedule sch ON pi.scheduleID = sch.scheduleID
                JOIN Section s ON pi.sectionID = s.sectionID
                JOIN Course c ON s.courseID = c.courseID
                WHERE sch.studentID = ?
                ORDER BY c.courseCode
            ', [$studentID]);

            $totalPlannedCount = count($plannedSections);

            foreach ($plannedSections as $sec) {
                $totalPlannedUnits += (int)$sec['credits'];
                $capacity = max((int)$sec['capacity'], 1);
                $enrolled = (int)$sec['enrolledCount'];
                if (($enrolled / $capacity) >= 0.8) {
                    $highDemandCount++;
                }
            }

            if ($totalPlannedUnits === 0) {
                $readinessPercent = 0;
                $readinessText = "Add courses to start";
            } else {
                $baseReadiness = min(100, round(($totalPlannedUnits / 18) * 100));
                
                $fullSectionsCount = 0;
                foreach ($plannedSections as $sec) {
                    if ($sec['enrolledCount'] >= $sec['capacity']) {
                        $fullSectionsCount++;
                    }
                }
                $readinessPercent = max(0, $baseReadiness - ($fullSectionsCount * 20));
                
                if ($readinessPercent >= 90) {
                    $readinessText = "Excellent! Ready to enroll";
                } elseif ($readinessPercent >= 70) {
                    $readinessText = "Good to proceed";
                } elseif ($readinessPercent >= 40) {
                    $readinessText = "Needs attention (low units/full sections)";
                } else {
                    $readinessText = "Not ready (review your plan)";
                }
            }
        }

        $enrollmentUpdates = $this->db->query('
            SELECT title, description, status, created_at 
            FROM enrollmentUpdates 
            ORDER BY created_at DESC 
            LIMIT 20
        ');

        return require __DIR__ . '/../../public/views/student/dashboard.php';
    }

    /**
     * Show section demand page
     */
    public function sectionDemand() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        return require __DIR__ . '/../../public/views/student/section-demand.php';
    }

    /**
     * Show enrollment plan page
     */
    public function enrollmentPlan() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $studentID = $this->getStudentIdByUserId((int) ($_SESSION['user']['id'] ?? 0));

        return require __DIR__ . '/../../public/views/student/enrollment-plan.php';
    }

    /**
     * Show alternative sections page
     */
    public function alternativeSections() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $studentID = $this->getStudentIdByUserId((int) ($_SESSION['user']['id'] ?? 0));

        return require __DIR__ . '/../../public/views/student/alternative-sections.php';
    }

    /**
     * Show enrollment updates page
     */
    public function enrollmentUpdates() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $type = $_GET['type'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 5; // 5 announcements per page
        $offset = ($page - 1) * $limit;

        $sql = 'SELECT * FROM enrollmentUpdates';
        $countSql = 'SELECT COUNT(*) as total FROM enrollmentUpdates';
        $params = [];

        if ($type !== '') {
            $sql .= ' WHERE status = ?';
            $countSql .= ' WHERE status = ?';
            $params[] = $type;
        }

        $sql .= ' ORDER BY created_at DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;

        $totalResult = $this->db->queryOne($countSql, $params);
        $totalUpdates = (int)($totalResult['total'] ?? 0);
        $totalPages = max(1, ceil($totalUpdates / $limit));

        $updates = $this->db->query($sql, $params);

        return require __DIR__ . '/../../public/views/student/enrollment-updates.php';
    }

    /**
     * Show change password page
     */
    public function changePassword() {
        return require __DIR__ . '/../../public/views/auth/change-password.php';
    }

    /**
     * Handle updating user password
     */
    public function handleChangePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $student_id = trim($_POST['student_id'] ?? '');
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($email) || empty($student_id) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /change-password');
            exit;
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: /change-password');
            exit;
        }

        if (strlen($new_password) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters long';
            header('Location: /change-password');
            exit;
        }

        try {
            // Find user by email
            $user = $this->db->queryOne("SELECT userID, email, userType FROM User WHERE email = ?", [$email]);
            if (!$user) {
                $_SESSION['error'] = 'User account not found';
                header('Location: /change-password');
                exit;
            }

            $userID = (int)$user['userID'];
            $userType = $user['userType'];

            // Verify identifier (studentNumber or adminCode)
            if ($userType === 'admin') {
                $admin = $this->db->queryOne("SELECT adminID FROM Admin WHERE userID = ? AND adminCode = ? LIMIT 1", [$userID, $student_id]);
                if (!$admin) {
                    $_SESSION['error'] = 'Invalid Admin Code for this email';
                    header('Location: /change-password');
                    exit;
                }
            } else {
                $student = $this->db->queryOne("SELECT studentID FROM Student WHERE userID = ? AND studentNumber = ? LIMIT 1", [$userID, $student_id]);
                if (!$student) {
                    $_SESSION['error'] = 'Invalid Student ID for this email';
                    header('Location: /change-password');
                    exit;
                }
            }

            // Update user password in database
            $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
            $this->db->execute("UPDATE User SET password = ? WHERE userID = ?", [$hashedPassword, $userID]);

            $_SESSION['success'] = 'Password updated successfully!';

            if (isset($_SESSION['user'])) {
                // User is already logged in, redirect to their dashboard
                header('Location: /dashboard');
                exit;
            } else {
                // Not logged in (forgot password flow), redirect to login page (/)
                header('Location: /');
                exit;
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating password. Please try again.';
            header('Location: /change-password');
            exit;
        }
    }

    /**
     * Get user by ID
     */
    public function getUser($id) {
        return $this->db->queryOne('SELECT * FROM User WHERE userID = ?', [$id]);
    }

    /**
     * Resolve the Student table ID for a User account.
     */
    public function getStudentIdByUserId(int $userId): ?int {
        $student = $this->db->queryOne(
            'SELECT studentID FROM Student WHERE userID = ? LIMIT 1',
            [$userId]
        );

        return ($student !== false) ? (int) $student['studentID'] : null;
    }

    /**
     * Get the student record for a User account.
     */
    public function getStudentByUserId(int $userId): ?array {
        $student = $this->db->queryOne(
            'SELECT studentID, studentNumber, program FROM Student WHERE userID = ? LIMIT 1',
            [$userId]
        );
        return $student !== false ? $student : null;
    }

    /**
     * Verify login credentials
     */
    public function login($email, $password) {
        $user = $this->db->queryOne(
            'SELECT userID as id, email, password, firstName as first_name, lastName as last_name, userType as role FROM User WHERE email = ?',
            [$email]
        );
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }

    /**
     * Create new student user (User + Student record)
     */
    public function createUser($email, $password, $first_name, $last_name, $academic_year = 2026, $user_type = 'student', $status = 'active') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert into User table
        $this->db->execute(
            'INSERT INTO User (email, password, firstName, lastName, academicYear, userType, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)',
            [$email, $hashedPassword, $first_name, $last_name, $academic_year, $user_type, $status]
        );

        // Get the new user's ID
        $newUser = $this->db->queryOne(
            'SELECT userID FROM User WHERE email = ?',
            [$email]
        );

        // If admin type, create the Admin specialization record
        if ($user_type === 'admin' && $newUser) {
            $adminCode = trim($_POST['admin_code'] ?? '');
            if (empty($adminCode)) {
                $adminCode = 'ADM-' . date('Y') . '-' . str_pad($newUser['userID'], 3, '0', STR_PAD_LEFT);
            }
            $department = $_POST['department'] ?? 'Enrollment Services';
            $designation = $_POST['designation'] ?? 'Staff';

            $this->db->execute(
                'INSERT INTO Admin (userID, adminCode, role, department, designation) VALUES (?, ?, ?, ?, ?)',
                [$newUser['userID'], $adminCode, 'admin', $department, $designation]
            );
        }

        // If student type, create the Student specialization record
        if ($user_type === 'student' && $newUser) {
            // Generate student number starting with 26-0000-000
            $lastStudent = $this->db->queryOne("SELECT studentNumber FROM Student WHERE studentNumber LIKE '26-%-%' ORDER BY studentNumber DESC LIMIT 1");
            if ($lastStudent && isset($lastStudent['studentNumber'])) {
                $lastNumStr = str_replace('-', '', substr($lastStudent['studentNumber'], 3));
                $nextNum = ((int)$lastNumStr) + 1;
            } else {
                $nextNum = 0;
            }
            $padded = sprintf('%07d', $nextNum);
            $studentNumber = '26-' . substr($padded, 0, 4) . '-' . substr($padded, 4);

            $program = 'BSCS';

            $this->db->execute(
                'INSERT INTO Student (userID, studentNumber, program, yearLevel) VALUES (?, ?, ?, 2)',
                [$newUser['userID'], $studentNumber, $program]
            );
        }

        return $newUser['userID'] ?? null;
    }

    /**
     * Update user
     */
    public function updateUser($id, $first_name, $last_name) {
        return $this->db->execute(
            'UPDATE User SET firstName = ?, lastName = ? WHERE userID = ?',
            [$first_name, $last_name, $id]
        );
    }

    /**
     * Delete user
     */
    public function deleteUser($id) {
        return $this->db->execute('DELETE FROM User WHERE userID = ?', [$id]);
    }

    /**
     * Show admin student interest monitoring page
     */
    public function adminStudentInterest() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        return require __DIR__ . '/../../public/views/admin/student-interest.php';
    }

    /**
     * Show admin enrollment updates page
     */
    public function adminEnrollmentUpdates() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        $type = $_GET['type'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 5; // 5 announcements per page
        $offset = ($page - 1) * $limit;

        $sql = 'SELECT * FROM enrollmentUpdates';
        $countSql = 'SELECT COUNT(*) as total FROM enrollmentUpdates';
        $params = [];

        if ($type !== '') {
            $sql .= ' WHERE status = ?';
            $countSql .= ' WHERE status = ?';
            $params[] = $type;
        }

        $sql .= ' ORDER BY created_at DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;

        $totalResult = $this->db->queryOne($countSql, $params);
        $totalUpdates = (int)($totalResult['total'] ?? 0);
        $totalPages = max(1, ceil($totalUpdates / $limit));

        $updates = $this->db->query($sql, $params);
        
        return require __DIR__ . '/../../public/views/admin/enrollment-updates.php';
    }
}
