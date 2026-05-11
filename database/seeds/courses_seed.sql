-- Seeds: Insert course data
-- This populates the Course table with sample course data

INSERT INTO Course (courseCode, courseName, credits, category, description, department) VALUES
('CS101', 'Introduction to Computer Science', 3, 'Core', 'Foundational concepts of computer science', 'Computer Science'),
('CS201', 'Data Structures', 4, 'Core', 'Learn fundamental data structures and algorithms', 'Computer Science'),
('CS301', 'Database Systems', 4, 'Core', 'Design and implementation of database systems', 'Computer Science'),
('CS401', 'Web Development', 3, 'Elective', 'Modern web development techniques', 'Computer Science'),
('IT101', 'Information Technology Fundamentals', 3, 'Core', 'Basics of IT infrastructure', 'Information Technology'),
('IT201', 'Network Administration', 4, 'Core', 'Network setup and management', 'Information Technology'),
('IT301', 'Cybersecurity', 3, 'Elective', 'Introduction to cybersecurity practices', 'Information Technology'),
('IT401', 'Cloud Computing', 3, 'Elective', 'Cloud platforms and services', 'Information Technology');
