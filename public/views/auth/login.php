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
</head>
<body>
    <header class="header-brand">
        <img src="/assets/images/logo.png" alt="CIT University Logo">
        <div class="header-text">
            <h1>CIT University</h1>
            <p>Enrollment System</p>
        </div>
    </header>

    <main class="login-card">
        <section class="left-panel">
            <div class="left-content">
                <h2>WITS</h2>
                <h3>Student Portal</h3>

                <p class="apply-text">For new enrollees, click <strong>APPLY</strong>.</p>
                <button type="button" class="apply-btn">APPLY</button>
                <p class="apply-note">The apply button is only enabled during the official application period.</p>
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

            <p class="forgot-password">Forgot Password? <a href="#">Click here</a></p>

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
