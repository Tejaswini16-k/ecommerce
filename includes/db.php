<?php
$host = 'localhost'; // Change this if using a different host
$dbname = 'ecommerce';
$username = 'root'; // Change this if using a different database user
$password = ''; // If using XAMPP, leave empty. If using another setup, add the password.

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection not established: " . $e->getMessage()); // Debugging message
}
?>
