-- Migration: Update users table and create enrollment_updates table
-- Description: Adds role and status to users, and creates enrollment_updates table

ALTER TABLE users 
MODIFY COLUMN role ENUM('student', 'admin') DEFAULT 'student' AFTER last_name,
ADD COLUMN IF NOT EXISTS status ENUM('Active', 'Inactive') DEFAULT 'Active' AFTER role,
ADD COLUMN IF NOT EXISTS admin_id VARCHAR(20) UNIQUE AFTER id;

CREATE TABLE IF NOT EXISTS enrollment_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Critical', 'New', 'Advisory') DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
