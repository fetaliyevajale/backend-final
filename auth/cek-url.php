<?php
session_start();


$urlRequired = [
    "/backend-project/auth/login.php",
    "/backend-project/auth/register.php",
    "/backend-project/otp.php",
];

if (isset($_SESSION['user_id']) && in_array($_SERVER['REQUEST_URI'], $urlRequired)) {
    header('Location: final_project/index.php');
    exit();
} else if (!isset($_SESSION['user_id']) && !in_array($_SERVER['REQUEST_URI'], $urlRequired)) {
    header('Location:final_project/auth/login.php');
    exit();
}
?>