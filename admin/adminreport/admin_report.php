<?php
include('../config/config.php');
session_start();

// Admin yoxlaması
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Cari gün və cari ay üçün blogların sayı
try {
    // Cari gün üçün blogların sayı
    $stmt_day = $connection->prepare("
        SELECT COUNT(*) as count
        FROM blogs
        WHERE DATE(created_at) = CURDATE()
    ");
    $stmt_day->execute();
    $blogs_today = $stmt_day->fetch(PDO::FETCH_ASSOC)['count'];

    // Cari ay üçün blogların sayı
    $stmt_month = $connection->prepare("
        SELECT COUNT(*) as count
        FROM blogs
        WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())
    ");
    $stmt_month->execute();
    $blogs_month = $stmt_month->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    $blogs_today = $blogs_month = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Report</title>
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
        .report h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Report</h1>
    </header>
    <div class="container">
        <div class="report">
            <h2>Blog Count</h2>
            <p>Blogs Created Today: <?php echo htmlspecialchars($blogs_today); ?></p>
            <p>Blogs Created This Month: <?php echo htmlspecialchars($blogs_month); ?></p>
        </div>
    </div>
</body>
</html>
