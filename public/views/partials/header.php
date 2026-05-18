<header class="top-header">
    <div class="search-bar">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search subjects, courses, or schedules...">
    </div>
    <div class="header-actions">
        <a href="/enrollment-updates" class="notification-btn">
            <i class="fa-regular fa-bell"></i>
            <span class="notification-badge"></span>
        </a>
        <div class="user-profile-container">
            <div class="user-profile" id="userProfileBtn">
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars(($_SESSION['user']['first_name'] ?? 'Student') . ' ' . ($_SESSION['user']['last_name'] ?? '')); ?></span>
                    <span class="user-id">Student ID: <?php echo htmlspecialchars($_SESSION['user']['student_number'] ?? '26-0000-000'); ?></span>
                </div>
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['user']['first_name'] ?? 'S', 0, 1)); ?>
                </div>
                <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem; color: var(--text-medium); margin-left: 4px;"></i>
            </div>
            
            <!-- User Profile Dropdown -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <strong><?php echo htmlspecialchars(($_SESSION['user']['first_name'] ?? 'Student') . ' ' . ($_SESSION['user']['last_name'] ?? '')); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
                </div>
                <div class="dropdown-divider"></div>
                <a href="/change-password" class="dropdown-item">
                    <i class="fa-solid fa-key"></i>
                    Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout" class="dropdown-item logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileBtn = document.getElementById('userProfileBtn');
        const dropdown = document.getElementById('profileDropdown');

        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
</script>
