<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections Management</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f5;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            color: #333;
            font-size: 28px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .refresh-status {
            font-size: 12px;
            color: #999;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .refresh-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .table-wrapper {
            background: white;
            border-radius: 8px;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        table th {
            padding: 15px;
            text-align: left;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }

        table tbody tr {
            border-bottom: 1px solid #dee2e6;
            transition: background 0.2s;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        table td {
            padding: 15px;
            color: #555;
            font-size: 14px;
        }

        .capacity-bar {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .capacity-fill {
            flex: 1;
            background: #e0e0e0;
            border-radius: 4px;
            height: 6px;
            overflow: hidden;
        }

        .capacity-progress {
            background: #667eea;
            height: 100%;
        }

        .capacity-text {
            font-size: 12px;
            color: #666;
            white-space: nowrap;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }

        .action-btn.edit {
            color: #667eea;
            border-color: #667eea;
        }

        .action-btn.edit:hover {
            background: #667eea;
            color: white;
        }

        .action-btn.delete {
            color: #dc3545;
            border-color: #dc3545;
        }

        .action-btn.delete:hover {
            background: #dc3545;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #667eea;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            color: #333;
            font-size: 20px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>📋 Sections Management</h1>
            <div class="header-actions">
                <div class="refresh-status">
                    <span class="refresh-indicator"></span>
                    <span id="refreshStatus">Live updates enabled</span>
                </div>
                <button class="btn" onclick="openAddSectionModal()">+ Add Section</button>
                <button class="btn btn-secondary btn-sm" onclick="toggleAutoRefresh()" id="toggleRefreshBtn">Auto-refresh: ON</button>
            </div>
        </div>

        <div id="alertContainer"></div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course</th>
                        <th>Instructor</th>
                        <th>Timeslot</th>
                        <th>Room</th>
                        <th>Capacity</th>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sectionsTable">
                    <tr class="loading">
                        <td colspan="8">
                            <div class="spinner"></div>
                            Loading sections...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Section Modal -->
    <div id="sectionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add Section</h2>
                <button class="modal-close" onclick="closeSectionModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="sectionForm" onsubmit="saveSection(event)">
                    <input type="hidden" id="sectionId">
                    
                    <div class="form-group">
                        <label for="courseSelect">Course *</label>
                        <select id="courseSelect" required onchange="updateCourseInfo()">
                            <option value="">Select a Course</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sectionCode">Section Code *</label>
                        <input type="text" id="sectionCode" placeholder="e.g., CS101-A" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input type="number" id="capacity" value="50" min="1">
                        </div>

                        <div class="form-group">
                            <label for="enrolledCount">Currently Enrolled</label>
                            <input type="number" id="enrolledCount" value="0" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="instructor">Instructor</label>
                        <input type="text" id="instructor" placeholder="e.g., Dr. Smith">
                    </div>

                    <div class="form-group">
                        <label for="timeslot">Timeslot</label>
                        <input type="text" id="timeslot" placeholder="e.g., MWF 10:00-11:00">
                    </div>

                    <div class="form-group">
                        <label for="room">Room/Location</label>
                        <input type="text" id="room" placeholder="e.g., Room 101">
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select id="semester">
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeSectionModal()">Cancel</button>
                <button class="btn" form="sectionForm">Save Section</button>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const AUTO_REFRESH_INTERVAL = 5000; // 5 seconds
        let autoRefreshEnabled = true;
        let refreshIntervalId = null;
        let sectionsCache = [];
        let coursesCache = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
            loadSections();
            startAutoRefresh();
        });

        function startAutoRefresh() {
            if (autoRefreshEnabled) {
                refreshIntervalId = setInterval(loadSections, AUTO_REFRESH_INTERVAL);
            }
        }

        function stopAutoRefresh() {
            if (refreshIntervalId) {
                clearInterval(refreshIntervalId);
                refreshIntervalId = null;
            }
        }

        function toggleAutoRefresh() {
            autoRefreshEnabled = !autoRefreshEnabled;
            const btn = document.getElementById('toggleRefreshBtn');
            const status = document.getElementById('refreshStatus');

            if (autoRefreshEnabled) {
                btn.textContent = 'Auto-refresh: ON';
                status.textContent = 'Live updates enabled';
                startAutoRefresh();
            } else {
                btn.textContent = 'Auto-refresh: OFF';
                status.textContent = 'Live updates disabled';
                stopAutoRefresh();
            }
        }

        async function loadCourses() {
            try {
                const response = await fetch('/api/courses');
                const result = await response.json();

                if (result.success) {
                    coursesCache = result.data;
                    populateCourseSelect();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
            }
        }

        function populateCourseSelect() {
            const select = document.getElementById('courseSelect');
            select.innerHTML = '<option value="">Select a Course</option>' +
                coursesCache.map(c => `<option value="${c.courseID}" data-name="${c.courseName}">${c.courseCode} - ${c.courseName}</option>`).join('');
        }

        function updateCourseInfo() {
            const select = document.getElementById('courseSelect');
            const option = select.options[select.selectedIndex];
            // You can update other fields based on course selection here
        }

        async function loadSections() {
            try {
                const response = await fetch('/api/sections');
                const result = await response.json();

                if (result.success) {
                    sectionsCache = result.data;
                    renderSectionsTable(sectionsCache);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error loading sections:', error);
                showAlert('Failed to load sections', 'error');
            }
        }

        function renderSectionsTable(sections) {
            const tbody = document.getElementById('sectionsTable');

            if (sections.length === 0) {
                tbody.innerHTML = '<tr class="no-data"><td colspan="8">No sections found</td></tr>';
                return;
            }

            tbody.innerHTML = sections.map(section => {
                const capacityPercent = (section.enrolledCount / section.capacity) * 100;
                return `
                    <tr>
                        <td><strong>${section.sectionCode}</strong></td>
                        <td>${section.courseCode}</td>
                        <td>${section.instructor || 'TBA'}</td>
                        <td>${section.timeslot || 'TBA'}</td>
                        <td>${section.room || 'TBA'}</td>
                        <td>
                            <div class="capacity-bar">
                                <div class="capacity-fill">
                                    <div class="capacity-progress" style="width: ${capacityPercent}%"></div>
                                </div>
                                <span class="capacity-text">${section.enrolledCount}/${section.capacity}</span>
                            </div>
                        </td>
                        <td>${section.semester}</td>
                        <td>
                            <div class="actions">
                                <button class="action-btn edit" onclick="openEditSectionModal(${section.sectionID})">Edit</button>
                                <button class="action-btn delete" onclick="deleteSection(${section.sectionID})">Delete</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openAddSectionModal() {
            document.getElementById('sectionId').value = '';
            document.getElementById('modalTitle').textContent = 'Add Section';
            document.getElementById('sectionForm').reset();
            document.getElementById('courseSelect').value = '';
            document.getElementById('sectionModal').classList.add('show');
        }

        async function openEditSectionModal(sectionId) {
            try {
                const section = sectionsCache.find(s => s.sectionID === sectionId);
                if (section) {
                    document.getElementById('sectionId').value = section.sectionID;
                    document.getElementById('courseSelect').value = section.courseID;
                    document.getElementById('sectionCode').value = section.sectionCode;
                    document.getElementById('capacity').value = section.capacity;
                    document.getElementById('enrolledCount').value = section.enrolledCount;
                    document.getElementById('instructor').value = section.instructor || '';
                    document.getElementById('timeslot').value = section.timeslot || '';
                    document.getElementById('room').value = section.room || '';
                    document.getElementById('semester').value = section.semester;
                    document.getElementById('modalTitle').textContent = 'Edit Section';
                    document.getElementById('sectionModal').classList.add('show');
                }
            } catch (error) {
                showAlert('Failed to load section details', 'error');
            }
        }

        function closeSectionModal() {
            document.getElementById('sectionModal').classList.remove('show');
            document.getElementById('sectionForm').reset();
        }

        async function saveSection(event) {
            event.preventDefault();

            const sectionId = document.getElementById('sectionId').value;
            const sectionData = {
                courseID: document.getElementById('courseSelect').value,
                sectionCode: document.getElementById('sectionCode').value,
                capacity: document.getElementById('capacity').value,
                enrolledCount: document.getElementById('enrolledCount').value,
                instructor: document.getElementById('instructor').value,
                timeslot: document.getElementById('timeslot').value,
                room: document.getElementById('room').value,
                semester: document.getElementById('semester').value
            };

            if (sectionId) {
                sectionData.sectionID = sectionId;
            }

            try {
                const method = sectionId ? 'PUT' : 'POST';
                const endpoint = '/api/sections';

                const response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(sectionData)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    closeSectionModal();
                    loadSections();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error saving section', 'error');
            }
        }

        async function deleteSection(sectionId) {
            if (!confirm('Are you sure you want to delete this section?')) {
                return;
            }

            try {
                const response = await fetch('/api/sections', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ sectionID: sectionId })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Section deleted successfully', 'success');
                    loadSections();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error deleting section', 'error');
            }
        }

        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = `alert-${Date.now()}`;
            const alertHtml = `
                <div class="alert alert-${type}" id="${alertId}">
                    ${message}
                </div>
            `;

            alertContainer.insertAdjacentHTML('beforeend', alertHtml);

            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('sectionModal');
            if (event.target === modal) {
                closeSectionModal();
            }
        };
    </script>
</body>
</html>
