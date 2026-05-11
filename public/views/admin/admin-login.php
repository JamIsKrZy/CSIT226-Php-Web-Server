<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CIT University</title>
    <link rel="stylesheet" href="/assets/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: #1a1a1a;"> <!-- Darker background for admin portal -->
    <div class="header-brand" style="flex-direction: column; margin-bottom: 25px;">
        <div style="background: white; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; box-shadow: 0 0 20px rgba(244, 180, 0, 0.2);">
            <img src="/assets/images/logo.png" alt="CIT University Logo" style="height: 70px;">
        </div>
        <div class="header-text" style="text-align: center;">
            <h1 style="color: white; font-size: 2rem;">CIT University</h1>
            <p style="color: var(--secondary-color); font-weight: 700; letter-spacing: 2px;">ADMINISTRATOR PORTAL</p>
        </div>
    </div>

    <div class="login-card" style="max-width: 450px; flex-direction: column; border-top: 5px solid var(--secondary-color);">
        <div class="right-panel" style="padding: 45px 40px;">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <p class="login-instruction" style="text-align: center; margin-bottom: 35px; color: var(--text-dark); font-weight: 500;">
                Please enter your administrative credentials to access the management dashboard.
            </p>

            <form action="/admin/login" method="POST">
                <div class="form-group">
                    <label for="email"><i class="fa-solid fa-user-shield" style="margin-right: 8px; color: var(--primary-color);"></i> Admin Email</label>
                    <input type="email" id="email" name="email" placeholder="admin.name@cit.edu" required style="background: #fdfdfd;">
                </div>

                <div class="form-group" style="margin-top: 25px;">
                    <label for="password"><i class="fa-solid fa-key" style="margin-right: 8px; color: var(--primary-color);"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required style="background: #fdfdfd;">
                </div>

                <button type="submit" class="btn-login" style="width: 100%; margin-top: 35px; height: 50px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    Login to Admin Panel <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div style="margin-top: 30px; text-align: center;">
                <a href="/" style="color: var(--text-light); text-decoration: none; font-size: 0.9rem; transition: color 0.2s;">
                    <i class="fa-solid fa-arrow-left"></i> Return to Student Login
                </a>
            </div>
        </div>
    </div>

    <div style="margin-top: 40px; color: rgba(255,255,255,0.4); font-size: 0.8rem; text-align: center;">
        <p>&copy; 2026 CIT University Enrollment Planning System</p>
        <p>Authorized Access Only</p>
    </div>
</body>
</html>
