-- Create database
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Create admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$8KzO1N6YM6HlGRVmn5h5.uHj5m3QfUcpxgR.QoltBJZl4BbLJMWPa');

-- Create works table
CREATE TABLE IF NOT EXISTS works (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample works
INSERT INTO works (title, description, category, image, created_at) VALUES
('Portfolio Website', 'A personal portfolio website built with HTML, CSS, and JavaScript.', 'website', 'sample-work-1.jpg', NOW()),
('E-commerce Design', 'UI/UX design for an e-commerce platform.', 'design', 'sample-work-2.jpg', NOW()),
('Mobile App', 'A mobile application for tracking fitness activities.', 'website', 'sample-work-3.jpg', NOW());

-- Insert sample testimonials
INSERT INTO testimonials (name, position, content, image, created_at) VALUES
('John Doe', 'CEO, Tech Company', 'Working with Farid was a great experience. He delivered the project on time and with excellent quality.', 'testimonial-1.jpg', NOW()),
('Jane Smith', 'Marketing Director', 'Farid is a talented developer who understands client needs and delivers beyond expectations.', 'testimonial-2.jpg', NOW()),
('Mike Johnson', 'Startup Founder', 'I highly recommend Farid for any web development project. His work is clean, efficient, and modern.', 'testimonial-3.jpg', NOW());
