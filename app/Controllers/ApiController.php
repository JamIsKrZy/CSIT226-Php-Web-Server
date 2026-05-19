<?php
namespace App\Controllers;

use App\Core\Database;
use App\Services\EnrollmentPlanService;

class ApiController {
    private $db;
    private EnrollmentPlanService $enrollmentPlanService;

    public function __construct() {
        $this->db = new Database();
        $this->enrollmentPlanService = new EnrollmentPlanService();
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

    /**
     * Resolve student ID from session (students) or optional request param (admins).
     */
    protected function resolveStudentId(?int $requestedStudentId = null): ?int {
        if (!isset($_SESSION['user']['id'])) {
            return null;
        }

        $row = $this->db->queryOne(
            'SELECT studentID FROM Student WHERE userID = ? LIMIT 1',
            [$_SESSION['user']['id']]
        );

        if ($row === false) {
            return null;
        }

        $sessionStudentId = (int) $row['studentID'];

        if (($requestedStudentId !== null)
            && (($_SESSION['user']['role'] ?? 'student') === 'admin')
        ) {
            return $requestedStudentId;
        }

        return $sessionStudentId;
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
                    [$userID, $studentNumber, $program]
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
                SELECT s.sectionID, s.courseID, s.sectionCode, s.timeslot, s.room, s.capacity, 
                       (SELECT COUNT(*) FROM PlannedItem pi WHERE pi.sectionID = s.sectionID) AS enrolledCount, 
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

            foreach (['sectionCode', 'timeslot', 'room', 'capacity', 'instructor', 'semester'] as $field) {
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
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo $this->response(false, 'Unauthorized: Admin access required', null, 403);
            return;
        }
        
        try {
            $search = $_GET['search'] ?? '';
            $role = $_GET['role'] ?? '';
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, (int)($_GET['limit'] ?? 10));
            $offset = ($page - 1) * $limit;

            $whereParts = [];
            $params = [];

            if ($search !== '') {
                $whereParts[] = '(u.firstName LIKE ? OR u.lastName LIKE ? OR u.email LIKE ? OR a.adminCode LIKE ? OR CONCAT(u.firstName, " ", u.lastName) LIKE ?)';
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            if ($role !== '') {
                $whereParts[] = 'a.role = ?';
                $params[] = $role;
            }

            $whereClause = !empty($whereParts) ? 'WHERE ' . implode(' AND ', $whereParts) : '';

            $countSql = "
                SELECT COUNT(*) as total
                FROM Admin a
                JOIN User u ON a.userID = u.userID
                $whereClause
            ";
            $countResult = $this->db->queryOne($countSql, $params);
            $total = (int)($countResult['total'] ?? 0);
            $totalPages = max(1, ceil($total / $limit));

            $sql = "
                SELECT a.adminID AS id, a.adminCode AS admin_id, CONCAT(u.firstName, ' ', u.lastName) AS name, 
                       u.email, a.role, u.status, u.createdAt AS created_at
                FROM Admin a
                JOIN User u ON a.userID = u.userID
                $whereClause
                ORDER BY a.createdAt DESC
                LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

            $admins = $this->db->query($sql, $params);

            echo $this->response(true, 'Admins retrieved successfully', [
                'list' => $admins,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => $totalPages
            ], 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching admins: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get single admin
     */
    public function getAdmin() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo $this->response(false, 'Unauthorized: Admin access required', null, 403);
            return;
        }
        
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
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo $this->response(false, 'Unauthorized: Admin access required', null, 403);
            return;
        }
        
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
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo $this->response(false, 'Unauthorized: Admin access required', null, 403);
            return;
        }
        
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
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo $this->response(false, 'Unauthorized: Admin access required', null, 403);
            return;
        }
        
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
        $studentID = $this->resolveStudentId(
            isset($_GET['studentID']) ? (int) $_GET['studentID'] : null
        );

        if (!$studentID) {
            echo $this->response(false, 'Student profile not found. Please log in again.', null, 401);
            return;
        }

        try {
            $plan = $this->enrollmentPlanService->getEnrollmentPlan($studentID);
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

        if (!$data || !isset($data['sectionID'])) {
            echo $this->response(false, 'Section ID is required', null, 400);
            return;
        }

        $studentID = $this->resolveStudentId(
            isset($data['studentID']) ? (int) $data['studentID'] : null
        );

        if (!$studentID) {
            echo $this->response(false, 'Student profile not found. Please log in again.', null, 401);
            return;
        }

        try {
            $this->enrollmentPlanService->addSectionToPlan(
                $studentID,
                (int) $data['sectionID'],
                $data['semester'] ?? '1st Semester',
                (int) ($data['priority'] ?? 1)
            );

            echo $this->response(true, 'Section added to enrollment plan', null, 201);
        } catch (\InvalidArgumentException $e) {
            echo $this->response(false, $e->getMessage(), null, 400);
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
            $this->enrollmentPlanService->removeSectionFromPlan((int) $data['plannedItemID']);
            echo $this->response(true, 'Section removed from enrollment plan', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error removing section: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get planned subjects with alternative sections for the student
     */
    public function getAlternativeSections() {
        $studentID = $this->resolveStudentId(
            isset($_GET['studentID']) ? (int) $_GET['studentID'] : null
        );

        if (!$studentID) {
            echo $this->response(false, 'Student profile not found. Please log in again.', null, 401);
            return;
        }

        try {
            $planned = $this->db->query('
                SELECT pi.plannedItemID, pi.sectionID AS preferredSectionID, pi.backupSectionID,
                       c.courseID, c.courseCode, c.courseName,
                       s.sectionCode, s.timeslot, s.room, s.capacity
                FROM PlannedItem pi
                JOIN Schedule sch ON pi.scheduleID = sch.scheduleID
                JOIN Section s ON pi.sectionID = s.sectionID
                JOIN Course c ON s.courseID = c.courseID
                WHERE sch.studentID = ?
                ORDER BY c.courseCode
            ', [$studentID]);

            if (empty($planned)) {
                echo $this->response(true, 'No planned subjects', [
                    'subjects' => [],
                    'stats' => [
                        'plannedSections' => 0,
                        'subjectsWithAlternatives' => 0,
                        'highInterestAlerts' => 0,
                    ],
                ], 200);
                return;
            }

            $courseIDs = array_unique(array_column($planned, 'courseID'));
            $placeholders = implode(',', array_fill(0, count($courseIDs), '?'));

            $allSections = $this->db->query("
                SELECT s.sectionID, s.courseID, s.sectionCode, s.timeslot, s.room, s.capacity,
                       COUNT(pi.plannedItemID) AS interest
                FROM Section s
                LEFT JOIN PlannedItem pi ON s.sectionID = pi.sectionID
                WHERE s.courseID IN ($placeholders)
                GROUP BY s.sectionID
                ORDER BY s.sectionCode
            ", $courseIDs);

            $sectionsByCourse = [];
            foreach ($allSections as $section) {
                $sectionsByCourse[$section['courseID']][] = $section;
            }

            $subjects = [];
            $subjectsWithAlternatives = 0;
            $highInterestAlerts = 0;

            foreach ($planned as $item) {
                $courseSections = $sectionsByCourse[$item['courseID']] ?? [];
                $preferred = null;
                $alternatives = [];

                foreach ($courseSections as $section) {
                    $sectionData = $this->formatSectionWithDemand($section);

                    if ((int) $section['sectionID'] === (int) $item['preferredSectionID']) {
                        $preferred = $sectionData;
                        if ($sectionData['label'] === 'HIGH') {
                            $highInterestAlerts++;
                        }
                    } else {
                        $alternatives[] = $sectionData;
                    }
                }

                if (count($alternatives) > 0) {
                    $subjectsWithAlternatives++;
                }

                $subjects[] = [
                    'plannedItemID' => (int) $item['plannedItemID'],
                    'courseID' => (int) $item['courseID'],
                    'code' => $item['courseCode'],
                    'title' => $item['courseName'],
                    'preferred' => $preferred,
                    'backupSectionID' => $item['backupSectionID'] ? (int) $item['backupSectionID'] : null,
                    'alternatives' => $alternatives,
                ];
            }

            echo $this->response(true, 'Alternative sections retrieved', [
                'subjects' => $subjects,
                'stats' => [
                    'plannedSections' => count($planned),
                    'subjectsWithAlternatives' => $subjectsWithAlternatives,
                    'highInterestAlerts' => $highInterestAlerts,
                ],
            ], 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching alternative sections: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Set a backup section for a planned item
     */
    public function setBackupSection() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['plannedItemID'])) {
            echo $this->response(false, 'Planned item ID is required', null, 400);
            return;
        }

        $plannedItemID = (int) $data['plannedItemID'];
        $backupSectionID = isset($data['sectionID']) && $data['sectionID'] !== null ? (int) $data['sectionID'] : null;

        try {
            // Verify planned item exists
            $plannedItem = $this->db->queryOne('
                SELECT pi.plannedItemID, pi.scheduleID, s.courseID
                FROM PlannedItem pi
                JOIN Section s ON pi.sectionID = s.sectionID
                WHERE pi.plannedItemID = ?
            ', [$plannedItemID]);

            if (!$plannedItem) {
                echo $this->response(false, 'Planned section not found', null, 404);
                return;
            }

            if ($backupSectionID !== null) {
                // Verify new backup section exists and belongs to the same course
                $newSection = $this->db->queryOne(
                    'SELECT sectionID, courseID FROM Section WHERE sectionID = ?',
                    [$backupSectionID]
                );

                if (!$newSection) {
                    echo $this->response(false, 'Backup section not found', null, 404);
                    return;
                }

                if ((int) $newSection['courseID'] !== (int) $plannedItem['courseID']) {
                    echo $this->response(false, 'Backup section must belong to the same course/subject', null, 400);
                    return;
                }
            }

            // Update backupSectionID
            $this->db->execute('
                UPDATE PlannedItem
                SET backupSectionID = ?, updatedAt = CURRENT_TIMESTAMP
                WHERE plannedItemID = ?
            ', [$backupSectionID, $plannedItemID]);

            echo $this->response(true, 'Backup section successfully updated', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error setting backup section: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Switch a planned item to a different section of the same subject
     */
    public function switchPlannedSection() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['plannedItemID'], $data['sectionID'])) {
            echo $this->response(false, 'Planned item ID and section ID are required', null, 400);
            return;
        }

        try {
            $plannedItem = $this->db->queryOne('
                SELECT pi.plannedItemID, pi.scheduleID, s.courseID AS currentCourseID
                FROM PlannedItem pi
                JOIN Section s ON pi.sectionID = s.sectionID
                WHERE pi.plannedItemID = ?
            ', [$data['plannedItemID']]);

            if (!$plannedItem) {
                echo $this->response(false, 'Planned section not found', null, 404);
                return;
            }

            $newSection = $this->db->queryOne(
                'SELECT sectionID, courseID FROM Section WHERE sectionID = ?',
                [$data['sectionID']]
            );

            if (!$newSection) {
                echo $this->response(false, 'Target section not found', null, 404);
                return;
            }

            if ((int) $plannedItem['currentCourseID'] !== (int) $newSection['courseID']) {
                echo $this->response(false, 'Cannot switch to a section from a different subject', null, 400);
                return;
            }

            $duplicate = $this->db->queryOne(
                'SELECT plannedItemID FROM PlannedItem WHERE scheduleID = ? AND sectionID = ? AND plannedItemID != ?',
                [$plannedItem['scheduleID'], $data['sectionID'], $data['plannedItemID']]
            );

            if ($duplicate) {
                echo $this->response(false, 'This section is already in your enrollment plan', null, 400);
                return;
            }

            $this->db->execute(
                'UPDATE PlannedItem SET sectionID = ? WHERE plannedItemID = ?',
                [$data['sectionID'], $data['plannedItemID']]
            );

            echo $this->response(true, 'Planned section updated successfully', null, 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error switching section: ' . $e->getMessage(), null, 500);
        }
    }

    private function formatSectionWithDemand(array $section): array {
        $interest = (int) ($section['interest'] ?? 0);
        $capacity = max((int) ($section['capacity'] ?? 1), 1);

        if ($interest > 20) {
            $label = 'HIGH';
        } elseif ($interest > 10) {
            $label = 'MODERATE';
        } else {
            $label = 'LOW';
        }

        return [
            'sectionID' => (int) $section['sectionID'],
            'section' => $section['sectionCode'],
            'schedule' => $section['timeslot'] ?? 'TBA',
            'room' => $section['room'] ?? 'TBA',
            'interest' => $interest,
            'capacity' => $capacity,
            'label' => $label,
        ];
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
                       (SELECT COUNT(*) FROM PlannedItem pi WHERE pi.sectionID = s.sectionID) AS enrolledCount, 
                       s.capacity, c.courseCode, c.courseName
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
            $search = $_GET['search'] ?? '';
            $section = $_GET['section'] ?? '';
            $demand = $_GET['demand'] ?? '';
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, (int)($_GET['limit'] ?? 10));
            $offset = ($page - 1) * $limit;

            $whereParts = [];
            $havingParts = [];
            $params = [];

            if ($search !== '') {
                $whereParts[] = '(c.courseCode LIKE ? OR c.courseName LIKE ? OR s.sectionCode LIKE ?)';
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            if ($section !== '') {
                $whereParts[] = 's.sectionCode = ?';
                $params[] = $section;
            }

            if ($demand !== '') {
                if ($demand === 'High') {
                    $havingParts[] = 'COUNT(pi.plannedItemID) > 20';
                } elseif ($demand === 'Moderate') {
                    $havingParts[] = 'COUNT(pi.plannedItemID) > 10 AND COUNT(pi.plannedItemID) <= 20';
                } elseif ($demand === 'Low') {
                    $havingParts[] = 'COUNT(pi.plannedItemID) <= 10';
                }
            }

            $whereClause = !empty($whereParts) ? 'WHERE ' . implode(' AND ', $whereParts) : '';
            $havingClause = !empty($havingParts) ? 'HAVING ' . implode(' AND ', $havingParts) : '';

            $countSql = "
                SELECT COUNT(*) as total FROM (
                    SELECT s.sectionID
                    FROM Section s
                    JOIN Course c ON s.courseID = c.courseID
                    LEFT JOIN PlannedItem pi ON s.sectionID = pi.sectionID
                    $whereClause
                    GROUP BY s.sectionID
                    $havingClause
                ) AS subquery
            ";
            $countResult = $this->db->queryOne($countSql, $params);
            $total = (int)($countResult['total'] ?? 0);
            $totalPages = max(1, ceil($total / $limit));

            $sql = "
                SELECT c.courseCode AS code, c.courseName AS name, s.sectionCode AS section,
                       COUNT(pi.plannedItemID) AS interest,
                       CASE 
                           WHEN COUNT(pi.plannedItemID) > 20 THEN 'High'
                           WHEN COUNT(pi.plannedItemID) > 10 THEN 'Moderate'
                           ELSE 'Low'
                       END AS demand
                FROM Section s
                JOIN Course c ON s.courseID = c.courseID
                LEFT JOIN PlannedItem pi ON s.sectionID = pi.sectionID
                $whereClause
                GROUP BY s.sectionID
                $havingClause
                ORDER BY interest DESC
                LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

            $interests = $this->db->query($sql, $params);

            // Fetch distinct sectionCodes for the filter dropdown
            $distinctSectionsResult = $this->db->query("
                SELECT DISTINCT sectionCode FROM Section ORDER BY sectionCode ASC
            ");
            $sectionsList = array_column($distinctSectionsResult, 'sectionCode');

            echo $this->response(true, 'Interest data retrieved', [
                'list' => $interests,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => $totalPages,
                'sectionsList' => $sectionsList
            ], 200);
        } catch (\Exception $e) {
            echo $this->response(false, 'Error fetching interest data: ' . $e->getMessage(), null, 500);
        }
    }
}
