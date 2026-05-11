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
}
