<?php
include('../config/config.php'); 
session_start();

// Admin yoxlaması
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];

        // Blogun silinməsi
        try {
            $stmt = $connection->prepare("DELETE FROM blogs WHERE id = ?");
            $stmt->execute([$id]);

            header('Location: blogs.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Blog ID is required.";
    }
}
?>

<form method="post" action="delete_blog.php">
    <input type="hidden" name="id" value="<?php echo isset($blog['id']) ? htmlspecialchars($blog['id']) : ''; ?>">
    <button type="submit">Delete Blog</button>
</form>
