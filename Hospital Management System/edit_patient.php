<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success_msg = $error_msg = "";
$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: view_patients.php");
    exit;
}

// Fetch current data
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = :id");
$stmt->execute([':id' => $id]);
$patient = $stmt->fetch();

if (!$patient) {
    header("Location: view_patients.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = trim($_POST['contact']);

    if(empty($name) || empty($age) || empty($gender) || empty($contact)) {
        $error_msg = "Please fill in all fields.";
    } else {
        $sql = "UPDATE patients SET name = :name, age = :age, gender = :gender, contact = :contact WHERE id = :id";
        if ($update_stmt = $pdo->prepare($sql)) {
            $update_stmt->execute([
                ':name' => $name,
                ':age' => $age,
                ':gender' => $gender,
                ':contact' => $contact,
                ':id' => $id
            ]);
            $success_msg = "Patient updated successfully!";
            // Refresh data
            $stmt->execute([':id' => $id]);
            $patient = $stmt->fetch();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Patient - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h2>HMS</h2></div>
        <ul class="nav-links">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="add_patient.php">Add Patient</a></li>
            <li><a href="view_patients.php" class="active">View Patients</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li class="logout-link"><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="page-header">
            <h1>Edit Patient</h1>
            <a href="view_patients.php" class="btn btn-danger">Back to List</a>
        </div>
        <div class="card" style="max-width: 600px;">
            <?php if(!empty($success_msg)): ?><div class="alert alert-success"><?php echo $success_msg; ?></div><?php endif; ?>
            <?php if(!empty($error_msg)): ?><div class="alert alert-danger"><?php echo $error_msg; ?></div><?php endif; ?>
            
            <form action="edit_patient.php?id=<?php echo $patient['id']; ?>" method="post">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($patient['age']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male" <?php echo ($patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($patient['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($patient['contact']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Update Patient</button>
            </form>
        </div>
    </div>
</body>
</html>
