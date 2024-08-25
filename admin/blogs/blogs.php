<?php
include('../../config/config.php'); 
include('../../helper/function.php'); 
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: http://localhost/xampp/backend_project/admin/auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveBlog'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];

    // Şəkil yüklənməsi
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = '../../uploads/' . $image;

    if (move_uploaded_file($image_tmp, $image_folder)) {
        try {
            $stmt = $connection->prepare("INSERT INTO blogs (user_id, category_id, title, description, image, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $category_id, $title, $description, $image, $status]);

            header('Location: blogs.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Failed to upload image.";
    }
}

// Kateqoriyaları gətir
$categories = $connection->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        .blog-form {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .blog-form input[type="text"], .blog-form textarea, .blog-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .blog-form input[type="file"] {
            margin-bottom: 10px;
        }
        .blog-form input[type="submit"] {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .blog-form input[type="submit"]:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Add Blog</h1>
    </header>
    <div class="container">
        <div class="blog-form">
            <h2>Create a New Blog</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Blog Title" required>
                <textarea name="description" placeholder="Blog Description" rows="5" required></textarea>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="file" name="image" required>
                <select name="status" required>
                    <option value="1">Publish</option>
                    <option value="0">Draft</option>
                </select>
                <input type="submit" name="saveBlog" value="Save Blog">
            </form>
        </div>
    </div>
</body>
</html>
