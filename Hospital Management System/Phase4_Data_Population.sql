-- Phase 4: Data Population (PostgreSQL) - Updated with exact requirements

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

-- 3. Populating 10 Patients (Alice and Bob start with A and B)
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

-- 4. Populating 8 Medications
INSERT INTO Medication (medication_name, description, medication_cost) VALUES
('Aspirin', 'Pain reliever and blood thinner', 5.99),
('Amoxicillin', 'Antibiotic', 15.50),
('Lisinopril', 'Blood pressure medication', 25.00),
('Ibuprofen', 'NSAID for pain and inflammation', 8.50),
('Zofran', 'Anti-nausea medication', 30.00),
('Morphine', 'Severe pain management', 75.00),
('Doxorubicin', 'Chemotherapy drug', 450.00),
('Albuterol', 'Asthma inhaler', 45.00);

-- 5. Populating 15 Appointments (Patient 1 gets multiple)
INSERT INTO Appointment (appointment_date, diagnosis, doctor_id, patient_id) VALUES
('2023-01-10', 'Chest pain', 1, 1),
('2023-01-15', 'Follow-up for chest pain', 2, 1),
('2023-02-20', 'Routine cardiac stress test', 2, 1),
('2023-03-05', 'Sprained ankle', 4, 2),
('2023-03-10', 'Severe allergic reaction', 3, 3),
('2023-04-12', 'Pediatric annual checkup', 6, 4),
('2023-05-01', 'Minor burns', 4, 5),
('2023-05-15', 'Oncology consultation', 7, 6),
('2023-06-20', 'Chemotherapy session 1', 8, 6),
('2023-07-10', 'Asthma exacerbation', 5, 7),
('2023-08-05', 'High blood pressure management', 1, 8),
('2023-09-12', 'Persistent migraines', 3, 9),
('2023-10-01', 'Flu symptoms', 6, 10),
('2023-10-15', 'Broken arm', 3, 2),
('2023-11-20', 'Heart palpitations', 2, 3);

-- 6. Populating 20 Prescriptions (Appt 1 gets multiple)
INSERT INTO Prescription (dosage, instructions, appointment_id, medication_id) VALUES
('81mg', 'Take once daily', 1, 1),
('10mg', 'Take for blood pressure', 1, 3),
('400mg', 'Take for pain', 1, 4),
('500mg', 'Take twice daily', 2, 2),
('400mg', 'As needed for pain', 4, 4),
('10mg', 'Daily in the morning', 5, 3),
('2 puffs', 'Use inhaler as needed', 10, 8),
('8mg', 'Take for nausea', 8, 5),
('50mg/m2', 'IV administration', 9, 7),
('15mg', 'Take every 4 hours for severe pain', 9, 6),
('500mg', 'Take with food', 11, 2),
('81mg', 'Daily', 11, 1),
('400mg', 'Every 6 hours', 12, 4),
('2 puffs', 'Every 4 hours', 13, 8),
('15mg', 'For fracture pain', 14, 6),
('500mg', 'Antibiotic prophylaxis', 14, 2),
('10mg', 'Daily', 15, 3),
('81mg', 'Daily', 3, 1),
('8mg', 'For nausea', 12, 5),
('400mg', 'As needed', 7, 4);
