<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollment Updates - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php 
    // Ensure only admins can access this page
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: /');
        exit;
    }
    $currentPage = 'admin-updates'; include __DIR__ . '/../partials/sidebar.php'; 
    ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div style="max-width: 800px; margin: 0 auto; width: 100%;">
                
                <!-- Unified Page Header Container -->
                <div class="page-header" style="position: sticky; top: 70px; background: var(--bg-main); z-index: 80; padding: 10px 0 20px 0; margin-bottom: 16px;">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: var(--primary-maroon); margin-bottom: 4px;">Enrollment Updates</h2>
                    <p style="color: var(--text-medium); font-size: 0.95rem; margin-bottom: 0;">Manage student-facing enrollment announcements and advisories.</p>
                </div>

                <!-- Compact Action & Filter Row -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                    <form method="GET" action="" style="margin: 0;">
                        <select name="type" class="btn btn-outline" style="padding: 8px 16px; font-weight: 500; min-width: 150px; background-color: white;" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="New" <?php echo ($type === 'New') ? 'selected' : ''; ?>>General News</option>
                            <option value="Advisory" <?php echo ($type === 'Advisory') ? 'selected' : ''; ?>>Academic Advisory</option>
                            <option value="Critical" <?php echo ($type === 'Critical') ? 'selected' : ''; ?>>Critical Alert</option>
                        </select>
                    </form>

                    <button class="btn btn-primary" onclick="openModal('addUpdateModal')" style="height: 38px;">
                        <i class="fa-solid fa-plus"></i> Create Update
                    </button>
                </div>

                <!-- Feed Container -->
                <div class="updates-feed-container">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="updates-list" style="padding: 0 24px;">
                                <?php if (empty($updates)): ?>
                                    <div style="padding: 40px; text-align: center; color: var(--text-medium);">
                                        <i class="fa-solid fa-bell-slash" style="font-size: 3rem; opacity: 0.2; margin-bottom: 15px; display: block;"></i>
                                        <p>No enrollment updates at the moment.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($updates as $update): ?>
                                    <div class="update-item" style="padding: 24px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start;">
                                        <div style="display: flex; gap: 20px; flex: 1;">
                                            <div class="update-icon" style="background: <?php 
                                                echo match($update['status']) {
                                                    'Critical' => '#fdf2f2',
                                                    'New' => '#e8f8f5',
                                                    'Advisory' => '#fff9e6',
                                                    default => '#eef2ff'
                                                }; ?>; color: <?php 
                                                echo match($update['status']) {
                                                    'Critical' => '#d63031',
                                                    'New' => '#16a085',
                                                    'Advisory' => '#d35400',
                                                    default => '#4f46e5'
                                                }; ?>; width: 44px; height: 44px; font-size: 1.3rem; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa-solid <?php 
                                                    echo match($update['status']) {
                                                        'Critical' => 'fa-circle-exclamation',
                                                        'New' => 'fa-circle-check',
                                                        'Advisory' => 'fa-triangle-exclamation',
                                                        default => 'fa-info-circle'
                                                    }; ?>"></i>
                                            </div>
                                            <div class="update-content">
                                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                                    <h4 style="font-size: 1.05rem; margin-bottom: 0; font-weight: 700;"><?php echo $update['title']; ?></h4>
                                                    <span class="badge <?php 
                                                        echo match($update['status']) {
                                                            'Critical' => 'badge-danger',
                                                            'New' => 'badge-success',
                                                            'Advisory' => 'badge-warning',
                                                            default => ''
                                                        }; ?>" style="font-size: 0.65rem;"><?php echo strtoupper($update['status']); ?></span>
                                                </div>
                                                <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-medium); margin-bottom: 12px;"><?php echo $update['description']; ?></p>
                                                <span class="update-time" style="font-size: 0.8rem; color: #94a3b8;"><i class="fa-regular fa-clock"></i> <?php echo date('M d, Y h:i A', strtotime($update['created_at'])); ?></span>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 8px; margin-left: 20px;">
                                            <button class="btn btn-outline" style="padding: 8px; aspect-ratio: 1;" onclick='openEditModal(<?php echo json_encode($update); ?>)' title="Edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: var(--primary-maroon);"></i>
                                            </button>
                                            <button class="btn btn-outline" style="padding: 8px; aspect-ratio: 1;" onclick="openDeleteModal(<?php echo $update['id']; ?>)" title="Delete">
                                                <i class="fa-solid fa-trash-can" style="color: var(--status-danger);"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($totalPages > 1): ?>
                            <div class="card-footer" style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                                <span style="font-size: 0.85rem; color: var(--text-medium);">
                                    Showing <?php echo count($updates) > 0 ? ($offset + 1) : 0; ?> to <?php echo min($offset + $limit, $totalUpdates); ?> of <?php echo $totalUpdates; ?> entries
                                </span>
                                <div style="display: flex; gap: 8px;">
                                    <!-- Previous Button -->
                                    <a href="?type=<?php echo urlencode($type); ?>&page=<?php echo max(1, $page - 1); ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none; <?php echo ($page <= 1) ? 'pointer-events: none; opacity: 0.5;' : ''; ?>">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <?php if ($totalPages > 5 && abs($page - $i) > 2 && $i !== 1 && $i !== $totalPages): ?>
                                            <?php if ($i === 2 || $i === $totalPages - 1): ?>
                                                <span style="align-self: center; color: var(--text-medium);">...</span>
                                            <?php endif; ?>
                                            <?php continue; ?>
                                        <?php endif; ?>
                                        <a href="?type=<?php echo urlencode($type); ?>&page=<?php echo $i; ?>" class="btn <?php echo ($i === $page) ? 'btn-primary' : 'btn-outline'; ?>" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none;">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <!-- Next Button -->
                                    <a href="?type=<?php echo urlencode($type); ?>&page=<?php echo min($totalPages, $page + 1); ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none; <?php echo ($page >= $totalPages) ? 'pointer-events: none; opacity: 0.5;' : ''; ?>">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals (Add, Edit, Delete) -->
    <div id="addUpdateModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Create Enrollment Update</h3>
                <i class="fa-solid fa-xmark close" onclick="closeModal('addUpdateModal')"></i>
            </div>
            <form action="/admin/enrollment-updates/add" method="POST" class="modal-body">
                <div class="form-group">
                    <label>Update Title</label>
                    <input type="text" name="title" placeholder="e.g. CSIT122 F1 - Section Full" required>
                </div>
                <div class="form-group">
                    <label>Announcement Type</label>
                    <select name="status" required>
                        <option value="New">General News</option>
                        <option value="Advisory">Academic Advisory</option>
                        <option value="Critical">Critical Alert</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="Enter detailed announcement content..." required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-family: inherit; resize: vertical; outline: none;"></textarea>
                </div>
                <div class="modal-footer" style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addUpdateModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editUpdateModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Edit Update</h3>
                <i class="fa-solid fa-xmark close" onclick="closeModal('editUpdateModal')"></i>
            </div>
            <form action="/admin/enrollment-updates/edit" method="POST" class="modal-body">
                <input type="hidden" name="id" id="edit_update_id">
                <div class="form-group">
                    <label>Update Title</label>
                    <input type="text" name="title" id="edit_title" required>
                </div>
                <div class="form-group">
                    <label>Announcement Type</label>
                    <select name="status" id="edit_status" required>
                        <option value="Critical">Critical Alert</option>
                        <option value="New">General News</option>
                        <option value="Advisory">Academic Advisory</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="edit_description" rows="4" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-family: inherit; resize: vertical; outline: none;"></textarea>
                </div>
                <div class="modal-footer" style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editUpdateModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <form action="/admin/enrollment-updates/delete" method="POST" class="modal-body" style="padding: 40px 30px;">
                <input type="hidden" name="id" id="delete_update_id">
                <i class="fa-solid fa-trash-can" style="font-size: 3rem; color: var(--status-danger); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">Remove Update?</h3>
                <p style="color: var(--text-medium); font-size: 0.95rem; line-height: 1.5;">Are you sure you want to delete this announcement? This will remove it from all student dashboards.</p>
                
                <div style="margin-top: 32px; display: flex; gap: 12px;">
                    <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1; background: var(--status-danger);">Delete Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).style.display = 'flex'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }
        
        function openEditModal(update) {
            document.getElementById('edit_update_id').value = update.id;
            document.getElementById('edit_title').value = update.title;
            document.getElementById('edit_status').value = update.status;
            document.getElementById('edit_description').value = update.description;
            openModal('editUpdateModal');
        }

        function openDeleteModal(id) {
            document.getElementById('delete_update_id').value = id;
            openModal('deleteConfirmModal');
        }

        window.onclick = function(event) { if (event.target.classList.contains('modal')) event.target.style.display = 'none'; }
    </script>

    <style>
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-content { background: white; width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; animation: modalSlide 0.3s ease-out; }
        @keyframes modalSlide { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-header { padding: 20px 30px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #fafafa; }
        .modal-header h3 { font-size: 1.15rem; color: var(--primary-maroon); margin: 0; }
        .modal-header .close { cursor: pointer; color: var(--text-light); font-size: 1.2rem; }
        .modal-body { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-size: 0.9rem; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: var(--primary-maroon); }
    </style>
</body>
</html>
