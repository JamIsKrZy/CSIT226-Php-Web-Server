<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CIT University Enrollment System</title>
    <link rel="stylesheet" href="/assets/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-section">
                <img src="/assets/images/logo.png" alt="CIT University Logo">
                <div class="logo-text">
                    <h1>CIT University</h1>
                    <p>Enrollment System</p>
                </div>
            </div>
            <nav>
                <a href="/dashboard" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <a href="/plot-schedule"><i class="fa-solid fa-calendar-days"></i> Plot Schedule</a>
                <a href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </nav>
        </div>
    </header>

    <div class="main-wrapper">
        <section class="title-section">
            <div>
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? 'Student'); ?>!</h2>
                <p>Here's your enrollment overview for Academic Year 2026-2027</p>
            </div>
        </section>

        <section class="stats-grid">
            <div class="stat-card maroon">
                <div class="stat-info">
                    <h4>Enrolled Units</h4>
                    <div class="value">18</div>
                </div>
                <i class="fa-solid fa-book-open fa-2x"></i>
            </div>

            <div class="stat-card yellow">
                <div class="stat-info">
                    <h4>Pending Subjects</h4>
                    <div class="value">3</div>
                </div>
                <i class="fa-solid fa-clock fa-2x"></i>
            </div>

            <div class="stat-card maroon">
                <div class="stat-info">
                    <h4>Total Progress</h4>
                    <div class="value">75%</div>
                </div>
                <i class="fa-solid fa-chart-line fa-2x"></i>
            </div>
        </section>

        <div class="dashboard-grid">
            <div class="left-col">
                <section class="card">
                    <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Classes</h3>
                    
                    <div class="subject-item">
                        <div class="subject-details">
                            <div class="subject-name">Data Structures <span class="subject-code">CS201</span></div>
                            <div class="subject-info-line"><i class="fa-regular fa-clock"></i> 9:00 AM - 10:30 AM</div>
                            <div class="subject-info-line"><i class="fa-solid fa-user-tie"></i> Dr. Maria Santos</div>
                            <div class="subject-info-line"><i class="fa-solid fa-location-dot"></i> Room 301</div>
                        </div>
                    </div>

                    <div class="subject-item">
                        <div class="subject-details">
                            <div class="subject-name">Database Systems <span class="subject-code">CS202</span></div>
                            <div class="subject-info-line"><i class="fa-regular fa-clock"></i> 1:00 PM - 2:30 PM</div>
                            <div class="subject-info-line"><i class="fa-solid fa-user-tie"></i> Prof. Juan Dela Cruz</div>
                            <div class="subject-info-line"><i class="fa-solid fa-location-dot"></i> Room 205</div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="right-col">
                <section class="card">
                    <h3><i class="fa-solid fa-bullhorn"></i> Announcements</h3>
                    
                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4>Pre-Enrollment Extended</h4>
                            <span class="date">May 8</span>
                        </div>
                        <p>Deadline moved to May 15, 2026.</p>
                    </div>

                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4>System Maintenance</h4>
                            <span class="date">May 3</span>
                        </div>
                        <p>Scheduled for May 10, 2:00 AM.</p>
                    </div>
                </section>

                <section class="summary-card">
                    <h3>Quick Actions</h3>
                    <div class="summary-row">
                        <span>Enrollment Form</span>
                        <a href="#" style="color: var(--accent-yellow); text-decoration: none;">Download</a>
                    </div>
                    <div class="summary-row">
                        <span>Academic Calendar</span>
                        <a href="#" style="color: var(--accent-yellow); text-decoration: none;">View</a>
                    </div>
                    <div class="summary-row total">
                        <a href="#" class="btn-add" style="background: var(--accent-yellow); color: var(--primary-maroon); width: 100%; justify-content: center; margin-top: 10px;">Contact Advisor</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
