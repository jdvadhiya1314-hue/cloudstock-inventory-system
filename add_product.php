<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);

    // Basic Validation
    if ($name !== '' && $category !== '' && $price >= 0 && $quantity >= 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, quantity) VALUES (:name, :category, :price, :quantity)");
            $stmt->execute([
                'name' => $name,
                'category' => $category,
                'price' => $price,
                'quantity' => $quantity
            ]);
        } catch (PDOException $e) {
            die("Error adding product: " . $e->getMessage());
        }
    }
}

// Redirect back to inventory
header("Location: index.php");
exit;
?>
