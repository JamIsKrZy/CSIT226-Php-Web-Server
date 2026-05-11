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
    <?php $currentPage = 'enrollment-plan'; include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

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
                    <div class="stat-value"><span id="planned-units">0</span> / 24 Units</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" id="unit-progress-bar" style="width: 0%;"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Planned Subjects</span>
                    </div>
                    <div class="stat-value"><span id="planned-subjects">0</span> Subjects</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Schedule Conflicts</span>
                    </div>
                    <div class="stat-value"><span id="conflict-count">0</span> Conflicts</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <span>Enrollment Readiness</span>
                    </div>
                    <div class="stat-value" id="readiness-status" style="color: var(--status-warning);">Incomplete</div>
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
                                    <tbody id="enrollment-table-body">
                                        <!-- Loaded by JavaScript -->
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
                                            <p><span id="selected-units">0</span> Units Selected</p>
                                            <span>Maximum Allowed: 24</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="validation-section">
                                    <h4>Enrollment Readiness</h4>
                                    <div style="margin-top: 10px; padding: 16px; background: #f8f9fa; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                        <p style="font-weight: 700; font-size: 0.9rem; margin-bottom: 4px; color: var(--text-medium);">Plan Status: <span id="plan-status">Incomplete</span></p>
                                        <p style="font-size: 0.8rem; color: var(--text-medium);" id="plan-message">Please select sections for all planned subjects.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let enrollmentPlan = [];
        let currentStudentID = 1; // Get from session or auth context in production

        function loadEnrollmentPlan() {
            fetch(`/api/student/enrollment-plan?studentID=${currentStudentID}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        enrollmentPlan = data.data || [];
                        renderEnrollmentTable();
                        updateStats();
                    }
                })
                .catch(error => console.error('Error loading enrollment plan:', error));
        }

        function renderEnrollmentTable() {
            const tbody = document.getElementById('enrollment-table-body');
            tbody.innerHTML = '';

            if (enrollmentPlan.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px; color: var(--text-medium);">No sections selected. Select a section to get started.</td></tr>';
                return;
            }

            enrollmentPlan.forEach((item, index) => {
                const row = document.createElement('tr');
                const statusBadgeColor = item.enrollmentStatus === 'enrolled' ? 'badge-valid' : item.enrollmentStatus === 'pending' ? '#ffc107' : '#6c757d';
                const statusLabel = item.enrollmentStatus ? item.enrollmentStatus.charAt(0).toUpperCase() + item.enrollmentStatus.slice(1) : 'Pending';

                row.innerHTML = `
                    <td>
                        <div class="section-row-info">
                            <span class="section-row-title">${item.courseCode}</span>
                            <span class="section-row-subtitle">${item.courseName}</span>
                        </div>
                    </td>
                    <td>${item.sectionCode}</td>
                    <td style="font-size: 0.9rem;">${item.timeslot || '---'}</td>
                    <td>${item.room || '---'}</td>
                    <td>${item.credits}</td>
                    <td><span class="badge" style="background: ${statusBadgeColor}; color: white;">${statusLabel}</span></td>
                    <td>
                        <button class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem; color: var(--status-danger);" 
                            onclick="removeSection(${item.plannedItemID})">
                            <i class="fa-solid fa-trash-can"></i> Remove
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function updateStats() {
            let totalUnits = 0;
            let plannedSubjects = enrollmentPlan.length;

            enrollmentPlan.forEach(item => {
                totalUnits += item.credits || 0;
            });

            document.getElementById('planned-units').textContent = totalUnits;
            document.getElementById('planned-subjects').textContent = plannedSubjects;
            document.getElementById('selected-units').textContent = totalUnits;

            const maxUnits = 24;
            const progressPercent = Math.min((totalUnits / maxUnits) * 100, 100);
            document.getElementById('unit-progress-bar').style.width = progressPercent + '%';

            // Update readiness status
            const readinessElement = document.getElementById('readiness-status');
            const planStatusElement = document.getElementById('plan-status');
            const planMessageElement = document.getElementById('plan-message');

            if (totalUnits === 0) {
                readinessElement.textContent = 'No Plan';
                readinessElement.style.color = 'var(--text-medium)';
                planStatusElement.textContent = 'Empty';
                planMessageElement.textContent = 'No sections have been selected yet.';
            } else if (totalUnits < 12) {
                readinessElement.textContent = 'Incomplete';
                readinessElement.style.color = 'var(--status-warning)';
                planStatusElement.textContent = 'Incomplete';
                planMessageElement.textContent = 'At least 12 units are recommended for full-time status.';
            } else if (totalUnits <= maxUnits) {
                readinessElement.textContent = 'Complete';
                readinessElement.style.color = 'var(--status-valid)';
                planStatusElement.textContent = 'Complete';
                planMessageElement.textContent = 'Your enrollment plan is ready for submission.';
            } else {
                readinessElement.textContent = 'Exceeds Max';
                readinessElement.style.color = 'var(--status-danger)';
                planStatusElement.textContent = 'Over Limit';
                planMessageElement.textContent = `Total units (${totalUnits}) exceeds maximum (${maxUnits}).`;
            }
        }

        function removeSection(plannedItemID) {
            if (!confirm('Are you sure you want to remove this section?')) return;

            fetch('/api/student/remove-section', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ plannedItemID: plannedItemID })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadEnrollmentPlan();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error removing section:', error));
        }

        // Load enrollment plan on page load
        document.addEventListener('DOMContentLoaded', loadEnrollmentPlan);
    </script>
</body>
</html>
