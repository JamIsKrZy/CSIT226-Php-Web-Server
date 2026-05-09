<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Plotter - CIT University Enrollment System</title>
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
                <a href="/dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <a href="/plot-schedule" class="active"><i class="fa-solid fa-calendar-days"></i> Plot Schedule</a>
                <a href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </nav>
        </div>
    </header>

    <div class="main-wrapper">
        <section class="title-section">
            <div>
                <h2>Schedule Plotter</h2>
                <p>Plan your academic schedule for the upcoming semester</p>
            </div>
            <a href="#" class="btn-add"><i class="fa-solid fa-plus"></i> Add Subject</a>
        </section>

        <div class="dashboard-grid">
            <div class="left-col">
                <section class="card">
                    <h3><i class="fa-solid fa-calendar-days"></i> Weekly Schedule</h3>
                    <div class="schedule-table-container">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td class="time-col">7:00 AM</td><td></td><td></td><td></td><td></td><td></td></tr>
                                <tr><td class="time-col">8:30 AM</td><td></td><td></td><td></td><td></td><td></td></tr>
                                <tr><td class="time-col">10:00 AM</td><td></td><td></td><td></td><td></td><td></td></tr>
                                <tr><td class="time-col">11:30 AM</td><td></td><td></td><td></td><td></td><td></td></tr>
                                <tr>
                                    <td class="time-col">1:00 PM</td>
                                    <td></td>
                                    <td><div class="plotted-cell">CS202<br>Room 205</div></td>
                                    <td></td>
                                    <td><div class="plotted-cell">CS202<br>Room 205</div></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="time-col">2:30 PM</td>
                                    <td></td>
                                    <td><div class="plotted-cell">CS202<br>Room 205</div></td>
                                    <td></td>
                                    <td><div class="plotted-cell">CS202<br>Room 205</div></td>
                                    <td></td>
                                </tr>
                                <tr><td class="time-col">4:00 PM</td><td></td><td></td><td></td><td></td><td></td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <h3 style="margin-bottom: 0;"><i class="fa-solid fa-list-check"></i> Plotted Subjects</h3>
                        <span class="subject-code" style="background: var(--accent-yellow); color: var(--primary-maroon); font-weight: 700; padding: 5px 15px; font-size: 0.9rem;">Total Units: 6</span>
                    </div>

                    <div class="subject-item">
                        <div class="subject-details">
                            <div class="subject-name">Data Structures <span class="subject-code">CS201</span></div>
                            <div class="subject-info-line"><i class="fa-regular fa-clock"></i> 9:00 AM - 10:30 AM</div>
                            <div class="subject-info-line"><i class="fa-solid fa-user-tie"></i> Dr. Maria Santos</div>
                            <div class="subject-info-line"><i class="fa-solid fa-location-dot"></i> Room 301</div>
                            <div class="subject-info-line"><i class="fa-regular fa-calendar"></i> Mon, Wed, Fri</div>
                        </div>
                        <i class="fa-solid fa-xmark btn-remove"></i>
                    </div>

                    <div class="subject-item">
                        <div class="subject-details">
                            <div class="subject-name">Database Systems <span class="subject-code">CS202</span></div>
                            <div class="subject-info-line"><i class="fa-regular fa-clock"></i> 1:00 PM - 2:30 PM</div>
                            <div class="subject-info-line"><i class="fa-solid fa-user-tie"></i> Prof. Juan Dela Cruz</div>
                            <div class="subject-info-line"><i class="fa-solid fa-location-dot"></i> Room 205</div>
                            <div class="subject-info-line"><i class="fa-regular fa-calendar"></i> Tue, Thu</div>
                        </div>
                        <i class="fa-solid fa-xmark btn-remove"></i>
                    </div>
                </section>
            </div>

            <div class="right-col">
                <section class="card">
                    <h3><i class="fa-solid fa-bullhorn"></i> Announcements</h3>
                    
                    <div class="announcement-item" style="background: #fff8e1; padding: 15px; border-radius: 8px; border-left: 4px solid #ffa000; margin-bottom: 20px;">
                        <h4 style="color: #e65100; font-size: 0.95rem; margin-bottom: 5px;">Enrollment Deadline Approaching</h4>
                        <p style="font-size: 0.85rem; color: #5d4037;">Complete your enrollment by May 15, 2026</p>
                    </div>

                    <div class="announcement-item" style="background: #e3f2fd; padding: 15px; border-radius: 8px; border-left: 4px solid #1e88e5;">
                        <h4 style="color: #0d47a1; font-size: 0.95rem; margin-bottom: 5px;">New Elective Available</h4>
                        <p style="font-size: 0.85rem; color: #1565c0;">Artificial Intelligence elective now open for registration</p>
                    </div>
                </section>

                <section class="summary-card">
                    <h3>Enrollment Summary</h3>
                    <div class="summary-row">
                        <span>Total Subjects:</span>
                        <span>2</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Units:</span>
                        <span>6</span>
                    </div>
                    <div class="summary-row">
                        <span>Max Units Allowed:</span>
                        <span>24</span>
                    </div>
                    <div class="summary-row total">
                        <span>Remaining Units:</span>
                        <span>18</span>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
