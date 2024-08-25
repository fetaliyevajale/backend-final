<?php
include('../config/config.php');
session_start();

// Admin yoxlaması
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Hər kateqoriyadakı blogların sayı
try {
    $stmt = $connection->query("
        SELECT c.name as category_name, COUNT(b.id) as blog_count
        FROM categories c
        LEFT JOIN blogs b ON c.id = b.category_id
        GROUP BY c.name
    ");
    $category_blog_counts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $category_blog_counts = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Blog Count</title>
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
        .report {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Blog Count by Category</h1>
    </header>
    <div class="container">
        <div class="report">
            <h2>Blog Count by Category</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Blog Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($category_blog_counts as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($category['blog_count']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
