<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php $currentPage = 'admin-management'; include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h2>Admin Management</h2>
                    <p>Manage administrative user accounts and access control.</p>
                </div>
                <button class="btn btn-primary" onclick="openModal('addAdminModal')">
                    <i class="fa-solid fa-user-plus"></i> Add Admin
                </button>
            </div>

            <div class="card" style="margin-top: 24px;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="search-bar" style="width: 350px; background: #f8f9fa; border: 1px solid var(--border-color);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search by name, email, or admin ID...">
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <select class="btn btn-outline" style="padding: 8px 16px; font-weight: 500;">
                            <option value="">All Roles</option>
                            <option value="Registrar">Registrar Admin</option>
                            <option value="Department">Department Admin</option>
                            <option value="Chair">Program Chair</option>
                        </select>
                    </div>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="scrollable-card" style="max-height: 600px;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Admin ID</th>
                                    <th>Full Name</th>
                                    <th>CIT Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Administrator</h3>
                <i class="fa-solid fa-xmark close" onclick="closeModal('addAdminModal')"></i>
            </div>
            <div class="modal-body">
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" id="add_first_name" placeholder="e.g. Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" id="add_last_name" placeholder="e.g. Dela Cruz" required>
                    </div>
                    <div class="form-group">
                        <label>CIT Email</label>
                        <input type="email" id="add_email" placeholder="name@cit.edu" required>
                    </div>
                    <div class="form-group">
                        <label>Admin Code</label>
                        <input type="text" id="add_adminCode" placeholder="ADM-###" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select id="add_role">
                            <option value="">Select Role</option>
                            <option value="Registrar">Registrar</option>
                            <option value="Department">Department</option>
                            <option value="Chair">Program Chair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="add_password" required>
                    </div>
                    <div class="form-group" style="grid-column: 1/-1;">
                        <label>Department</label>
                        <input type="text" id="add_department" placeholder="e.g. Enrollment Services">
                    </div>
                    <div class="form-group" style="grid-column: 1/-1;">
                        <label>Designation</label>
                        <input type="text" id="add_designation" placeholder="e.g. Director">
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addAdminModal')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitAddAdmin()">Create Admin</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editAdminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Administrator Details</h3>
                <i class="fa-solid fa-xmark close" onclick="closeModal('editAdminModal')"></i>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_admin_id_internal">
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" id="edit_first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" id="edit_last_name" required>
                    </div>
                    <div class="form-group">
                        <label>CIT Email</label>
                        <input type="email" id="edit_email" required>
                    </div>
                    <div class="form-group">
                        <label>Admin Code</label>
                        <input type="text" id="edit_adminCode" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select id="edit_role">
                            <option value="">Select Role</option>
                            <option value="Registrar">Registrar</option>
                            <option value="Department">Department</option>
                            <option value="Chair">Program Chair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="edit_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1/-1;">
                        <label>Department</label>
                        <input type="text" id="edit_department">
                    </div>
                    <div class="form-group" style="grid-column: 1/-1;">
                        <label>Designation</label>
                        <input type="text" id="edit_designation">
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editAdminModal')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditAdmin()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <div class="modal-body" style="padding: 40px 30px;">
                <input type="hidden" id="delete_admin_id">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 3rem; color: var(--status-danger); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">Confirm Deletion</h3>
                <p style="color: var(--text-medium); font-size: 0.95rem; line-height: 1.5;">Are you sure you want to remove this admin account? This action cannot be undone.</p>
                
                <div style="margin-top: 32px; display: flex; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                    <button type="button" class="btn btn-primary" style="flex: 1; background: var(--status-danger);" onclick="submitDeleteAdmin()">Confirm Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let adminList = [];

        // Load admin list on page load
        function loadAdmins() {
            fetch('/api/admin/list')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        adminList = data.data;
                        renderAdminTable();
                    }
                })
                .catch(error => console.error('Error loading admins:', error));
        }

        function renderAdminTable() {
            const tbody = document.querySelector('.data-table tbody');
            tbody.innerHTML = '';
            
            adminList.forEach(admin => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><span style="font-weight: 600; color: var(--primary-maroon);">${admin.admin_id}</span></td>
                    <td style="font-weight: 500;">${admin.name}</td>
                    <td>${admin.email}</td>
                    <td>${admin.role || '-'}</td>
                    <td>
                        <span class="badge ${admin.status == 'active' ? 'badge-valid' : 'badge-danger'}">
                            ${admin.status.charAt(0).toUpperCase() + admin.status.slice(1)}
                        </span>
                    </td>
                    <td style="color: var(--text-medium); font-size: 0.85rem;">${new Date(admin.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <button class="btn btn-outline" style="padding: 6px; aspect-ratio: 1; border-color: #eee;" title="Edit" 
                                onclick="fetchAndOpenEditModal(${admin.id})">
                                <i class="fa-solid fa-pen-to-square" style="color: var(--primary-maroon);"></i>
                            </button>
                            <button class="btn btn-outline" style="padding: 6px; aspect-ratio: 1; border-color: #eee;" title="Delete" 
                                onclick="openDeleteModal(${admin.id})">
                                <i class="fa-solid fa-trash-can" style="color: var(--status-danger);"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function fetchAndOpenEditModal(adminID) {
            fetch(`/api/admin/detail?id=${adminID}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const admin = data.data;
                        document.getElementById('edit_admin_id_internal').value = admin.id;
                        document.getElementById('edit_first_name').value = admin.firstName;
                        document.getElementById('edit_last_name').value = admin.lastName;
                        document.getElementById('edit_email').value = admin.email;
                        document.getElementById('edit_adminCode').value = admin.adminCode;
                        document.getElementById('edit_role').value = admin.role || '';
                        document.getElementById('edit_status').value = admin.status;
                        document.getElementById('edit_department').value = admin.department || '';
                        document.getElementById('edit_designation').value = admin.designation || '';
                        openModal('editAdminModal');
                    }
                })
                .catch(error => console.error('Error fetching admin:', error));
        }

        function submitAddAdmin() {
            const data = {
                firstName: document.getElementById('add_first_name').value,
                lastName: document.getElementById('add_last_name').value,
                email: document.getElementById('add_email').value,
                adminCode: document.getElementById('add_adminCode').value,
                role: document.getElementById('add_role').value,
                password: document.getElementById('add_password').value,
                department: document.getElementById('add_department').value,
                designation: document.getElementById('add_designation').value
            };

            if (!data.firstName || !data.lastName || !data.email || !data.adminCode || !data.password) {
                alert('Please fill in all required fields');
                return;
            }

            fetch('/api/admin/create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Admin created successfully');
                        closeModal('addAdminModal');
                        loadAdmins();
                        // Clear form
                        document.getElementById('add_first_name').value = '';
                        document.getElementById('add_last_name').value = '';
                        document.getElementById('add_email').value = '';
                        document.getElementById('add_adminCode').value = '';
                        document.getElementById('add_password').value = '';
                        document.getElementById('add_department').value = '';
                        document.getElementById('add_designation').value = '';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error creating admin: ' + error);
                    console.error(error);
                });
        }

        function submitEditAdmin() {
            const data = {
                adminID: document.getElementById('edit_admin_id_internal').value,
                firstName: document.getElementById('edit_first_name').value,
                lastName: document.getElementById('edit_last_name').value,
                email: document.getElementById('edit_email').value,
                adminCode: document.getElementById('edit_adminCode').value,
                role: document.getElementById('edit_role').value,
                status: document.getElementById('edit_status').value,
                department: document.getElementById('edit_department').value,
                designation: document.getElementById('edit_designation').value
            };

            if (!data.firstName || !data.lastName || !data.email) {
                alert('Please fill in all required fields');
                return;
            }

            fetch('/api/admin/update', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Admin updated successfully');
                        closeModal('editAdminModal');
                        loadAdmins();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error updating admin: ' + error);
                    console.error(error);
                });
        }

        function openDeleteModal(id) {
            document.getElementById('delete_admin_id').value = id;
            openModal('deleteConfirmModal');
        }

        function submitDeleteAdmin() {
            const adminID = document.getElementById('delete_admin_id').value;
            
            fetch('/api/admin/delete', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ adminID: adminID })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Admin deleted successfully');
                        closeModal('deleteConfirmModal');
                        loadAdmins();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error deleting admin: ' + error);
                    console.error(error);
                });
        }

        // Close on click outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Load admins on page load
        document.addEventListener('DOMContentLoaded', loadAdmins);
    </script>

    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: white;
            width: 100%;
            max-width: 700px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: modalSlide 0.3s ease-out;
        }

        @keyframes modalSlide {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafafa;
        }

        .modal-header h3 {
            font-size: 1.15rem;
            color: var(--primary-maroon);
        }

        .modal-header .close {
            cursor: pointer;
            color: var(--text-light);
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        .modal-header .close:hover {
            color: var(--status-danger);
        }

        .modal-body {
            padding: 30px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: var(--primary-maroon);
        }
    </style>
</body>
</html>
