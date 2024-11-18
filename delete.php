<?php
require 'config/config.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "SELECT image FROM student WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    if ($student['image']) {
        unlink('uploads/' . $student['image']);
    }

    $query = "DELETE FROM student WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Delete Student</h1>
    <form action="delete.php?id=<?php echo $id; ?>" method="post">
        <p>Are you sure you want to delete this student?</p>
        <button type="submit">Yes, Delete</button>
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
