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
                       s.enrolledCount, s.instructor, s.semester, c.courseCode, c.courseName
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

        $updates = $this->db->query('SELECT * FROM enrollmentUpdates ORDER BY created_at DESC');

        return require __DIR__ . '/../../public/views/student/enrollment-updates.php';
    }

    /**
     * Show change password page
     */
    public function changePassword() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        return require __DIR__ . '/../../public/views/student/change-password.php';
    }

    /**
     * Get user by ID
     */
    public function getUser($id) {
        return $this->db->queryOne('SELECT * FROM User WHERE userID = ?', [$id]);
    }

    /**
     * Verify login credentials
     */
    public function login($email, $password) {
        $user = $this->db->queryOne('SELECT userID as id, email, password, firstName as first_name, userType as role FROM User WHERE email = ?', [$email]);
        
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
            $studentNumber = trim($_POST['student_number'] ?? '');
            if (empty($studentNumber)) {
                $studentNumber = 'STU-' . date('y') . '-' . str_pad($newUser['userID'], 4, '0', STR_PAD_LEFT);
            }
            $program = $_POST['program'] ?? 'BSCS';
            $major = $_POST['major'] ?? 'General';

            $this->db->execute(
                'INSERT INTO Student (userID, studentNumber, program, yearLevel, points, major) VALUES (?, ?, ?, 1, 0, ?)',
                [$newUser['userID'], $studentNumber, $program, $major]
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

        $updates = $this->db->query('SELECT * FROM enrollmentUpdates ORDER BY created_at DESC');
        
        return require __DIR__ . '/../../public/views/admin/enrollment-updates.php';
    }
}
