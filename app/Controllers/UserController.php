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
            $users = $this->db->query('SELECT id, email, first_name, last_name, created_at FROM users ORDER BY created_at DESC');
            return require __DIR__ . '/../../public/views/users.php';
        } catch (\Exception $e) {
            echo "Error fetching users: " . $e->getMessage();
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
        return $this->db->queryOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    /**
     * Verify login credentials
     */
    public function login($email, $password) {
        $user = $this->db->queryOne('SELECT id, email, password, first_name FROM users WHERE email = ?', [$email]);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }

    /**
     * Create new user
     */
    public function createUser($email, $password, $first_name, $last_name) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        return $this->db->execute(
            'INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)',
            [$email, $hashedPassword, $first_name, $last_name]
        );
    }

    /**
     * Update user
     */
    public function updateUser($id, $first_name, $last_name) {
        return $this->db->execute(
            'UPDATE users SET first_name = ?, last_name = ? WHERE id = ?',
            [$first_name, $last_name, $id]
        );
    }

    /**
     * Delete user
     */
    public function deleteUser($id) {
        return $this->db->execute('DELETE FROM users WHERE id = ?', [$id]);
    }
}
