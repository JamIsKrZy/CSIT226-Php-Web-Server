-- Seed admins and enrollment updates
-- Password is 'password' hashed with password_hash() -> $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

TRUNCATE TABLE enrollment_updates;

INSERT INTO users (admin_id, email, password, first_name, last_name, role, status) VALUES
('ADM-000', 'admin@cit.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 'System', 'Admin', 'admin', 'Active'),
('ADM-001', 'juan.delacruz@cit.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Dela Cruz', 'admin', 'Active'),
('ADM-002', 'm.santos@cit.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria', 'Santos', 'admin', 'Active'),
('ADM-003', 'r.lim@cit.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ricardo', 'Lim', 'admin', 'Inactive')
ON DUPLICATE KEY UPDATE 
    role = VALUES(role),
    status = VALUES(status),
    admin_id = VALUES(admin_id);

INSERT INTO enrollment_updates (title, description, status) VALUES
('CSIT122 F1 - Section Full', 'Section F1 (Intermediate Programming II) is now at full capacity (40/40 students). Students are advised to consider Section F2 or F3.', 'Critical'),
('CSIT228 F3 - New Section Opened', 'A new block (Section F3) for Database Management Systems has been opened to accommodate high demand. Schedule: TTH 3:00-4:30 PM.', 'New'),
('MATH215 F2 - Room Change', 'The room for Discrete Mathematics Section F2 has been changed from Room 108 to Room 110. Please update your plotted schedule.', 'Advisory');
