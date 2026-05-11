-- Seeds: Insert section data
-- This populates the Section table with sample section data

INSERT INTO Section (courseID, sectionCode, timeslot, room, capacity, enrolledCount, instructor, semester) VALUES
(1, 'CS101-A', 'MWF 08:00-09:00', 'Room 101', 50, 35, 'Dr. Smith', '1st Semester'),
(1, 'CS101-B', 'MWF 10:00-11:00', 'Room 102', 50, 42, 'Prof. Johnson', '1st Semester'),
(2, 'CS201-A', 'TTh 09:00-10:30', 'Room 201', 40, 28, 'Dr. Anderson', '1st Semester'),
(2, 'CS201-B', 'TTh 14:00-15:30', 'Room 202', 40, 35, 'Prof. Davis', '1st Semester'),
(3, 'CS301-A', 'MWF 11:00-12:00', 'Lab 301', 30, 22, 'Dr. Wilson', '1st Semester'),
(4, 'CS401-A', 'TTh 16:00-17:30', 'Room 303', 35, 28, 'Prof. Martinez', '1st Semester'),
(5, 'IT101-A', 'MWF 13:00-14:00', 'Room 104', 50, 38, 'Dr. Brown', '1st Semester'),
(6, 'IT201-A', 'TTh 11:00-12:30', 'Lab 201', 35, 25, 'Prof. Taylor', '1st Semester'),
(7, 'IT301-A', 'MWF 15:00-16:00', 'Room 305', 40, 18, 'Dr. Lee', '1st Semester'),
(8, 'IT401-A', 'TTh 13:00-14:30', 'Room 306', 30, 20, 'Prof. Garcia', '1st Semester');
