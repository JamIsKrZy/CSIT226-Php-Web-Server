<?php
namespace App\Controllers;

use App\Core\Database;

class ApiController {
    private $db;

    public function __construct() {
        $this->db = new Database();
        header('Content-Type: application/json');
    }

    /**
     * Return JSON response
     */
    protected function response($success, $message, $data = null, $code = 200) {
        http_response_code($code);
        return json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }

    // ==================== USER CRUD ====================

    /**
     * Get all users
     */
    public function getUsers() {
        try {
            $users = $this->db->query('
                SELECT u.userID, u.firstName, u.lastName, u.email, u.userType, u.status, u.createdAt 
                FROM User u 
                ORDER BY u.createdAt DESC
            ');
            echo $this->response(true, 'Users retrieved successfully', $users, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching users: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get single user
     */
    public function getUser() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo $this->response(false, 'User ID is required', null, 400);
            return;
        }

        try {
            $user = $this->db->queryOne('SELECT * FROM User WHERE userID = ?', [$id]);
            if (!$user) {
                echo $this->response(false, 'User not found', null, 404);
                return;
            }
            echo $this->response(true, 'User retrieved successfully', $user, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching user: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Create new user
     */
    public function createUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['firstName'], $data['lastName'], $data['email'], $data['password'])) {
            echo $this->response(false, 'Missing required fields', null, 400);
            return;
        }

        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $userType = $data['userType'] ?? 'student';
            
            $this->db->execute(
                'INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['firstName'],
                    $data['lastName'],
                    $data['email'],
                    $hashedPassword,
                    $data['academicYear'] ?? 2026,
                    $userType,
                    'active'
                ]
            );

            $userID = $this->db->lastInsertId();

            // If student, create student record
            if ($userType === 'student') {
                $this->db->execute(
                    'INSERT INTO Student (userID, points, studentNumber, major) VALUES (?, ?, ?, ?)',
                    [$userID, 0, $data['studentNumber'] ?? null, $data['major'] ?? null]
                );
            }

            // If admin, create admin record
            if ($userType === 'admin') {
                $this->db->execute(
                    'INSERT INTO Admin (userID, department, designation) VALUES (?, ?, ?)',
                    [$userID, $data['department'] ?? null, $data['designation'] ?? null]
                );
            }

            echo $this->response(true, 'User created successfully', ['userID' => $userID], 201);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error creating user: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Update user
     */
    public function updateUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['userID'])) {
            echo $this->response(false, 'User ID is required', null, 400);
            return;
        }

        try {
            $id = $data['userID'];
            $sql = 'UPDATE User SET ';
            $params = [];
            $fields = [];

            if (isset($data['firstName'])) {
                $fields[] = 'firstName = ?';
                $params[] = $data['firstName'];
            }
            if (isset($data['lastName'])) {
                $fields[] = 'lastName = ?';
                $params[] = $data['lastName'];
            }
            if (isset($data['email'])) {
                $fields[] = 'email = ?';
                $params[] = $data['email'];
            }
            if (isset($data['status'])) {
                $fields[] = 'status = ?';
                $params[] = $data['status'];
            }

            if (empty($fields)) {
                echo $this->response(false, 'No fields to update', null, 400);
                return;
            }

            $sql .= implode(', ', $fields) . ' WHERE userID = ?';
            $params[] = $id;

            $this->db->execute($sql, $params);
            echo $this->response(true, 'User updated successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error updating user: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Delete user
     */
    public function deleteUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['userID'])) {
            echo $this->response(false, 'User ID is required', null, 400);
            return;
        }

        try {
            $this->db->execute('DELETE FROM User WHERE userID = ?', [$data['userID']]);
            echo $this->response(true, 'User deleted successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error deleting user: ' . $e->getMessage(), null, 500);
        }
    }

    // ==================== COURSE CRUD ====================

    /**
     * Get all courses
     */
    public function getCourses() {
        try {
            $courses = $this->db->query('
                SELECT courseID, courseCode, courseName, credits, category, department, createdAt 
                FROM Course 
                ORDER BY courseCode ASC
            ');
            echo $this->response(true, 'Courses retrieved successfully', $courses, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching courses: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Create course
     */
    public function createCourse() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['courseCode'], $data['courseName'], $data['credits'])) {
            echo $this->response(false, 'Missing required fields', null, 400);
            return;
        }

        try {
            $this->db->execute(
                'INSERT INTO Course (courseCode, courseName, credits, category, description, department) 
                 VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $data['courseCode'],
                    $data['courseName'],
                    $data['credits'],
                    $data['category'] ?? null,
                    $data['description'] ?? null,
                    $data['department'] ?? null
                ]
            );

            echo $this->response(true, 'Course created successfully', ['courseID' => $this->db->lastInsertId()], 201);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error creating course: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Update course
     */
    public function updateCourse() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['courseID'])) {
            echo $this->response(false, 'Course ID is required', null, 400);
            return;
        }

        try {
            $id = $data['courseID'];
            $sql = 'UPDATE Course SET ';
            $params = [];
            $fields = [];

            foreach (['courseName', 'credits', 'category', 'description', 'department'] as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }

            if (empty($fields)) {
                echo $this->response(false, 'No fields to update', null, 400);
                return;
            }

            $sql .= implode(', ', $fields) . ' WHERE courseID = ?';
            $params[] = $id;

            $this->db->execute($sql, $params);
            echo $this->response(true, 'Course updated successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error updating course: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Delete course
     */
    public function deleteCourse() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['courseID'])) {
            echo $this->response(false, 'Course ID is required', null, 400);
            return;
        }

        try {
            $this->db->execute('DELETE FROM Course WHERE courseID = ?', [$data['courseID']]);
            echo $this->response(true, 'Course deleted successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error deleting course: ' . $e->getMessage(), null, 500);
        }
    }

    // ==================== SECTION CRUD ====================

    /**
     * Get all sections
     */
    public function getSections() {
        try {
            $sections = $this->db->query('
                SELECT s.sectionID, s.courseID, s.sectionCode, s.timeslot, s.room, s.capacity, s.enrolledCount, 
                       s.instructor, s.semester, c.courseName, c.courseCode
                FROM Section s
                JOIN Course c ON s.courseID = c.courseID
                ORDER BY s.sectionCode ASC
            ');
            echo $this->response(true, 'Sections retrieved successfully', $sections, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching sections: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Create section
     */
    public function createSection() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['courseID'], $data['sectionCode'])) {
            echo $this->response(false, 'Missing required fields', null, 400);
            return;
        }

        try {
            $this->db->execute(
                'INSERT INTO Section (courseID, sectionCode, timeslot, room, capacity, instructor, semester) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['courseID'],
                    $data['sectionCode'],
                    $data['timeslot'] ?? null,
                    $data['room'] ?? null,
                    $data['capacity'] ?? 50,
                    $data['instructor'] ?? null,
                    $data['semester'] ?? '1st Semester'
                ]
            );

            echo $this->response(true, 'Section created successfully', ['sectionID' => $this->db->lastInsertId()], 201);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error creating section: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Update section
     */
    public function updateSection() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['sectionID'])) {
            echo $this->response(false, 'Section ID is required', null, 400);
            return;
        }

        try {
            $id = $data['sectionID'];
            $sql = 'UPDATE Section SET ';
            $params = [];
            $fields = [];

            foreach (['sectionCode', 'timeslot', 'room', 'capacity', 'enrolledCount', 'instructor', 'semester'] as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }

            if (empty($fields)) {
                echo $this->response(false, 'No fields to update', null, 400);
                return;
            }

            $sql .= implode(', ', $fields) . ' WHERE sectionID = ?';
            $params[] = $id;

            $this->db->execute($sql, $params);
            echo $this->response(true, 'Section updated successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error updating section: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Delete section
     */
    public function deleteSection() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['sectionID'])) {
            echo $this->response(false, 'Section ID is required', null, 400);
            return;
        }

        try {
            $this->db->execute('DELETE FROM Section WHERE sectionID = ?', [$data['sectionID']]);
            echo $this->response(true, 'Section deleted successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error deleting section: ' . $e->getMessage(), null, 500);
        }
    }

    // ==================== ADMIN CRUD ====================

    /**
     * Get all admins
     */
    public function getAdmins() {
        try {
            $admins = $this->db->query('
                SELECT a.adminID AS id, a.adminCode AS admin_id, CONCAT(u.firstName, " ", u.lastName) AS name, 
                       u.email, a.role, u.status, u.createdAt AS created_at
                FROM Admin a
                JOIN User u ON a.userID = u.userID
                ORDER BY a.createdAt DESC
            ');
            echo $this->response(true, 'Admins retrieved successfully', $admins, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching admins: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get single admin
     */
    public function getAdmin() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo $this->response(false, 'Admin ID is required', null, 400);
            return;
        }

        try {
            $admin = $this->db->queryOne('
                SELECT a.adminID AS id, a.adminCode, a.role, a.department, a.designation, 
                       u.userID, u.firstName, u.lastName, u.email, u.status, u.createdAt
                FROM Admin a
                JOIN User u ON a.userID = u.userID
                WHERE a.adminID = ?
            ', [$id]);
            
            if (!$admin) {
                echo $this->response(false, 'Admin not found', null, 404);
                return;
            }
            echo $this->response(true, 'Admin retrieved successfully', $admin, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching admin: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Create admin
     */
    public function createAdmin() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['firstName'], $data['lastName'], $data['email'], $data['password'], $data['adminCode'])) {
            echo $this->response(false, 'Missing required fields', null, 400);
            return;
        }

        try {
            $hashed = password_hash($data['password'], PASSWORD_BCRYPT);
            $this->db->execute(
                'INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$data['firstName'], $data['lastName'], $data['email'], $hashed, 2026, 'admin', 'active']
            );
            $userID = $this->db->lastInsertId();

            $this->db->execute(
                'INSERT INTO Admin (userID, adminCode, role, department, designation) VALUES (?, ?, ?, ?, ?)',
                [$userID, $data['adminCode'], $data['role'] ?? null, $data['department'] ?? null, $data['designation'] ?? null]
            );

            echo $this->response(true, 'Admin created successfully', ['adminID' => $this->db->lastInsertId()], 201);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error creating admin: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Update admin
     */
    public function updateAdmin() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['adminID'])) {
            echo $this->response(false, 'Admin ID is required', null, 400);
            return;
        }

        try {
            $adminID = $data['adminID'];
            $admin = $this->db->queryOne('SELECT userID FROM Admin WHERE adminID = ?', [$adminID]);
            if (!$admin) {
                echo $this->response(false, 'Admin not found', null, 404);
                return;
            }

            $userID = $admin['userID'];

            // Update User fields
            $userFields = [];
            $userParams = [];
            foreach (['firstName', 'lastName', 'email', 'status'] as $field) {
                if (isset($data[$field])) {
                    $userFields[] = "$field = ?";
                    $userParams[] = $data[$field];
                }
            }

            if (!empty($userFields)) {
                $userParams[] = $userID;
                $this->db->execute('UPDATE User SET ' . implode(', ', $userFields) . ' WHERE userID = ?', $userParams);
            }

            // Update Admin fields
            $adminFields = [];
            $adminParams = [];
            foreach (['adminCode', 'role', 'department', 'designation'] as $field) {
                if (isset($data[$field])) {
                    $adminFields[] = "$field = ?";
                    $adminParams[] = $data[$field];
                }
            }

            if (!empty($adminFields)) {
                $adminParams[] = $adminID;
                $this->db->execute('UPDATE Admin SET ' . implode(', ', $adminFields) . ' WHERE adminID = ?', $adminParams);
            }

            echo $this->response(true, 'Admin updated successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error updating admin: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Delete admin
     */
    public function deleteAdmin() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['adminID'])) {
            echo $this->response(false, 'Admin ID is required', null, 400);
            return;
        }

        try {
            $admin = $this->db->queryOne('SELECT userID FROM Admin WHERE adminID = ?', [$data['adminID']]);
            if ($admin) {
                $this->db->execute('DELETE FROM Admin WHERE adminID = ?', [$data['adminID']]);
                $this->db->execute('DELETE FROM User WHERE userID = ?', [$admin['userID']]);
            }
            echo $this->response(true, 'Admin deleted successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error deleting admin: ' . $e->getMessage(), null, 500);
        }
    }

    // ==================== STUDENT CRUD ====================

    /**
     * Get student's enrollment plan with sections
     */
    public function getStudentEnrollmentPlan() {
        $studentID = $_GET['studentID'] ?? null;
        if (!$studentID) {
            echo $this->response(false, 'Student ID is required', null, 400);
            return;
        }

        try {
            $plan = $this->db->query('
                SELECT pi.plannedItemID, c.courseCode, c.courseName, c.credits, s.sectionCode, 
                       s.timeslot, s.room, pi.enrollmentStatus, pi.priority, pi.commitmentLevel
                FROM PlannedItem pi
                JOIN Schedule sch ON pi.scheduleID = sch.scheduleID
                JOIN Section s ON pi.sectionID = s.sectionID
                JOIN Course c ON s.courseID = c.courseID
                WHERE sch.studentID = ?
                ORDER BY c.courseCode
            ', [$studentID]);
            
            echo $this->response(true, 'Enrollment plan retrieved', $plan, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching plan: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Add section to enrollment
     */
    public function addSectionToEnrollment() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['studentID'], $data['sectionID'])) {
            echo $this->response(false, 'Missing required fields', null, 400);
            return;
        }

        try {
            // Get or create schedule
            $schedule = $this->db->queryOne(
                'SELECT scheduleID FROM Schedule WHERE studentID = ? AND semester = ? LIMIT 1',
                [$data['studentID'], $data['semester'] ?? '1st Semester']
            );

            if (!$schedule) {
                $this->db->execute(
                    'INSERT INTO Schedule (studentID, semester, academicYear, status) VALUES (?, ?, ?, ?)',
                    [$data['studentID'], $data['semester'] ?? '1st Semester', 2026, 'draft']
                );
                $scheduleID = $this->db->lastInsertId();
            } else {
                $scheduleID = $schedule['scheduleID'];
            }

            $this->db->execute(
                'INSERT INTO PlannedItem (scheduleID, sectionID, commitmentLevel, priority) VALUES (?, ?, ?, ?)',
                [$scheduleID, $data['sectionID'], $data['commitmentLevel'] ?? 5, $data['priority'] ?? 1]
            );

            echo $this->response(true, 'Section added to enrollment plan', null, 201);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error adding section: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove section from enrollment
     */
    public function removeSectionFromEnrollment() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['plannedItemID'])) {
            echo $this->response(false, 'Planned Item ID is required', null, 400);
            return;
        }

        try {
            $this->db->execute('DELETE FROM PlannedItem WHERE plannedItemID = ?', [$data['plannedItemID']]);
            echo $this->response(true, 'Section removed from enrollment plan', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error removing section: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get section demand data (for section-demand page)
     */
    public function getSectionDemand() {
        try {
            $courses = $this->db->query('
                SELECT c.courseID, c.courseCode, c.courseName, 
                       COUNT(DISTINCT s.sectionID) AS sectionCount
                FROM Course c
                LEFT JOIN Section s ON c.courseID = s.courseID
                GROUP BY c.courseID
                ORDER BY c.courseCode
            ');

            $sections = $this->db->query('
                SELECT s.sectionID, s.sectionCode, s.timeslot, s.room, 
                       s.enrolledCount, s.capacity, c.courseCode, c.courseName
                FROM Section s
                JOIN Course c ON s.courseID = c.courseID
                ORDER BY s.sectionCode
            ');

            echo $this->response(true, 'Section demand retrieved', [
                'courses' => $courses,
                'sections' => $sections
            ], 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching demand: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get student interest data (aggregated from planned items)
     */
    public function getStudentInterestData() {
        try {
            // Aggregate interest counts per section
            $interests = $this->db->query('
                SELECT c.courseCode AS code, c.courseName AS name, s.sectionCode AS section,
                       COUNT(pi.plannedItemID) AS interest,
                       CASE 
                           WHEN COUNT(pi.plannedItemID) > 20 THEN "High"
                           WHEN COUNT(pi.plannedItemID) > 10 THEN "Moderate"
                           ELSE "Low"
                       END AS demand
                FROM Section s
                JOIN Course c ON s.courseID = c.courseID
                LEFT JOIN PlannedItem pi ON s.sectionID = pi.sectionID
                GROUP BY s.sectionID
                ORDER BY interest DESC
            ');

            echo $this->response(true, 'Interest data retrieved', $interests, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching interest data: ' . $e->getMessage(), null, 500);
        }
    }
}
