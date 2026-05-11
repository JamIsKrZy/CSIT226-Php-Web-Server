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
                    <div class="stat-value" id="total-sections">0</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">across all courses</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>High Demand</span>
                    </div>
                    <div class="stat-value" style="color: var(--status-danger);" id="high-demand">0</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">sections nearing capacity</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-users-viewfinder"></i>
                        <span>Low Demand</span>
                    </div>
                    <div class="stat-value" style="color: var(--status-success);" id="low-demand">0</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">sections with availability</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Section Comparison by Course</h3>
                </div>
                <div class="card-body">
                    <div class="scrollable-card" style="max-height: 600px;" id="course-accordion-container">
                        <!-- Loaded by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let demandData = { courses: [], sections: [] };

        function getDemandBadgeClass(fillPercent) {
            if (fillPercent >= 80) return 'badge-high';
            if (fillPercent >= 50) return 'badge-moderate';
            return 'badge-low';
        }

        function getDemandLabel(fillPercent) {
            if (fillPercent >= 80) return 'HIGH';
            if (fillPercent >= 50) return 'MODERATE';
            return 'LOW';
        }

        function getProgressBarColor(fillPercent) {
            if (fillPercent >= 80) return 'red';
            if (fillPercent >= 50) return '#ffc107';
            return 'green';
        }

        function loadSectionDemand() {
            fetch('/api/student/section-demand')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        demandData = data.data;
                        renderSectionDemand();
                        updateStats();
                    }
                })
                .catch(error => console.error('Error loading section demand:', error));
        }

        function renderSectionDemand() {
            const container = document.getElementById('course-accordion-container');
            container.innerHTML = '';

            // Group sections by course
            const courseMap = {};
            demandData.sections.forEach(section => {
                const courseCode = section.courseCode;
                if (!courseMap[courseCode]) {
                    courseMap[courseCode] = {
                        code: courseCode,
                        name: section.courseName,
                        sections: []
                    };
                }
                courseMap[courseCode].sections.push(section);
            });

            // Render each course accordion
            Object.values(courseMap).forEach((course, index) => {
                const accordion = document.createElement('div');
                accordion.className = 'course-accordion';
                if (index === 0) accordion.classList.add('active');

                const fillPercent = course.sections.length > 0 
                    ? (course.sections.reduce((sum, s) => sum + (s.enrolledCount / s.capacity * 100), 0) / course.sections.length)
                    : 0;

                accordion.innerHTML = `
                    <div class="accordion-header" onclick="this.parentElement.classList.toggle('active')">
                        <div>
                            <h4>${course.code} - ${course.name}</h4>
                            <span>${course.sections.length} sections available</span>
                        </div>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content" id="course-${course.code}">
                        <!-- Sections will be added here -->
                    </div>
                `;

                container.appendChild(accordion);

                // Add section cards
                const contentDiv = accordion.querySelector('.accordion-content');
                course.sections.forEach(section => {
                    const enrollPercent = (section.enrolledCount / section.capacity) * 100;
                    const demandLabel = getDemandLabel(enrollPercent);
                    const badgeClass = getDemandBadgeClass(enrollPercent);
                    const progressColor = getProgressBarColor(enrollPercent);

                    const card = document.createElement('div');
                    card.className = 'section-demand-card';
                    card.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                    <span style="font-weight: 700; font-size: 1rem;">${section.sectionCode}</span>
                                    <span class="badge ${badgeClass}">${demandLabel}</span>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-medium);">
                                    ${section.timeslot || 'TBA'}<br>
                                    ${section.room || 'TBA'}
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.85rem; font-weight: 600;"><i class="fa-solid fa-users"></i> ${section.enrolledCount}/${section.capacity}</div>
                                <div style="font-size: 0.75rem; color: var(--text-medium);">${Math.round(enrollPercent)}% filled</div>
                            </div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-label"><span>Enrolled</span><span>${section.enrolledCount} / ${section.capacity}</span></div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar ${progressColor}" style="width: ${enrollPercent}%;"></div>
                            </div>
                        </div>
                    `;
                    contentDiv.appendChild(card);
                });
            });

            // Add accordion click handlers
            document.querySelectorAll('.accordion-header').forEach(header => {
                header.style.cursor = 'pointer';
            });
        }

        function updateStats() {
            const totalSections = demandData.sections.length;
            let highDemand = 0;
            let lowDemand = 0;

            demandData.sections.forEach(section => {
                const fillPercent = (section.enrolledCount / section.capacity) * 100;
                if (fillPercent >= 80) highDemand++;
                else if (fillPercent < 50) lowDemand++;
            });

            document.getElementById('total-sections').textContent = totalSections;
            document.getElementById('high-demand').textContent = highDemand;
            document.getElementById('low-demand').textContent = lowDemand;
        }

        // Load section demand on page load
        document.addEventListener('DOMContentLoaded', loadSectionDemand);
    </script>
    <style>
        .course-accordion .accordion-content { display: none; }
        .course-accordion.active .accordion-content { display: block; }
        .course-accordion.active .accordion-header i { transform: rotate(180deg); }
        .accordion-header i { transition: transform 0.3s; }
    </style>
</body>
</html>
