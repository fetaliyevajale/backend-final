<?php
include('../config/config.php'); 
include('../helper/function.php'); 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $birth_date = trim($_POST['birth_date']);
    $gender = trim($_POST['gender']);


    $profile_image = $_FILES['profile_image']['name'];
    $profile_image_tmp = $_FILES['profile_image']['tmp_name'];
    $upload_dir = __DIR__ . "/uploads/";
    $profile_image_path = $upload_dir . basename($profile_image);

 
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            die("Failed to create directory: " . $upload_dir);
        }
    }


    if ($_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
        die("File upload error: " . $_FILES['profile_image']['error']);
    }

   
    if (move_uploaded_file($profile_image_tmp, $profile_image_path)) {
        try {
            $stmt = $connection->prepare("INSERT INTO users (name, username, email, password, dob, gender, profile_image, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')");
            $stmt->execute([$name, $username, $email, $password, $birth_date, $gender, $profile_image]);
            header('Location: login.php');
            exit();
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }
    } else {
        echo "Failed to move uploaded file.";
        echo "Temporary file path: " . $profile_image_tmp;
        echo "Destination path: " . $profile_image_path;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        input[type="radio"] {
            margin-right: 10px;
        }
        
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
        
        .login-link {
            text-align: center;
            margin-top: 15px;
            color: #555;
        }
        
        .login-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <h2>Register</h2>

            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" required>

            <label for="gender">Gender:</label>
            <input type="radio" id="male" name="gender" value="1" required> Male
            <input type="radio" id="female" name="gender" value="2" required> Female

            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*">

            <button type="submit">Register</button>
            
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>
</body>
</html>
