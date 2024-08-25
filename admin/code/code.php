<?php
include('../config/config.php');

if (isset($_POST['saveUser'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Şifrəni hashi
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Verilənlər bazası
        $query = "INSERT INTO users (name, username, email, password, role) VALUES (:name, :username, :email, :password, :role)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            echo "<p>User added successfully.</p>";
        } else {
            echo "<p>Failed to add user. Please try again.</p>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
