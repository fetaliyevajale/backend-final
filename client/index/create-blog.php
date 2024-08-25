<?php
include('../config/config.php');

$categories = $connection->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

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

    if (move_uploaded_file($profile_image_tmp, $profile_image_path)) {
        try {
            $stmt = $connection->prepare("INSERT INTO blogs (title, description, category_id, profile_image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $category_id, $profile_image]);
            header('Location: blogs.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>


<form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="profile_image">Profile Image:</label>
        <input type="file" id="profile_image" name="profile_image" required>

        <button type="submit">Create Blog</button>
    </form>