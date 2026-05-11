-- Seeds: Insert schedule and planned item data
-- This populates the Schedule and PlannedItem tables with sample data

-- Create schedules for each student
INSERT INTO Schedule (studentID, semester, academicYear, status, notes) VALUES
(1, '1st Semester', 2026, 'draft', 'Initial enrollment plan'),
(2, '1st Semester', 2026, 'draft', 'Initial enrollment plan'),
(3, '1st Semester', 2026, 'draft', 'Initial enrollment plan'),
(4, '1st Semester', 2026, 'draft', 'Initial enrollment plan'),
(5, '1st Semester', 2026, 'draft', 'Initial enrollment plan');

-- Add planned items (course registrations) for students
-- Student 1: John Doe
INSERT INTO PlannedItem (scheduleID, sectionID, commitmentLevel, priority, enrollmentStatus) VALUES
(1, 1, 8, 1, 'planned'),
(1, 3, 9, 2, 'planned'),
(1, 5, 7, 3, 'planned');

-- Student 2: Jane Smith
INSERT INTO PlannedItem (scheduleID, sectionID, commitmentLevel, priority, enrollmentStatus) VALUES
(2, 2, 9, 1, 'planned'),
(2, 4, 8, 2, 'planned'),
(2, 6, 6, 3, 'planned');

-- Student 3: Bob Wilson
INSERT INTO PlannedItem (scheduleID, sectionID, commitmentLevel, priority, enrollmentStatus) VALUES
(3, 1, 7, 1, 'planned'),
(3, 7, 8, 2, 'planned'),
(3, 9, 5, 3, 'planned');

-- Student 4: Alice Johnson
INSERT INTO PlannedItem (scheduleID, sectionID, commitmentLevel, priority, enrollmentStatus) VALUES
(4, 2, 9, 1, 'planned'),
(4, 8, 7, 2, 'planned'),
(4, 10, 8, 3, 'planned');

-- Student 5: Charlie Brown
INSERT INTO PlannedItem (scheduleID, sectionID, commitmentLevel, priority, enrollmentStatus) VALUES
(5, 3, 6, 1, 'planned'),
(5, 5, 9, 2, 'planned'),
(5, 7, 7, 3, 'planned');
