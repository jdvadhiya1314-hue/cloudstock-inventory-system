<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($id > 0) {
        try {
            if ($action === 'increase') {
                $stmt = $pdo->prepare("UPDATE products SET quantity = quantity + 1 WHERE id = :id");
                $stmt->execute(['id' => $id]);
            } elseif ($action === 'decrease') {
                // The condition AND quantity > 0 prevents negative stock at database level
                $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - 1 WHERE id = :id AND quantity > 0");
                $stmt->execute(['id' => $id]);
            }
        } catch (PDOException $e) {
            die("Error updating stock: " . $e->getMessage());
        }
    }
}

// Redirect back to inventory
header("Location: index.php");
exit;
?>
