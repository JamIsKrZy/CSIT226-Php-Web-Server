-- Seeds: Insert notification data
-- This populates the Notification table with sample notifications

INSERT INTO Notification (type, title, message, isRead, studentID) VALUES
(1, 'Enrollment', 'Your enrollment plan has been created', 1, 1),
(1, 'Schedule', 'Your schedule is ready for review', 0, 1),
(1, 'Enrollment', 'Enrollment deadline approaching', 0, 2),
(1, 'Update', 'Course CS201 section A is now full', 1, 2),
(1, 'Enrollment', 'Your enrollment plan has been created', 0, 3),
(1, 'Schedule', 'Conflict detected in your schedule', 0, 3),
(1, 'Enrollment', 'Your enrollment has been confirmed', 1, 4),
(1, 'Schedule', 'New high-demand section available', 0, 4),
(1, 'Enrollment', 'Waitlist notification for CS301-A', 0, 5),
(1, 'Update', 'Course availability updated', 1, 5);
