<?php
namespace App\Controllers;

use App\Core\Database;

class AdminController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Helper to check if user is admin
     */
    private function checkAdmin() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? 'student') !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
    }

    /**
     * Student Interest Monitoring (Read-Only)
     */
    public function studentInterest() {
        $this->checkAdmin();
        
        // Mock data for student interest
        $interestData = [
            ['code' => 'CSIT122', 'name' => 'Intermediate Programming', 'section' => 'F1', 'program' => 'BSCS', 'year' => '1', 'interest' => 55, 'demand' => 'High'],
            ['code' => 'CSIT122', 'name' => 'Intermediate Programming', 'section' => 'F2', 'program' => 'BSIT', 'year' => '1', 'interest' => 32, 'demand' => 'Moderate'],
            ['code' => 'CSIT228', 'name' => 'Database Systems', 'section' => 'F1', 'program' => 'BSCS', 'year' => '2', 'interest' => 48, 'demand' => 'High'],
            ['code' => 'MATH136', 'name' => 'Calculus I', 'section' => 'F1', 'program' => 'BSCS', 'year' => '1', 'interest' => 20, 'demand' => 'Low'],
            ['code' => 'CS132', 'name' => 'Computer Systems', 'section' => 'F2', 'program' => 'BSCS', 'year' => '1', 'interest' => 42, 'demand' => 'High']
        ];

        return require __DIR__ . '/../../public/views/admin/student-interest.php';
    }

    /**
     * Enrollment Updates Management (CRUD)
     */
    public function enrollmentUpdates() {
        $this->checkAdmin();
        
        $updates = $this->db->query('SELECT * FROM enrollment_updates ORDER BY created_at DESC');

        return require __DIR__ . '/../../public/views/admin/enrollment-updates.php';
    }

    public function addUpdate() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $status = $_POST['status'] ?? 'New';

            $this->db->execute(
                'INSERT INTO enrollment_updates (title, description, status) VALUES (?, ?, ?)',
                [$title, $description, $status]
            );
        }
        header('Location: /admin/enrollment-updates');
        exit;
    }

    public function editUpdate() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $status = $_POST['status'] ?? 'New';

            if ($id) {
                $this->db->execute(
                    'UPDATE enrollment_updates SET title = ?, description = ?, status = ? WHERE id = ?',
                    [$title, $description, $status, $id]
                );
            }
        }
        header('Location: /admin/enrollment-updates');
        exit;
    }

    public function deleteUpdate() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $this->db->execute('DELETE FROM enrollment_updates WHERE id = ?', [$id]);
            }
        }
        header('Location: /admin/enrollment-updates');
        exit;
    }

    /**
     * Admin Management (CRUD)
     */
    public function adminManagement() {
        $this->checkAdmin();
        
        $admins = $this->db->query("SELECT id, admin_id, first_name, last_name, CONCAT(first_name, ' ', last_name) as name, email, role, status, created_at FROM users WHERE role = 'admin'");

        return require __DIR__ . '/../../public/views/admin/admin-management.php';
    }

    public function addAdmin() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $admin_id = $_POST['admin_id'] ?? '';
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = password_hash($_POST['password'] ?? 'password123', PASSWORD_BCRYPT);
            $role = 'admin';
            $status = $_POST['status'] ?? 'Active';

            $this->db->execute(
                'INSERT INTO users (admin_id, first_name, last_name, email, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$admin_id, $first_name, $last_name, $email, $password, $role, $status]
            );
        }
        header('Location: /admin/management');
        exit;
    }

    public function editAdmin() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $admin_id = $_POST['admin_id'] ?? '';
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $status = $_POST['status'] ?? 'Active';

            if ($id) {
                $this->db->execute(
                    'UPDATE users SET admin_id = ?, first_name = ?, last_name = ?, email = ?, status = ? WHERE id = ?',
                    [$admin_id, $first_name, $last_name, $email, $status, $id]
                );
            }
        }
        header('Location: /admin/management');
        exit;
    }

    public function deleteAdmin() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $this->db->execute("DELETE FROM users WHERE id = ? AND role = 'admin'", [$id]);
            }
        }
        header('Location: /admin/management');
        exit;
    }
}
