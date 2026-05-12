<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Interest Monitoring - CIT University</title>
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
    $currentPage = 'student-interest'; include __DIR__ . '/../partials/sidebar.php'; 
    ?>

    <main class="main-container">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header">
                <div>
                    <h2>Student Interest Monitoring</h2>
                    <p>Monitor real-time pre-enrollment section interest across programs.</p>
                </div>
            </div>

            <div class="filters-container card" style="margin-bottom: 24px;">
                <div class="card-body" style="display: flex; gap: 20px; align-items: flex-end;">
                    <div class="form-group" style="flex: 2; margin-bottom: 0;">
                        <label>Search Subject / Section</label>
                        <div class="search-bar" style="width: 100%; background: #f8f9fa; border: 1px solid var(--border-color);">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="e.g. CSIT122 or F1">
                        </div>
                    </div>
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Program</label>
                        <select class="btn btn-outline" style="width: 100%; text-align: left;">
                            <option value="">All Programs</option>
                            <option value="BSCS">BS Computer Science</option>
                            <option value="BSIT">BS Information Technology</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Year Level</label>
                        <select class="btn btn-outline" style="width: 100%; text-align: left;">
                            <option value="">All Years</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" style="height: 42px; padding: 0 24px;">Apply Filters</button>
                </div>
            </div>

            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <div class="scrollable-card" style="max-height: 600px;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Section</th>
                                    <th style="text-align: center;">Interested Students</th>
                                    <th>Demand Label</th>
                                </tr>
                            </thead>
                            <tbody id="interest-table-body">
                                <!-- Loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px; padding: 16px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: var(--radius-md); display: flex; gap: 12px; align-items: center;">
                <i class="fa-solid fa-circle-info" style="color: #d97706;"></i>
                <p style="font-size: 0.85rem; color: #92400e; margin: 0;">
                    <strong>Read-Only Access:</strong> Interest data is aggregated from student enrollment plans. This view is for monitoring purposes only and cannot be modified.
                </p>
            </div>
        </div>
    </main>

    <script>
        let interestData = [];

        function loadInterestData() {
            fetch('/api/student/interest-data')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        interestData = data.data || [];
                        renderInterestTable();
                    }
                })
                .catch(error => console.error('Error loading interest data:', error));
        }

        function renderInterestTable() {
            const tbody = document.getElementById('interest-table-body');
            tbody.innerHTML = '';

            if (interestData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: var(--text-medium);">No interest data available.</td></tr>';
                return;
            }

            interestData.forEach(row => {
                const demandBadgeClass = row.demand === 'High' ? 'badge-danger' : row.demand === 'Moderate' ? 'badge-warning' : 'badge-valid';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span style="font-weight: 600; color: var(--primary-maroon);">${row.code}</span></td>
                    <td>${row.name}</td>
                    <td><span style="font-weight: 500;">${row.section}</span></td>
                    <td style="text-align: center; font-weight: 600;">${row.interest}</td>
                    <td>
                        <span class="badge ${demandBadgeClass}">
                            ${row.demand}
                        </span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Load interest data on page load
        document.addEventListener('DOMContentLoaded', loadInterestData);
    </script>
</body>
</html>
