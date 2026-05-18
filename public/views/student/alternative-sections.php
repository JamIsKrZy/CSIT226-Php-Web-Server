<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alternative Sections - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php $currentPage = 'alternative-sections'; include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header">
                <h2>Alternative Section</h2>
                <p>Prepare backup sections before official enrollment begins.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-list-check"></i>
                        <span>Planned Sections</span>
                    </div>
                    <div class="stat-value" id="stat-planned-sections">0</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">Selected in your plan</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-shuffle"></i>
                        <span>Backup Readiness</span>
                    </div>
                    <div class="stat-value" id="stat-backup-readiness" style="color: var(--status-warning);">0 / 0</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">Subjects with alternative sections</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>High Interest Alert</span>
                    </div>
                    <div class="stat-value" id="stat-high-interest" style="color: var(--status-danger);">0</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">Preferred sections nearing capacity</p>
                </div>
            </div>

            <div class="alternative-planning-list" id="alternative-planning-list">
                <div class="card" style="text-align: center; padding: 40px; color: var(--text-medium);">
                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: 12px;"></i>
                    <p>Loading your planned subjects...</p>
                </div>
            </div>

            <div style="background: #eef2ff; border: 1px solid #c7d2fe; border-radius: var(--radius-md); padding: 24px; display: flex; gap: 20px; align-items: flex-start; margin-top: 32px;">
                <i class="fa-solid fa-info-circle" style="color: #4f46e5; font-size: 1.5rem; margin-top: 4px;"></i>
                <div>
                    <h4 style="color: #3730a3; margin-bottom: 8px;">Pre-Enrollment Planning Notice</h4>
                    <p style="color: #4338ca; font-size: 0.9rem; line-height: 1.5; margin-bottom: 0;">
                        This platform is for pre-enrollment planning only. Switching sections updates your enrollment plan for that subject.
                        Interest counts are estimates based on student preferences and may change as official enrollment approaches.
                        Please ensure your final plan remains within the 24-unit limit and satisfies all prerequisites.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        const currentStudentID = <?php echo $studentID !== null ? (int) $studentID : 'null'; ?>;
        let alternativeData = { subjects: [], stats: {} };

        function showToast(message, type = 'success') {
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) existingToast.remove();

            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
                <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.classList.add('active'), 10);
            setTimeout(() => {
                toast.classList.remove('active');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function getInterestPercent(section) {
            return Math.round((section.interest / section.capacity) * 100);
        }

        function getProgressBarClass(label) {
            return label === 'HIGH' ? 'red' : 'maroon';
        }

        function renderSectionCard(section, options = {}) {
            const { isPreferred = false, plannedItemID = null, showActions = false } = options;
            const percent = getInterestPercent(section);
            const borderStyle = isPreferred
                ? 'border-color: var(--primary-maroon); background: #fffdfd;'
                : 'padding: 12px 16px;';

            let actionsHtml = '';
            if (showActions && plannedItemID) {
                actionsHtml = `
                    <div style="display: flex; gap: 8px;">
                        <button class="btn btn-primary switch-section-btn"
                            style="padding: 6px 12px; font-size: 0.75rem;"
                            data-planned-item-id="${plannedItemID}"
                            data-section-id="${section.sectionID}"
                            data-section-code="${section.section}">
                            <i class="fa-solid fa-shuffle"></i> Switch
                        </button>
                    </div>
                `;
            }

            return `
                <div class="section-demand-card" style="${borderStyle}">
                    <div style="display: flex; justify-content: space-between; align-items: ${isPreferred ? 'flex-start' : 'center'}; margin-bottom: ${isPreferred ? '12px' : '0'};">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: ${isPreferred ? '4px' : '0'};">
                                <span style="font-weight: 700; font-size: ${isPreferred ? '1.1rem' : '1rem'}; color: ${isPreferred ? 'var(--primary-maroon)' : 'inherit'};">${section.section}</span>
                                <span class="badge badge-${section.label.toLowerCase()}"${!isPreferred ? ' style="font-size: 0.65rem; padding: 2px 6px;"' : ''}>${section.label}</span>
                            </div>
                            <div style="font-size: ${isPreferred ? '0.9rem' : '0.8rem'}; color: var(--text-medium);">
                                ${section.schedule}${isPreferred ? '<br>' + section.room : ` | ${section.interest} interested`}
                            </div>
                        </div>
                        <div style="text-align: right; display: flex; align-items: center; gap: 16px;">
                            ${isPreferred ? `
                                <div>
                                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-dark);">${section.interest} Students</div>
                                    <div style="font-size: 0.75rem; color: var(--text-medium);">Interested</div>
                                </div>
                            ` : actionsHtml}
                        </div>
                    </div>
                    ${isPreferred ? `
                        <div class="progress-container" style="margin-bottom: 0;">
                            <div class="progress-label">
                                <span>Interest Level</span>
                                <span>${percent}%</span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar ${getProgressBarClass(section.label)}" style="width: ${percent}%;"></div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        function renderSubjectCard(subject) {
            const hasAlternatives = subject.alternatives && subject.alternatives.length > 0;
            const preferredHtml = subject.preferred
                ? renderSectionCard(subject.preferred, { isPreferred: true })
                : '<p style="color: var(--text-medium); font-size: 0.9rem;">No preferred section selected.</p>';

            let alternativesHtml = '';
            if (hasAlternatives) {
                alternativesHtml = subject.alternatives.map(alt =>
                    renderSectionCard(alt, {
                        showActions: true,
                        plannedItemID: subject.plannedItemID
                    })
                ).join('');
            } else {
                alternativesHtml = `
                    <p style="color: var(--text-medium); font-size: 0.9rem; padding: 12px 0;">
                        No other sections are available for this subject.
                    </p>
                `;
            }

            return `
                <div class="card" style="margin-bottom: 24px;" data-planned-item-id="${subject.plannedItemID}">
                    <div class="card-header" style="background: #fafafa;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="background: var(--primary-maroon); color: white; padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 0.9rem;">
                                ${subject.code}
                            </div>
                            <h3 style="margin-bottom: 0;">${subject.title}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                            <div style="border-right: 1px solid var(--border-color); padding-right: 32px;">
                                <h4 style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Current Preferred Section</h4>
                                ${preferredHtml}
                            </div>
                            <div>
                                <h4 style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Available Alternative Sections</h4>
                                <div class="alternatives-container" style="display: flex; flex-direction: column; gap: 12px;">
                                    ${alternativesHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateStats(stats) {
            document.getElementById('stat-planned-sections').textContent = stats.plannedSections ?? 0;
            document.getElementById('stat-backup-readiness').textContent =
                `${stats.subjectsWithAlternatives ?? 0} / ${stats.plannedSections ?? 0}`;
            document.getElementById('stat-high-interest').textContent = stats.highInterestAlerts ?? 0;
        }

        function renderAlternativeList() {
            const container = document.getElementById('alternative-planning-list');
            const subjects = alternativeData.subjects || [];

            if (subjects.length === 0) {
                container.innerHTML = `
                    <div class="card" style="text-align: center; padding: 40px;">
                        <i class="fa-solid fa-calendar-plus" style="font-size: 2rem; color: var(--text-medium); margin-bottom: 16px;"></i>
                        <h3 style="margin-bottom: 8px;">No Planned Subjects Yet</h3>
                        <p style="color: var(--text-medium); margin-bottom: 20px;">
                            Add sections to your enrollment plan first to see alternative options here.
                        </p>
                        <a href="/enrollment-plan" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Go to Enrollment Plan
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = subjects.map(renderSubjectCard).join('');
            bindSwitchButtons();
        }

        function bindSwitchButtons() {
            document.querySelectorAll('.switch-section-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const plannedItemID = parseInt(this.dataset.plannedItemId, 10);
                    const sectionID = parseInt(this.dataset.sectionId, 10);
                    const sectionCode = this.dataset.sectionCode || 'this section';

                    if (!confirm(`Switch your planned section to ${sectionCode}?`)) return;

                    switchSection(plannedItemID, sectionID);
                });
            });
        }

        function switchSection(plannedItemID, sectionID) {
            fetch('/api/student/switch-section', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ plannedItemID, sectionID })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Your planned section has been updated.', 'success');
                        loadAlternativeSections();
                    } else {
                        showToast(data.message || 'Failed to switch section.', 'error');
                    }
                })
                .catch(() => showToast('Network error while switching section.', 'error'));
        }

        function loadAlternativeSections() {
            if (!currentStudentID) {
                document.getElementById('alternative-planning-list').innerHTML = `
                    <div class="card" style="text-align: center; padding: 40px; color: var(--status-danger);">
                        <p>No student profile linked to this account.</p>
                    </div>
                `;
                return;
            }

            fetch('/api/student/alternative-sections')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alternativeData = data.data || { subjects: [], stats: {} };
                        updateStats(alternativeData.stats || {});
                        renderAlternativeList();
                    } else {
                        document.getElementById('alternative-planning-list').innerHTML = `
                            <div class="card" style="text-align: center; padding: 40px; color: var(--status-danger);">
                                <p>${data.message || 'Failed to load alternative sections.'}</p>
                            </div>
                        `;
                    }
                })
                .catch(() => {
                    document.getElementById('alternative-planning-list').innerHTML = `
                        <div class="card" style="text-align: center; padding: 40px; color: var(--status-danger);">
                            <p>Network error while loading alternative sections.</p>
                        </div>
                    `;
                });
        }

        document.addEventListener('DOMContentLoaded', loadAlternativeSections);
    </script>
</body>
</html>
