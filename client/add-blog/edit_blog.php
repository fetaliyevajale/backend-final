<?php
include('../config/config.php'); 
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $blogId = $_GET['id'];

    try {
     
        $stmt = $connection->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([$blogId]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$blog) {
            echo "Blog tapılmadı.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Blog ID tapılmadı.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'], $_POST['description'], $_POST['category_id'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];

        try {
         
            $stmt = $connection->prepare("UPDATE blogs SET title = ?, description = ?, category_id = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $description, $category_id, $blogId]);

            header('Location: blogs.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Bütün sahələr doldurulmalıdır.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogu Redaktə et</title>
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

        .form-group {
            margin: 15px 0;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Blogu Redaktə et</h1>
    </header>

    <div class="container">
        <form method="post">
            <div class="form-group">
                <label for="title">Başlıq:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Təsvir:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($blog['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="category_id">Kateqoriya:</label>
                <select id="category_id" name="category_id" required>
                    <?php
              
                    try {
                        $stmt = $connection->query("SELECT * FROM categories");
                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($categories as $category) {
                            echo "<option value='{$category['id']}'" . ($blog['category_id'] == $category['id'] ? ' selected' : '') . ">{$category['name']}</option>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>

            <button type="submit">Yenilə</button>
        </form>
    </div>
</body>
</html>
