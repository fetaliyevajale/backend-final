<?php
include('../../config/config.php'); 
include('../../helper/function.php'); 
session_start();



if (!isset($connection)) {
    die("Database connection is not established.");
}

$blogs = $connection->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$last_5_blogs = array_slice($blogs, 0, 5);

 
$top_5_blogs = $connection->query("SELECT * FROM blogs ORDER BY view_count DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .blog-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 300px;
        }

        .blog-card img {
            max-width: 100%;
            border-radius: 8px;
        }

        .blog-card h2 {
            font-size: 18px;
            margin: 10px 0;
        }

        .blog-card p {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>All Blogs</h1>
    <div class="blog-cards">
        <?php foreach ($blogs as $blog): ?>
            <div class="blog-card">
                <img src="uploads/<?php echo htmlspecialchars($blog['profile_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                <p><?php echo htmlspecialchars($blog['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Last 5 Blogs</h2>
    <div class="blog-cards">
        <?php foreach ($last_5_blogs as $blog): ?>
            <div class="blog-card">
                <img src="uploads/<?php echo htmlspecialchars($blog['profile_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                <p><?php echo htmlspecialchars($blog['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Top 5 Blogs</h2>
    <div class="blog-cards">
        <?php foreach ($top_5_blogs as $blog): ?>
            <div class="blog-card">
                <img src="uploads/<?php echo htmlspecialchars($blog['profile_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                <p><?php echo htmlspecialchars($blog['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
