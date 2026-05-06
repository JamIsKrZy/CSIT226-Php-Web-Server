-- Seeds: Insert dummy user data
-- This populates the users table with sample data

INSERT INTO users (email, password, first_name, last_name) VALUES
('demo@example.com', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 'Demo', 'User'),
('john.doe@example.com', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 'John', 'Doe'),
('jane.smith@example.com', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 'Jane', 'Smith'),
('bob.wilson@example.com', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 'Bob', 'Wilson'),
('alice.johnson@example.com', '$2y$10$slYQmyNdGzin7olVCrmK.OPST9/PgBkqquzi.Ee60kiKy67fsqkha', 'Alice', 'Johnson');

-- Note: All passwords are hashed version of 'password123'
-- Hashing: password_hash('password123', PASSWORD_BCRYPT)
