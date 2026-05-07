<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle status updates
if(isset($_GET['complete_id'])){
    $pdo->prepare("UPDATE appointments SET status = 'Completed' WHERE id = ?")->execute([$_GET['complete_id']]);
    header("location: view_appointments.php");
    exit;
}
if(isset($_GET['cancel_id'])){
    $pdo->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ?")->execute([$_GET['cancel_id']]);
    header("location: view_appointments.php");
    exit;
}

// Fetch all appointments joined with patients
$sql = "SELECT a.*, p.name AS patient_name FROM appointments a JOIN patients p ON a.patient_id = p.id ORDER BY a.date ASC";
$appointments = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Appointments - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h2>HMS</h2></div>
        <ul class="nav-links">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="add_patient.php">Add Patient</a></li>
            <li><a href="view_patients.php">View Patients</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="view_appointments.php" class="active">View Appointments</a></li>
            <li class="logout-link"><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="page-header">
            <h1>Appointments List</h1>
            <a href="schedule.php" class="btn btn-primary">+ Schedule New</a>
        </div>
        <div class="card">
            <table>
                <thead>
                    <tr><th>Date</th><th>Patient</th><th>Doctor</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(count($appointments) > 0): ?>
                        <?php foreach($appointments as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                                <td>
                                    <?php if($row['status'] == 'Scheduled') echo "<span style='color:var(--primary-color);font-weight:bold;'>Scheduled</span>"; ?>
                                    <?php if($row['status'] == 'Completed') echo "<span style='color:var(--success);font-weight:bold;'>Completed</span>"; ?>
                                    <?php if($row['status'] == 'Cancelled') echo "<span style='color:var(--danger);font-weight:bold;'>Cancelled</span>"; ?>
                                </td>
                                <td>
                                    <?php if($row['status'] == 'Scheduled'): ?>
                                        <a href="view_appointments.php?complete_id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 5px; font-size: 0.8rem; background-color: var(--success);">Complete</a>
                                        <a href="view_appointments.php?cancel_id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 5px; font-size: 0.8rem;">Cancel</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center;">No appointments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
