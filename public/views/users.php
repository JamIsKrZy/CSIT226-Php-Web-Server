<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
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
            max-width: 1200px;
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

        .user-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-student {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-admin {
            background: #fff3e0;
            color: #f57c00;
        }

        .badge-active {
            background: #e8f5e9;
            color: #388e3c;
        }

        .badge-inactive {
            background: #ffebee;
            color: #d32f2f;
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
            max-width: 500px;
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

        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .filter-group label {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        .filter-group select {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>👥 Users Management</h1>
            <div class="header-actions">
                <div class="refresh-status">
                    <span class="refresh-indicator"></span>
                    <span id="refreshStatus">Live updates enabled</span>
                </div>
                <button class="btn" onclick="openAddUserModal()">+ Add User</button>
                <button class="btn btn-secondary btn-sm" onclick="toggleAutoRefresh()" id="toggleRefreshBtn">Auto-refresh: ON</button>
            </div>
        </div>

        <div class="filters">
            <div class="filter-group">
                <label>Filter by Type:</label>
                <select id="typeFilter" onchange="filterUsers()">
                    <option value="">All Users</option>
                    <option value="student">Students</option>
                    <option value="admin">Admins</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Filter by Status:</label>
                <select id="statusFilter" onchange="filterUsers()">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <div id="alertContainer"></div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTable">
                    <tr class="loading">
                        <td colspan="6">
                            <div class="spinner"></div>
                            Loading users...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add User</h2>
                <button class="modal-close" onclick="closeUserModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="userForm" onsubmit="saveUser(event)">
                    <input type="hidden" id="userId">
                    
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" id="firstName" required>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" id="lastName" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="userType">User Type *</label>
                        <select id="userType" required onchange="updateTypeFields()">
                            <option value="">Select Type</option>
                            <option value="student">Student</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="form-group" id="programInfo" style="display: none; background-color: #f7f9fc; padding: 10px; border-left: 3px solid var(--primary-maroon); border-radius: 4px; font-size: 0.9rem;">
                        <strong>Program:</strong> BS Computer Science (2nd Year, 1st Semester)
                    </div>

                    <div class="form-group" id="departmentField" style="display: none;">
                        <label for="department">Department</label>
                        <input type="text" id="department">
                    </div>

                    <div class="form-group" id="designationField" style="display: none;">
                        <label for="designation">Designation</label>
                        <input type="text" id="designation">
                    </div>

                    <div class="form-group" id="passwordField">
                        <label for="password">Password *</label>
                        <input type="password" id="password" required>
                    </div>

                    <div class="form-group">
                        <label for="academicYear">Academic Year</label>
                        <input type="number" id="academicYear" value="2026">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeUserModal()">Cancel</button>
                <button class="btn" form="userForm">Save User</button>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const AUTO_REFRESH_INTERVAL = 5000; // 5 seconds
        let autoRefreshEnabled = true;
        let refreshIntervalId = null;
        let usersCache = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
            startAutoRefresh();
        });

        function startAutoRefresh() {
            if (autoRefreshEnabled) {
                refreshIntervalId = setInterval(loadUsers, AUTO_REFRESH_INTERVAL);
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

        async function loadUsers() {
            try {
                const response = await fetch('/api/users');
                const result = await response.json();

                if (result.success) {
                    usersCache = result.data;
                    renderUsersTable(usersCache);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error loading users:', error);
                showAlert('Failed to load users', 'error');
            }
        }

        function renderUsersTable(users) {
            const tbody = document.getElementById('usersTable');

            if (users.length === 0) {
                tbody.innerHTML = '<tr class="no-data"><td colspan="6">No users found</td></tr>';
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr>
                    <td><strong>${user.firstName} ${user.lastName}</strong></td>
                    <td>${user.email}</td>
                    <td><span class="user-badge badge-${user.userType}">${user.userType}</span></td>
                    <td><span class="user-badge badge-${user.status}">${user.status}</span></td>
                    <td><small>${new Date(user.createdAt).toLocaleDateString()}</small></td>
                    <td>
                        <div class="actions">
                            <button class="action-btn edit" onclick="openEditUserModal(${user.userID})">Edit</button>
                            <button class="action-btn delete" onclick="deleteUser(${user.userID})">Delete</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function filterUsers() {
            const typeFilter = document.getElementById('typeFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            let filtered = usersCache;

            if (typeFilter) {
                filtered = filtered.filter(u => u.userType === typeFilter);
            }

            if (statusFilter) {
                filtered = filtered.filter(u => u.status === statusFilter);
            }

            renderUsersTable(filtered);
        }

        function openAddUserModal() {
            document.getElementById('userId').value = '';
            document.getElementById('modalTitle').textContent = 'Add User';
            document.getElementById('userForm').reset();
            document.getElementById('passwordField').style.display = 'block';
            document.getElementById('password').required = true;
            updateTypeFields();
            document.getElementById('userModal').classList.add('show');
        }

        async function openEditUserModal(userId) {
            try {
                const response = await fetch(`/api/users/detail?id=${userId}`);
                const result = await response.json();

                if (result.success) {
                    const user = result.data;
                    document.getElementById('userId').value = user.userID;
                    document.getElementById('firstName').value = user.firstName;
                    document.getElementById('lastName').value = user.lastName;
                    document.getElementById('email').value = user.email;
                    document.getElementById('userType').value = user.userType;
                    document.getElementById('academicYear').value = user.academicYear;
                    document.getElementById('status').value = user.status;
                    document.getElementById('modalTitle').textContent = 'Edit User';
                    document.getElementById('passwordField').style.display = 'none';
                    document.getElementById('password').required = false;
                    updateTypeFields();
                    document.getElementById('userModal').classList.add('show');
                }
            } catch (error) {
                showAlert('Failed to load user details', 'error');
            }
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.remove('show');
            document.getElementById('userForm').reset();
        }

        function updateTypeFields() {
            const userType = document.getElementById('userType').value;
            document.getElementById('programInfo').style.display = userType === 'student' ? 'block' : 'none';
            document.getElementById('departmentField').style.display = userType === 'admin' ? 'block' : 'none';
            document.getElementById('designationField').style.display = userType === 'admin' ? 'block' : 'none';
        }

        async function saveUser(event) {
            event.preventDefault();

            const userId = document.getElementById('userId').value;
            const userData = {
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                userType: document.getElementById('userType').value,
                academicYear: document.getElementById('academicYear').value,
                status: document.getElementById('status').value
            };

            if (userId) {
                userData.userID = userId;
            } else {
                userData.password = document.getElementById('password').value;
                if (userData.userType === 'student') {
                    userData.program = 'BSCS';
                } else if (userData.userType === 'admin') {
                    userData.department = document.getElementById('department').value;
                    userData.designation = document.getElementById('designation').value;
                }
            }

            try {
                const method = userId ? 'PUT' : 'POST';
                const endpoint = userId ? '/api/users' : '/api/users';

                const response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(userData)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(result.message, 'success');
                    closeUserModal();
                    loadUsers();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error saving user', 'error');
            }
        }

        async function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user?')) {
                return;
            }

            try {
                const response = await fetch('/api/users', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userID: userId })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('User deleted successfully', 'success');
                    loadUsers();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error deleting user', 'error');
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
            const modal = document.getElementById('userModal');
            if (event.target === modal) {
                closeUserModal();
            }
        };
    </script>
</body>
</html>
</head>
<body>
    <div class="page-container">
        <div class="nav-links">
            <a href="/">Home</a>
            <a href="/login">Login</a>
            <a href="/signup">Sign Up</a>
            <a href="/users" style="font-weight: bold; color: #333;">Users</a>
        </div>

        <div class="page-header">
            <h1>Users Directory</h1>
            <a href="/login" class="btn">Login</a>
        </div>

        <?php if (empty($users)): ?>
            <div class="users-table">
                <div class="no-data">
                    <p>No users found in the database.</p>
                    <p style="font-size: 12px; color: #ccc;">Run the migration and seed scripts to populate data.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($user['last_name'] ?? '-'); ?></td>
                                <td>
                                    <span class="timestamp">
                                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px; color: #999; font-size: 12px;">
            <p>Total Users: <?php echo count($users); ?></p>
        </div>
    </div>
</body>
</html>
