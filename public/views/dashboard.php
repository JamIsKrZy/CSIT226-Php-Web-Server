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
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo-section">
            <img src="/assets/images/logo.png" alt="CIT University Logo">
            <div class="logo-text">
                <h1>CIT University</h1>
                <p>Enrollment System</p>
            </div>
        </div>
        <nav>
            <a href="/dashboard" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="#"><i class="fa-solid fa-calendar-days"></i> Plot Schedule</a>
            <a href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </header>

    <main>
        <section class="welcome-msg">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? 'Student'); ?>!</h2>
            <p>Here's your enrollment overview for Academic Year 2026-2027</p>
        </section>

        <section class="stats-grid">
            <div class="stat-card maroon">
                <div class="stat-info">
                    <h4>Enrolled Units</h4>
                    <div class="value">18</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>

            <div class="stat-card yellow">
                <div class="stat-info">
                    <h4>Pending Subjects</h4>
                    <div class="value">3</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>

            <div class="stat-card maroon">
                <div class="stat-info">
                    <h4>Total Progress</h4>
                    <div class="value">75%</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </section>

        <div class="content-grid">
            <section class="card">
                <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Classes</h3>
                
                <div class="class-item">
                    <h4>Data Structures</h4>
                    <p>Mon, Wed, Fri - 9:00 AM</p>
                    <p class="room">Room 301</p>
                </div>

                <div class="class-item">
                    <h4>Database Systems</h4>
                    <p>Tue, Thu - 1:00 PM</p>
                    <p class="room">Room 205</p>
                </div>

                <div class="class-item">
                    <h4>Web Development</h4>
                    <p>Mon, Wed - 3:00 PM</p>
                    <p class="room">Room 410</p>
                </div>
            </section>

            <section class="card">
                <h3><i class="fa-solid fa-bullhorn"></i> Announcements</h3>
                
                <div class="announcement-item">
                    <div class="announcement-header">
                        <h4>Pre-Enrollment Period Extended</h4>
                        <span class="date">May 8, 2026</span>
                    </div>
                    <p>The pre-enrollment period has been extended until May 15, 2026. Please ensure all requirements are submitted.</p>
                </div>

                <div class="announcement-item">
                    <div class="announcement-header">
                        <h4>New Course Offerings</h4>
                        <span class="date">May 5, 2026</span>
                    </div>
                    <p>New elective courses are now available for the upcoming semester. Check the Plot Schedule page.</p>
                </div>

                <div class="announcement-item">
                    <div class="announcement-header">
                        <h4>System Maintenance Notice</h4>
                        <span class="date">May 3, 2026</span>
                    </div>
                    <p>The enrollment system will undergo maintenance on May 10, 2026 from 2:00 AM to 4:00 AM.</p>
                </div>
            </section>
        </div>

        <section class="quick-actions">
            <h3>Quick Actions</h3>
            <a href="#" class="action-btn secondary">View Full Schedule</a>
            <a href="#" class="action-btn secondary">Download Enrollment Form</a>
            <a href="#" class="action-btn primary">Contact Advisor</a>
        </section>
    </main>
</body>
</html>
