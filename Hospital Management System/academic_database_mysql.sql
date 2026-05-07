-- ==========================================================
-- HMS ACADEMIC SCHEMA (MySQL Version for XAMPP)
-- ==========================================================

CREATE DATABASE IF NOT EXISTS academic_hospital_db;
USE academic_hospital_db;

-- 1. Departments
CREATE TABLE IF NOT EXISTS Department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    location VARCHAR(200)
);

-- 2. Doctors
CREATE TABLE IF NOT EXISTS Doctor (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialty VARCHAR(100),
    salary DECIMAL(10,2),
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES Department(department_id) ON DELETE CASCADE
);

-- 3. Patients
CREATE TABLE IF NOT EXISTS Patient (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    phone_number VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE
);

-- 4. Medications
CREATE TABLE IF NOT EXISTS Medication (
    medication_id INT AUTO_INCREMENT PRIMARY KEY,
    medication_name VARCHAR(100) NOT NULL,
    description TEXT,
    medication_cost DECIMAL(10,2)
);

-- 5. Appointments
CREATE TABLE IF NOT EXISTS Appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_date DATE DEFAULT (CURRENT_DATE),
    diagnosis TEXT,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES Doctor(doctor_id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES Patient(patient_id) ON DELETE CASCADE
);

-- 6. Prescriptions
CREATE TABLE IF NOT EXISTS Prescription (
    prescription_id INT AUTO_INCREMENT PRIMARY KEY,
    dosage VARCHAR(100),
    instructions TEXT,
    appointment_id INT NOT NULL,
    medication_id INT NOT NULL,
    FOREIGN KEY (appointment_id) REFERENCES Appointment(appointment_id) ON DELETE CASCADE,
    FOREIGN KEY (medication_id) REFERENCES Medication(medication_id) ON DELETE CASCADE
);

-- 7. Users (For Web Access)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin'
);

-- DATA POPULATION
-- ==========================================================

INSERT INTO Department (department_name, location) VALUES
('Cardiology', 'Building A, 2nd Floor'),
('Emergency', 'Main Building, Ground Floor'),
('Pediatrics', 'Building B, 1st Floor'),
('Oncology', 'Building C, 3rd Floor');

INSERT INTO Doctor (first_name, last_name, specialty, salary, department_id) VALUES
('John', 'Smith', 'Cardiothoracic Surgery', 250000.00, 1),
('Jane', 'Doe', 'General Cardiology', 200000.00, 1),
('Richard', 'Webb', 'Trauma Surgery', 220000.00, 2),
('Sarah', 'Jenkins', 'Emergency Medicine', 180000.00, 2),
('Michael', 'Scott', 'Neonatology', 195000.00, 3),
('Angela', 'Martin', 'General Pediatrics', 170000.00, 3),
('David', 'Wallace', 'Radiation Oncology', 270000.00, 4),
('Kelly', 'Kapoor', 'Medical Oncology', 240000.00, 4);

INSERT INTO Patient (first_name, last_name, date_of_birth, phone_number, email) VALUES
('Alice', 'Adams', '1985-02-14', '555-0001', 'alice@email.com'),
('Bob', 'Brown', '1990-06-22', '555-0002', 'bob@email.com'),
('Charlie', 'Clark', '1975-11-30', '555-0003', 'charlie@email.com'),
('Diana', 'Prince', '1988-04-10', '555-0004', 'diana@email.com'),
('Evan', 'Wright', '1960-01-25', '555-0005', 'evan@email.com'),
('Fiona', 'Gallagher', '2001-08-15', '555-0006', 'fiona@email.com'),
('George', 'Miller', '1955-12-05', '555-0007', 'george@email.com'),
('Hannah', 'Abbott', '1995-03-18', '555-0008', 'hannah@email.com'),
('Ian', 'Malcolm', '1982-09-27', '555-0009', 'ian@email.com'),
('Jessica', 'Jones', '1992-07-11', '555-0010', 'jessica@email.com');

INSERT INTO users (username, password, role) VALUES ('admin', 'password123', 'admin');

-- VIEW FOR BI REPORTS
CREATE OR REPLACE VIEW patient_medical_summary AS
SELECT 
    p.patient_id,
    CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
    COUNT(DISTINCT a.appointment_id) AS total_appointments,
    COALESCE(SUM(m.medication_cost), 0) AS total_medication_cost
FROM Patient p
LEFT JOIN Appointment a ON p.patient_id = a.patient_id
LEFT JOIN Prescription pr ON a.appointment_id = pr.appointment_id
LEFT JOIN Medication m ON pr.medication_id = m.medication_id
GROUP BY p.patient_id, p.first_name, p.last_name;
