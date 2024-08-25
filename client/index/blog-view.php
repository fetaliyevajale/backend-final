<?php
include('../config/config.php'); 

$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($blog_id) {
    try {
     
        $stmt = $pdo->prepare("UPDATE blogs SET view_count = view_count + 1 WHERE id = ?");
        $stmt->execute([$blog_id]);

        $stmt = $pdo->prepare("SELECT b.*, c.name as category_name, u.name as author_name 
                               FROM blogs b 
                               JOIN categories c ON b.category_id = c.id
                               JOIN users u ON b.user_id = u.id
                               WHERE b.id = ?");
        $stmt->execute([$blog_id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$blog) {
            echo "Blog not found.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid blog ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
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
        .blog-post {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .blog-post img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="blog-post">
            <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
            <?php if (!empty($blog['profile_image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($blog['profile_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
            <?php endif; ?>
            <p><?php echo htmlspecialchars($blog['description']); ?></p>
            <p>Views: <?php echo htmlspecialchars($blog['view_count']); ?></p>
            <p>Category: <?php echo htmlspecialchars($blog['category_name']); ?></p>
            <p>Author: <?php echo htmlspecialchars($blog['author_name']); ?></p>
        </div>
    </div>
</body>
</html>
