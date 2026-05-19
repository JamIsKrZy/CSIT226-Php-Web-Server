<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIT University - Register</title>
    <link rel="stylesheet" href="/assets/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom Styles for Registration Portal */
        .role-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .role-option {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px 10px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #fafafa;
        }

        .role-option i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: var(--text-light);
            transition: color 0.25s ease;
        }

        .role-option span {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .role-option:hover {
            border-color: var(--primary-color);
            background: rgba(128, 0, 0, 0.02);
        }

        .role-option.active {
            border-color: var(--primary-color);
            background: rgba(128, 0, 0, 0.05);
            box-shadow: 0 4px 10px rgba(128, 0, 0, 0.1);
        }

        .role-option.active i {
            color: var(--primary-color);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .dynamic-section {
            transition: opacity 0.3s ease, transform 0.3s ease;
            opacity: 1;
            transform: translateY(0);
        }

        .dynamic-section.hidden {
            display: none;
            opacity: 0;
            transform: translateY(10px);
        }

        .terms-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 15px;
        }

        .terms-container input {
            margin-top: 4px;
            cursor: pointer;
        }

        .terms-container label {
            font-size: 0.85rem;
            color: var(--text-light);
            line-height: 1.4;
            cursor: pointer;
        }

        .btn-register-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.25s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-register-submit:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.25);
        }

        .btn-return-login {
            display: block;
            text-align: center;
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            width: 100%;
            transition: all 0.25s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px;
        }

        .btn-return-login:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Adjust card height and layout */
        .login-card {
            max-width: 950px;
        }

        .right-panel {
            padding: 40px;
        }

        @media (max-width: 850px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <header class="header-brand">
        <img src="/assets/images/logo.png" alt="CIT University Logo">
        <div class="header-text">
            <h1>CIT University</h1>
            <p>Pre-Enrollment System</p>
        </div>
    </header>

    <main class="login-card">
        <!-- Left Panel: Brand info & Navigation -->
        <section class="left-panel">
            <div class="left-content">
                <h2>WildPlanner</h2>
                <h3>Registration Portal</h3>

                <p class="register-text" style="font-size: 0.95rem; color: var(--text-dark); margin-bottom: 25px; line-height: 1.5;">
                    Create a WITS account to access pre-enrollment schedules, section demand tracking, and personalized academic planning.
                </p>
                
                <div style="margin-top: 40px;">
                    <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 10px; font-weight: 500;">Already registered?</p>
                    <a href="/login" class="btn-register admin-btn" style="display: block; text-align: center; background-color: transparent; color: var(--primary-color); border: 2px solid var(--primary-color); padding: 12px 20px; border-radius: 4px; font-weight: 600; text-decoration: none; transition: all 0.2s; font-size: 0.95rem; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i> BACK TO LOGIN
                    </a>
                </div>
            </div>
        </section>

        <!-- Right Panel: Dynamic Form -->
        <section class="right-panel">
            <p class="login-instruction" style="margin-bottom: 20px;">Please select your role and provide your account details below.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i>
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Role Selector -->
            <?php 
                $selectedRole = $_GET['role'] ?? 'student';
                if (!in_array($selectedRole, ['student', 'admin'])) {
                    $selectedRole = 'student';
                }
            ?>
            <div class="role-selector">
                <div class="role-option <?php echo $selectedRole === 'student' ? 'active' : ''; ?>" data-role="student" onclick="selectRole('student')">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Student</span>
                </div>
                <div class="role-option <?php echo $selectedRole === 'admin' ? 'active' : ''; ?>" data-role="admin" onclick="selectRole('admin')">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Administrator</span>
                </div>
            </div>

            <!-- Signup Form -->
            <form method="POST" action="/signup" id="signupForm">
                <input type="hidden" name="user_type" id="user_type" value="<?php echo htmlspecialchars($selectedRole); ?>">
                <input type="hidden" name="status" value="active">

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            placeholder="First Name" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            placeholder="Last Name" 
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="e.g., student.name@cit.edu" 
                        required
                    >
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Minimum 8 characters" 
                            minlength="8"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            placeholder="Re-enter password" 
                            minlength="8"
                            required
                        >
                    </div>
                </div>

                <!-- DYNAMIC STUDENT SECTION -->
                <div id="student-section" class="dynamic-section <?php echo $selectedRole !== 'student' ? 'hidden' : ''; ?>">
                    <div style="background-color: #f7f9fc; border-left: 4px solid var(--primary-color); padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                        <p style="color: var(--text-dark); font-size: 0.95rem; margin: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-color); font-size: 1.1rem;"></i>
                            <span>You will be automatically registered under the <strong>Bachelor of Science in Computer Science (BSCS)</strong> program for <strong>2nd Year, 1st Semester</strong>.</span>
                        </p>
                    </div>
                </div>

                <!-- DYNAMIC ADMIN SECTION -->
                <div id="admin-section" class="dynamic-section <?php echo $selectedRole !== 'admin' ? 'hidden' : ''; ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department">Department:</label>
                            <select id="department" name="department" style="width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 1rem; background: white;">
                                <option value="Enrollment Services" selected>Enrollment Services</option>
                                <option value="Academic Affairs">Academic Affairs</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Registrar Office">Registrar's Office</option>
                                <option value="Information Technology">Information Technology</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="designation">Designation / Role:</label>
                            <select id="designation" name="designation" style="width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 1rem; background: white;">
                                <option value="Registrar Director" selected>Registrar Director</option>
                                <option value="Program Chair">Program Chair</option>
                                <option value="CCS Coordinator">CCS Coordinator</option>
                                <option value="Department Admin">Department Admin</option>
                                <option value="Academic Staff">Academic Staff</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="terms-container">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the CIT University Enrollment System Terms of Service and Privacy Policy, including data collection and retention rules.</label>
                </div>

                <div class="button-row" style="margin-top: 25px;">
                    <button type="submit" class="btn-register-submit">REGISTER ACCOUNT</button>
                </div>
            </form>
        </section>
    </main>

    <script>
        // Function to toggle role tabs
        function selectRole(role) {
            // Update hidden role input
            document.getElementById('user_type').value = role;

            // Toggle active classes on selector buttons
            document.querySelectorAll('.role-option').forEach(option => {
                if (option.getAttribute('data-role') === role) {
                    option.classList.add('active');
                } else {
                    option.classList.remove('active');
                }
            });

            // Show/Hide relevant dynamic field sections
            const studentSec = document.getElementById('student-section');
            const adminSec = document.getElementById('admin-section');

            if (role === 'student') {
                studentSec.classList.remove('hidden');
                adminSec.classList.add('hidden');
                
                document.getElementById('email').placeholder = 'e.g., student.name@cit.edu';
            } else {
                studentSec.classList.add('hidden');
                adminSec.classList.remove('hidden');
                
                document.getElementById('email').placeholder = 'e.g., admin.name@cit.edu';
            }
        }

        // Form password match validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match. Please verify and try again.');
            }
        });

        // Initialize state on page load based on select role
        document.addEventListener('DOMContentLoaded', function() {
            const currentRole = document.getElementById('user_type').value;
            selectRole(currentRole);
        });
    </script>
</body>
</html>
