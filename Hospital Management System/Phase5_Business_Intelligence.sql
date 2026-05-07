-- Phase 5: Business Intelligence (PostgreSQL)

-- 1. A 3-table join showing Patient Name, Doctor Name, Date, and Diagnosis.
SELECT 
    p.first_name || ' ' || p.last_name AS patient_name,
    d.first_name || ' ' || d.last_name AS doctor_name,
    a.appointment_date,
    a.diagnosis
FROM Appointment a
JOIN Patient p ON a.patient_id = p.patient_id
JOIN Doctor d ON a.doctor_id = d.doctor_id;

-- 2. A staffing report with Department Name, count of doctors, and average salary using GROUP BY.
SELECT 
    dep.department_name,
    COUNT(doc.doctor_id) AS number_of_doctors,
    ROUND(AVG(doc.salary), 2) AS average_salary
FROM Department dep
LEFT JOIN Doctor doc ON dep.department_id = doc.department_id
GROUP BY dep.department_name;

-- 3. A subquery listing doctors earning more than the hospital average.
SELECT 
    first_name || ' ' || last_name AS doctor_name, 
    specialty, 
    salary
FROM Doctor
WHERE salary > (SELECT AVG(salary) FROM Doctor);

-- 4. Total medication cost per patient using SUM and GROUP BY.
SELECT 
    p.first_name || ' ' || p.last_name AS patient_name,
    SUM(m.medication_cost) AS total_medication_cost
FROM Patient p
JOIN Appointment a ON p.patient_id = a.patient_id
JOIN Prescription pr ON a.appointment_id = pr.appointment_id
JOIN Medication m ON pr.medication_id = m.medication_id
GROUP BY p.patient_id, p.first_name, p.last_name;

-- 5. A search for patients with names starting with 'A' or 'B' in Cardiology or Pediatrics.
SELECT DISTINCT
    p.first_name || ' ' || p.last_name AS patient_name,
    dep.department_name
FROM Patient p
JOIN Appointment a ON p.patient_id = a.patient_id
JOIN Doctor d ON a.doctor_id = d.doctor_id
JOIN Department dep ON d.department_id = dep.department_id
WHERE (p.first_name LIKE 'A%' OR p.first_name LIKE 'B%')
  AND dep.department_name IN ('Cardiology', 'Pediatrics');

-- 6. A CREATE VIEW called patient_medical_summary showing total appointments and total costs per patient.
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

-- 7. A TRANSACTION (BEGIN/COMMIT) that adds a new appointment and a corresponding prescription.
BEGIN;

-- Insert a new appointment
INSERT INTO Appointment (appointment_date, diagnosis, doctor_id, patient_id)
VALUES (CURRENT_DATE, 'Acute Bronchitis', 1, 1);

-- Insert the corresponding prescription linked to the newly generated appointment ID
-- Using PostgreSQL's currval() to dynamically grab the last inserted serial ID for Appointment
INSERT INTO Prescription (dosage, instructions, appointment_id, medication_id)
VALUES ('250mg', 'Take twice daily for 7 days', currval(pg_get_serial_sequence('Appointment', 'appointment_id')), 2);

COMMIT;
