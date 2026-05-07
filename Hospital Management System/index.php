<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$patient_count = $appointment_count = $today_appointments = 0;
try {
    $patient_count = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
    $appointment_count = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
    $today_appointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE date = CURDATE()")->fetchColumn();
} catch (PDOException $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h2>HMS</h2></div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Dashboard</a></li>
            <li><a href="add_patient.php">Add Patient</a></li>
            <li><a href="view_patients.php">View Patients</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li class="logout-link"><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="page-header">
            <h1>Dashboard</h1>
            <div>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div class="card">
                <h3>Total Patients</h3>
                <p style="font-size: 2rem; color: var(--primary-color); font-weight: 600; margin-top: 10px;"><?php echo $patient_count; ?></p>
            </div>
            <div class="card">
                <h3>Total Appointments</h3>
                <p style="font-size: 2rem; color: var(--secondary-color); font-weight: 600; margin-top: 10px;"><?php echo $appointment_count; ?></p>
            </div>
            <div class="card">
                <h3>Appointments Today</h3>
                <p style="font-size: 2rem; color: var(--success); font-weight: 600; margin-top: 10px;"><?php echo $today_appointments; ?></p>
            </div>
        </div>

        <div class="page-header" style="margin-top: 40px;">
            <h1>Administrator Reports (BI)</h1>
        </div>
        <div class="card">
            <h3>Patient Medical Summary</h3>
            <div style="overflow-x: auto; margin-top: 15px;">
                <table>
                    <thead>
                        <tr><th>Patient Name</th><th>Appointments</th><th>Total Med Cost</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $summary = $pdo->query("SELECT * FROM patient_medical_summary LIMIT 5")->fetchAll();
                        foreach($summary as $row): 
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td><?php echo $row['total_appointments']; ?></td>
                                <td>$<?php echo number_format($row['total_medication_cost'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
