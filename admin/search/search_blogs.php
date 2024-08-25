<?php
include('../config/config.php'); 


$title = isset($_GET['title']) ? $_GET['title'] : '';
$description = isset($_GET['description']) ? $_GET['description'] : '';
$creator = isset($_GET['creator']) ? $_GET['creator'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';


try {
    $query = "
        SELECT b.*, u.name as creator_name, c.name as category_name
        FROM blogs b
        LEFT JOIN users u ON b.user_id = u.id
        LEFT JOIN categories c ON b.category_id = c.id
        WHERE 1=1
    ";

    if ($title) {
        $query .= " AND b.title LIKE :title";
    }
    if ($description) {
        $query .= " AND b.description LIKE :description";
    }
    if ($creator) {
        $query .= " AND u.name LIKE :creator";
    }
    if ($category) {
        $query .= " AND c.name LIKE :category";
    }

    $stmt = $connection->prepare($query);

    if ($title) {
        $stmt->bindValue(':title', '%' . $title . '%');
    }
    if ($description) {
        $stmt->bindValue(':description', '%' . $description . '%');
    }
    if ($creator) {
        $stmt->bindValue(':creator', '%' . $creator . '%');
    }
    if ($category) {
        $stmt->bindValue(':category', '%' . $category . '%');
    }

    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $blogs = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Blogs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
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
        .search-form {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .search-form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-form input[type="submit"] {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form input[type="submit"]:hover {
            background: #555;
        }
        .blog-list {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .blog-list .blog {
            margin-bottom: 20px;
        }
        .blog-list .blog h2 {
            margin: 0 0 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Search Blogs</h1>
    </header>
    <div class="container">
        <div class="search-form">
            <form method="get">
                <input type="text" name="title" placeholder="Title" value="<?php echo htmlspecialchars($title); ?>">
                <input type="text" name="description" placeholder="Description" value="<?php echo htmlspecialchars($description); ?>">
                <input type="text" name="creator" placeholder="Creator" value="<?php echo htmlspecialchars($creator); ?>">
                <input type="text" name="category" placeholder="Category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="submit" value="Search">
            </form>
        </div>

        <div class="blog-list">
            <h2>Search Results</h2>
            <?php if (isset($blogs) && !empty($blogs)): ?>
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog">
                        <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                        <p><?php echo htmlspecialchars($blog['description']); ?></p>
                        <p><strong>Creator:</strong> <?php echo htmlspecialchars($blog['creator_name']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($blog['category_name']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
