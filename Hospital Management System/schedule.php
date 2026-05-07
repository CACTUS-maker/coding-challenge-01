<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success_msg = $error_msg = "";
$patients = $pdo->query("SELECT id, name FROM patients ORDER BY name ASC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $doctor_name = trim($_POST['doctor_name']);
    $date = $_POST['date'];

    if(empty($patient_id) || empty($doctor_name) || empty($date)) {
        $error_msg = "Please fill in all fields.";
    } else {
        $sql = "INSERT INTO appointments (patient_id, doctor_name, date) VALUES (:patient_id, :doctor_name, :date)";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":patient_id", $patient_id);
            $stmt->bindParam(":doctor_name", $doctor_name);
            $stmt->bindParam(":date", $date);
            if ($stmt->execute()) {
                $success_msg = "Appointment scheduled successfully!";
            } else {
                $error_msg = "Something went wrong.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Schedule Appointment - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h2>HMS</h2></div>
        <ul class="nav-links">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="add_patient.php">Add Patient</a></li>
            <li><a href="view_patients.php">View Patients</a></li>
            <li><a href="schedule.php" class="active">Schedule</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li class="logout-link"><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="page-header"><h1>Schedule Appointment</h1></div>
        <div class="card" style="max-width: 600px;">
            <?php if(!empty($success_msg)): ?><div class="alert alert-success"><?php echo $success_msg; ?></div><?php endif; ?>
            <?php if(!empty($error_msg)): ?><div class="alert alert-danger"><?php echo $error_msg; ?></div><?php endif; ?>
            
            <form action="" method="post">
                <div class="form-group">
                    <label>Patient</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">Select Patient</option>
                        <?php foreach($patients as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Doctor Name</label>
                    <input type="text" name="doctor_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Schedule</button>
            </form>
        </div>
    </div>
</body>
</html>
