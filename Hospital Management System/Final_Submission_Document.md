# [COVER PAGE]
# INSY8222 - FINAL PROJECT SUBMISSION
# HOSPITAL MANAGEMENT SYSTEM

**STUDENT NAME:** [Your Full Name]  
**STUDENT ID:** [Your Student ID]  
**COURSE:** INSY8222 - Database Management  
**DATE:** April 24, 2026

---

## TASK 1: Analysis & ER Diagram

### Requirements Analysis
The Hospital Management Syfstem (HMS) is developed for a growing regional medical facility to streamline patient care and administrative reporting. Key requirements include:
- **Specialized Care**: Support for at least 4 key departments (Cardiology, Emergency, Pediatrics, Oncology).
- **Staff Management**: Tracking at least 8 doctors across different specialties and departments.
- **Patient Records**: Secure management of patient information, contact details, and medical history.
- **Clinical Operations**: Scheduling appointments, recording diagnoses, and issuing detailed prescriptions.
- **Business Intelligence**: Providing administrators with critical reports on patient load, staffing distribution, and total medication expenditures.

### Entity-Relationship Diagram (ERD)
![Hospital ER Diagram](file:///C:/Users/Eva/.gemini/antigravity/brain/2bcb80ea-f0f2-4c3f-8e3f-38cb5c1f11e5/hospital_er_diagram_1776985380053.png)

---

## TASK 2: Relational Schema & Normalization

### Relational Schema Mapping
The conceptual ER diagram is mapped into the following logical schema:
- **Department** (<u>department_id</u>, department_name, location)
- **Doctor** (<u>doctor_id</u>, first_name, last_name, specialty, salary, *department_id*)
- **Patient** (<u>patient_id</u>, first_name, last_name, date_of_birth, phone_number, email)
- **Medication** (<u>medication_id</u>, medication_name, description, medication_cost)
- **Appointment** (<u>appointment_id</u>, appointment_date, diagnosis, *doctor_id*, *patient_id*)
- **Prescription** (<u>prescription_id</u>, dosage, instructions, *appointment_id*, *medication_id*)

### Normalization Logic
The database design adheres to the **Third Normal Form (3NF)** to ensure data integrity and eliminate redundancy:

1. **First Normal Form (1NF)**: All attributes contain atomic values, and each record is unique via a Primary Key. There are no repeating groups of attributes.
2. **Second Normal Form (2NF)**: The database is in 1NF and all non-key attributes are fully functionally dependent on the entire primary key. In our schema, each table has a single-attribute primary key, naturally satisfying 2NF.
3. **Third Normal Form (3NF)**: The database is in 2NF and there are no transitive dependencies. Every non-prime attribute is dependent only on the primary key (e.g., in the `Doctor` table, `specialty` depends on `doctor_id`, not on `department_id`).

---

## TASK 3: DDL Code & Screenshots
### SQL Code (Data Definition Language)
```sql
-- PHASE 3: PHYSICAL IMPLEMENTATION (DDL)

CREATE TABLE Department (
    department_id SERIAL PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    location VARCHAR(200)
);

CREATE TABLE Doctor (
    doctor_id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialty VARCHAR(100),
    salary NUMERIC(10,2) CHECK (salary > 0),
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES Department(department_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Patient (
    patient_id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    phone_number VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE
);

CREATE TABLE Medication (
    medication_id SERIAL PRIMARY KEY,
    medication_name VARCHAR(100) NOT NULL,
    description TEXT,
    medication_cost NUMERIC(10,2) CHECK (medication_cost >= 0)
);

CREATE TABLE Appointment (
    appointment_id SERIAL PRIMARY KEY,
    appointment_date DATE DEFAULT CURRENT_DATE,
    diagnosis TEXT,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES Doctor(doctor_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES Patient(patient_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Prescription (
    prescription_id SERIAL PRIMARY KEY,
    dosage VARCHAR(100),
    instructions TEXT,
    appointment_id INT NOT NULL,
    medication_id INT NOT NULL,
    FOREIGN KEY (appointment_id) REFERENCES Appointment(appointment_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (medication_id) REFERENCES Medication(medication_id) ON DELETE CASCADE ON UPDATE CASCADE
);
```

### DDL Execution Screenshots
> [!IMPORTANT]
> Paste your psql screenshots here. Ensure the `psql` prompt and the `CREATE TABLE` commands are clearly visible.

---

## TASK 4: DML Code & Screenshots
### SQL Code (Data Manipulation Language)
```sql
-- PHASE 4: DATA POPULATION (DML)

-- 1. Populating 4 Departments
INSERT INTO Department (department_name, location) VALUES
('Cardiology', 'Building A, 2nd Floor'),
('Emergency', 'Main Building, Ground Floor'),
('Pediatrics', 'Building B, 1st Floor'),
('Oncology', 'Building C, 3rd Floor');

-- 2. Populating 8 Doctors
INSERT INTO Doctor (first_name, last_name, specialty, salary, department_id) VALUES
('John', 'Smith', 'Cardiothoracic Surgery', 250000.00, 1),
('Jane', 'Doe', 'General Cardiology', 200000.00, 1),
('Richard', 'Webb', 'Trauma Surgery', 220000.00, 2),
('Sarah', 'Jenkins', 'Emergency Medicine', 180000.00, 2),
('Michael', 'Scott', 'Neonatology', 195000.00, 3),
('Angela', 'Martin', 'General Pediatrics', 170000.00, 3),
('David', 'Wallace', 'Radiation Oncology', 270000.00, 4),
('Kelly', 'Kapoor', 'Medical Oncology', 240000.00, 4);

-- 3. Populating 10 Patients
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
```

### DML Execution Screenshots
> [!IMPORTANT]
> Paste your psql screenshots here showing the `INSERT` commands and the verification counts.

---

## TASK 5: All 7 Queries/Tasks (Code & Screenshots)

### 1. 3-Table Join (Patient, Doctor, Appointment)
```sql
SELECT 
    p.first_name || ' ' || p.last_name AS patient_name,
    d.first_name || ' ' || d.last_name AS doctor_name,
    a.appointment_date,
    a.diagnosis
FROM Appointment a
JOIN Patient p ON a.patient_id = p.patient_id
JOIN Doctor d ON a.doctor_id = d.doctor_id;
```
[Screenshot here]

### 2. Staffing Report (GROUP BY & AVG)
```sql
SELECT 
    dep.department_name,
    COUNT(doc.doctor_id) AS number_of_doctors,
    ROUND(AVG(doc.salary), 2) AS average_salary
FROM Department dep
LEFT JOIN Doctor doc ON dep.department_id = doc.department_id
GROUP BY dep.department_name;
```
[Screenshot here]

### 3. Subquery (High Earners)
```sql
SELECT 
    first_name || ' ' || last_name AS doctor_name, 
    specialty, 
    salary
FROM Doctor
WHERE salary > (SELECT AVG(salary) FROM Doctor);
```
[Screenshot here]

### 4. Total Medication Cost per Patient
```sql
SELECT 
    p.first_name || ' ' || p.last_name AS patient_name,
    SUM(m.medication_cost) AS total_medication_cost
FROM Patient p
JOIN Appointment a ON p.patient_id = a.patient_id
JOIN Prescription pr ON a.appointment_id = pr.appointment_id
JOIN Medication m ON pr.medication_id = m.medication_id
GROUP BY p.patient_id, p.first_name, p.last_name;
```
[Screenshot here]

### 5. Advanced Search (LIKE & IN)
```sql
SELECT DISTINCT
    p.first_name || ' ' || p.last_name AS patient_name,
    dep.department_name
FROM Patient p
JOIN Appointment a ON p.patient_id = a.patient_id
JOIN Doctor d ON a.doctor_id = d.doctor_id
JOIN Department dep ON d.department_id = dep.department_id
WHERE (p.first_name LIKE 'A%' OR p.first_name LIKE 'B%')
  AND dep.department_name IN ('Cardiology', 'Pediatrics');
```
[Screenshot here]

### 6. CREATE VIEW (Medical Summary)
```sql
CREATE OR REPLACE VIEW patient_medical_summary AS
SELECT 
    p.patient_id,
    p.first_name || ' ' || p.last_name AS patient_name,
    COUNT(DISTINCT a.appointment_id) AS total_appointments,
    COALESCE(SUM(m.medication_cost), 0) AS total_medication_cost
FROM Patient p
LEFT JOIN Appointment a ON p.patient_id = a.patient_id
LEFT JOIN Prescription pr ON a.appointment_id = pr.appointment_id
LEFT JOIN Medication m ON pr.medication_id = m.medication_id
GROUP BY p.patient_id, p.first_name, p.last_name;
```
[Screenshot here]

### 7. TRANSACTION (Appointment & Prescription)
```sql
BEGIN;
INSERT INTO Appointment (appointment_date, diagnosis, doctor_id, patient_id)
VALUES (CURRENT_DATE, 'Acute Bronchitis', 1, 1);
INSERT INTO Prescription (dosage, instructions, appointment_id, medication_id)
VALUES ('250mg', 'Take twice daily for 7 days', currval(pg_get_serial_sequence('Appointment', 'appointment_id')), 2);
COMMIT;
```
[Screenshot here]

---
**FILE NAMING CONVENTION:**  
`INSY8222_Final_StudentID.pdf`
