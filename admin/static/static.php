<?php
include('../config/config.php'); 

$today = date('Y-m-d');
$week_start = date('Y-m-d', strtotime('monday this week'));
$month_start = date('Y-m-01');

try {
    // Statistik məlumatlar
    $today_blogs_stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE created_at >= ?");
    $today_blogs_stmt->execute([$today]);
    $today_blogs = $today_blogs_stmt->fetchColumn();

    $week_blogs_stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE created_at >= ?");
    $week_blogs_stmt->execute([$week_start]);
    $week_blogs = $week_blogs_stmt->fetchColumn();

    $month_blogs_stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE created_at >= ?");
    $month_blogs_stmt->execute([$month_start]);
    $month_blogs = $month_blogs_stmt->fetchColumn();

    $today_views_stmt = $pdo->prepare("SELECT SUM(view_count) FROM blogs WHERE created_at >= ?");
    $today_views_stmt->execute([$today]);
    $today_views = $today_views_stmt->fetchColumn();

    $week_views_stmt = $pdo->prepare("SELECT SUM(view_count) FROM blogs WHERE created_at >= ?");
    $week_views_stmt->execute([$week_start]);
    $week_views = $week_views_stmt->fetchColumn();

    $month_views_stmt = $pdo->prepare("SELECT SUM(view_count) FROM blogs WHERE created_at >= ?");
    $month_views_stmt->execute([$month_start]);
    $month_views = $month_views_stmt->fetchColumn();
} catch (PDOException $e) {
    $error = "Error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        div {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #555;
        }

        p {
            margin: 5px 0;
        }

        .error {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <h1>Statistics</h1>
    <div>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php else: ?>
            <h2>Blogs Added</h2>
            <p>Today: <?php echo htmlspecialchars($today_blogs); ?></p>
            <p>This Week: <?php echo htmlspecialchars($week_blogs); ?></p>
            <p>This Month: <?php echo htmlspecialchars($month_blogs); ?></p>

            <h2>Views</h2>
            <p>Today: <?php echo htmlspecialchars($today_views); ?></p>
            <p>This Week: <?php echo htmlspecialchars($week_views); ?></p>
            <p>This Month: <?php echo htmlspecialchars($month_views); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
