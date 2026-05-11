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
            <div class="page-header" style="position: sticky; top: 70px; background: var(--bg-main); z-index: 80; padding: 10px 0 20px 0; margin-bottom: 20px;">
                <h2>Enrollment Updates</h2>
                <p>Stay informed about real-time section changes and announcements.</p>
            </div>

            <div class="updates-feed-container" style="max-width: 800px;">
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
                </div>
            </div>
        </div>
    </main>
</body>
</html>
