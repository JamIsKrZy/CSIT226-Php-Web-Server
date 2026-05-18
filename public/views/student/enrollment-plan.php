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

                <div class="stat-card" id="conflict-stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Schedule Conflicts</span>
                    </div>
                    <div class="stat-value" id="conflict-stat-value"><span id="conflict-count">0</span> Conflicts</div>
                    <p id="conflict-stat-note" style="font-size: 0.8rem; color: var(--text-medium); margin-top: 4px;">No overlapping schedules</p>
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
                                    <h4>Schedule Conflicts</h4>
                                    <div id="schedule-conflict-list" class="conflict-validation-list">
                                        <p class="conflict-validation-empty">No schedule conflicts detected.</p>
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
        const currentStudentID = <?php echo $studentID !== null ? (int) $studentID : 'null'; ?>;

        function getPlannedCourseIds() {
            return new Set(enrollmentPlan.map(item => Number(item.courseID)));
        }

        let scheduleConflicts = [];

        function parseTimeToMinutes(timeStr) {
            if (!timeStr) return null;
            const value = timeStr.trim().toUpperCase();
            const match12 = value.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/);
            if (match12) {
                let hours = parseInt(match12[1], 10);
                const minutes = parseInt(match12[2], 10);
                if (match12[3] === 'PM' && hours !== 12) hours += 12;
                if (match12[3] === 'AM' && hours === 12) hours = 0;
                return hours * 60 + minutes;
            }
            const match24 = value.match(/^(\d{1,2}):(\d{2})$/);
            if (match24) {
                return parseInt(match24[1], 10) * 60 + parseInt(match24[2], 10);
            }
            return null;
        }

        function parseDayTokens(daysPart) {
            const days = [];
            const normalized = (daysPart || '').replace(/\s+/g, '');
            let index = 0;

            while (index < normalized.length) {
                const remaining = normalized.slice(index).toLowerCase();
                if (remaining.startsWith('th')) {
                    days.push('Thursday');
                    index += 2;
                } else if (remaining.startsWith('sat')) {
                    days.push('Saturday');
                    index += 3;
                } else if (remaining.startsWith('sa')) {
                    days.push('Saturday');
                    index += 2;
                } else {
                    const token = normalized[index].toUpperCase();
                    const dayMap = {
                        M: 'Monday',
                        T: 'Tuesday',
                        W: 'Wednesday',
                        F: 'Friday',
                        S: 'Sunday',
                    };
                    if (dayMap[token]) {
                        days.push(dayMap[token]);
                    }
                    index += 1;
                }
            }

            return [...new Set(days)];
        }

        function parseTimeslot(timeslot) {
            if (!timeslot || timeslot === '---' || timeslot === 'TBA') {
                return null;
            }

            const trimmed = timeslot.trim();
            const timeMatch = trimmed.match(
                /(\d{1,2}:\d{2}(?:\s*[AP]M)?)\s*-\s*(\d{1,2}:\d{2}(?:\s*[AP]M)?)/i
            );
            if (!timeMatch) {
                return null;
            }

            const start = parseTimeToMinutes(timeMatch[1]);
            const end = parseTimeToMinutes(timeMatch[2]);
            if (start === null || end === null || end <= start) {
                return null;
            }

            const daysPart = trimmed.slice(0, trimmed.indexOf(timeMatch[0])).trim();
            const days = parseDayTokens(daysPart);
            if (days.length === 0) {
                return null;
            }

            return { days, start, end, raw: trimmed };
        }

        function slotsOverlap(slotA, slotB) {
            if (!slotA || !slotB) {
                return false;
            }
            const sharedDays = slotA.days.filter(day => slotB.days.includes(day));
            if (sharedDays.length === 0) {
                return false;
            }
            return slotA.start < slotB.end && slotB.start < slotA.end;
        }

        function getScheduleConflicts(items) {
            const conflicts = [];
            const parsedItems = items.map(item => ({
                item,
                slot: parseTimeslot(item.timeslot),
            }));

            for (let i = 0; i < parsedItems.length; i++) {
                for (let j = i + 1; j < parsedItems.length; j++) {
                    const left = parsedItems[i];
                    const right = parsedItems[j];
                    if (!left.slot || !right.slot) {
                        continue;
                    }
                    if (slotsOverlap(left.slot, right.slot)) {
                        conflicts.push({
                            a: left.item,
                            b: right.item,
                            sharedDays: left.slot.days.filter(day => right.slot.days.includes(day)),
                            message: `${left.item.courseCode} ${left.item.sectionCode} (${left.item.timeslot}) overlaps with ${right.item.courseCode} ${right.item.sectionCode} (${right.item.timeslot})`,
                        });
                    }
                }
            }

            return conflicts;
        }

        function getConflictingSectionIds(conflicts) {
            const ids = new Set();
            conflicts.forEach(conflict => {
                ids.add(Number(conflict.a.sectionID));
                ids.add(Number(conflict.b.sectionID));
            });
            return ids;
        }

        function getAddConflictsForSection(section) {
            if (!section) {
                return [];
            }
            const newSlot = parseTimeslot(section.timeslot);
            if (!newSlot) {
                return [];
            }

            return enrollmentPlan.filter(item => slotsOverlap(newSlot, parseTimeslot(item.timeslot)));
        }

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
            if (!currentStudentID) {
                document.getElementById('enrollment-table-body').innerHTML =
                    '<tr><td colspan="7" style="text-align: center; padding: 20px; color: var(--status-danger);">No student profile linked to this account.</td></tr>';
                return;
            }

            fetch('/api/student/enrollment-plan')
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
            scheduleConflicts = getScheduleConflicts(enrollmentPlan);
            const conflictingSectionIds = getConflictingSectionIds(scheduleConflicts);

            if (enrollmentPlan.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px; color: var(--text-medium);">No sections selected. Select a section to get started.</td></tr>';
                return;
            }

            enrollmentPlan.forEach((item) => {
                const row = document.createElement('tr');
                const hasConflict = conflictingSectionIds.has(Number(item.sectionID));
                if (hasConflict) {
                    row.classList.add('row-schedule-conflict');
                }

                const statusBadgeColor = item.enrollmentStatus === 'enrolled' ? 'badge-valid' : item.enrollmentStatus === 'pending' ? '#ffc107' : '#6c757d';
                const statusLabel = item.enrollmentStatus ? item.enrollmentStatus.charAt(0).toUpperCase() + item.enrollmentStatus.slice(1) : 'Pending';
                const scheduleCell = hasConflict
                    ? `<span class="schedule-conflict-label"><i class="fa-solid fa-triangle-exclamation"></i> ${item.timeslot || '---'}</span>`
                    : (item.timeslot || '---');

                row.innerHTML = `
                    <td>
                        <div class="section-row-info">
                            <span class="section-row-title">${item.courseCode}</span>
                            <span class="section-row-subtitle">${item.courseName}</span>
                        </div>
                    </td>
                    <td>${item.sectionCode}</td>
                    <td style="font-size: 0.9rem;">${scheduleCell}</td>
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

            scheduleConflicts = getScheduleConflicts(enrollmentPlan);
            const conflictCount = scheduleConflicts.length;
            const conflictCountEl = document.getElementById('conflict-count');
            const conflictStatValue = document.getElementById('conflict-stat-value');
            const conflictStatCard = document.getElementById('conflict-stat-card');
            const conflictStatNote = document.getElementById('conflict-stat-note');
            const conflictListEl = document.getElementById('schedule-conflict-list');

            conflictCountEl.textContent = conflictCount;

            if (conflictCount > 0) {
                if (conflictStatValue) conflictStatValue.style.color = 'var(--status-danger)';
                if (conflictStatCard) {
                    conflictStatCard.style.borderColor = '#f5c6cb';
                    conflictStatCard.classList.add('has-conflicts');
                }
                if (conflictStatNote) {
                    conflictStatNote.textContent = 'Resolve overlapping class times';
                    conflictStatNote.style.color = 'var(--status-danger)';
                }
                if (conflictListEl) {
                    conflictListEl.innerHTML = scheduleConflicts.map(conflict => `
                        <div class="conflict-validation-item">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <p>${conflict.message}</p>
                        </div>
                    `).join('');
                }
            } else {
                if (conflictStatValue) conflictStatValue.style.color = '';
                if (conflictStatCard) {
                    conflictStatCard.style.borderColor = '';
                    conflictStatCard.classList.remove('has-conflicts');
                }
                if (conflictStatNote) {
                    conflictStatNote.textContent = 'No overlapping schedules';
                    conflictStatNote.style.color = 'var(--text-medium)';
                }
                if (conflictListEl) {
                    conflictListEl.innerHTML = '<p class="conflict-validation-empty">No schedule conflicts detected.</p>';
                }
            }

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
            } else if (conflictCount > 0) {
                readinessElement.textContent = 'Has Conflicts';
                readinessElement.style.color = 'var(--status-danger)';
                planStatusElement.textContent = 'Conflicts Found';
                planMessageElement.textContent = `Resolve ${conflictCount} schedule conflict${conflictCount > 1 ? 's' : ''} before enrollment.`;
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

        let modalCurrentPage = 1;
        const modalPageSize = 2; // Keep it clean (2 subjects/page) since each subject has multiple sections

        function openAddSectionModal() {
            const modal = document.getElementById('add-section-modal');
            document.getElementById('section-search-input').value = ''; // Reset search
            modalCurrentPage = 1; // Reset to page 1
            
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
            const container = document.getElementById('modal-subjects-container');
            container.innerHTML = '';
            
            const searchTerm = document.getElementById('section-search-input').value.toLowerCase().trim();

            // 1. Group sections by Subject/Course Code
            const groups = {};
            allSections.forEach(sec => {
                const code = sec.courseCode;
                if (!groups[code]) {
                    groups[code] = {
                        courseCode: code,
                        courseName: sec.courseName,
                        sections: []
                    };
                }
                
                // Search matching logic (matches subject or specific section/instructor)
                const subjectMatches = (sec.courseCode || '').toLowerCase().includes(searchTerm) || 
                                       (sec.courseName || '').toLowerCase().includes(searchTerm);
                
                const sectionMatches = (sec.sectionCode || '').toLowerCase().includes(searchTerm) ||
                                       (sec.instructor || '').toLowerCase().includes(searchTerm);
                
                if (searchTerm === '' || subjectMatches || sectionMatches) {
                    groups[code].sections.push(sec);
                }
            });

            // Filter out subjects that have 0 sections after filtering
            let groupedSubjects = Object.values(groups).filter(sub => sub.sections.length > 0);

            if (groupedSubjects.length === 0) {
                container.innerHTML = '<div style="text-align: center; padding: 40px; color: var(--text-medium);"><i class="fa-solid fa-folder-open" style="font-size: 2.5rem; margin-bottom: 12px; display: block; color: var(--text-light);"></i> No matching courses or sections found.</div>';
                document.getElementById('modal-pagination-controls').style.display = 'none';
                return;
            }

            // 2. Paginate Subjects
            const totalSubjects = groupedSubjects.length;
            const totalPages = Math.ceil(totalSubjects / modalPageSize);
            
            if (modalCurrentPage > totalPages) {
                modalCurrentPage = totalPages;
            }
            if (modalCurrentPage < 1) {
                modalCurrentPage = 1;
            }

            const startIndex = (modalCurrentPage - 1) * modalPageSize;
            const endIndex = startIndex + modalPageSize;
            const paginatedSubjects = groupedSubjects.slice(startIndex, endIndex);
            const plannedCourseIds = getPlannedCourseIds();

            // 3. Render Grouped Cards
            paginatedSubjects.forEach(sub => {
                const card = document.createElement('div');
                card.className = 'subject-card';
                
                let sectionsRowsHtml = '';
                sub.sections.forEach(sec => {
                    const isAlreadyAdded = enrollmentPlan.some(
                        planItem => Number(planItem.sectionID) === Number(sec.sectionID)
                    );
                    const isSubjectBlocked = plannedCourseIds.has(Number(sec.courseID)) && !isAlreadyAdded;
                    const availabilityColor = sec.enrolledCount >= sec.capacity ? 'var(--status-danger)' : 'var(--text-dark)';

                    let actionButtonHtml;
                    if (isAlreadyAdded) {
                        actionButtonHtml = `
                            <button class="btn-added-state" disabled>
                                <i class="fa-solid fa-check"></i> Added
                            </button>
                        `;
                    } else if (isSubjectBlocked) {
                        actionButtonHtml = `
                            <button class="btn-subject-planned-state" disabled title="You already planned a section for this subject">
                                <i class="fa-solid fa-ban"></i> Subject Planned
                            </button>
                        `;
                    } else {
                        const sectionLabel = `${sec.courseCode} ${sec.sectionCode}`.replace(/'/g, "\\'");
                        const wouldConflict = getAddConflictsForSection(sec).length > 0;
                        const conflictHint = wouldConflict
                            ? ' title="This section may conflict with your current schedule"'
                            : '';
                        actionButtonHtml = `
                            <button class="btn-add-to-plan${wouldConflict ? ' btn-add-conflict-warning' : ''}"${conflictHint} onclick="addSectionToPlan(${sec.sectionID}, '${sectionLabel}')">
                                <i class="fa-solid fa-${wouldConflict ? 'triangle-exclamation' : 'plus'}"></i> ${wouldConflict ? 'Add (Conflict)' : 'Add'}
                            </button>
                        `;
                    }

                    sectionsRowsHtml += `
                        <tr class="${isSubjectBlocked ? 'row-subject-planned' : ''}">
                            <td style="font-weight: 600; font-size: 0.95rem;">${sec.sectionCode}</td>
                            <td>
                                <div style="font-weight: 500; font-size: 0.85rem;">${sec.timeslot || '---'}</div>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-light);">
                                <i class="fa-solid fa-user-tie" style="margin-right: 4px;"></i> ${sec.instructor || 'Staff'}
                            </td>
                            <td style="font-size: 0.85rem; font-weight: 500;">${sec.room || '---'}</td>
                            <td style="font-size: 0.85rem;">
                                <span style="font-weight: 700; color: ${availabilityColor};">${sec.enrolledCount}</span> / ${sec.capacity}
                            </td>
                            <td style="text-align: right; padding-right: 20px;">
                                ${actionButtonHtml}
                            </td>
                        </tr>
                    `;
                });

                card.innerHTML = `
                    <div class="subject-card-header">
                        <span class="subject-code">${sub.courseCode}</span>
                        <span class="subject-name">${sub.courseName}</span>
                    </div>
                    <div class="subject-card-body">
                        <table class="modal-data-table">
                            <thead>
                                <tr>
                                    <th>Section</th>
                                    <th>Schedule</th>
                                    <th>Instructor</th>
                                    <th>Room</th>
                                    <th>Availability</th>
                                    <th style="text-align: right; padding-right: 20px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${sectionsRowsHtml}
                            </tbody>
                        </table>
                    </div>
                `;
                container.appendChild(card);
            });

            // 4. Render Pagination Buttons
            renderPaginationControls(totalPages);
        }

        /**
         * Build a compact page list: 1 … 4 5 [6] 7 8 … 22
         * Shows at most maxVisible numbered buttons between first and last page.
         */
        function buildModalPageList(currentPage, totalPages, maxVisible = 5) {
            if (totalPages <= 1) {
                return [];
            }

            if (totalPages <= maxVisible + 2) {
                return Array.from({ length: totalPages }, (_, i) => i + 1);
            }

            const pages = [1];
            let start = Math.max(2, currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(totalPages - 1, currentPage + Math.floor(maxVisible / 2));

            if (currentPage <= Math.floor(maxVisible / 2) + 1) {
                start = 2;
                end = Math.min(totalPages - 1, maxVisible);
            }

            if (currentPage >= totalPages - Math.floor(maxVisible / 2)) {
                end = totalPages - 1;
                start = Math.max(2, totalPages - maxVisible);
            }

            if (start > 2) {
                pages.push('ellipsis');
            }

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            if (end < totalPages - 1) {
                pages.push('ellipsis');
            }

            pages.push(totalPages);
            return pages;
        }

        function goToModalPage(page) {
            modalCurrentPage = page;
            renderModalSectionsTable();
            const modalBody = document.querySelector('#add-section-modal .modal-body');
            if (modalBody) {
                modalBody.scrollTop = 0;
            }
        }

        function renderPaginationControls(totalPages) {
            const paginationControls = document.getElementById('modal-pagination-controls');
            paginationControls.innerHTML = '';

            if (totalPages <= 1) {
                paginationControls.style.display = 'none';
                return;
            }

            paginationControls.style.display = 'flex';

            const info = document.createElement('span');
            info.className = 'pagination-info';
            info.textContent = `Page ${modalCurrentPage} of ${totalPages}`;
            paginationControls.appendChild(info);

            const pagesWrapper = document.createElement('div');
            pagesWrapper.className = 'modal-pagination-pages';

            const prevBtn = document.createElement('button');
            prevBtn.className = 'btn-page';
            prevBtn.setAttribute('aria-label', 'Previous page');
            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
            prevBtn.disabled = modalCurrentPage === 1;
            prevBtn.onclick = () => {
                if (modalCurrentPage > 1) {
                    goToModalPage(modalCurrentPage - 1);
                }
            };
            pagesWrapper.appendChild(prevBtn);

            buildModalPageList(modalCurrentPage, totalPages).forEach(item => {
                if (item === 'ellipsis') {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'pagination-ellipsis';
                    ellipsis.textContent = '…';
                    ellipsis.setAttribute('aria-hidden', 'true');
                    pagesWrapper.appendChild(ellipsis);
                    return;
                }

                const pageBtn = document.createElement('button');
                pageBtn.className = `btn-page ${modalCurrentPage === item ? 'active' : ''}`;
                pageBtn.textContent = item;
                pageBtn.setAttribute('aria-label', `Page ${item}`);
                pageBtn.setAttribute('aria-current', modalCurrentPage === item ? 'page' : 'false');
                pageBtn.onclick = () => goToModalPage(item);
                pagesWrapper.appendChild(pageBtn);
            });

            const nextBtn = document.createElement('button');
            nextBtn.className = 'btn-page';
            nextBtn.setAttribute('aria-label', 'Next page');
            nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
            nextBtn.disabled = modalCurrentPage === totalPages;
            nextBtn.onclick = () => {
                if (modalCurrentPage < totalPages) {
                    goToModalPage(modalCurrentPage + 1);
                }
            };
            pagesWrapper.appendChild(nextBtn);

            paginationControls.appendChild(pagesWrapper);
        }

        function filterSections() {
            modalCurrentPage = 1; // Reset to page 1 on active search
            renderModalSectionsTable();
        }

        function addSectionToPlan(sectionID, sectionLabel) {
            if (!currentStudentID) {
                showToast('No student profile linked to this account.', 'error');
                return;
            }

            const section = allSections.find(sec => Number(sec.sectionID) === Number(sectionID));
            const addConflicts = getAddConflictsForSection(section);

            if (addConflicts.length > 0) {
                const conflictDetails = addConflicts
                    .map(item => `• ${item.courseCode} ${item.sectionCode} (${item.timeslot || 'TBA'})`)
                    .join('\n');

                const proceed = confirm(
                    `This section will conflict with your existing schedule:\n\n${conflictDetails}\n\nAre you sure you want to add it anyway?`
                );

                if (!proceed) {
                    return;
                }
            }

            fetch('/api/student/add-section', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
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
            
            <div class="modal-body" id="modal-subjects-container">
                <!-- Grouped Subject Cards Loaded dynamically via Javascript -->
            </div>
            
            <div class="modal-pagination" id="modal-pagination-controls">
                <!-- Dynamic Pagination Footer -->
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

        /* Grouped Subject Cards Style */
        .subject-card {
            border: 1px solid var(--border-color, #dddddd);
            border-radius: 8px;
            margin-bottom: 24px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
        }

        .subject-card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        .subject-card-header {
            background: #fafafa;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color, #dddddd);
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--primary-color, #800000);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .subject-card-header .subject-code {
            background: var(--primary-color, #800000);
            color: white;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .subject-card-header .subject-name {
            color: var(--text-dark, #333333);
            font-size: 0.95rem;
        }

        .subject-card-body {
            padding: 0;
        }

        /* Dynamic Pagination Footer */
        .modal-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 20px;
            border-top: 1px solid var(--border-color, #dddddd);
            background: #fafafa;
            flex-shrink: 0;
        }

        .pagination-info {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-medium, #666666);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .modal-pagination-pages {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            flex-wrap: nowrap;
            min-width: 0;
            overflow: hidden;
        }

        .pagination-ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 34px;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-light, #999999);
            user-select: none;
            letter-spacing: 1px;
        }

        .btn-page {
            background: #fff;
            border: 1px solid var(--border-color, #dddddd);
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark, #333333);
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            flex-shrink: 0;
        }

        .btn-page:hover:not(:disabled) {
            border-color: var(--primary-color, #800000);
            color: var(--primary-color, #800000);
            background: rgba(128, 0, 0, 0.02);
        }

        .btn-page.active {
            background: var(--primary-color, #800000);
            border-color: var(--primary-color, #800000);
            color: white !important;
        }

        .btn-page:disabled {
            color: var(--text-light, #aaaaaa);
            cursor: not-allowed;
            background: #f5f5f5;
        }

        @media (max-width: 560px) {
            .modal-pagination {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .pagination-info {
                text-align: center;
            }

            .modal-pagination-pages {
                justify-content: center;
            }
        }

        .modal-data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        .modal-data-table th {
            background: #fafafa;
            padding: 10px 16px;
            font-weight: 600;
            color: var(--text-dark, #333333);
            border-bottom: 1px solid var(--border-color, #dddddd);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-data-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color, #dddddd);
            vertical-align: middle;
        }

        .modal-data-table tr:last-child td {
            border-bottom: none;
        }

        .modal-data-table tr:hover td {
            background: #fcfcfc;
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

        .btn-add-conflict-warning {
            background-color: #fff4e5;
            color: #b45309;
            border: 1px solid #fbbf24;
        }

        .btn-add-conflict-warning:hover {
            background-color: #ffedd5;
            color: #92400e;
            border-color: #f59e0b;
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.2);
        }

        .row-schedule-conflict {
            background-color: #fff5f5;
        }

        .row-schedule-conflict td {
            border-top: 1px solid #fecaca;
            border-bottom: 1px solid #fecaca;
        }

        .schedule-conflict-label {
            color: var(--status-danger);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .conflict-validation-list {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .conflict-validation-empty {
            font-size: 0.8rem;
            color: var(--text-medium);
            margin: 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
        }

        .conflict-validation-item {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 12px;
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: var(--radius-sm);
        }

        .conflict-validation-item i {
            color: var(--status-danger);
            margin-top: 2px;
            flex-shrink: 0;
        }

        .conflict-validation-item p {
            margin: 0;
            font-size: 0.8rem;
            color: #7f1d1d;
            line-height: 1.45;
        }

        #conflict-stat-card.has-conflicts {
            border: 1px solid #fecaca;
            background: #fffbfb;
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

        .btn-subject-planned-state {
            background-color: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            padding: 7px 15px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: not-allowed;
            text-transform: uppercase;
        }

        .row-subject-planned {
            opacity: 0.65;
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
