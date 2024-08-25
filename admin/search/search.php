<?php
include('../config/config.php'); 

$title = $_GET['title'] ?? '';
$description = $_GET['description'] ?? '';
$author = $_GET['author'] ?? '';
$category_id = $_GET['category_id'] ?? '';

$query = "SELECT * FROM blogs WHERE 1=1";
$params = [];

if ($title) {
    $query .= " AND title LIKE ?";
    $params[] = "%$title%";
}

if ($description) {
    $query .= " AND description LIKE ?";
    $params[] = "%$description%";
}

if ($author) {
    $query .= " AND author LIKE ?";
    $params[] = "%$author%";
}

if ($category_id) {
    $query .= " AND category_id = ?";
    $params[] = $category_id;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <style>
       
    </style>
</head>
<body>
    <form method="get">
     
    </form>

    <h1>Search Results</h1>
    <div class="blog-list">
        <?php foreach ($blogs as $blog): ?>
            <div class="blog-card">
                <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                <p><?php echo htmlspecialchars($blog['description']); ?></p>
                <a href="blog-view.php?id=<?php echo $blog['id']; ?>">Read more</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
