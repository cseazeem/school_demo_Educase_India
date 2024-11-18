<?php
require 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and handle file upload
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    
    // File upload handling
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'png'])) {
            $image = uniqid() . '.' . $imageFileType;
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
        } else {
            $image = null;
        }
    }

    $query = "INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssis', $name, $email, $address, $class_id, $image);
    $stmt->execute();
    header('Location: index.php');
}

$classes = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Create Student</h1>
    <form action="create.php" method="post" enctype="multipart/form-data">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Address: <textarea name="address"></textarea></label><br>
        <label>Class:
            <select name="class_id">
                <?php while ($class = $classes->fetch_assoc()): ?>
                    <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </label><br>
        <label>Image: <input type="file" name="image" accept="image/png, image/jpeg"></label><br>
        <button type="submit">Create</button>
    </form>
    <a href="index.php">Back to list</a>
</body>
</html>
