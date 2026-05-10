<header class="top-header">
    <div class="search-bar">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search subjects, courses, or schedules...">
    </div>
    <div class="header-actions">
        <div class="notification-btn">
            <i class="fa-regular fa-bell"></i>
            <span class="notification-badge"></span>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']); ?></span>
                <span class="user-id">Student ID: <?php echo htmlspecialchars($_SESSION['user']['student_id'] ?? '24-1234-567'); ?></span>
            </div>
            <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['user']['first_name'] ?? 'S', 0, 1)); ?>
            </div>
        </div>
    </div>
</header>
