<?php
require_once 'db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM records WHERE id = :id");
    $stmt->bindParam(':id', $id);
    
    $stmt->execute();
}

header("Location: index.php");
exit();
?>
