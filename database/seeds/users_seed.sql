-- Seeds: Insert dummy user data
-- This populates the User, Student, and Admin tables with sample data

-- Insert Students
INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) VALUES
('John', 'Doe', 'john.doe@university.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 2026, 'student', 'active'),
('Jane', 'Smith', 'jane.smith@university.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 2026, 'student', 'active'),
('Bob', 'Wilson', 'bob.wilson@university.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 2026, 'student', 'active'),
('Alice', 'Johnson', 'alice.johnson@university.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 2026, 'student', 'active'),
('Charlie', 'Brown', 'charlie.brown@university.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 2026, 'student', 'active');

-- Insert Admins
INSERT INTO User (firstName, lastName, email, password, academicYear, userType, status) VALUES
('Dr.', 'Anderson', 'admin.anderson@university.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 2026, 'admin', 'active'),
('Ms.', 'White', 'admin.white@university.edu', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 2026, 'admin', 'active');

-- Link Students to Student table
INSERT INTO Student (userID, studentNumber, program, yearLevel, points, major) VALUES
(1, 'STU001', 'BSCS', 2, 25, 'Computer Science'),
(2, 'STU002', 'BSIT', 2, 30, 'Information Technology'),
(3, 'STU003', 'BSCS', 1, 20, 'Computer Science'),
(4, 'STU004', 'BSIT', 3, 35, 'Information Technology'),
(5, 'STU005', 'BSCS', 1, 28, 'Computer Science');

-- Link Admins to Admin table
INSERT INTO Admin (userID, adminCode, role, department, designation) VALUES
(6, 'ADM001', 'Registrar', 'Enrollment Services', 'Director'),
(7, 'ADM002', 'Department', 'Academic Affairs', 'Coordinator');

-- Note: All passwords are hashed version of 'password123'
