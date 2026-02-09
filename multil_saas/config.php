<?php
// Database configuration
$host = "localhost";
$db = "multi_saas";
$user = "root"; // or your MySQL username
$pass = "";     // or your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
