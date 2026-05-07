<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success_msg = $error_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = trim($_POST['contact']);

    if(empty($name) || empty($age) || empty($gender) || empty($contact)) {
        $error_msg = "Please fill in all fields.";
    } else {
        $sql = "INSERT INTO patients (name, age, gender, contact) VALUES (:name, :age, :gender, :contact)";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":age", $age);
            $stmt->bindParam(":gender", $gender);
            $stmt->bindParam(":contact", $contact);
            if ($stmt->execute()) {
                $success_msg = "Patient added successfully!";
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
    <title>Add Patient - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h2>HMS</h2></div>
        <ul class="nav-links">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="add_patient.php" class="active">Add Patient</a></li>
            <li><a href="view_patients.php">View Patients</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li class="logout-link"><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="page-header"><h1>Register New Patient</h1></div>
        <div class="card" style="max-width: 600px;">
            <?php if(!empty($success_msg)): ?><div class="alert alert-success"><?php echo $success_msg; ?></div><?php endif; ?>
            <?php if(!empty($error_msg)): ?><div class="alert alert-danger"><?php echo $error_msg; ?></div><?php endif; ?>
            
            <form action="" method="post">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Save Patient</button>
            </form>
        </div>
    </div>
</body>
</html>
