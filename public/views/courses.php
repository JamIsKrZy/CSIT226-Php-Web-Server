<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Management</title>
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

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
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
            overflow: hidden;
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

        .category-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-core {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-elective {
            background: #f3e5f5;
            color: #7b1fa2;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
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

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>📚 Courses Management</h1>
            <div class="header-actions">
                <div class="refresh-status">
                    <span class="refresh-indicator"></span>
                    <span id="refreshStatus">Live updates enabled</span>
                </div>
                <button class="btn" onclick="openAddCourseModal()">+ Add Course</button>
                <button class="btn btn-secondary btn-sm" onclick="toggleAutoRefresh()" id="toggleRefreshBtn">Auto-refresh: ON</button>
            </div>
        </div>

        <div id="alertContainer"></div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Category</th>
                        <th>Department</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="coursesTable">
                    <tr class="loading">
                        <td colspan="7">
                            <div class="spinner"></div>
                            Loading courses...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Course Modal -->
    <div id="courseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add Course</h2>
                <button class="modal-close" onclick="closeCourseModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="courseForm" onsubmit="saveCourse(event)">
                    <input type="hidden" id="courseId">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="courseCode">Course Code *</label>
                            <input type="text" id="courseCode" required>
                        </div>

                        <div class="form-group">
                            <label for="credits">Credits *</label>
                            <input type="number" id="credits" min="1" max="6" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="courseName">Course Name *</label>
                        <input type="text" id="courseName" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" rows="3"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category">
                                <option value="">Select Category</option>
                                <option value="Core">Core</option>
                                <option value="Elective">Elective</option>
                                <option value="General Education">General Education</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" id="department" placeholder="e.g., Computer Science">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCourseModal()">Cancel</button>
                <button class="btn" form="courseForm">Save Course</button>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const AUTO_REFRESH_INTERVAL = 5000; // 5 seconds
        let autoRefreshEnabled = true;
        let refreshIntervalId = null;
        let coursesCache = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
            startAutoRefresh();
        });

        function startAutoRefresh() {
            if (autoRefreshEnabled) {
                refreshIntervalId = setInterval(loadCourses, AUTO_REFRESH_INTERVAL);
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
                    renderCoursesTable(coursesCache);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showAlert('Failed to load courses', 'error');
            }
        }

        function renderCoursesTable(courses) {
            const tbody = document.getElementById('coursesTable');

            if (courses.length === 0) {
                tbody.innerHTML = '<tr class="no-data"><td colspan="7">No courses found</td></tr>';
                return;
            }

            tbody.innerHTML = courses.map(course => {
                const categoryBadgeClass = course.category === 'Core' ? 'badge-core' : 'badge-elective';
                return `
                    <tr>
                        <td><strong>${course.courseCode}</strong></td>
                        <td>${course.courseName}</td>
                        <td><strong>${course.credits}</strong></td>
                        <td><span class="category-badge ${categoryBadgeClass}">${course.category || 'N/A'}</span></td>
                        <td>${course.department || 'N/A'}</td>
                        <td><small>${new Date(course.createdAt).toLocaleDateString()}</small></td>
                        <td>
                            <div class="actions">
                                <button class="action-btn edit" onclick="openEditCourseModal(${course.courseID})">Edit</button>
                                <button class="action-btn delete" onclick="deleteCourse(${course.courseID})">Delete</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openAddCourseModal() {
            document.getElementById('courseId').value = '';
            document.getElementById('modalTitle').textContent = 'Add Course';
            document.getElementById('courseForm').reset();
            document.getElementById('courseModal').classList.add('show');
        }

        async function openEditCourseModal(courseId) {
            try {
                const course = coursesCache.find(c => c.courseID === courseId);
                if (course) {
                    document.getElementById('courseId').value = course.courseID;
                    document.getElementById('courseCode').value = course.courseCode;
                    document.getElementById('courseName').value = course.courseName;
                    document.getElementById('credits').value = course.credits;
                    document.getElementById('description').value = course.description || '';
                    document.getElementById('category').value = course.category || '';
                    document.getElementById('department').value = course.department || '';
                    document.getElementById('modalTitle').textContent = 'Edit Course';
                    document.getElementById('courseModal').classList.add('show');
                }
            } catch (error) {
                showAlert('Failed to load course details', 'error');
            }
        }

        function closeCourseModal() {
            document.getElementById('courseModal').classList.remove('show');
            document.getElementById('courseForm').reset();
        }

        async function saveCourse(event) {
            event.preventDefault();

            const courseId = document.getElementById('courseId').value;
            const courseData = {
                courseCode: document.getElementById('courseCode').value,
                courseName: document.getElementById('courseName').value,
                credits: document.getElementById('credits').value,
                description: document.getElementById('description').value,
                category: document.getElementById('category').value,
                department: document.getElementById('department').value
            };

            if (courseId) {
                courseData.courseID = courseId;
            }

            try {
                const method = courseId ? 'PUT' : 'POST';
                const endpoint = '/api/courses';

                const response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(courseData)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    closeCourseModal();
                    loadCourses();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error saving course', 'error');
            }
        }

        async function deleteCourse(courseId) {
            if (!confirm('Are you sure you want to delete this course? This will also delete all associated sections.')) {
                return;
            }

            try {
                const response = await fetch('/api/courses', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ courseID: courseId })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Course deleted successfully', 'success');
                    loadCourses();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error deleting course', 'error');
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
            const modal = document.getElementById('courseModal');
            if (event.target === modal) {
                closeCourseModal();
            }
        };
    </script>
</body>
</html>
