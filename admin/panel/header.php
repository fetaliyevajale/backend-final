<?php
include 'config/config.php';

$todayBlogs = 0;
$thisMonthBlogs = 0;

$categoryBlogCounts = [];

$userBlogCounts = [];

try {
    $stmt = $connection->prepare("SELECT COUNT(*) as count FROM blogs WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $todayBlogs = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

   
    $stmt = $connection->prepare("SELECT COUNT(*) as count FROM blogs WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    $stmt->execute();
    $thisMonthBlogs = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

   
    $stmt = $connection->prepare("SELECT categories.name, COUNT(blogs.id) as count FROM blogs JOIN categories ON blogs.category_id = categories.id GROUP BY categories.name");
    $stmt->execute();
    $categoryBlogCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  
    $stmt = $connection->prepare("SELECT users.username, COUNT(blogs.id) as count FROM blogs JOIN users ON blogs.user_id = users.id GROUP BY users.username");
    $stmt->execute();
    $userBlogCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/index.css">
    <style>
     
    </style>
</head>

<body>
    <?php include '../admin/panel/navbar.php'; ?>
    
    <div class="container">
        <h1>Admin Dashboard</h1>
        
    
        <h2>Bugün yaradılan bloglar: <?php echo $todayBlogs; ?></h2>
        <h2>Cari ayda yaradılan bloglar: <?php echo $thisMonthBlogs; ?></h2>
        
        <h2>Hər category-də neçə blog var</h2>
        <ul>
            <?php foreach ($categoryBlogCounts as $category): ?>
                <li><?php echo $category['name']; ?>: <?php echo $category['count']; ?> blog</li>
            <?php endforeach; ?>
        </ul>
    
        <h2>İstifadəçilərə görə blog sayı</h2>
        <ul>
            <?php foreach ($userBlogCounts as $user): ?>
                <li><?php echo $user['username']; ?>: <?php echo $user['count']; ?> blog</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- <?php include 'footer.php'; ?> -->
</body>

</html>
