<?php
require 'config/config.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and handle file upload
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    
    $image = $_POST['current_image'];
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'png'])) {
            $image = uniqid() . '.' . $imageFileType;
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
        }
    }

    $query = "UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssis', $name, $email, $address, $class_id, $image, $id);
    $stmt->execute();
    header('Location: index.php');
}

$studentQuery = "SELECT * FROM student WHERE id = ?";
$stmt = $conn->prepare($studentQuery);
$stmt->bind_param('i', $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$classes = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Student</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($student['image']); ?>">
        <label>Name: <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required></label><br>
        <label>Address: <textarea name="address"><?php echo htmlspecialchars($student['address']); ?></textarea></label><br>
        <label>Class:
            <select name="class_id">
                <?php while ($class = $classes->fetch_assoc()): ?>
                    <option value="<?php echo $class['class_id']; ?>" <?php echo $class['class_id'] == $student['class_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($class['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label><br>
        <label>Image: <input type="file" name="image" accept="image/png, image/jpeg"></label><br>
        <?php if ($student['image']): ?>
            <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Image" width="100"><br>
        <?php endif; ?>
        <button type="submit">Update</button>
    </form>
    <a href="index.php">Back to list</a>
</body>
</html>
