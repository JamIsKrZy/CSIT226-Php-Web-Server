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
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name']
            ];
            $_SESSION['success'] = 'Login successful!';
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /');
            exit;
        }
    }

    // Show the signup form
    public function showSignup() {
        return require __DIR__ . '/../../public/views/signup.php';
    }

    // Handle signup form submission
    public function handleSignup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /signup');
            exit;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format';
            header('Location: /signup');
            exit;
        }

        // Validate password length
        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters long';
            header('Location: /signup');
            exit;
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: /signup');
            exit;
        }

        // Check if email already exists
        $db = new Database();
        $existingUser = $db->queryOne('SELECT id FROM users WHERE email = ?', [$email]);
        
        if ($existingUser) {
            $_SESSION['error'] = 'Email already registered. Please login or use a different email.';
            header('Location: /signup');
            exit;
        }

        // Create the user
        try {
            $this->userController->createUser($email, $password, $first_name, $last_name);
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
        session_destroy();
        header('Location: /');
        exit;
    }
}