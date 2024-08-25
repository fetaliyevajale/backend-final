<?php
if (isset($_POST['saveUser'])) {
    // POST məlumatları
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $gender = intval($_POST['gender']);
    $dob = trim($_POST['dob']);
    $profile = trim($_POST['profile']);
    $active = isset($_POST['active']) ? 1 : 0;
    $role = intval($_POST['role']);
    $user_id = intval($_POST['user_id']);

    $errors = [];

    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }

    if (empty($surname)) {
        $errors[] = 'Surname is required.';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }

    if (empty($dob) || !DateTime::createFromFormat('Y-m-d', $dob)) {
        $errors[] = 'Valid date of birth is required.';
    }

    if ($gender != 1 && $gender != 2) {
        $errors[] = 'Gender must be either Male or Female.';
    }

    if ($role != 0 && $role != 1) {
        $errors[] = 'Role must be either User or Admin.';
    }

    if (empty($errors)) {
        try {
            if (!empty($password)) {
                // Şifrə hash-ləmə
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET name = :name, surname = :surname, email = :email, password = :password, gender = :gender, dob = :dob, profile = :profile, active = :active, rol = :role WHERE id = :id";
                $stmt = $connection->prepare($update_query);
                $stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);
            } else {
                $update_query = "UPDATE users SET name = :name, surname = :surname, email = :email, gender = :gender, dob = :dob, profile = :profile, active = :active, rol = :role WHERE id = :id";
                $stmt = $connection->prepare($update_query);
            }


            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':gender', $gender, PDO::PARAM_INT);
            $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
            $stmt->bindParam(':profile', $profile, PDO::PARAM_STR);
            $stmt->bindParam(':active', $active, PDO::PARAM_INT);
            $stmt->bindParam(':role', $role, PDO::PARAM_INT);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "User updated successfully.";
            } else {
                echo "Failed to update user.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}


function route($path)
{
    return 'http://localhost/xampp/backend-project/' . $path;
}

function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit();
}
