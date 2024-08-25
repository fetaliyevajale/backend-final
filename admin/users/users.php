<?php
include('../../config/config.php'); 
include('../../helper/function.php'); 
session_start();


// İstifadəçilərin məlumatları
$query = "SELECT id, name, username, email, role FROM users";
$stmt = $connection->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="rootDiv">
    <div class="rootDivChild">
        <div class="card">
            <div class="card-Header">
                <h4>User List</h4>
                <a href="users-create.php" class="btn btn-primary">Add User</a>
            </div>
            <div class="card-body">
                <?php if (count($users) > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                                    <td><?php echo isset($user['is_ban']) && $user['is_ban'] ? 'Banned' : 'Active'; ?></td>
                                    <td>
                                        <a href="user-edit.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a> | 
                                        <a href="user-delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No users found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php

?>
