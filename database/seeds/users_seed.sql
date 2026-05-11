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
INSERT INTO Student (userID, points, studentNumber, major) VALUES
(1, 25, 'STU001', 'Computer Science'),
(2, 30, 'STU002', 'Information Technology'),
(3, 20, 'STU003', 'Computer Science'),
(4, 35, 'STU004', 'Information Technology'),
(5, 28, 'STU005', 'Computer Science');

-- Link Admins to Admin table
INSERT INTO Admin (userID, department, designation) VALUES
(6, 'Enrollment Services', 'Director'),
(7, 'Academic Affairs', 'Coordinator');

-- Note: All passwords are hashed version of 'password123'
