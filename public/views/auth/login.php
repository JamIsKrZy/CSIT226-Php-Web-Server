<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIT University Enrollment System - Login</title>
    <link rel="stylesheet" href="/assets/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <section class="left-panel">
            <div class="left-content">
                <h2>WildPlanner</h2>
                <h3>Student Portal</h3>

                <p class="register-text" style="font-size: 0.95rem; color: var(--text-dark); margin-bottom: 25px; line-height: 1.5;">
                    Need a new account? Register below to start planning your pre-enrollment.
                </p>
                <div class="registration-links" style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="/signup?role=student" class="btn-register student-btn" style="display: block; text-align: center; background-color: var(--primary-color); color: white; padding: 12px 20px; border-radius: 4px; font-weight: 600; text-decoration: none; transition: all 0.2s; border: 2px solid var(--primary-color); font-size: 0.95rem; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-graduation-cap" style="margin-right: 8px;"></i> REGISTER AS STUDENT
                    </a>
                    <a href="/signup?role=admin" class="btn-register admin-btn" style="display: block; text-align: center; background-color: transparent; color: var(--primary-color); border: 2px solid var(--primary-color); padding: 10px 20px; border-radius: 4px; font-weight: 600; text-decoration: none; transition: all 0.2s; font-size: 0.95rem; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-user-shield" style="margin-right: 8px;"></i> REGISTER AS ADMIN
                    </a>
                </div>
            </div>
        </section>

        <section class="right-panel">
            <p class="login-instruction">For students or applicants with existing account, you can login here.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form method="POST" action="/login" id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Email Address" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Password" 
                        required
                    >
                </div>

                <div class="button-row">
                    <button type="reset" class="btn-clear">CLEAR ENTRIES</button>
                    <button type="submit" class="btn-login">LOGIN</button>
                </div>
            </form>

            <p class="forgot-password">Forgot Password? <a href="/change-password">Click here</a></p>

            <div class="contact-info">
                <p>For inquiries, email us at</p>
                <a href="mailto:wits.admin@cit.edu">wits.admin@cit.edu</a>
            </div>
        </section>
    </main>

    <script>
        // Ensure form clear button works as expected
        document.querySelector('.btn-clear').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('loginForm').reset();
        });
    </script>
</body>
</html>
