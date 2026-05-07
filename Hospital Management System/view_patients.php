<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if(isset($_GET['delete_id'])){
    $sql = "DELETE FROM patients WHERE id = :id";
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":id", $_GET['delete_id']);
        $stmt->execute();
    }
    header("location: view_patients.php");
    exit;
}

$patients = $pdo->query("SELECT * FROM patients ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Patients - HMS</title>
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
            <h1>Patients List</h1>
            <a href="add_patient.php" class="btn btn-primary">+ Add New Patient</a>
        </div>
        <div class="card">
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Contact</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach($patients as $row): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                            <td>
                                <a href="edit_patient.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 5px; margin-right: 5px;">Edit</a>
                                <a href="view_patients.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 5px;" onclick="return confirm('Delete this patient?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
