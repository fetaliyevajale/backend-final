<?php
include('../../config/config.php'); 
include('../../helper/function.php'); 
session_start(); 

// İstifadəçi ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']);

    
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo "User not found.";
        exit;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Forma göndərildikdə
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $profile = $_POST['profile']; // Profil şəkli üçün 
        $active = isset($_POST['active']) ? 1 : 0;
        $role = $_POST['role'];

        // Şifrəni boş qoyanda dəyişməsin
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET name = :name, surname = :surname, email = :email, password = :password, gender = :gender, dob = :dob, profile = :profile, active = :active, role = :role WHERE id = :id";
            $update_stmt = $connection->prepare($update_query);
            $update_stmt->bindParam(':password', $password_hash);
        } else {
            $update_query = "UPDATE users SET name = :name, surname = :surname, email = :email, gender = :gender, dob = :dob, profile = :profile, active = :active, role = :role WHERE id = :id";
            $update_stmt = $connection->prepare($update_query);
        }

        $update_stmt->bindParam(':name', $name);
        $update_stmt->bindParam(':surname', $surname);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':gender', $gender);
        $update_stmt->bindParam(':dob', $dob);
        $update_stmt->bindParam(':profile', $profile);
        $update_stmt->bindParam(':active', $active, PDO::PARAM_INT);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            echo "User updated successfully.";
            header("Location: users.php");
            exit;
        } else {
            echo "Failed to update user.";
        }
    }
} else {
    echo "Invalid user ID.";
    exit;
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
                            <label for="surname">Surname</label>
                            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required>
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
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="1" <?php echo $user['gender'] == 1 ? 'selected' : ''; ?>>Male</option>
                                <option value="2" <?php echo $user['gender'] == 2 ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>

                        <div class="row-cold">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
                        </div>

                        <div class="row-cold">
                            <label for="profile">Profile Image URL</label>
                            <input type="text" id="profile" name="profile" value="<?php echo htmlspecialchars($user['profile']); ?>">
                        </div>

                        <div class="row-cold">
                            <label for="active">Active</label>
                            <input type="checkbox" id="active" name="active" <?php echo $user['active'] ? 'checked' : ''; ?>>
                        </div>

                        <div class="row-cold">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="1" <?php echo $user['rol'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                <option value="0" <?php echo $user['rol'] == 0 ? 'selected' : ''; ?>>User</option>
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
include 'footer.php';
?>
