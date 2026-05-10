<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enrollment Plan - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php $currentPage = 'enrollment-plan'; include 'partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include 'partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h2>My Enrollment Plan</h2>
                    <p>Prepare and organize your intended enrollment before official section enrollment begins.</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button class="btn btn-outline"><i class="fa-solid fa-floppy-disk"></i> Save Plan</button>
                    <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Section</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Planned Units</span>
                    </div>
                    <div class="stat-value">0 / 24 Units</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: 0%;"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Planned Subjects</span>
                    </div>
                    <div class="stat-value">9 Subjects</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Schedule Conflicts</span>
                    </div>
                    <div class="stat-value">0 Conflicts</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <span>Enrollment Readiness</span>
                    </div>
                    <div class="stat-value" style="color: var(--status-warning);">Incomplete</div>
                </div>
            </div>

            <div class="dashboard-main-grid">
                <div class="left-col">
                    <div class="card">
                        <div class="card-header">
                            <h3>Planned Sections</h3>
                            <span style="font-size: 0.8rem; color: var(--text-medium);">Selected Sections</span>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="scrollable-card" style="max-height: 500px;">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Section</th>
                                            <th>Schedule</th>
                                            <th>Room</th>
                                            <th>Units</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $courses = [
                                            ['code' => 'CS132', 'title' => 'Introduction to Computer Systems', 'units' => '3.0'],
                                            ['code' => 'CSIT112', 'title' => 'Discrete Structures 1', 'units' => '3.0'],
                                            ['code' => 'CSIT122', 'title' => 'Intermediate Programming', 'units' => '3.0'],
                                            ['code' => 'CSIT201', 'title' => 'Platform-based Development 2 (Web)', 'units' => '1.0'],
                                            ['code' => 'HUM031', 'title' => 'Art Appreciation', 'units' => '3.0'],
                                            ['code' => 'MATH136', 'title' => 'Differential and Integral Calculus', 'units' => '3.0'],
                                            ['code' => 'NSTP112', 'title' => 'National Service Training Program 2', 'units' => '3.0'],
                                            ['code' => 'PE104', 'title' => 'Fitness Exercises / PATHFit 2', 'units' => '2.0'],
                                            ['code' => 'SOCSCI031', 'title' => 'Readings in Philippine History', 'units' => '3.0'],
                                        ];
                                        foreach ($courses as $course): ?>
                                        <tr>
                                            <td>
                                                <div class="section-row-info">
                                                    <span class="section-row-title"><?php echo $course['code']; ?></span>
                                                    <span class="section-row-subtitle"><?php echo $course['title']; ?></span>
                                                </div>
                                            </td>
                                            <td style="color: var(--text-light); font-style: italic;">No Section</td>
                                            <td style="color: var(--text-light);">---</td>
                                            <td style="color: var(--text-light);">---</td>
                                            <td><?php echo $course['units']; ?></td>
                                            <td><span class="badge" style="background: #eee; color: #999;">Pending</span></td>
                                            <td>
                                                <button class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem;">Select Section</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Schedule Preview -->
                    <div class="card" style="margin-top: 24px;">
                        <div class="card-header">
                            <h3>Weekly Schedule Preview</h3>
                        </div>
                        <div class="card-body">
                            <div class="schedule-container">
                                <div class="schedule-grid">
                                    <div class="schedule-header">Time</div>
                                    <div class="schedule-header">Monday</div>
                                    <div class="schedule-header">Tuesday</div>
                                    <div class="schedule-header">Wednesday</div>
                                    <div class="schedule-header">Thursday</div>
                                    <div class="schedule-header">Friday</div>
                                    <div class="schedule-header">Saturday</div>

                                    <?php 
                                    $times = ['7:00 AM', '8:30 AM', '10:00 AM', '11:30 AM', '1:00 PM', '2:30 PM', '4:00 PM'];
                                    foreach ($times as $time): ?>
                                        <div class="time-slot time-label"><?php echo $time; ?></div>
                                        <?php for ($i = 0; $i < 6; $i++): ?>
                                            <div class="time-slot"></div>
                                        <?php endfor; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <p style="text-align: center; color: var(--text-medium); font-size: 0.85rem; margin-top: 20px;">
                                <i class="fa-solid fa-info-circle"></i> Select sections to visualize your schedule.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="right-col">
                    <div class="card">
                        <div class="card-header">
                            <h3>Validation Results</h3>
                        </div>
                        <div class="card-body">
                            <div class="validation-sidebar">
                                <div class="validation-section">
                                    <h4>Unit Validation</h4>
                                    <div class="validation-item">
                                        <i class="fa-solid fa-circle-info" style="color: var(--status-info);"></i>
                                        <div class="validation-text">
                                            <p>0 Units Selected</p>
                                            <span>Maximum Allowed: 24</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="validation-section">
                                    <h4>Enrollment Readiness</h4>
                                    <div style="margin-top: 10px; padding: 16px; background: #f8f9fa; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                        <p style="font-weight: 700; font-size: 0.9rem; margin-bottom: 4px; color: var(--text-medium);">Plan Status: Incomplete</p>
                                        <p style="font-size: 0.8rem; color: var(--text-medium);">Please select sections for all planned subjects.</p>
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
