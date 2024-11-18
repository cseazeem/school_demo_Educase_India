<?php
require 'config/config.php';

$id = $_GET['id'];
$query = "SELECT student.*, classes.name as class_name FROM student 
          JOIN classes ON student.class_id = classes.class_id WHERE student.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>View Student</h1>
    <p>Name: <?php echo htmlspecialchars($student['name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
    <p>Address: <?php echo htmlspecialchars($student['address']); ?></p>
    <p>Class: <?php echo htmlspecialchars($student['class_name']); ?></p>
    <p>Created At: <?php echo htmlspecialchars($student['created_at']); ?></p>
    <?php if ($student['image']): ?>
        <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Image" width="200">
    <?php endif; ?>
    <a href="index.php">Back to list</a>
</body>
</html>
