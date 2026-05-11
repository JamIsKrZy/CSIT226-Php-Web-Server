<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="/assets/images/logo.png" alt="CIT University Logo">
        <div class="logo-text">
            <h1>CIT University</h1>
            <p>Enrollment System</p>
        </div>
    </div>
    <nav class="sidebar-menu">
        <?php if (($_SESSION['user']['role'] ?? 'student') === 'admin'): ?>
            <!-- Admin Menu -->
            <a href="/admin/student-interest" class="menu-item <?php echo $currentPage == 'student-interest' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users-viewfinder"></i>
                Student Interest Monitoring
            </a>
            <a href="/admin/enrollment-updates" class="menu-item <?php echo $currentPage == 'admin-updates' ? 'active' : ''; ?>">
                <i class="fa-solid fa-bullhorn"></i>
                Enrollment Updates
            </a>
            <a href="/admin/management" class="menu-item <?php echo $currentPage == 'admin-management' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-shield"></i>
                Admin Management
            </a>
        <?php else: ?>
            <!-- Student Menu -->
            <a href="/dashboard" class="menu-item <?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge"></i>
                Dashboard
            </a>
            <!-- Student Menu -->
            <a href="/enrollment-plan" class="menu-item <?php echo $currentPage == 'enrollment-plan' ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i>
                My Enrollment Plan
            </a>
            <a href="/section-demand" class="menu-item <?php echo $currentPage == 'section-demand' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i>
                Section Demand
            </a>
            <a href="/alternative-sections" class="menu-item <?php echo $currentPage == 'alternative-sections' ? 'active' : ''; ?>">
                <i class="fa-solid fa-shuffle"></i>
                Alternative Sections
            </a>
            <a href="/enrollment-updates" class="menu-item <?php echo $currentPage == 'enrollment-updates' ? 'active' : ''; ?>">
                <i class="fa-solid fa-bullhorn"></i>
                Enrollment Updates
            </a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        <a href="/logout" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </div>
</aside>
