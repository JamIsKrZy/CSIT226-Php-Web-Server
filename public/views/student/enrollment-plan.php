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
                    <button class="btn btn-primary" onclick="openAddSectionModal()"><i class="fa-solid fa-plus"></i> Add Section</button>
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
        let allSections = [];
        let currentStudentID = 1; // Get from session or auth context in production

        function showToast(message, type = 'success') {
            // Remove existing toast if any
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
                <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);

            // Trigger reflow & slide-in
            setTimeout(() => {
                toast.classList.add('active');
            }, 10);

            // Slide out & remove
            setTimeout(() => {
                toast.classList.remove('active');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        function loadEnrollmentPlan() {
            fetch(`/api/student/enrollment-plan?studentID=${currentStudentID}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        enrollmentPlan = data.data || [];
                        renderEnrollmentTable();
                        updateStats();
                        // Automatically update modal list if the modal is open
                        if (document.getElementById('add-section-modal').classList.contains('active')) {
                            renderModalSectionsTable();
                        }
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
                        showToast('Section successfully removed from plan.', 'success');
                    } else {
                        showToast('Error removing section: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error removing section:', error);
                    showToast('Network error while removing section.', 'error');
                });
        }

        function openAddSectionModal() {
            const modal = document.getElementById('add-section-modal');
            document.getElementById('section-search-input').value = ''; // Reset search
            
            // Show modal overlay
            modal.classList.add('active');

            // Fetch available sections
            fetch('/api/sections')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allSections = data.data || [];
                        renderModalSectionsTable();
                    } else {
                        showToast('Failed to retrieve sections: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching sections:', error);
                    showToast('Network error while retrieving sections.', 'error');
                });
        }

        function closeAddSectionModal() {
            const modal = document.getElementById('add-section-modal');
            modal.classList.remove('active');
        }

        function renderModalSectionsTable() {
            const tbody = document.getElementById('modal-sections-table-body');
            tbody.innerHTML = '';

            const searchTerm = document.getElementById('section-search-input').value.toLowerCase().trim();

            const filtered = allSections.filter(sec => {
                return (sec.courseCode || '').toLowerCase().includes(searchTerm) || 
                       (sec.courseName || '').toLowerCase().includes(searchTerm) || 
                       (sec.sectionCode || '').toLowerCase().includes(searchTerm) ||
                       (sec.instructor || '').toLowerCase().includes(searchTerm);
            });

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: var(--text-medium);">No matching sections found.</td></tr>';
                return;
            }

            filtered.forEach(sec => {
                // Check if this section is already present in the student's enrollment plan
                const isAlreadyAdded = enrollmentPlan.some(planItem => planItem.sectionCode === sec.sectionCode);

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="section-row-info">
                            <span class="section-row-title" style="font-weight: 600; color: var(--primary-color);">${sec.courseCode}</span>
                            <span class="section-row-subtitle" style="color: var(--text-light); font-size: 0.8rem; display: block; margin-top: 2px;">${sec.courseName}</span>
                        </div>
                    </td>
                    <td style="font-weight: 500;">${sec.sectionCode}</td>
                    <td style="font-size: 0.85rem;">
                        <div style="font-weight: 500;">${sec.timeslot || '---'}</div>
                        <div style="font-size: 0.75rem; color: var(--text-light); margin-top: 2px;"><i class="fa-solid fa-user-tie"></i> ${sec.instructor || 'Staff'}</div>
                    </td>
                    <td>${sec.room || '---'}</td>
                    <td>
                        <span style="font-weight: 600; color: ${sec.enrolledCount >= sec.capacity ? 'var(--status-danger)' : 'var(--text-dark)'};">
                            ${sec.enrolledCount}
                        </span> / ${sec.capacity}
                    </td>
                    <td>
                        ${isAlreadyAdded ? `
                            <button class="btn-added-state" disabled>
                                <i class="fa-solid fa-check"></i> Added
                            </button>
                        ` : `
                            <button class="btn-add-to-plan" onclick="addSectionToPlan(${sec.sectionID}, '${sec.courseCode} ${sec.sectionCode}')">
                                <i class="fa-solid fa-plus"></i> Add
                            </button>
                        `}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function filterSections() {
            renderModalSectionsTable();
        }

        function addSectionToPlan(sectionID, sectionLabel) {
            fetch('/api/student/add-section', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    studentID: currentStudentID,
                    sectionID: sectionID,
                    commitmentLevel: 5,
                    priority: 1,
                    semester: '1st Semester'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(`Successfully added ${sectionLabel} to plan!`, 'success');
                        loadEnrollmentPlan(); // Reload main dashboard list and automatically refreshes modal states
                    } else {
                        showToast('Failed to add section: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error adding section:', error);
                    showToast('Network error while adding section.', 'error');
                });
        }

        // Close modal when clicking outside content container
        document.addEventListener('DOMContentLoaded', function() {
            loadEnrollmentPlan();
            
            document.getElementById('add-section-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddSectionModal();
                }
            });
        });
    </script>

    <!-- Add Section Modal Overlay -->
    <div id="add-section-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fa-solid fa-circle-plus"></i> Add Section to Enrollment Plan</h3>
                <button class="modal-close" onclick="closeAddSectionModal()">&times;</button>
            </div>
            
            <div class="modal-search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="section-search-input" placeholder="Search by subject code, course title, or instructor..." oninput="filterSections()">
            </div>
            
            <div class="modal-body">
                <div class="modal-table-container">
                    <table class="modal-data-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Section</th>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Capacity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="modal-sections-table-body">
                            <!-- Loaded via API -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Modal and Toast CSS Styles -->
    <style>
        /* Modern Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background: var(--white, #ffffff);
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(30px) scale(0.98);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border-top: 5px solid var(--primary-color, #800000);
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1);
        }

        .modal-header {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color, #dddddd);
            background: #fafafa;
        }

        .modal-header h3 {
            margin: 0;
            color: var(--primary-color, #800000);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--text-light, #666666);
            cursor: pointer;
            transition: color 0.2s;
            line-height: 1;
            padding: 0;
        }

        .modal-close:hover {
            color: var(--primary-color, #800000);
        }

        .modal-search-bar {
            padding: 16px 24px;
            background: #fff;
            border-bottom: 1px solid var(--border-color, #dddddd);
            position: relative;
            display: flex;
            align-items: center;
        }

        .modal-search-bar i {
            position: absolute;
            left: 38px;
            color: var(--text-light, #666666);
            font-size: 0.95rem;
        }

        .modal-search-bar input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1px solid var(--border-color, #dddddd);
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            outline: none;
        }

        .modal-search-bar input:focus {
            border-color: var(--primary-color, #800000);
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-table-container {
            border: 1px solid var(--border-color, #dddddd);
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .modal-data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        .modal-data-table th {
            background: #fafafa;
            padding: 12px 16px;
            font-weight: 600;
            color: var(--text-dark, #333333);
            border-bottom: 1px solid var(--border-color, #dddddd);
        }

        .modal-data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color, #dddddd);
        }

        .modal-data-table tr:last-child td {
            border-bottom: none;
        }

        .modal-data-table tr:hover td {
            background: #fafafa;
        }

        /* Action Buttons */
        .btn-add-to-plan {
            background-color: var(--primary-color, #800000);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
        }

        .btn-add-to-plan:hover {
            background-color: var(--primary-hover, #a00000);
            box-shadow: 0 4px 8px rgba(128, 0, 0, 0.2);
            transform: translateY(-1px);
        }

        .btn-add-to-plan:active {
            transform: translateY(0);
        }

        .btn-added-state {
            background-color: #e6f4ea;
            color: #137333;
            border: 1px solid #c2e7c9;
            padding: 7px 15px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: not-allowed;
            text-transform: uppercase;
        }
        
        /* Toast Notifications */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #333;
            color: white;
            padding: 14px 24px;
            border-radius: 6px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1100;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .toast-notification.active {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-success {
            border-left: 4px solid #137333;
            background: #eef9f0;
            color: #137333;
        }

        .toast-error {
            border-left: 4px solid #c5221f;
            background: #fce8e6;
            color: #c5221f;
        }
    </style>
</body>
</html>
