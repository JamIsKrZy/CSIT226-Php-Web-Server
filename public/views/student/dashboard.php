<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Enrollment Dashboard - CIT University</title>
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php $currentPage = 'dashboard'; include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header">
                <h2>Pre-Enrollment Dashboard</h2>
                <p><?= htmlspecialchars($semesterName ?? '1st Semester') ?>, AY <?= htmlspecialchars($academicYear ?? 2026) ?>-<?= htmlspecialchars(($academicYear ?? 2026) + 1) ?></p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-book-open" style="color: var(--primary-maroon);"></i>
                        <span>Planned Units</span>
                    </div>
                    <div class="stat-value"><?= (int)$totalPlannedUnits ?> / 24</div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" style="width: <?= min(100, max(0, ((int)$totalPlannedUnits / 24) * 100)) ?>%;"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-fire" style="color: #e67e22;"></i>
                        <span>High Demand Sections</span>
                    </div>
                    <div class="stat-value"><?= (int)$highDemandCount ?> <span style="font-size: 0.9rem; font-weight: 500; color: var(--text-medium);">out of <?= (int)$totalPlannedCount ?> planned</span></div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-circle-check" style="color: #2ecc71;"></i>
                        <span>Enrollment Readiness</span>
                    </div>
                    <div class="stat-value"><?= (int)$readinessPercent ?>%</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium); margin-top: 4px; font-weight: 500;"><?= htmlspecialchars($readinessText ?? 'Add courses to start') ?></p>
                </div>
            </div>

            <div class="dashboard-main-grid">
                <div class="left-col">
                    <div class="card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3>Selected Sections</h3>
                            <span style="font-size: 0.8rem; color: var(--text-medium); font-weight: 600;"><?= (int)$totalPlannedCount ?> <?= $totalPlannedCount == 1 ? 'course' : 'courses' ?></span>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="scrollable-card" style="max-height: 400px;">
                                <?php if (empty($plannedSections)): ?>
                                    <div style="padding: 48px 24px; text-align: center; color: var(--text-medium);">
                                        <i class="fa-solid fa-calendar-plus" style="font-size: 3rem; color: var(--border-color); margin-bottom: 16px; display: block;"></i>
                                        <p style="margin: 0 0 16px 0; font-size: 0.95rem;">You haven't added any sections to your enrollment plan yet.</p>
                                        <a href="/enrollment-plan" class="btn" style="display: inline-block; background-color: var(--primary-maroon); color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem; transition: background-color 0.2s;">Build Enrollment Plan</a>
                                    </div>
                                <?php else: ?>
                                    <table class="data-table">
                                        <tbody>
                                            <?php foreach ($plannedSections as $sec): ?>
                                                <?php
                                                    $capacity = max((int)$sec['capacity'], 1);
                                                    $enrolled = (int)$sec['enrolledCount'];
                                                    $studentsBefore = (int)($sec['studentsBefore'] ?? 0);
                                                    $isOnWaitlist = $studentsBefore >= $capacity;
                                                    $waitlistPos = $studentsBefore - $capacity + 1;

                                                    $fillPercent = ($enrolled / $capacity) * 100;
                                                    if ($fillPercent >= 80) {
                                                        $badgeClass = 'badge-high';
                                                        $demandLabel = 'High';
                                                    } elseif ($fillPercent >= 50) {
                                                        $badgeClass = 'badge-moderate';
                                                        $demandLabel = 'Moderate';
                                                    } else {
                                                        $badgeClass = 'badge-low';
                                                        $demandLabel = 'Low';
                                                    }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="section-row-info">
                                                            <span class="section-row-title" style="font-weight: 600;"><?= htmlspecialchars($sec['courseCode'] . ' — ' . $sec['courseName']) ?></span>
                                                            <span class="section-row-subtitle" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                                <span>Section <?= htmlspecialchars($sec['sectionCode']) ?></span>
                                                                <span>•</span>
                                                                <span><?= htmlspecialchars($sec['timeslot'] ?: 'TBA') ?></span>
                                                                <span>•</span>
                                                                <span><?= htmlspecialchars($sec['room'] ?: 'TBA') ?></span>
                                                                <span>•</span>
                                                                <?php if ($isOnWaitlist): ?>
                                                                    <span style="color: #e53e3e; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                                                        <i class="fa-solid fa-clock-rotate-left"></i> Waitlist #<?= $waitlistPos ?>
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span style="color: #38a169; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                                                        <i class="fa-solid fa-circle-check"></i> Secured
                                                                    </span>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                        <span class="badge <?= $badgeClass ?>"><?= $demandLabel ?></span>
                                                    </td>
                                                    <td style="text-align: right; font-weight: 600; white-space: nowrap; vertical-align: middle; padding-right: 16px;"><?= htmlspecialchars($sec['credits']) ?> <?= $sec['credits'] == 1 ? 'unit' : 'units' ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-col">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fa-solid fa-bell" style="color: var(--primary-maroon); margin-right: 8px;"></i> Enrollment Updates</h3>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="scrollable-card" style="max-height: 400px; padding: 0 24px;">
                                <?php
                                $timeFormatter = function($dateStr) {
                                    $timestamp = strtotime($dateStr);
                                    $diff = time() - $timestamp;
                                    if ($diff < 60) {
                                        return 'Just now';
                                    } elseif ($diff < 3600) {
                                        $mins = max(1, round($diff / 60));
                                        return $mins . ($mins == 1 ? ' minute' : ' minutes') . ' ago';
                                    } elseif ($diff < 86400) {
                                        $hours = max(1, round($diff / 3600));
                                        return $hours . ($hours == 1 ? ' hour' : ' hours') . ' ago';
                                    } elseif ($diff < 172800) {
                                        return 'Yesterday';
                                    } else {
                                        return date('M j, Y', $timestamp);
                                    }
                                };
                                ?>

                                <?php if (empty($enrollmentUpdates)): ?>
                                    <div style="padding: 32px 16px; text-align: center; color: var(--text-medium); font-size: 0.9rem;">
                                        <i class="fa-solid fa-bullhorn" style="font-size: 2rem; color: var(--border-color); margin-bottom: 12px; display: block;"></i>
                                        No announcements at this time.
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($enrollmentUpdates as $upd): ?>
                                        <?php
                                            $status = $upd['status'];
                                            $iconClass = 'fa-solid fa-info-circle';
                                            $styleStr = 'background: #eef2ff; color: #4f46e5;';
                                            if ($status === 'Critical') {
                                                $iconClass = 'fa-solid fa-circle-exclamation';
                                                $styleStr = 'background: #fdf2f2; color: #d63031;';
                                            } elseif ($status === 'New') {
                                                $iconClass = 'fa-solid fa-circle-check';
                                                $styleStr = 'background: #e8f8f5; color: #16a085;';
                                            } elseif ($status === 'Advisory') {
                                                $iconClass = 'fa-solid fa-triangle-exclamation';
                                                $styleStr = 'background: #fff9e6; color: #d35400;';
                                            }
                                        ?>
                                        <div class="update-item">
                                            <div class="update-icon" style="<?= $styleStr ?>">
                                                <i class="<?= $iconClass ?>"></i>
                                            </div>
                                            <div class="update-content">
                                                <h4><?= htmlspecialchars($upd['title']) ?></h4>
                                                <p><?= htmlspecialchars($upd['description']) ?></p>
                                                <span class="update-time"><?= $timeFormatter($upd['created_at']) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <!-- Admin Management Section -->
            <div style="margin-top: 40px;">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3><i class="fa-solid fa-shield" style="color: var(--primary-maroon); margin-right: 8px;"></i> Admin Panel</h3>
                            <p style="margin: 8px 0 0 0; color: var(--text-medium); font-size: 0.85rem;">Access administrative functions and management tools</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            <a href="/admin/management" class="admin-panel-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; border: 2px solid var(--border-color); border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.2s ease; cursor: pointer;">
                                <i class="fa-solid fa-users-gear" style="font-size: 2rem; color: var(--primary-maroon); margin-bottom: 12px;"></i>
                                <h4 style="margin: 0; text-align: center;">Admin Management</h4>
                                <p style="margin: 8px 0 0 0; font-size: 0.85rem; color: var(--text-medium); text-align: center;">Manage admin accounts</p>
                            </a>
                            <a href="/admin/student-interest" class="admin-panel-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; border: 2px solid var(--border-color); border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.2s ease; cursor: pointer;">
                                <i class="fa-solid fa-chart-bar" style="font-size: 2rem; color: var(--primary-maroon); margin-bottom: 12px;"></i>
                                <h4 style="margin: 0; text-align: center;">Student Interest</h4>
                                <p style="margin: 8px 0 0 0; font-size: 0.85rem; color: var(--text-medium); text-align: center;">Monitor enrollment interest</p>
                            </a>
                            <a href="/admin/enrollment-updates" class="admin-panel-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; border: 2px solid var(--border-color); border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.2s ease; cursor: pointer;">
                                <i class="fa-solid fa-bullhorn" style="font-size: 2rem; color: var(--primary-maroon); margin-bottom: 12px;"></i>
                                <h4 style="margin: 0; text-align: center;">Enrollment Updates</h4>
                                <p style="margin: 8px 0 0 0; font-size: 0.85rem; color: var(--text-medium); text-align: center;">Manage announcements</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
