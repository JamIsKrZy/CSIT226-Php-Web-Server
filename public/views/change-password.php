<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - CIT University</title>
    <link rel="stylesheet" href="/assets/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #f5f7fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
    <div class="header-brand" style="flex-direction: column; text-align: center; margin-bottom: 20px;">
        <img src="/assets/images/logo.png" alt="CIT University Logo" style="height: 60px; margin-right: 0; margin-bottom: 10px;">
        <div class="header-text">
            <h1 style="font-size: 1.5rem;">CIT University</h1>
            <p style="font-size: 0.9rem;">Enrollment System</p>
        </div>
    </div>

    <div class="login-card" style="max-width: 500px; flex-direction: column;">
        <div class="right-panel" style="padding: 40px;">
            <h2 style="color: var(--primary-color); font-size: 1.5rem; margin-bottom: 10px; text-align: center;">Update Password</h2>
            <p class="login-instruction" style="text-align: center; margin-bottom: 30px;">Secure your account by updating your credentials.</p>

            <form action="/handle-change-password" method="POST">
                <div class="form-group">
                    <label for="email">CIT University Email</label>
                    <input type="email" id="email" name="email" placeholder="student@cit.edu" required>
                </div>

                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" placeholder="##-####-###" required>
                </div>

                <div class="form-group" style="position: relative;">
                    <label for="new_password">New Password</label>
                    <div style="position: relative;">
                        <input type="password" id="new_password" name="new_password" required>
                        <i class="fa-solid fa-eye password-toggle" onclick="togglePassword('new_password', this)" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-light);"></i>
                    </div>
                </div>

                <div class="form-group" style="position: relative;">
                    <label for="confirm_password">Confirm New Password</label>
                    <div style="position: relative;">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <i class="fa-solid fa-eye password-toggle" onclick="togglePassword('confirm_password', this)" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-light);"></i>
                    </div>
                </div>

                <div class="button-row" style="margin-top: 30px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-login" style="flex: 2; height: 45px;">Update Password</button>
                    <a href="/dashboard" class="btn-clear" style="flex: 1; height: 45px; text-decoration: none; display: flex; align-items: center; justify-content: center; background: #eee; color: var(--text-dark);">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
