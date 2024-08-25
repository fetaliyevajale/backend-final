<?php
include('../../config/config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: http://localhost/xampp/backend-project/auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


$user = $connection->query("SELECT * FROM users WHERE id = $user_id")->fetch(PDO::FETCH_ASSOC);


$blog_count = $connection->query("SELECT COUNT(*) FROM blogs WHERE user_id = $user_id")->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        .dashboard {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .dashboard div {
            background: #fff;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }
        .dashboard div h2 {
            margin-top: 0;
        }
        .logout-button {
            background: #d9534f;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-button:hover {
            background: #c9302c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Dashboard</h1>
    </header>
    <div class="container">
        <div class="dashboard">
            <div>
                <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div>
                <h2>Statistics</h2>
                <p>Total Blogs: <?php echo $blog_count; ?></p>
            </div>
        </div>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
