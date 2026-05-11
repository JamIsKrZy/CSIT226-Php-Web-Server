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
                    <div class="stat-value">9</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">Selected in your plan</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-shuffle"></i>
                        <span>Backup Readiness</span>
                    </div>
                    <div class="stat-value" style="color: var(--status-warning);">4 / 9</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">Subjects with backup plans</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>High Interest Alert</span>
                    </div>
                    <div class="stat-value" style="color: var(--status-danger);">3</div>
                    <p style="font-size: 0.8rem; color: var(--text-medium);">Preferred sections nearing capacity</p>
                </div>
            </div>

            <div class="alternative-planning-list">
                <?php 
                $plannedSubjects = [
                    [
                        'code' => 'CSIT122',
                        'title' => 'Intermediate Programming',
                        'preferred' => ['section' => 'F1', 'schedule' => 'MWF 8:00-10:00 AM', 'room' => 'Room 301', 'interest' => 38, 'capacity' => 40, 'label' => 'HIGH'],
                        'alternatives' => [
                            ['section' => 'F2', 'schedule' => 'TTh 1:00-2:30 PM', 'room' => 'Room 305', 'interest' => 22, 'capacity' => 40, 'label' => 'MODERATE'],
                            ['section' => 'F3', 'schedule' => 'Sat 8:00-11:00 AM', 'room' => 'Room 301', 'interest' => 12, 'capacity' => 35, 'label' => 'LOW']
                        ]
                    ],
                    [
                        'code' => 'CSIT228',
                        'title' => 'Database Management Systems',
                        'preferred' => ['section' => 'F1', 'schedule' => 'TTh 1:00-2:30 PM', 'room' => 'Room 205', 'interest' => 35, 'capacity' => 40, 'label' => 'HIGH'],
                        'alternatives' => [
                            ['section' => 'F2', 'schedule' => 'MWF 1:00-2:30 PM', 'room' => 'Room 205', 'interest' => 18, 'capacity' => 40, 'label' => 'LOW'],
                            ['section' => 'F3', 'schedule' => 'TTh 3:00-4:30 PM', 'room' => 'Room 205', 'interest' => 5, 'capacity' => 40, 'label' => 'LOW']
                        ]
                    ],
                    [
                        'code' => 'MATH136',
                        'title' => 'Differential and Integral Calculus',
                        'preferred' => ['section' => 'F1', 'schedule' => 'MWF 10:00-11:30 AM', 'room' => 'Room 108', 'interest' => 20, 'capacity' => 40, 'label' => 'MODERATE'],
                        'alternatives' => [
                            ['section' => 'F2', 'schedule' => 'TTh 9:00-10:30 AM', 'room' => 'Room 108', 'interest' => 15, 'capacity' => 40, 'label' => 'LOW']
                        ]
                    ]
                ];

                foreach ($plannedSubjects as $subject): ?>
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-header" style="background: #fafafa;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="background: var(--primary-maroon); color: white; padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 0.9rem;">
                                <?php echo $subject['code']; ?>
                            </div>
                            <h3 style="margin-bottom: 0;"><?php echo $subject['title']; ?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                            <!-- Current Preferred Section -->
                            <div style="border-right: 1px solid var(--border-color); padding-right: 32px;">
                                <h4 style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Current Preferred Section</h4>
                                <div class="section-demand-card" style="border-color: var(--primary-maroon); background: #fffdfd;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                        <div>
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                                <span style="font-weight: 700; font-size: 1.1rem; color: var(--primary-maroon);"><?php echo $subject['preferred']['section']; ?></span>
                                                <span class="badge <?php echo 'badge-' . strtolower($subject['preferred']['label']); ?>"><?php echo $subject['preferred']['label']; ?></span>
                                            </div>
                                            <div style="font-size: 0.9rem; color: var(--text-medium);">
                                                <?php echo $subject['preferred']['schedule']; ?><br>
                                                <?php echo $subject['preferred']['room']; ?>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-dark);"><?php echo $subject['preferred']['interest']; ?> Students</div>
                                            <div style="font-size: 0.75rem; color: var(--text-medium);">Interested</div>
                                        </div>
                                    </div>
                                    <div class="progress-container" style="margin-bottom: 0;">
                                        <div class="progress-label">
                                            <span>Interest Level</span>
                                            <span><?php echo round(($subject['preferred']['interest'] / $subject['preferred']['capacity']) * 100); ?>%</span>
                                        </div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar <?php echo $subject['preferred']['label'] == 'HIGH' ? 'red' : 'maroon'; ?>" style="width: <?php echo ($subject['preferred']['interest'] / $subject['preferred']['capacity']) * 100; ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alternative Options -->
                            <div>
                                <h4 style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Available Alternative Sections</h4>
                                <div class="alternatives-container" style="display: flex; flex-direction: column; gap: 12px;">
                                    <?php foreach ($subject['alternatives'] as $alt): ?>
                                    <div class="section-demand-card" style="padding: 12px 16px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <span style="font-weight: 700; font-size: 1rem;"><?php echo $alt['section']; ?></span>
                                                    <span class="badge <?php echo 'badge-' . strtolower($alt['label']); ?>" style="font-size: 0.65rem; padding: 2px 6px;"><?php echo $alt['label']; ?></span>
                                                </div>
                                                <div style="font-size: 0.8rem; color: var(--text-medium);">
                                                    <?php echo $alt['schedule']; ?> | <?php echo $alt['interest']; ?> interested
                                                </div>
                                            </div>
                                            <div style="display: flex; gap: 8px;">
                                                <button class="btn btn-outline" style="padding: 6px 12px; font-size: 0.75rem;"><i class="fa-solid fa-bookmark"></i> Mark as Backup</button>
                                                <button class="btn btn-primary" style="padding: 6px 12px; font-size: 0.75rem;"><i class="fa-solid fa-shuffle"></i> Switch</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Notice for Pre-Enrollment -->
            <div style="background: #eef2ff; border: 1px solid #c7d2fe; border-radius: var(--radius-md); padding: 24px; display: flex; gap: 20px; align-items: flex-start; margin-top: 32px;">
                <i class="fa-solid fa-info-circle" style="color: #4f46e5; font-size: 1.5rem; margin-top: 4px;"></i>
                <div>
                    <h4 style="color: #3730a3; margin-bottom: 8px;">Pre-Enrollment Planning Notice</h4>
                    <p style="color: #4338ca; font-size: 0.9rem; line-height: 1.5; margin-bottom: 0;">
                        This platform is for pre-enrollment planning only. Saving backup sections does not guarantee a seat. 
                        Interest counts are estimates based on student preferences and may change as official enrollment approaches. 
                        Please ensure your final plan remains within the 24-unit limit and satisfies all prerequisites.
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
