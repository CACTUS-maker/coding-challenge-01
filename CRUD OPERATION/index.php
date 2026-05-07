<?php
require_once 'db.php';

// Fetch all records
$stmt = $pdo->query("SELECT * FROM records ORDER BY created_at DESC");
$records = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Students</h1>
            <a href="create.php" class="btn btn-primary">+ Add Student</a>
        </header>

        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($records) > 0): ?>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars(date('M j, Y, g:i a', strtotime($row['created_at']))) ?></td>
                                    <td class="action-links">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Edit</a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">No students found. Click "Add Student" to create one.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
