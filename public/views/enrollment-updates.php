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
    <?php $currentPage = 'enrollment-updates'; include 'partials/sidebar.php'; ?>

    <main class="main-container">
        <?php include 'partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header" style="position: sticky; top: 70px; background: var(--bg-main); z-index: 80; padding: 10px 0 20px 0; margin-bottom: 20px;">
                <h2>Enrollment Updates</h2>
                <p>Stay informed about real-time section changes and announcements.</p>
            </div>

            <div class="updates-feed-container" style="max-width: 800px;">
                <div class="card">
                    <div class="card-body" style="padding: 0;">
                        <div class="updates-list" style="padding: 0 24px;">
                            <!-- Update 1 -->
                            <div class="update-item" style="padding: 24px 0;">
                                <div class="update-icon" style="background: #fdf2f2; color: #d63031; width: 40px; height: 40px; font-size: 1.2rem;">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                </div>
                                <div class="update-content">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0;">CSIT122 F1 - Section Full</h4>
                                        <span class="badge badge-danger" style="font-size: 0.7rem;">CRITICAL</span>
                                    </div>
                                    <p style="font-size: 0.9rem; margin-bottom: 8px;">Section F1 (Intermediate Programming II) is now at full capacity (40/40 students). Students are advised to consider Section F2 or F3.</p>
                                    <span class="update-time"><i class="fa-regular fa-clock"></i> 2 hours ago  •  May 11, 2026</span>
                                </div>
                            </div>

                            <!-- Update 2 -->
                            <div class="update-item" style="padding: 24px 0;">
                                <div class="update-icon" style="background: #e8f8f5; color: #16a085; width: 40px; height: 40px; font-size: 1.2rem;">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="update-content">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0;">CSIT228 F3 - New Section Opened</h4>
                                        <span class="badge badge-success" style="background: #e8f8f5; color: #16a085; font-size: 0.7rem;">NEW</span>
                                    </div>
                                    <p style="font-size: 0.9rem; margin-bottom: 8px;">A new block (Section F3) for Database Management Systems has been opened to accommodate high demand. Schedule: TTH 3:00-4:30 PM.</p>
                                    <span class="update-time"><i class="fa-regular fa-clock"></i> 5 hours ago  •  May 11, 2026</span>
                                </div>
                            </div>

                            <!-- Update 3 -->
                            <div class="update-item" style="padding: 24px 0;">
                                <div class="update-icon" style="background: #fff9e6; color: #d35400; width: 40px; height: 40px; font-size: 1.2rem;">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div class="update-content">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0;">MATH215 F2 - Room Change</h4>
                                        <span class="badge badge-warning" style="font-size: 0.7rem;">ADVISORY</span>
                                    </div>
                                    <p style="font-size: 0.9rem; margin-bottom: 8px;">The room for Discrete Mathematics Section F2 has been changed from Room 108 to Room 110. Please update your plotted schedule.</p>
                                    <span class="update-time"><i class="fa-regular fa-clock"></i> 1 day ago  •  May 10, 2026</span>
                                </div>
                            </div>

                            <!-- Update 4 -->
                            <div class="update-item" style="padding: 24px 0;">
                                <div class="update-icon" style="background: #eef2ff; color: #4f46e5; width: 40px; height: 40px; font-size: 1.2rem;">
                                    <i class="fa-solid fa-info-circle"></i>
                                </div>
                                <div class="update-content">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0;">Department Notice: Pre-enrollment Extension</h4>
                                        <span class="badge" style="background: #eef2ff; color: #4f46e5; font-size: 0.7rem;">GENERAL</span>
                                    </div>
                                    <p style="font-size: 0.9rem; margin-bottom: 8px;">The Computer Science Department has extended the pre-enrollment planning period until May 15, 2026. Make sure to finalize your intended sections.</p>
                                    <span class="update-time"><i class="fa-regular fa-clock"></i> 2 days ago  •  May 9, 2026</span>
                                </div>
                            </div>

                            <!-- Update 5 -->
                            <div class="update-item" style="padding: 24px 0;">
                                <div class="update-icon" style="background: #fff5f5; color: #e53e3e; width: 40px; height: 40px; font-size: 1.2rem;">
                                    <i class="fa-solid fa-calendar-xmark"></i>
                                </div>
                                <div class="update-content">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0;">ENGL201 F3 - Schedule Adjustment</h4>
                                        <span class="badge badge-danger" style="font-size: 0.7rem;">ADVISORY</span>
                                    </div>
                                    <p style="font-size: 0.9rem; margin-bottom: 8px;">Technical Writing Section F3 has been adjusted from 2:00-3:30 PM to 2:30-4:00 PM on Mondays and Wednesdays.</p>
                                    <span class="update-time"><i class="fa-regular fa-clock"></i> 3 days ago  •  May 8, 2026</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
