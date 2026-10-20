-- ============================================================
-- Doctor Appointment Booking System Database
-- Database Name: doctor_appointment
-- Target Server: MySQL / MariaDB (XAMPP Default)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `doctor_appointment`
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `doctor_appointment`;

-- ------------------------------------------------------------
-- Table structure for `doctors`
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `doctors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `specialization` VARCHAR(100) NOT NULL,
    `consultation_fee` DECIMAL(10, 2) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table structure for `appointments`
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `appointments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `doctor_id` INT NOT NULL,
    `appointment_date` DATE NOT NULL,
    `appointment_time` VARCHAR(30) NOT NULL,
    `booking_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_appointment_doctor` 
        FOREIGN KEY (`doctor_id`) 
        REFERENCES `doctors` (`id`) 
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Insert 3 Realistic Sample Doctors (Consultation fees in INR)
-- ------------------------------------------------------------
DELETE FROM `doctors`;

INSERT INTO `doctors` (`id`, `name`, `specialization`, `consultation_fee`, `image`) VALUES
(1, 'Dr. Rajesh Sharma', 'Senior Cardiologist', 800.00, 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=500&auto=format&fit=crop&q=80'),
(2, 'Dr. Priya Patel', 'Consultant Neurologist', 1000.00, 'https://images.unsplash.com/photo-1594824813589-9a700fa3246f?w=500&auto=format&fit=crop&q=80'),
(3, 'Dr. Amit Verma', 'Pediatric Specialist', 600.00, 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?w=500&auto=format&fit=crop&q=80');
