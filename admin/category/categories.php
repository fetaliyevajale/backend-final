<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
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
        .button {
            padding: 10px 15px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Categories Management</h1>
        <a href="../category//manage_categories.php">Create Category</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
          include('../../config/config.php');

            $categories = $connection->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as $category) {
                echo "<tr>
                    <td>{$category['id']}</td>
                    <td>{$category['name']}</td>
                    <td>{$category['created_at']}</td>
                    <td>{$category['updated_at']}</td>
                    <td>
                        <form method='post' action='manage_categories.php' style='display:inline;'>
                            <input type='hidden' name='id' value='{$category['id']}'>
                            <button type='submit' name='update' class='button'>Update</button>
                        </form>
                        <form method='post' action='manage_categories.php' style='display:inline;'>
                            <input type='hidden' name='id' value='{$category['id']}'>
                            <button type='submit' name='delete' class='button' style='background-color: #dc3545;'>Delete</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
