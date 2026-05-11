<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Demand Overview - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php $currentPage = 'section-demand'; include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header">
                <h2>Section Demand Overview</h2>
                <p>Compare enrollment demand across available sections</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Total Sections</span>
                    </div>
                    <div class="stat-value">27</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">across all courses</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>High Demand</span>
                    </div>
                    <div class="stat-value" style="color: var(--status-danger);">8</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">sections nearing capacity</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-users-viewfinder"></i>
                        <span>Low Demand</span>
                    </div>
                    <div class="stat-value" style="color: var(--status-success);">12</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">sections with availability</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Section Comparison by Course</h3>
                </div>
                <div class="card-body">
                    <div class="scrollable-card" style="max-height: 600px;">
                        <?php 
                        $courses = [
                            ['code' => 'CSIT122', 'title' => 'Intermediate Programming II', 'sections' => 3],
                            ['code' => 'CSIT228', 'title' => 'Database Management Systems', 'sections' => 3],
                            ['code' => 'MATH136', 'title' => 'Differential and Integral Calculus', 'sections' => 3],
                            ['code' => 'CS132', 'title' => 'Introduction to Computer Systems', 'sections' => 3],
                            ['code' => 'CSIT112', 'title' => 'Discrete Structures 1', 'sections' => 3],
                            ['code' => 'CSIT201', 'title' => 'Platform-based Development 2 (Web)', 'sections' => 3],
                            ['code' => 'HUM031', 'title' => 'Art Appreciation', 'sections' => 3],
                            ['code' => 'NSTP112', 'title' => 'National Service Training Program 2', 'sections' => 3],
                            ['code' => 'PE104', 'title' => 'Fitness Exercises / PATHFit 2', 'sections' => 3],
                        ];

                        foreach ($courses as $index => $course): ?>
                        <div class="course-accordion">
                            <div class="accordion-header" onclick="this.parentElement.classList.toggle('active')">
                                <div>
                                    <h4><?php echo $course['code']; ?> - <?php echo $course['title']; ?></h4>
                                    <span><?php echo $course['sections']; ?> sections available</span>
                                </div>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="accordion-content">
                                <!-- Section F1 -->
                                <div class="section-demand-card">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                        <div>
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                <span style="font-weight: 700; font-size: 1rem;">Section F1</span>
                                                <span class="badge badge-high">HIGH</span>
                                            </div>
                                            <div style="font-size: 0.85rem; color: var(--text-medium);">
                                                MWF 9:00-10:00 AM<br>Room 301
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 0.85rem; font-weight: 600;"><i class="fa-solid fa-users"></i> 38/40</div>
                                            <div style="font-size: 0.75rem; color: var(--text-medium);">95% filled</div>
                                        </div>
                                    </div>
                                    <div class="progress-container">
                                        <div class="progress-label"><span>Enrolled</span><span>38 / 40</span></div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar red" style="width: 95%;"></div>
                                        </div>
                                    </div>
                                    <div class="progress-container">
                                        <div class="progress-label"><span>Estimated Interest</span><span>52 students</span></div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar maroon" style="width: 100%;"></div>
                                        </div>
                                    </div>
                                    <div class="demand-alert">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        Interest exceeds capacity by 12 students
                                    </div>
                                </div>

                                <!-- Section F2 -->
                                <div class="section-demand-card">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                        <div>
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                <span style="font-weight: 700; font-size: 1rem;">Section F2</span>
                                                <span class="badge badge-moderate">MODERATE</span>
                                            </div>
                                            <div style="font-size: 0.85rem; color: var(--text-medium);">
                                                TTh 1:00-2:30 PM<br>Room 305
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 0.85rem; font-weight: 600;"><i class="fa-solid fa-users"></i> 22/40</div>
                                            <div style="font-size: 0.75rem; color: var(--text-medium);">55% filled</div>
                                        </div>
                                    </div>
                                    <div class="progress-container">
                                        <div class="progress-label"><span>Enrolled</span><span>22 / 40</span></div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar green" style="width: 55%;"></div>
                                        </div>
                                    </div>
                                    <div class="progress-container">
                                        <div class="progress-label"><span>Estimated Interest</span><span>28 students</span></div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar maroon" style="width: 70%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section F3 -->
                                <div class="section-demand-card">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                        <div>
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                <span style="font-weight: 700; font-size: 1rem;">Section F3</span>
                                                <span class="badge badge-low">LOW</span>
                                            </div>
                                            <div style="font-size: 0.85rem; color: var(--text-medium);">
                                                Sat 8:00-11:00 AM<br>Room 301
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 0.85rem; font-weight: 600;"><i class="fa-solid fa-users"></i> 12/35</div>
                                            <div style="font-size: 0.75rem; color: var(--text-medium);">34% filled</div>
                                        </div>
                                    </div>
                                    <div class="progress-container">
                                        <div class="progress-label"><span>Enrolled</span><span>12 / 35</span></div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar green" style="width: 34%;"></div>
                                        </div>
                                    </div>
                                    <div class="progress-container">
                                        <div class="progress-label"><span>Estimated Interest</span><span>15 students</span></div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar maroon" style="width: 42%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Simple accordion logic
        document.querySelectorAll('.course-accordion').forEach(accordion => {
            // Default first one open
            if(accordion.querySelector('h4').textContent.includes('CSIT122')) {
                accordion.classList.add('active');
            }
        });
    </script>
    <style>
        .course-accordion .accordion-content { display: none; }
        .course-accordion.active .accordion-content { display: block; }
        .course-accordion.active .accordion-header i { transform: rotate(180deg); }
        .accordion-header i { transition: transform 0.3s; }
    </style>
</body>
</html>
