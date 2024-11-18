<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config/config.php';

// Fetch students
$query = "SELECT student.id, student.name, student.email, student.address, student.image, student.created_at, classes.name as class_name
          FROM student
          JOIN classes ON student.class_id = classes.class_id";
$students = $conn->query($query);

if ($students === false) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Student List</h1>
    <a href="create.php">Add New Student</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Class</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students->num_rows > 0): ?>
                <?php while ($student = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($student['class_name']); ?></td>
                        <td><img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Image" width="100"></td>
                        <td>
                            <a href="view.php?id=<?php echo $student['id']; ?>">View</a> |
                            <a href="edit.php?id=<?php echo $student['id']; ?>">Edit</a> |
                            <a href="delete.php?id=<?php echo $student['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
