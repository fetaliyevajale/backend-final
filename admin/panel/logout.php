<?php
    session_start();

    $_SESSION = [];

    session_destroy();


    header('Location: http://localhost/xampp/backend-project/auth/login.php');
    exit();
    ?> 
