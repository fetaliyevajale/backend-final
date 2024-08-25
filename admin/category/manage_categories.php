<?php
include('../../config/config.php'); 
session_start();


print_r($_SESSION);
exit();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addCategory'])) {
    $categoryName = $_POST['categoryName'];

    try {
        $stmt = $connection->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
        $stmt->execute();
        $message = "Category added successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

try {
    $stmt = $connection->prepare("SELECT * FROM categories ORDER BY created_at DESC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}


if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    try {
        $stmt = $connection->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: manage_categories.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
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

        .message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .category-form {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .category-form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .category-form input[type="submit"] {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .category-form input[type="submit"]:hover {
            background: #555;
        }

        .category-list {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .category-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .category-list th,
        .category-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .category-list th {
            background-color: #f2f2f2;
        }

        .delete-link {
            color: red;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header>
        <h1>Manage Categories</h1>
    </header>
    <div class="container">
        <?php if (isset($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>

        <div class="category-form">
            <h2>Add New Category</h2>
            <form method="post">
                <input type="text" name="categoryName" placeholder="Category Name" required>
                <input type="submit" name="addCategory" value="Add Category">
            </form>
        </div>

        <div class="category-list">
            <h2>Existing Categories</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['id']); ?></td>
                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                            <td>
                                <a href="edit_category.php?id=<?php echo $category['id']; ?>">Edit</a> |
                                <a href="manage_categories.php?delete_id=<?php echo $category['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>