<?php
include('../../config/config.php'); 
include('../../helper/function.php'); 
session_start();

try {
    $blogs = $connection->query("SELECT id, title FROM blogs")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog List</title>
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
        .blog-item {
            background: #fff;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .blog-item a {
            text-decoration: none;
            color: #333;
        }
        .blog-item a:hover {
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Blog List</h1>
        <?php foreach ($blogs as $blog): ?>
            <div class="blog-item">
                <h2><a href="blog-view.php?id=<?php echo htmlspecialchars($blog['id']); ?>"><?php echo htmlspecialchars($blog['title']); ?></a></h2>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
