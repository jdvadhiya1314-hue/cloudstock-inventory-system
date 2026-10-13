<?php
// db.php
$host = 'localhost';
$dbname = 'cloudstock';
$username = 'cloudstock_user';
$password = 'MyNewPassword@2026'; // Change this to your production MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>