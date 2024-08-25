<?php
include('../../config/config.php'); 
include('../../helper/function.php'); 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: http://localhost/xampp/final_project/admin/auth/login.php');
    exit();
}
// İstifadəçi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "User ID is missing.";
    exit;
}

$user_id = intval($_GET['id']);

try {
    // İstifadəçi məlumatları
    $query = "SELECT id, name, username, email, role FROM users WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "User not found.";
        exit;
    }

    // Form məlumatları
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Şifrəni boş qoyanda dəyişməsin
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET name = :name, username = :username, email = :email, password = :password, role = :role WHERE id = :id";
            $stmt = $connection->prepare($update_query);
            $stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);
        } else {
            $update_query = "UPDATE users SET name = :name, username = :username, email = :email, role = :role WHERE id = :id";
            $stmt = $connection->prepare($update_query);
        }

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "User updated successfully.";
            header("Location: users.php");
            exit;
        } else {
            echo "Failed to update user.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="rootDiv">
    <div class="rootDivChild">
        <div class="card">
            <div class="card-Header">
                <h4>Edit User</h4>
                <a href="users.php">Back to User List</a>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="row-cold">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>

                        <div class="row-cold">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>

                        <div class="row-cold">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="row-cold">
                            <label for="password">Password (leave empty if not changing)</label>
                            <input type="password" id="password" name="password">
                        </div>

                        <div class="row-cold">
                            <label for="role">Select Role</label>
                            <select id="role" name="role" required>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            </select>
                        </div>
                    </div>

                    <div class="btn-card">
                        <button type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

?>
