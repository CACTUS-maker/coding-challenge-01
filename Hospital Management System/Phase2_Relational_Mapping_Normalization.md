# Phase 2: Relational Mapping & Normalization (Task 2)

## Part A: Relational Schema

Based on the conceptual design, here is the relational mapping translated into table structures. As requested, the M:N relationship between `Appointments` and `Medications` has been resolved using a `Prescriptions` junction table. 

*Format used: `TableName(PrimaryKey, Attribute1, Attribute2, ForeignKey*)`*

*   **Department**(DepartmentID, DepartmentName, Location)
*   **Doctor**(DoctorID, FirstName, LastName, Specialty, DepartmentID*)
*   **Patient**(PatientID, FirstName, LastName, DateOfBirth, PhoneNumber)
*   **Medication**(MedicationID, MedicationName, Description, Cost)
*   **Appointment**(AppointmentID, AppointmentDate, Diagnosis, DoctorID*, PatientID*)
*   **Prescription**(PrescriptionID, Dosage, Instructions, AppointmentID*, MedicationID*)
*(Note: In the Prescription table, `PrescriptionID` is a surrogate primary key. Alternatively, a composite primary key of `(AppointmentID, MedicationID)` could be used).*

---

## Part B: Normalization Analysis

**Original Table:** `DoctorAppointmentRecord (DoctorID, DoctorName, DoctorSpecialty, DoctorSalary, PatientID, PatientName, PatientPhone, AppointmentDate, Diagnosis, MedicationName, MedicationCost, Dosage)`
**Composite Primary Key:** `(DoctorID, PatientID, AppointmentDate, MedicationName)`

### 1. 1NF Analysis (First Normal Form)
To be in 1NF, a table must have atomic (indivisible) values, and there can be no repeating groups or arrays. In the context of this unnormalized table, a single appointment might involve multiple medications. If `MedicationName` and `Dosage` were stored as a comma-separated list (e.g., "Aspirin, Ibuprofen") in a single row, it would violate 1NF. To achieve 1NF, we must eliminate multi-valued attributes by creating a new row for each medication prescribed during that specific appointment. This flattens the table but introduces significant data redundancy, leading to insertion, update, and deletion anomalies.

### 2. 2NF Analysis (Second Normal Form & Partial Dependencies)
A table is in 2NF if it is in 1NF and contains *no partial dependencies*. A partial dependency occurs when a non-key attribute depends on only a *part* of the composite primary key. In our table, almost everything violates 2NF because of partial dependencies:
*   `DoctorName`, `DoctorSpecialty`, and `DoctorSalary` depend **only** on `DoctorID`.
*   `PatientName` and `PatientPhone` depend **only** on `PatientID`.
*   `MedicationCost` depends **only** on `MedicationName`.
*   `Diagnosis` depends on the appointment itself, represented by the partial key `(DoctorID, PatientID, AppointmentDate)`.
*   Only `Dosage` is fully functionally dependent on the entire composite key `(DoctorID, PatientID, AppointmentDate, MedicationName)`.

### 3. 3NF Decomposition (Third Normal Form & Transitive Dependencies)
A table is in 3NF if it is in 2NF and contains *no transitive dependencies* (where a non-key attribute depends on another non-key attribute). For example, if a `DoctorSalary` is determined strictly by the `DoctorSpecialty` rather than the `DoctorID` directly, that would be a transitive dependency (`DoctorID` -> `DoctorSpecialty` -> `DoctorSalary`). 

To bring the database into 3NF, we decompose it into distinct, focused tables where every non-key attribute depends *the key, the whole key, and nothing but the key*:

*   **Doctor**(DoctorID, DoctorName, DoctorSpecialty, DoctorSalary) *(Note: If Salary is fixed by Specialty, Specialty should become a separate table).*
*   **Patient**(PatientID, PatientName, PatientPhone)
*   **Medication**(MedicationName, MedicationCost)
*   **Appointment**(DoctorID, PatientID, AppointmentDate, Diagnosis) *(Here, DoctorID, PatientID, and AppointmentDate form the composite PK).*
*   **Prescription**(DoctorID, PatientID, AppointmentDate, MedicationName, Dosage) *(This acts as the junction table).*

*By separating these out, we remove all partial and transitive dependencies, eliminating anomalies and ensuring a structurally sound database.*
