<?php
include('../config/config.php');

$categories = $connection->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

$blog_id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->execute([$blog_id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = trim($_POST['category_id']);
    $profile_image = $_FILES['profile_image']['name'];
    $profile_image_tmp = $_FILES['profile_image']['tmp_name'];
    $upload_dir = __DIR__ . "/uploads/";
    $profile_image_path = $upload_dir . basename($profile_image);

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if ($profile_image_tmp) {
        if (move_uploaded_file($profile_image_tmp, $profile_image_path)) {
            $stmt = $connection->prepare("UPDATE blogs SET title = ?, description = ?, category_id = ?, profile_image = ? WHERE id = ?");
            $stmt->execute([$title, $description, $category_id, $profile_image, $blog_id]);
        } else {
            echo "Failed to upload image.";
        }
    } else {
        $stmt = $connection->prepare("UPDATE blogs SET title = ?, description = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$title, $description, $category_id, $blog_id]);
    }
    header('Location: blogs.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($blog['description']); ?></textarea>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo ($category['id'] == $blog['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="profile_image">Profile Image:</label>
        <input type="file" id="profile_image" name="profile_image">

        <button type="submit">Update Blog</button>
    </form>
</body>
</html>