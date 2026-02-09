<?php
$host = "localhost";
$dbname = "multil_saas";
$username = "root";    // default for XAMPP/Wamp
$password = "";        // default is empty for XAMPP/Wamp

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
