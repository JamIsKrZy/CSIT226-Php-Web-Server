<?php
$isLoggedIn = isset($_SESSION['user']);
$prefilledEmail = $isLoggedIn ? ($_SESSION['user']['email'] ?? '') : '';
$prefilledId = $isLoggedIn ? ($_SESSION['user']['student_number'] ?? '') : '';
$cancelUrl = $isLoggedIn ? '/dashboard' : '/';
$userRole = $isLoggedIn ? ($_SESSION['user']['role'] ?? '') : '';

if ($isLoggedIn) {
    if ($userRole === 'admin') {
        $idLabel = 'Admin Code';
        $idPlaceholder = 'ADM-YYYY-XXX';
    } else {
        $idLabel = 'Student ID';
        $idPlaceholder = '##-####-###';
    }
} else {
    $idLabel = 'Student ID / Admin Code';
    $idPlaceholder = '##-####-### or ADM-YYYY-XXX';
}
?>
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
    <div style="display: flex; flex-direction: column; align-items: center; width: 100%; max-width: 500px;">
        <div class="header-brand" style="flex-direction: column; text-align: center; margin-bottom: 20px; width: 100%;">
            <img src="/assets/images/logo.png" alt="CIT University Logo" style="height: 60px; margin-right: 0; margin-bottom: 10px;">
            <div class="header-text">
                <h1 style="font-size: 1.5rem; margin: 0;">CIT University</h1>
                <p style="font-size: 0.9rem; margin: 5px 0 0 0;">Enrollment System</p>
            </div>
        </div>

        <div class="login-card" style="width: 100%; flex-direction: column; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: white; border-radius: 8px;">
            <div class="right-panel" style="padding: 40px; width: 100%; box-sizing: border-box;">
                <h2 style="color: var(--primary-color); font-size: 1.5rem; margin-bottom: 10px; text-align: center;">Update Password</h2>
                <p class="login-instruction" style="text-align: center; margin-bottom: 30px;">Secure your account by updating your credentials.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error" style="margin-bottom: 20px; padding: 12px; background-color: #fde8e8; color: #e53e3e; border-radius: 4px; font-size: 0.9rem;">
                        <i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($_SESSION['error']); ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px; background-color: #def7ec; color: #03543f; border-radius: 4px; font-size: 0.9rem;">
                        <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($_SESSION['success']); ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form action="/handle-change-password" method="POST">
                    <div class="form-group">
                        <label for="email">CIT University Email</label>
                        <input type="email" id="email" name="email" placeholder="student@cit.edu" 
                               value="<?php echo htmlspecialchars($prefilledEmail); ?>" 
                               <?php echo $isLoggedIn ? 'readonly style="background-color: #eef2f6; cursor: not-allowed;"' : ''; ?> required>
                    </div>

                    <div class="form-group">
                        <label for="student_id"><?php echo htmlspecialchars($idLabel); ?></label>
                        <input type="text" id="student_id" name="student_id" placeholder="<?php echo htmlspecialchars($idPlaceholder); ?>" 
                               value="<?php echo htmlspecialchars($prefilledId); ?>" 
                               <?php echo $isLoggedIn ? 'readonly style="background-color: #eef2f6; cursor: not-allowed;"' : ''; ?> required>
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
                        <a href="<?php echo htmlspecialchars($cancelUrl); ?>" class="btn-clear" style="flex: 1; height: 45px; text-decoration: none; display: flex; align-items: center; justify-content: center; background: #eee; color: var(--text-dark); border-radius: 4px; font-weight: 500;">Cancel</a>
                    </div>
                </form>
            </div>
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
