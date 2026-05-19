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
                            <input type="text" id="search-input" placeholder="e.g. CSIT122 or F1">
                        </div>
                    </div>
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Section</label>
                        <select id="filter-section" class="btn btn-outline" style="width: 100%; text-align: left;">
                            <option value="">All Sections</option>
                            <option value="F1">F1</option>
                            <option value="F2">F2</option>
                            <option value="F3">F3</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Demand Label</label>
                        <select id="filter-demand" class="btn btn-outline" style="width: 100%; text-align: left;">
                            <option value="">All Demands</option>
                            <option value="High">High</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" id="apply-filters-btn" style="height: 42px; padding: 0 24px;">Apply Filters</button>
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
                <div class="card-footer" style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                    <span id="pagination-info" style="font-size: 0.85rem; color: var(--text-medium);">Showing 0 to 0 of 0 entries</span>
                    <div id="pagination-controls" style="display: flex; gap: 8px;">
                        <!-- Rendered by JavaScript -->
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
        let currentPage = 1;
        let limit = 10;
        let totalPages = 1;
        let totalRecords = 0;
        let sectionsPopulated = false;

        function loadInterestData() {
            const search = document.getElementById('search-input').value;
            const section = document.getElementById('filter-section').value;
            const demand = document.getElementById('filter-demand').value;

            const url = `/api/student/interest-data?search=${encodeURIComponent(search)}&section=${encodeURIComponent(section)}&demand=${encodeURIComponent(demand)}&page=${currentPage}&limit=${limit}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        interestData = data.data.list || [];
                        currentPage = parseInt(data.data.page) || 1;
                        totalPages = parseInt(data.data.totalPages) || 1;
                        totalRecords = parseInt(data.data.total) || 0;

                        if (!sectionsPopulated && data.data.sectionsList) {
                            populateSectionsDropdown(data.data.sectionsList);
                            sectionsPopulated = true;
                        }

                        renderInterestTable();
                        renderPagination();
                    }
                })
                .catch(error => console.error('Error loading interest data:', error));
        }

        function populateSectionsDropdown(sections) {
            const select = document.getElementById('filter-section');
            select.innerHTML = '<option value="">All Sections</option>';
            sections.forEach(sec => {
                const opt = document.createElement('option');
                opt.value = sec;
                opt.textContent = sec;
                select.appendChild(opt);
            });
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

        function renderPagination() {
            const info = document.getElementById('pagination-info');
            const controls = document.getElementById('pagination-controls');

            const start = totalRecords === 0 ? 0 : (currentPage - 1) * limit + 1;
            const end = Math.min(currentPage * limit, totalRecords);
            info.textContent = `Showing ${start} to ${end} of ${totalRecords} entries`;

            controls.innerHTML = '';

            // Previous Button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'btn btn-outline';
            prevBtn.style.padding = '6px 12px';
            prevBtn.style.fontSize = '0.8rem';
            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    loadInterestData();
                }
            };
            controls.appendChild(prevBtn);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (totalPages > 5 && Math.abs(currentPage - i) > 2 && i !== 1 && i !== totalPages) {
                    if (i === 2 || i === totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.textContent = '...';
                        dots.style.alignSelf = 'center';
                        dots.style.color = 'var(--text-medium)';
                        controls.appendChild(dots);
                    }
                    continue;
                }

                const pageBtn = document.createElement('button');
                pageBtn.className = i === currentPage ? 'btn btn-primary' : 'btn btn-outline';
                pageBtn.style.padding = '6px 12px';
                pageBtn.style.fontSize = '0.8rem';
                pageBtn.textContent = i;
                pageBtn.onclick = () => {
                    currentPage = i;
                    loadInterestData();
                };
                controls.appendChild(pageBtn);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'btn btn-outline';
            nextBtn.style.padding = '6px 12px';
            nextBtn.style.fontSize = '0.8rem';
            nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadInterestData();
                }
            };
            controls.appendChild(nextBtn);
        }

        document.getElementById('apply-filters-btn').addEventListener('click', () => {
            currentPage = 1;
            loadInterestData();
        });

        document.getElementById('search-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                currentPage = 1;
                loadInterestData();
            }
        });

        // Load interest data on page load
        document.addEventListener('DOMContentLoaded', loadInterestData);
    </script>
</body>
</html>
