<?php
namespace App\Controllers;

use App\Core\Database;

class LoginController {
    private $userController;

    public function __construct() {
        $this->userController = new UserController();
    }
    
    // Show the login form
    public function showLogin() {
        return require __DIR__ . '/../../public/views/auth/login.php';
    }

    public function showAdminLogin() {
        return require __DIR__ . '/../../public/views/admin/admin-login.php';
    }

    // Handle admin login form submission
    public function handleAdminLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Admin credentials are required';
            header('Location: /admin/login');
            exit;
        }

        // Validate against database
        $user = $this->userController->login($email, $password);
        
        if ($user && ($user['role'] ?? 'student') === 'admin') {
            $db = new Database();
            $adminRec = $db->queryOne("SELECT adminCode FROM Admin WHERE userID = ? LIMIT 1", [$user['id']]);
            $adminCode = $adminRec ? $adminRec['adminCode'] : null;

            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'] ?? '',
                'role' => 'admin',
                'student_number' => $adminCode
            ];
            $_SESSION['success'] = 'Admin login successful!';
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid admin credentials or insufficient privileges';
            header('Location: /admin/login');
            exit;
        }
    }

    // Handle login form submission
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required';
            header('Location: /');
            exit;
        }

        // Validate against database
        $user = $this->userController->login($email, $password);
        
        if ($user) {
            $studentId = null;
            $studentNumber = null;
            if (($user['role'] ?? 'student') === 'student') {
                $studentRec = $this->userController->getStudentByUserId((int) $user['id']);
                if ($studentRec) {
                    $studentId = (int) $studentRec['studentID'];
                    $studentNumber = $studentRec['studentNumber'];
                }
            } else if (($user['role'] ?? 'student') === 'admin') {
                $db = new Database();
                $adminRec = $db->queryOne("SELECT adminCode FROM Admin WHERE userID = ? LIMIT 1", [$user['id']]);
                if ($adminRec) {
                    $studentNumber = $adminRec['adminCode'];
                }
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'] ?? '',
                'role' => $user['role'],
                'student_id' => $studentId,
                'student_number' => $studentNumber,
            ];
            $_SESSION['success'] = 'Login successful!';
            
            // Redirect based on user role
            $redirectUrl = ($user['role'] === 'admin') ? '/admin/student-interest' : '/dashboard';
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /');
            exit;
        }
    }

    // Show the signup form
    public function showSignup() {
        return require __DIR__ . '/../../public/views/auth/signup.php';
    }

    public function handleSignup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $first_name       = trim($_POST['first_name'] ?? '');
        $last_name        = trim($_POST['last_name'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $password         = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $academic_year    = $_POST['academic_year'] ?? 2026;
        $user_type        = $_POST['user_type'] ?? 'student';
        $status           = $_POST['status'] ?? 'active';

        // Whitelist user_type and status to prevent arbitrary values
        $allowed_types    = ['student', 'admin'];
        $allowed_statuses = ['active', 'inactive'];

        if (!in_array($user_type, $allowed_types)) {
            $user_type = 'student';
        }
        if (!in_array($status, $allowed_statuses)) {
            $status = 'active';
        }

        // Required field validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /signup');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format';
            header('Location: /signup');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters long';
            header('Location: /signup');
            exit;
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: /signup');
            exit;
        }

        if (!is_numeric($academic_year) || $academic_year < 2000 || $academic_year > 2100) {
            $_SESSION['error'] = 'Invalid academic year';
            header('Location: /signup');
            exit;
        }

        // Check duplicate email
        $db = new Database();
        $existingUser = $db->queryOne('SELECT userID FROM User WHERE email = ?', [$email]);
        if ($existingUser) {
            $_SESSION['error'] = 'Email already registered. Please login or use a different email.';
            header('Location: /signup');
            exit;
        }

        // Create the user
        try {
            $this->userController->createUser(
                $email, $password, $first_name, $last_name,
                (int) $academic_year, $user_type, $status
            );
            $_SESSION['success'] = 'Account created successfully! Please login with your credentials.';
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating account. Please try again.';
            header('Location: /signup');
            exit;
        }
    }

    // Handle logout
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /');
        exit;
    }
}