<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Enrollment Dashboard - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php $currentPage = 'dashboard'; include 'partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include 'partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header">
                <h2>Pre-Enrollment Dashboard</h2>
                <p>1st Semester, AY 2026-2027</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Planned Units</span>
                    </div>
                    <div class="stat-value">15 / 24</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 62.5%;"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-fire"></i>
                        <span>High Demand Sections</span>
                    </div>
                    <div class="stat-value">2 <span style="font-size: 0.9rem; font-weight: 500; color: var(--text-medium);">out of 5 planned</span></div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Enrollment Readiness</span>
                    </div>
                    <div class="stat-value">75%</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">Good to proceed</p>
                </div>
            </div>

            <div class="dashboard-main-grid">
                <div class="left-col">
                    <div class="card">
                        <div class="card-header">
                            <h3>Selected Sections</h3>
                            <span style="font-size: 0.8rem; color: var(--text-medium);">5 courses</span>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="scrollable-card" style="max-height: 400px;">
                                <table class="data-table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="section-row-info">
                                                    <span class="section-row-title">CSIT122 — Intermediate Programming II</span>
                                                    <span class="section-row-subtitle">Section F1  •  MWF 8:00-10:00 AM  •  Room 301</span>
                                                </div>
                                            </td>
                                            <td style="text-align: right;">
                                                <span class="badge badge-high">High</span>
                                            </td>
                                            <td style="text-align: right; font-weight: 600;">3 units</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="section-row-info">
                                                    <span class="section-row-title">CSIT228 — Database Management Systems</span>
                                                    <span class="section-row-subtitle">Section F1  •  TTH 1:00-2:30 PM  •  Room 205</span>
                                                </div>
                                            </td>
                                            <td style="text-align: right;">
                                                <span class="badge badge-high">High</span>
                                            </td>
                                            <td style="text-align: right; font-weight: 600;">3 units</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="section-row-info">
                                                    <span class="section-row-title">MATH215 — Discrete Mathematics</span>
                                                    <span class="section-row-subtitle">Section F2  •  MWF 10:00-11:00 AM  •  Room 108</span>
                                                </div>
                                            </td>
                                            <td style="text-align: right;">
                                                <span class="badge badge-moderate">Moderate</span>
                                            </td>
                                            <td style="text-align: right; font-weight: 600;">3 units</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="section-row-info">
                                                    <span class="section-row-title">CSI1221 — Computer Architecture</span>
                                                    <span class="section-row-subtitle">Section F1  •  TTH 10:00-11:30 AM  •  Room 412</span>
                                                </div>
                                            </td>
                                            <td style="text-align: right;">
                                                <span class="badge badge-low">Low</span>
                                            </td>
                                            <td style="text-align: right; font-weight: 600;">3 units</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="section-row-info">
                                                    <span class="section-row-title">ENGL201 — Technical Writing</span>
                                                    <span class="section-row-subtitle">Section F3  •  MW 2:00-3:30 PM  •  Room 203</span>
                                                </div>
                                            </td>
                                            <td style="text-align: right;">
                                                <span class="badge badge-moderate">Moderate</span>
                                            </td>
                                            <td style="text-align: right; font-weight: 600;">3 units</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-col">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fa-solid fa-bell" style="color: var(--primary-maroon); margin-right: 8px;"></i> Enrollment Updates</h3>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="scrollable-card" style="max-height: 400px; padding: 0 24px;">
                                <div class="update-item">
                                    <div class="update-icon" style="background: #fdf2f2; color: #d63031;">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                    </div>
                                    <div class="update-content">
                                        <h4>CSIT122 F1</h4>
                                        <p>Section F1 is now at full capacity (40/40 students)</p>
                                        <span class="update-time">2 hours ago</span>
                                    </div>
                                </div>
                                <div class="update-item">
                                    <div class="update-icon" style="background: #e8f8f5; color: #16a085;">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <div class="update-content">
                                        <h4>CSIT228 F3</h4>
                                        <p>New section F3 opened - TTH 3:00-4:30 PM</p>
                                        <span class="update-time">5 hours ago</span>
                                    </div>
                                </div>
                                <div class="update-item">
                                    <div class="update-icon" style="background: #fff9e6; color: #d35400;">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div class="update-content">
                                        <h4>MATH215 F2</h4>
                                        <p>Room changed from 108 to 110</p>
                                        <span class="update-time">1 day ago</span>
                                    </div>
                                </div>
                                <div class="update-item">
                                    <div class="update-icon" style="background: #eef2ff; color: #4f46e5;">
                                        <i class="fa-solid fa-info-circle"></i>
                                    </div>
                                    <div class="update-content">
                                        <h4>Department Notice</h4>
                                        <p>Pre-enrollment deadline extended to May 15</p>
                                        <span class="update-time">2 days ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
