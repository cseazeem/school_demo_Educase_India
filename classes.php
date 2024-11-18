<?php
require 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    if (isset($_POST['class_id'])) {
        // Update class
        $class_id = $_POST['class_id'];
        $query = "UPDATE classes SET name = ? WHERE class_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $name, $class_id);
    } else {
        // Create class
        $query = "INSERT INTO classes (name) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $name);
    }
    $stmt->execute();
    header('Location: classes.php');
}

if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $query = "DELETE FROM classes WHERE class_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $class_id);
    $stmt->execute();
    header('Location: classes.php');
}

$classes = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Manage Classes</h1>
    <form action="classes.php" method="post">
        <input type="hidden" name="class_id" value="">
        <label>Class Name: <input type="text" name="name" required></label><br>
        <button type="submit">Save Class</button>
    </form>
    <h2>Existing Classes</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($class = $classes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($class['name']); ?></td>
                    <td>
                        <a href="classes.php?edit=<?php echo $class['class_id']; ?>">Edit</a> |
                        <a href="classes.php?delete=<?php echo $class['class_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
