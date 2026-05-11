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
                                <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td><span style="font-weight: 600; color: var(--primary-maroon);"><?php echo $admin['admin_id']; ?></span></td>
                                    <td style="font-weight: 500;"><?php echo $admin['name']; ?></td>
                                    <td><?php echo $admin['email']; ?></td>
                                    <td><?php echo $admin['role']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $admin['status'] == 'Active' ? 'badge-valid' : 'badge-danger'; ?>">
                                            <?php echo $admin['status']; ?>
                                        </span>
                                    </td>
                                    <td style="color: var(--text-medium); font-size: 0.85rem;"><?php echo date('M d, Y', strtotime($admin['created_at'])); ?></td>
                                    <td style="text-align: right;">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <button class="btn btn-outline" style="padding: 6px; aspect-ratio: 1; border-color: #eee;" title="Edit" 
                                                onclick='openEditModal(<?php echo json_encode($admin); ?>)'>
                                                <i class="fa-solid fa-pen-to-square" style="color: var(--primary-maroon);"></i>
                                            </button>
                                            <button class="btn btn-outline" style="padding: 6px; aspect-ratio: 1; border-color: #eee;" title="Delete" 
                                                onclick="openDeleteModal(<?php echo $admin['id']; ?>)">
                                                <i class="fa-solid fa-trash-can" style="color: var(--status-danger);"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
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
            <form action="/admin/management/add" method="POST" class="modal-body">
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="e.g. Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="e.g. Dela Cruz" required>
                    </div>
                    <div class="form-group">
                        <label>CIT Email</label>
                        <input type="email" name="email" placeholder="name@cit.edu" required>
                    </div>
                    <div class="form-group">
                        <label>Admin ID</label>
                        <input type="text" name="admin_id" placeholder="ADM-###" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addAdminModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Admin</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editAdminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Administrator Details</h3>
                <i class="fa-solid fa-xmark close" onclick="closeModal('editAdminModal')"></i>
            </div>
            <form action="/admin/management/edit" method="POST" class="modal-body">
                <input type="hidden" name="id" id="edit_admin_id_internal">
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" id="edit_first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" id="edit_last_name" required>
                    </div>
                    <div class="form-group">
                        <label>CIT Email</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>
                    <div class="form-group">
                        <label>Admin ID</label>
                        <input type="text" name="admin_id" id="edit_admin_id" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editAdminModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <form action="/admin/management/delete" method="POST" class="modal-body" style="padding: 40px 30px;">
                <input type="hidden" name="id" id="delete_admin_id">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 3rem; color: var(--status-danger); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">Confirm Deletion</h3>
                <p style="color: var(--text-medium); font-size: 0.95rem; line-height: 1.5;">Are you sure you want to remove this admin account? This action cannot be undone.</p>
                
                <div style="margin-top: 32px; display: flex; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1; background: var(--status-danger);">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function openEditModal(admin) {
            document.getElementById('edit_admin_id_internal').value = admin.id;
            // Split name if needed, but since we are fetching separately now...
            // Wait, in my controller I used CONCAT. I should probably fetch separate fields.
            // Let's fix the controller query first or handle it here.
            
            // I'll update the controller query to include first_name and last_name separately.
            document.getElementById('edit_first_name').value = admin.first_name || '';
            document.getElementById('edit_last_name').value = admin.last_name || '';
            document.getElementById('edit_email').value = admin.email;
            document.getElementById('edit_admin_id').value = admin.admin_id;
            document.getElementById('edit_status').value = admin.status;
            openModal('editAdminModal');
        }

        function openDeleteModal(id) {
            document.getElementById('delete_admin_id').value = id;
            openModal('deleteConfirmModal');
        }

        // Close on click outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
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
