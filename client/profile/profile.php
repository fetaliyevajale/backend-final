<?php
session_start(); 


// if (!isset($_SESSION['user'])) {
//     header('Location: login.php');
//     exit();
// }

include('../../config/config.php'); 
include('../../helper/function.php'); 

$user = $_SESSION['user']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

  
    $password_hash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    try {
     
        $query = "UPDATE users SET name = :name, email = :email" . ($password_hash ? ", password = :password" : "") . " WHERE id = :id";
        $stmt = $connection->prepare($query);

 
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        if ($password_hash) {
            $stmt->bindParam(':password', $password_hash);
        }
        $stmt->bindParam(':id', $user['id']);

       
        if ($stmt->execute()) {
            echo "<p>Profile updated successfully!</p>";
         
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            if ($password_hash) {
                $_SESSION['user']['password'] = $password_hash;
            }
        } else {
            echo "<p>Failed to update profile. Please try again.</p>";
        }
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
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
        }
        header a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            margin-left: 20px;
        }
        header a:hover {
            text-decoration: underline;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .profile-form h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        .profile-form input[type="text"],
        .profile-form input[type="email"],
        .profile-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .profile-form input[type="submit"] {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .profile-form input[type="submit"]:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Profile</h1>
        <a href="logout.php">Logout</a>
    </header>
    <div class="container">
        <div class="profile-form">
            <h2>Update Profile</h2>
            <form method="post">
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Name" required>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
                <input type="password" name="password" placeholder="New Password">
                <input type="submit" value="Update Profile">
            </form>
        </div>
    </div>
</body>
</html>
