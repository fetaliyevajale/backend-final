<?php
$servername = "localhost";
$username = "root";
$password = "";
$database_name = "backend_project";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$database_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
