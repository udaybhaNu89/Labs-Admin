CREATE DATABASE IF NOT EXISTS lab_db;

USE lab_db;

-- Table for storing complaints
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_number VARCHAR(50) NOT NULL,
    pc_number VARCHAR(50) NOT NULL,
    issue_description TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table for admins (Login details)
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Insert a default admin so you can log in later
INSERT INTO admins (username, password) VALUES ('admin', 'admin');
