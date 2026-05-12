<?php

namespace App\Controllers;

use App\Core\Database;

class AdminController {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Display admin management page (data loaded via AJAX)
     */
    public function management() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        return require __DIR__ . '/../../public/views/admin/admin-management.php';
    }

    /**
     * Add admin (form POST - for backwards compatibility)
     */
    public function add() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        $data = $_POST;
        $hashed = password_hash($data['password'], PASSWORD_BCRYPT);

        $this->db->execute(
            'INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [$data['first_name'], $data['last_name'], $data['email'], $hashed, 2026, 'admin', 'active']
        );
        $userID = $this->db->lastInsertId();

        $this->db->execute(
            'INSERT INTO Admin (userID, adminCode, role) VALUES (?, ?, ?)',
            [$userID, $data['admin_id'], 'admin']
        );

        header('Location: /admin/management?success=1');
    }

    /**
     * Edit admin (form POST - for backwards compatibility)
     */
    public function edit() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        $data = $_POST;
        $adminID = $data['id'];

        $admin = $this->db->queryOne('SELECT userID FROM Admin WHERE adminID = ?', [$adminID]);
        if (!$admin) {
            header('Location: /admin/management?error=1');
            return;
        }

        $userID = $admin['userID'];

        $this->db->execute(
            'UPDATE User SET firstName = ?, lastName = ?, email = ?, status = ? WHERE userID = ?',
            [$data['first_name'], $data['last_name'], $data['email'], $data['status'] == 'Active' ? 'active' : 'inactive', $userID]
        );

        $this->db->execute(
            'UPDATE Admin SET adminCode = ? WHERE adminID = ?',
            [$data['admin_id'], $adminID]
        );

        header('Location: /admin/management?updated=1');
    }

    /**
     * Delete admin (form POST - for backwards compatibility)
     */
    public function delete() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        $data = $_POST;
        $adminID = $data['id'];

        $admin = $this->db->queryOne('SELECT userID FROM Admin WHERE adminID = ?', [$adminID]);
        if ($admin) {
            $this->db->execute('DELETE FROM Admin WHERE adminID = ?', [$adminID]);
            $this->db->execute('DELETE FROM User WHERE userID = ?', [$admin['userID']]);
        }

        header('Location: /admin/management?deleted=1');
    }
}
