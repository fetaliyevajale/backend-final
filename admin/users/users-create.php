<?php
include('../../config/config.php'); 
include('../../helper/function.php'); 
session_start();

// dd($_SESSION);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    $route = route('auth/login.php');
    header('Location:'.$route);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveUser'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Şifrənin hasi
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Verilənlər bazası
    $query = "INSERT INTO users (name, username, email, password, role) VALUES (:name, :username, :email, :password, :role)";
    $stmt = $connection->prepare($query);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header('Location: users.php');
        exit();
    } else {
        echo "<p>Failed to add user. Please try again.</p>";
    }
}
?>

<div class="rootDiv">
    <div class="rootDivChild">
        <div class="card">
            <div class="card-Header">
                <h4>Add User</h4>
                <a href="users.php">Back</a>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="row-cold">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="row-cold">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>

                        <div class="row-cold">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="row-cold">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <div class="row-cold">
                            <label for="role">Select Role</label>
                            <select id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>

                    <div class="btn-card">
                        <button type="submit" name="saveUser">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../panel/footer.php';
?>
