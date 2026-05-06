<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <style>
        .page-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #333;
            margin: 0;
        }

        .btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .users-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        table th {
            padding: 15px;
            text-align: left;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        table tbody tr {
            border-bottom: 1px solid #dee2e6;
            transition: background 0.2s;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        table td {
            padding: 15px;
            color: #555;
        }

        table td:first-child {
            font-weight: 500;
            color: #667eea;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .action-links {
            display: flex;
            gap: 10px;
        }

        .action-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            padding: 5px 10px;
            border: 1px solid #667eea;
            border-radius: 3px;
            transition: all 0.2s;
        }

        .action-links a:hover {
            background: #667eea;
            color: white;
        }

        .action-links .delete {
            border-color: #dc3545;
            color: #dc3545;
        }

        .action-links .delete:hover {
            background: #dc3545;
            color: white;
        }

        .timestamp {
            color: #999;
            font-size: 13px;
        }

        .nav-links {
            text-align: center;
            margin-bottom: 20px;
        }

        .nav-links a {
            margin: 0 15px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="nav-links">
            <a href="/">Home</a>
            <a href="/login">Login</a>
            <a href="/signup">Sign Up</a>
            <a href="/users" style="font-weight: bold; color: #333;">Users</a>
        </div>

        <div class="page-header">
            <h1>Users Directory</h1>
            <a href="/login" class="btn">Login</a>
        </div>

        <?php if (empty($users)): ?>
            <div class="users-table">
                <div class="no-data">
                    <p>No users found in the database.</p>
                    <p style="font-size: 12px; color: #ccc;">Run the migration and seed scripts to populate data.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($user['last_name'] ?? '-'); ?></td>
                                <td>
                                    <span class="timestamp">
                                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px; color: #999; font-size: 12px;">
            <p>Total Users: <?php echo count($users); ?></p>
        </div>
    </div>
</body>
</html>
