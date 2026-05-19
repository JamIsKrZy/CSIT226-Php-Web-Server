<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Updates - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php $currentPage = 'enrollment-updates'; include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div style="max-width: 800px; margin: 0 auto; width: 100%;">
                
                <!-- Unified Page Header Container -->
                <div class="page-header" style="position: sticky; top: 70px; background: var(--bg-main); z-index: 80; padding: 10px 0 20px 0; margin-bottom: 16px;">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: var(--primary-maroon); margin-bottom: 4px;">Enrollment Updates</h2>
                    <p style="color: var(--text-medium); font-size: 0.95rem; margin-bottom: 0;">Stay informed about real-time section changes and announcements.</p>
                </div>

                <!-- Compact Action & Filter Row -->
                <div style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                    <form method="GET" action="" style="margin: 0; width: 100%;">
                        <select name="type" class="btn btn-outline" style="padding: 8px 16px; font-weight: 500; min-width: 150px; background-color: white;" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="New" <?php echo ($type === 'New') ? 'selected' : ''; ?>>General News</option>
                            <option value="Advisory" <?php echo ($type === 'Advisory') ? 'selected' : ''; ?>>Academic Advisory</option>
                            <option value="Critical" <?php echo ($type === 'Critical') ? 'selected' : ''; ?>>Critical Alert</option>
                        </select>
                    </form>
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
                                    <div class="update-item" style="padding: 24px 0; border-bottom: 1px solid #eee; display: flex; gap: 20px;">
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
                                            }; ?>; width: 40px; height: 40px; font-size: 1.2rem; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid <?php 
                                                echo match($update['status']) {
                                                    'Critical' => 'fa-circle-exclamation',
                                                    'New' => 'fa-circle-check',
                                                    'Advisory' => 'fa-triangle-exclamation',
                                                    default => 'fa-info-circle'
                                                }; ?>"></i>
                                        </div>
                                        <div class="update-content" style="flex: 1;">
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                                <h4 style="font-size: 1rem; margin-bottom: 0; font-weight: 700;"><?php echo $update['title']; ?></h4>
                                                <span class="badge <?php 
                                                    echo match($update['status']) {
                                                        'Critical' => 'badge-danger',
                                                        'New' => 'badge-success',
                                                        'Advisory' => 'badge-warning',
                                                        default => ''
                                                    }; ?>" style="font-size: 0.7rem;"><?php echo strtoupper($update['status']); ?></span>
                                            </div>
                                            <p style="font-size: 0.9rem; margin-bottom: 8px; color: var(--text-medium); line-height: 1.5;"><?php echo $update['description']; ?></p>
                                            <span class="update-time" style="font-size: 0.8rem; color: #94a3b8;"><i class="fa-regular fa-clock"></i> <?php echo date('M d, Y h:i A', strtotime($update['created_at'])); ?></span>
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
        </div>
    </main>
</body>
</html>
